// import { loadFromJson, saveToJson, fileExists } from "./exporter.mjs";
// import { ajoutScholyHubScholarships } from "./sources/scholyhub.mjs";
// import { scrapeScholarshipsAds, enrichScholarshipsAds } from "./sources/scholarshipsads/scholarshipsads.mjs";

// const RAW_DATA_PATH = "data/raw-scholarships.json";
// const FINAL_DATA_PATH = "storage/scholarships.json";

// async function main(){
//     console.log("Scraper Next démarré");
//     if (!await fileExists(RAW_DATA_PATH)){
//         console.log(`Fichier ${RAW_DATA_PATH} introuvable`);
//         console.log("Lance d'abord: npm run scrape");
//         return;
//     }
 
//     //charger les données collectées
//     console.log(`Chargement des données depuis ${RAW_DATA_PATH}...`);
//     const scholarships = await loadFromJson(RAW_DATA_PATH);
//     console.log(`${scholarships.length} bourses chargées depuis ${RAW_DATA_PATH}`);

//     //lancer l'ajout
//     console.log("Ajout des données");
//     const ajoutdata = await ajoutScholyHubScholarships(scholarships);

//     //Verification
//     if (ajoutdata.length === 0) {
//         console.warn('Aucun ajout fait. Veillez vérifier que les pages de détails sont accessibles');
//     }

//     //sauvegarder les données ajoutés
//     console.log(`Sauvegarde des fichiers dans ${FINAL_DATA_PATH}`)
//     await saveToJson(FINAL_DATA_PATH, ajoutdata);
//     console.log (`${ajoutdata.length} bourses sauvegardées dans storage/scholarships.json`);

//     console.log("Scraping terminé");
// }

// main().catch(console.error);






import { loadFromJson, saveToJson, fileExists } from "./exporter.mjs";
//import { ajoutScholyHubScholarships } from "./sources/scholyhub.mjs";
import { scrapeScholarshipsAds, enrichScholarshipsAds } from "./sources/scholarshipsads/scholarshipsads.mjs";

const RAW_DATA_PATH = "data/raw-scholarships.json";
const FINAL_DATA_PATH = "storage/scholarships.json";

async function main() {
    console.log("Scraper Next démarré");
    if (!await fileExists(RAW_DATA_PATH)) {
        console.log(`Fichier ${RAW_DATA_PATH} introuvable`);
        console.log("Lance d'abord: npm run scrape");
        return;
    }

    // Charger les données collectées
    console.log(`Chargement des données depuis ${RAW_DATA_PATH}...`);
    const scholarships = await loadFromJson(RAW_DATA_PATH);
    console.log(`${scholarships.length} bourses chargées depuis ${RAW_DATA_PATH}`);

    // Lancer l'ajout
    console.log("Ajout des données");
    const enrichedScholarships = await enrichScholarshipsAds(scholarships);

    // Vérification
    if (enrichedScholarships.length === 0) {
        console.warn('Aucun ajout fait. Vérifie que les pages de détails sont accessibles');
    }

    // Sauvegarder les données ajoutées
    console.log(`Sauvegarde des fichiers dans ${FINAL_DATA_PATH}`);
    await saveToJson(FINAL_DATA_PATH, enrichedScholarships);
    console.log(`${enrichedScholarships.length} bourses sauvegardées dans storage/scholarships.json`);

    console.log("Scraping terminé");
}

main().catch(console.error);