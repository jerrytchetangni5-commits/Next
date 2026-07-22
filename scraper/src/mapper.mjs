import { cleanText, getCountryLanguage } from "./utils.mjs";
import {SOURCES} from "../config/sources.mjs";

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
    const level = cleanText(data.level);
    const description = cleanText(data.description);
    const benefits = cleanText(data.benefits);
    const requirements = cleanText(data.requirements);
    const requiredDocuments = cleanText(data.required_documents);
    const university = cleanText(data.university);
    const domain = cleanText(data.domain);
    if (domain === '?' || domain === '' || domain === 'N/A' || domain === 'Unknown' || domain === 'null') {domain = null};
    const image = data.image ?? null;
    const link = data.link ?? null;

    let languages = null;

    if(data.language) {
        languages = Array.isArray(data.language) ? data.language : [data.language];
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
        link,
        title,
        country,
        level,
        university,
        domain: ['?', '', 'N/A', 'Unknown', 'null'].includes(domain) ? null : domain,
        description,
        funding_type: fundingType,
        amount: data.amount !== null && data.amount !== undefined
            ? Number(data.amount) || null
            : null,
        currency: data.currency ?? null,
        benefits,
        requirements,
        required_documents: requiredDocuments,
        deadline: data.deadline ?? null,
        deadline_days: data.deadline_days ?? null,
        languages,
        image,
        min_average: null,
        required_english_level: null,
        source: SOURCES.SCHOLYHUB
    };
}