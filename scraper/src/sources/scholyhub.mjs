import { createCrawler } from "../crawler.mjs";
import { SELECTORS } from "./selector.mjs";
import { DOMAIN_KEYWORDS } from "../config/keywords.mjs";

export async function ajoutScholyHubScholarships(scholarships){
    console.log(`Ajout de ${scholarships.length} bourses`);

    //créer une map pour une recherche rapide des bourses par lien
    const scholarshipsMap = new Map(
        scholarships.map((s) => [s.link, s])
    );

    const results = [];

    const crawler = createCrawler(
        async ({ request, page }) => {

            //récupération via la map
            const scholarship = scholarshipsMap.get(request.url);
            if(!scholarship){
                console.log(`Bourse introuvable pour ${request.url}`);
                return;
            }
            console.log(`Ajout: ${scholarship.title || "Bourse"}`);

            try{
                await page.waitForSelector(SELECTORS.mainContent, {
                    timeout: 15000,
                });
            } catch (error) {
                console.log(`Contenu introuvable ${request.url}`);
                //on garde les données de la carte
                results.push(scholarship);
                return;
            }

            const detailData = await page.evaluate((params) => {
                const { selectors, domainKeywords } = params;
                const clean = (t) => t?.trim().replace(/\s+/g, " ") || null;//fonction locale pour nettoyer du texte

                const facts = {};
                document.querySelectorAll(selectors.fact).forEach((fact) => {
                    const label = fact.querySelector(selectors.factLabel)?.textContent?.trim();
                    const value = fact.querySelector(selectors.factValue)?.textContent?.trim();
                    if(label && value){
                        facts[clean(label).toLowerCase()] = clean(value);//on stocke la clé
                    }
                });

                //récupérer les listes
                const getListItems = (sectionId) => {//cette fonction trouve une section par son ID et récupère tous les éléments ('li') qui se trouvent à l'intérieur.
                    const section = document.querySelector(sectionId);
                    if (!section) return null;
                    const items = section.querySelectorAll(selectors.listItems);
                    return Array.from(items)
                        .map((li) => clean(li.textContent))
                        .filter(Boolean);
                };

                //récupère description
                const aboutBody = document.querySelector(selectors.aboutBody);
                const details = aboutBody ? clean(aboutBody.textContent): null;

                //récupère les avantages
                const benefits = getListItems(selectors.sections.benefits)?.join(" ") || null;

                //récupère les conditions
                const requirements = getListItems(selectors.sections.eligibility)?.join(" ") || null;

                // recupère document requis
                const requiredDocuments = getListItems(selectors.sections.documents)?.join(" ") || null;

                //récuper le titre
                const title = clean(document.querySelector(selectors.pageTitle)?.textContent);

                let domain = null;
                if (aboutBody) {
                    const text = clean(aboutBody.textContent);
                    if (text) {
                        const lowerText = text.toLowerCase();
                        for (const keyword of domainKeywords) {
                            if (lowerText.includes(keyword.toLowerCase())){
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

                let university = document.querySelector(selectors.university)?.textContent?.trim() || facts["host university"] || facts["host institution"] || facts["institution"] || facts["university"] || null;
                if(!university && aboutBody) {
                    const text = clean(aboutBody.textContent);
                    if(text) {
                        const uniMatch = text.match(/(University\s+of\s+[A-Za-zÀ-ÿ\s\-]+|[A-Za-zÀ-ÿ\s\-]+University)/i);
                        if(uniMatch){
                            university = clean(uniMatch[1]);
                        }
                    }
                }

                //deadline
                let deadline = facts["deadline"] || null;
                if(!deadline){
                    const deadlineEl = document.querySelector(selectors.deadlineDate);
                    deadline = deadlineEl ? clean(deadlineEl.textContent) : null;
                }

                //apply_link et official_website
                const applyLink = document.querySelector(selectors.applyButton)?.href ?? null;

                const officialWebsite = document.querySelector(selectors.officialWebsiteButton)?.href ?? null;

                return{
                    title,
                    details,
                    benefits,
                    requirements,
                    required_documents: requiredDocuments,
                    domain,
                    university,
                    level: facts["level"] || facts["degree"] || null,
                    country: facts["location"] || facts["country"] || null,
                    funding_type: facts["funding type"] || facts["funding"] || null,
                    deadline,
                    apply_link: applyLink,
                    official_website: officialWebsite
                };
            }, {selectors: SELECTORS, domainKeywords: DOMAIN_KEYWORDS});

            //construction de l'object final
            const added = {
                ...scholarship,
                level: detailData.level || scholarship.level,    
                country: detailData.country || scholarship.country,
                funding_type: detailData.funding_type || scholarship.funding_type,
                domain: detailData.domain || scholarship.domain,
                university: detailData.university || scholarship.university,
                description: detailData.description || scholarship.description,
                details: detailData.details || scholarship.details,
                benefits: detailData.benefits || scholarship.benefits,
                requirements: detailData.requirements || scholarship.requirements,
                required_documents: detailData.required_documents || scholarship.required_documents,
                deadline: detailData.deadline || scholarship.deadline,
                apply_link: detailData.apply_link || scholarship.apply_link || null,
                official_website: detailData.official_website || scholarship.official_website || null,
            };

            results.push(added);
            console.log(`${scholarship.title || "Bourse"} ajoutée`);
        }
    );

    const links = scholarships.map((s) => s.link);
    await crawler.run(links);//lancer le crawler sur les liens
    console.log(`${results.length} bourses ajoutées`)
    return results;
}