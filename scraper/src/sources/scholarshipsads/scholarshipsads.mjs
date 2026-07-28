import { createCrawler } from "../../crawler.mjs";
import { SELECTORS, BASE_URL } from "./selector.mjs";

/**
 * Scrape les cartes de bourses depuis la page de listing
 */
export async function scrapeScholarshipsAds() {
    console.log("Scraping ScholarshipsAds (Listing)...");

    const allScholarships = [];
    const seenLinks = new Set();

    const crawler = createCrawler(
        async ({ request, page, enqueueLinks }) => {
            console.log(`Page: ${request.url}`);

            try {
                await page.waitForSelector(SELECTORS.card, { timeout: 15000 });
            } catch (error) {
                console.log(`Aucune carte trouvee sur ${request.url}`);
                return;
            }

            const newScholarships = await page.evaluate(({ selectors, baseUrl }) => {
                const clean = (t) => t?.trim().replace(/\s+/g, " ") || null;
                const cards = document.querySelectorAll(selectors.card);
                if (cards.length === 0) return [];

                // Fonction utilitaire pour extraire le texte d'un <li> contenant une icône
                const getListItemText = (iconSelector) => {
                    const icon = document.querySelector(iconSelector);
                    if (!icon || !icon.parentElement) return null;
                    const li = icon.closest('li');
                    if (!li) return clean(icon.parentElement.textContent); // Fallback
                    const fullText = li.textContent || '';
                    const iconText = icon.textContent || '';
                    return clean(fullText.replace(iconText, '')) || null;
                };

                const results = [];
                cards.forEach((card) => {
                    const linkEl = card.querySelector(selectors.cardLink);
                    const href = linkEl?.getAttribute("href");
                    if (!href) return;
                    const link = href.startsWith("http") ? href : baseUrl + href;

                    const titleEl = card.querySelector(selectors.cardTitle);
                    const title = titleEl ? clean(titleEl.textContent) : null;

                    // Utilisation de la fonction utilitaire pour les champs avec icônes
                    const country = getListItemText(selectors.countryBadge);
                    const university = getListItemText(selectors.university);
                    const domain = getListItemText(selectors.domain);
                    const level = getListItemText(selectors.level);
                    const deadline = getListItemText(selectors.deadline);
                    const funding_type = getListItemText(selectors.fundingType);

                    const imgEl = card.querySelector(selectors.cardImage);
                    let image = null;
                    if (imgEl) {
                        const style = imgEl.getAttribute('style') || '';
                        const match = style.match(/url\(['"]?(.*?)['"]?\)/);
                        if (match) image = match[1];
                    }

                    const excerptEl = card.querySelector(selectors.cardExcerpt);
                    const summary = excerptEl ? clean(excerptEl.textContent) : null;

                    results.push({
                        title, country, university, domain, level, deadline,
                        description: null, details: null, funding_type,
                        benefits: null, requirements: null, required_documents: null,
                        image, link, summary, apply_link: null, official_website: null,
                        source: "ScholarshipsAds",
                    });
                });
                return results;
            }, { selectors: SELECTORS, baseUrl: BASE_URL });

            newScholarships.forEach((s) => {
                if (!seenLinks.has(s.link)) {
                    seenLinks.add(s.link);
                    allScholarships.push(s);
                }
            });

            console.log(`${allScholarships.length} bourses ScholarshipsAds collectees sur cette page`);
            await enqueueLinks({ selector: SELECTORS.nextPage });
        }
    );

    await crawler.run([`${BASE_URL}/latest-scholarships`]);
    console.log(`${allScholarships.length} bourses ScholarshipsAds collectees au total`);
    return allScholarships;
}

/**
 * Enrichit les bourses avec les donnees des pages de detail
 */
export async function enrichScholarshipsAds(scholarships) {
    console.log(`Enrichissement ScholarshipsAds (Details)...`);

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

            const detailData = await page.evaluate(({ selectors }) => {
                const clean = (t) => t?.trim().replace(/\s+/g, " ") || null;

                // Fonction utilitaire pour extraire le texte à côté des icônes
                const getListItemText = (iconSelector) => {
                    const icon = document.querySelector(iconSelector);
                    if (!icon || !icon.parentElement) return null;
                    const li = icon.closest('li');
                    if (!li) return clean(icon.parentElement.textContent);
                    const fullText = li.textContent || '';
                    const iconText = icon.textContent || '';
                    return clean(fullText.replace(iconText, '')) || null;
                };

                const descEl = document.querySelector(selectors.description);
                let description = descEl ? clean(descEl.textContent) : null;

                const detailsEl = document.querySelector(selectors.details);
                let details = detailsEl ? clean(detailsEl.textContent) : null;

                //NETTOYAGE DES PUBLICITÉS DANS LE TEXTE
                if (details) {
                    details = details.replace(/\(adsbygoogle\s*=\s*window\.adsbygoogle\s*\|\|\s*\[\]\)\.push\(\{\}\);/g, '').trim();
                    details = details.replace(/\s{2,}/g, ' '); // Nettoie les doubles espaces

                    if (!description && details) {
                        // On prend tout le texte jusqu'au premier point (ou 200 caractères max)
                        const firstSentenceMatch = details.match(/^([^\.]{1,200})\./);
                        description = firstSentenceMatch ? clean(firstSentenceMatch[1] + '.') : clean(details.substring(0, 200) + '...');
                    }
                }
                if (description) {
                    description = description.replace(/\(adsbygoogle\s*=\s*window\.adsbygoogle\s*\|\|\s*\[\]\)\.push\(\{\}\);/g, '').trim();
                }

                const listContainers = document.querySelectorAll('.entry-content ul');
                let benefits = null;
                let requirements = null;
                let requiredDocuments = null;

                listContainers.forEach((list, index) => {
                    const items = Array.from(list.querySelectorAll('li'))
                        .map((li) => clean(li.textContent))
                        .filter(Boolean);

                    if (items.length === 0) return;

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
                        benefits = items.join(' | ');
                    } else if (headingText.includes('admissibilite') || headingText.includes('eligibility') || headingText.includes('criteres')) {
                        requirements = items.join(' | ');
                    } else if (headingText.includes('document') || headingText.includes('procedure') || headingText.includes('application')) {
                        requiredDocuments = items.join(' | ');
                    } else if (index === 0) {
                        benefits = items.join(' | ');
                    } else if (index === 1) {
                        requirements = items.join(' | ');
                    } else if (index === 2) {
                        requiredDocuments = items.join(' | ');
                    }
                });

                const title = clean(document.querySelector(selectors.pageTitle)?.textContent);

                //EXTRACTION DIRECTE (Plus de devinette pour le domaine)
                const domain = getListItemText(selectors.domain);
                const university = getListItemText(selectors.university);
                const level = getListItemText(selectors.level);
                const country = getListItemText(selectors.countryBadge);
                const funding_type = getListItemText(selectors.fundingType);
                const deadline = getListItemText(selectors.deadline);

                const applyLink = document.querySelector(selectors.applyButton)?.href ?? null;
                const officialWebsite = document.querySelector(selectors.officialWebsiteButton)?.href ?? null;

                return {
                    title,
                    description,
                    details,
                    benefits,
                    requirements,
                    required_documents: requiredDocuments,
                    domain,
                    university,
                    level,
                    country,
                    funding_type,
                    deadline,
                    apply_link: applyLink,
                    official_website: officialWebsite,
                };
            }, { selectors: SELECTORS });

            //CORRECTION : On vérifie request.url ICI (dans Node.js), pas dans page.evaluate
            const finalApplyLink = detailData.apply_link && detailData.apply_link !== request.url ? detailData.apply_link : null;

            const added = {
                ...scholarship,
                title: detailData.title || scholarship.title,
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
                apply_link: finalApplyLink || scholarship.apply_link || null,
                official_website: detailData.official_website || scholarship.official_website || null,
            };

            results.push(added);
            console.log(`${added.title || "Bourse"} enrichie`);
        }
    );

    const links = scholarships.map((s) => s.link);
    await crawler.run(links);
    console.log(`${results.length} bourses ScholarshipsAds enrichies avec succès`);
    return results;
}