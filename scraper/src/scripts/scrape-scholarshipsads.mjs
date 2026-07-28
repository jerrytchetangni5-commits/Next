import { saveToJson } from "../exporter.mjs";
import { scrapeScholarshipsAds, enrichScholarshipsAds } from "../sources/scholarshipsads/scholarshipsads.mjs";

async function main() {
    console.log("Scraping ScholarshipsAds...");

    //Étape 1 : Scraper les cartes
    console.log("Lancement du scraping des cartes...");
    const rawData = await scrapeScholarshipsAds();
    console.log(`${rawData.length} bourses collectées`);

    // Sauvegarder les données brutes
    await saveToJson("data/raw-scholarshipsads.json", rawData);
    console.log("Données brutes sauvegardées dans data/raw-scholarshipsads.json");

    //Étape 2 : Enrichir les données
    console.log("Enrichissement des données au niveau de la page de détail...");
    const enrichedData = await enrichScholarshipsAds(rawData);
    console.log(`${enrichedData.length} bourses enrichies`);

    // Sauvegarder les données enrichies
    await saveToJson("data/scholarshipsads-final.json", enrichedData);
    console.log("Données enrichies sauvegardées dans data/scholarshipsads-final.json");

    console.log("Scraping ScholarshipsAds terminé !");
}

main().catch(console.error);