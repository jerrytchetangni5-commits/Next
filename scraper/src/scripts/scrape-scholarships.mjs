import { createCrawler } from "../crawler.mjs";
import { BASE_URL, SELECTORS, CARD_DATA } from "../sources/selector.mjs";
import { saveToJson } from "../exporter.mjs";
import { ajoutScholyHubScholarships } from "../sources/scholyhub.mjs";

async function main(){
    console.log("Collecte des bourses");

    const allScholarships = [];
    const seenLinks = new Set(); //set pour eviter les doublons c'est comme un tableau qui ne peut contenir des valeurs uniques

    //on crée le crawler
    console.log("Collecte des liens et infos de base")
    const crawler = createCrawler(
        async ({ request, page, enqueueLinks }) => { //requestHandler sera exécuté pour chaque page
            console.log(`Page en cours: ${request.url}`);

            //wait load of cards
            try{
                await page.waitForSelector(SELECTORS.card, {
                    timeout: 15000,
                });
            } catch (error){
                console.log(`Aucune carte trouvée sur ${request.url}`);
                return;
            }
            

            const newScholarships = await page.evaluate((params) => {//exécute une fonctions dans le navigateur afin d'accéder au html de la page
                const {selectors, cardData, baseUrl} = params
                const clean = (t) => t?.trim().replace(/\s+/g, " ") || null;
                const cards = document.querySelectorAll(selectors.card);//on sélectionne les cartes de bourse 
                if (cards.length === 0){
                    return [];
                }
                const results = [];//ils sont sctoké ici
                cards.forEach((card) => {
                    //prendre les liens des bourses
                    const linkEl = card.querySelector(selectors.cardLink); // on cherche le lien
                    const href = linkEl?.getAttribute("href");
                    if(!href) return;

                    const link = href.startsWith("http")
                        ? href
                        : baseUrl + href;

                    const titleEl = card.querySelector(selectors.cardTitle);
                    const title = titleEl ? clean(titleEl.textContent) : null;

                    const country = card.getAttribute(cardData.country);

                    const level = card.getAttribute(cardData.degree);

                    const deadlineDays = card.getAttribute(cardData.deadlineDays);
                    const deadline_days = deadlineDays ? parseInt(deadlineDays, 10) : null;

                    const imgEl = card.querySelector(selectors.cardImage);
                    const image = imgEl?.getAttribute("src") || null;

                    const badgeEl = card.querySelector(selectors.fundedBadge);
                    const funding_type = badgeEl ? clean(badgeEl.textContent) : null;

                    const excerptEl = card.querySelector(selectors.cardExcerpt);
                    const summary = excerptEl ? clean(excerptEl.textContent) : null;
                    
                    results.push({
                        title,
                        country,
                        university: null,
                        domain: null,
                        level,
                        deadline: null,
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
                        source: "Scholyhub"
                    });
                });

                return results;
            }, { selectors: SELECTORS, cardData: CARD_DATA, baseUrl: BASE_URL });

            newScholarships.forEach((s) => {
                if (!seenLinks.has(s.link)){
                    seenLinks.add(s.link);
                    allScholarships.push(s);
                }
            });
            
            console.log(`${allScholarships.length} bourses collectées`);

            await enqueueLinks({
                selector: SELECTORS.nextPage,
            });
        }
    );

    await crawler.run([`${BASE_URL}/scholarships/`]);
    console.log(`${allScholarships.length} bourses brutes collectées`);

    console.log(`Ajout des données`);
    const enrichedData = await ajoutScholyHubScholarships(allScholarships);
    console.log(`${enrichedData.length} bourse enrichies`);

    const finalPath = "storage/scholarships.json";
    await saveToJson(finalPath, enrichedData);
    console.log(`${enrichedData.length} bourses sauvegardés dans storage/scholarships.json`);
    console.log("Scraping des bourses terminé.");
}

main().catch(console.error);
