import { cleanText, getCountryLanguage } from "./utils.mjs";
import {SOURCES} from "../config/sources.mjs";

function normalizeUrl(url) {
    if (!url) return null;
    url = url.trim();
    try {
        return new URL(url).href;
    } catch (_) {
        return null;
    }
}

export function mapScholyHubToNext(rawData) { //conversion des données de scholyhub en format next
    //pour chaque bourse de la liste rawData, on apllique .map pour prendre chaqur bourse individuellement
    if (Array.isArray(rawData)){
        return rawData.map((item) => mapSingle(item));
    }

    return mapSingle(rawData);
}

function mapSingle(data){
    //nettoyer les champs textes
    const title = cleanText(data.title);
    const country = cleanText(data.country);
    const university = cleanText(data.university);
    let domain = cleanText(data.domain);
    if (domain === '?' || domain === '' || domain === 'N/A' || domain === 'Unknown' || domain === 'null') {domain = null};
    const level = cleanText(data.level);
    const description = cleanText(data.summary);
    const details = cleanText(data.details);
    const benefits = cleanText(data.benefits);
    const requirements = cleanText(data.requirements);
    const requiredDocuments = cleanText(data.required_documents);
    const image = data.image ?? null;
    const link = data.link ?? null;
    let applyLink = normalizeUrl(data.apply_link ?? null);
    let officialWebsite = normalizeUrl(data.official_website ?? null);

    if (!applyLink && officialWebsite) {
        applyLink = officialWebsite;
    }

    //determiner le type de financement
    let fundingType = null;
    const ft = data.funding_type?.toLowerCase() ?? "";

    if(ft.includes("full") || ft.includes("fully")){
        fundingType = "full";
    } else if (ft.includes("partial") || ft.includes("tuition")){
        fundingType = "partial";
    } else{
        fundingType = "unfunded";
    }

    return {
        title,
        country,
        university,
        domain,
        level,
        deadline: data.deadline ?? null,
        description,
        details,
        funding_type: fundingType,
        benefits,
        requirements,
        required_documents: requiredDocuments,
        image,
        link,
        apply_link: applyLink,
        official_website: officialWebsite,
        source: SOURCES.SCHOLYHUB
    };
}