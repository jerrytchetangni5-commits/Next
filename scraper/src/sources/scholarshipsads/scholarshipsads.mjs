import { createCrawler } from "../../crawler.mjs";
import { SELECTORS, BASE_URL } from "./selector.mjs";
import { DOMAIN_KEYWORDS } from "../../config/keywords.mjs";

/**
 * Scrape les cartes de bourses depuis la page de listing
 */
export async function scrapeScholarshipsAds() {
    console.log("Scraping ScholarshipsAds...");

    const allScholarships = [];
    const seenLinks = new Set();

    const crawler = createCrawler(
        async ({ request, page, enqueueLinks }) => {
            console.log(`Page: ${request.url}`);

            try {
                await page.waitForSelector(SELECTORS.card, { timeout: 15000 });
            } catch (error) {
                console.log(`Aucune carte trouvée sur ${request.url}`);
                return;
            }

            const newScholarships = await page.evaluate((selectors, cardData, baseUrl) => {
                const clean = (t) => t?.trim().replace(/\s+/g, " ") || null;

                const cards = document.querySelectorAll(selectors.card);
                if (cards.length === 0) return [];

                const results = [];

                cards.forEach((card) => {
                    // ---- Lien vers la page détail ----
                    const linkEl = card.querySelector(selectors.cardLink);
                    const href = linkEl?.getAttribute("href");
                    if (!href) return;
                    const link = href.startsWith("http") ? href : baseUrl + href;

                    // ---- Titre ----
                    const titleEl = card.querySelector(selectors.cardTitle);
                    const title = titleEl ? clean(titleEl.textContent) : null;

                    // ---- Pays ----
                    const countryEl = card.querySelector(selectors.countryBadge);
                    const country = countryEl ? clean(countryEl.textContent) : null;

                    // ---- Université ----
                    const universityEl = card.querySelector(selectors.university);
                    const university = universityEl ? clean(universityEl.textContent) : null;

                    // ---- Domaine ----
                    const domainEl = card.querySelector(selectors.domain);
                    const domain = domainEl ? clean(domainEl.textContent) : null;

                    // ---- Niveau ----
                    const levelEl = card.querySelector(selectors.level);
                    const level = levelEl ? clean(levelEl.textContent) : null;

                    // ---- Date limite ----
                    const deadlineEl = card.querySelector(selectors.deadline);
                    const deadline = deadlineEl ? clean(deadlineEl.textContent) : null;

                    // ---- Type de financement ----
                    const fundingEl = card.querySelector(selectors.fundingType);
                    const funding_type = fundingEl ? clean(fundingEl.textContent) : null;

                    // ---- Image (background-image) ----
                    const imgEl = card.querySelector(selectors.cardImage);
                    let image = null;
                    if (imgEl) {
                        const style = imgEl.getAttribute('style') || '';
                        const match = style.match(/url\(['"]?(.*?)['"]?\)/);
                        if (match) image = match[1];
                    }

                    // ---- Résumé (extrait) ----
                    const excerptEl = card.querySelector(selectors.cardExcerpt);
                    const summary = excerptEl ? clean(excerptEl.textContent) : null;

                    results.push({
                        title,
                        country,
                        university,
                        domain,
                        level,
                        deadline,
                        description: null,
                        details: null,
                        funding_type,
                        benefits: null,
                        requirements: null,
                        required_documents: null,
                        image,
                        link,
                        summary,
                        apply_link: null,
                        official_website: null,
                        source: "ScholarshipsAds",
                    });
                });

                return results;
            }, SELECTORS, CARD_DATA, BASE_URL);

            newScholarships.forEach((s) => {
                if (!seenLinks.has(s.link)) {
                    seenLinks.add(s.link);
                    allScholarships.push(s);
                }
            });

            console.log(`${allScholarships.length} bourses ScholarshipsAds collectées`);

            // ---- Pagination ----
            await enqueueLinks({
                selector: SELECTORS.nextPage,
            });
        }
    );

    await crawler.run([`${BASE_URL}/latest-scholarships`]);

    console.log(`${allScholarships.length} bourses ScholarshipsAds collectées`);
    return allScholarships;
}

/**
 * Enrichit les bourses avec les données des pages de détail
 */
export async function enrichScholarshipsAds(scholarships) {
    console.log(`Enrichissement ScholarshipsAds...`);

    const scholarshipsMap = new Map(scholarships.map((s) => [s.link, s]));
    const results = [];

    const crawler = createCrawler(
        async ({ request, page }) => {
            const scholarship = scholarshipsMap.get(request.url);
            if (!scholarship) {
                console.log(`Bourse introuvable pour ${request.url}`);
                return;
            }
            console.log(`Enrichissement: ${scholarship.title || "Bourse"}`);

            try {
                await page.waitForSelector(SELECTORS.mainContent, { timeout: 15000 });
            } catch (error) {
                console.log(`Contenu introuvable ${request.url}`);
                results.push(scholarship);
                return;
            }

            const detailData = await page.evaluate((selectors, domainKeywords) => {
                const clean = (t) => t?.trim().replace(/\s+/g, " ") || null;

                // ---- Récupération des faits (facts) ----
                const facts = {};
                document.querySelectorAll(selectors.fact).forEach((fact) => {
                    const label = fact.querySelector(selectors.factLabel)?.textContent?.trim();
                    const value = fact.querySelector(selectors.factValue)?.textContent?.trim();
                    if (label && value) {
                        facts[clean(label).toLowerCase()] = clean(value);
                    }
                });

                // ---- Description courte (premier paragraphe) ----
                const descEl = document.querySelector(selectors.description);
                const description = descEl ? clean(descEl.textContent) : null;

                // ---- Détails complets (texte long) ----
                const detailsEl = document.querySelector(selectors.details);
                const details = detailsEl ? clean(detailsEl.textContent) : null;

                // ---- Récupération des listes (benefits, requirements, documents) ----
                const allLists = document.querySelectorAll(selectors.benefits);
                let benefits = null;
                let requirements = null;
                let requiredDocuments = null;

                // On parcourt les listes pour les assigner selon leur position ou leur titre
                // On peut les identifier par le titre <h3> précédent
                const listContainers = document.querySelectorAll('.entry-content ul');
                const headings = document.querySelectorAll('.entry-content h3');

                listContainers.forEach((list, index) => {
                    const items = Array.from(list.querySelectorAll('li'))
                        .map((li) => clean(li.textContent))
                        .filter(Boolean);

                    if (items.length === 0) return;

                    // Chercher le titre précédent
                    let prevHeading = null;
                    let prev = list.previousElementSibling;
                    while (prev) {
                        if (prev.tagName === 'H3') {
                            prevHeading = clean(prev.textContent);
                            break;
                        }
                        prev = prev.previousElementSibling;
                    }

                    const headingText = prevHeading ? prevHeading.toLowerCase() : '';

                    if (headingText.includes('avantage') || headingText.includes('benefit')) {
                        benefits = items.join(' ');
                    } else if (headingText.includes('admissibilité') || headingText.includes('eligibility') || headingText.includes('critère')) {
                        requirements = items.join(' ');
                    } else if (headingText.includes('document') || headingText.includes('procédure') || headingText.includes('application')) {
                        requiredDocuments = items.join(' ');
                    } else if (index === 0) {
                        benefits = items.join(' ');
                    } else if (index === 1) {
                        requirements = items.join(' ');
                    } else if (index === 2) {
                        requiredDocuments = items.join(' ');
                    }
                });

                // ---- Titre ----
                const title = clean(document.querySelector(selectors.pageTitle)?.textContent);

                // ---- Domaine ----
                let domain = null;
                const aboutBody = document.querySelector(selectors.details);
                if (aboutBody) {
                    const text = clean(aboutBody.textContent);
                    if (text) {
                        const lowerText = text.toLowerCase();
                        for (const keyword of domainKeywords) {
                            if (lowerText.includes(keyword.toLowerCase())) {
                                domain = keyword;
                                break;
                            }
                        }
                        if (!domain) {
                            const genericMap = {
                                'science': 'Science',
                                'engineering': 'Engineering',
                                'business': 'Business',
                                'law': 'Law',
                                'medicine': 'Medicine',
                                'education': 'Education',
                                'arts': 'Arts',
                                'humanities': 'Humanities',
                                'social': 'Social Sciences'
                            };
                            for (const [key, value] of Object.entries(genericMap)) {
                                if (lowerText.includes(key)) {
                                    domain = value;
                                    break;
                                }
                            }
                        }
                    }
                }

                // ---- Université (depuis les faits ou le texte) ----
                let university = document.querySelector(selectors.university)?.textContent?.trim() ||
                                 facts["host university"] ||
                                 facts["host institution"] ||
                                 facts["institution"] ||
                                 facts["university"] ||
                                 null;

                // ---- Niveau ----
                const level = document.querySelector(selectors.level)?.textContent?.trim() ||
                              facts["level"] || facts["degree"] || null;

                // ---- Pays ----
                const country = document.querySelector(selectors.countryBadge)?.textContent?.trim() ||
                                facts["location"] || facts["country"] || null;

                // ---- Type de financement ----
                const funding_type = document.querySelector(selectors.fundingType)?.textContent?.trim() ||
                                     facts["funding type"] || facts["funding"] || null;

                // ---- Date limite ----
                const deadline = document.querySelector(selectors.deadline)?.textContent?.trim() ||
                                 facts["deadline"] || null;

                // ---- Liens de candidature ----
                const applyLink = document.querySelector(selectors.applyButton)?.href ?? null;
                const officialWebsite = document.querySelector(selectors.officialWebsiteButton)?.href ?? null;

                return {
                    title,
                    description,
                    details,
                    benefits,
                    requirements,
                    required_documents,
                    domain,
                    university,
                    level,
                    country,
                    funding_type,
                    deadline,
                    apply_link: applyLink,
                    official_website: officialWebsite,
                };
            }, SELECTORS, DOMAIN_KEYWORDS);

            // ---- Fusion des données ----
            const added = {
                ...scholarship,
                level: detailData.level || scholarship.level,
                country: detailData.country || scholarship.country,
                funding_type: detailData.funding_type || scholarship.funding_type,
                domain: detailData.domain || scholarship.domain,
                university: detailData.university || scholarship.university,
                description: detailData.description || scholarship.summary,
                details: detailData.details || scholarship.details,
                benefits: detailData.benefits || scholarship.benefits,
                requirements: detailData.requirements || scholarship.requirements,
                required_documents: detailData.required_documents || scholarship.required_documents,
                deadline: detailData.deadline || scholarship.deadline,
                apply_link: detailData.apply_link || scholarship.apply_link || null,
                official_website: detailData.official_website || scholarship.official_website || null,
            };

            results.push(added);
            console.log(`${scholarship.title || "Bourse"} enrichie`);
        }
    );

    const links = scholarships.map((s) => s.link);
    await crawler.run(links);
    console.log(`${results.length} bourses ScholarshipsAds enrichies`);
    return results;
}