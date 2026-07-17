//elle sert à rendre le texre propre. cleanText est réutilisable partout ailleurs
export function cleanText(text) {

    if (!text) {
        return null;
    }

    return text
        .trim()
        .replace(/\s+/g, " ");
}


export function extractAmountAndCurrency(text) {
    if(!text) {
        return {amount: null, currency: null};
    }

    const currencyMatch = text.match(/\b(EUR|USD|GBP|CAD|AUD|CHF|JPY|CNY|SEK|NOK|DKK|NZD|SGD)\b/i); //recherche d'une devise parmi celle cité
    const symbolMatch = text.match(/([$€¥])/);
    const amountMatch = text.match(/([\d\s,]+\.?\d*)/);

    let currency = null;
    if (currencyMatch) {
        currency = currencyMatch[1].toUpperCase();
    } else if (symbolMatch) {
        currency = symbolMatch[1];
    }

    const amount = amountMatch
        ? Number(amountMatch[1].replace(/,/g, "").replace(/\s/g, ""))
        : null;

    return { amount, currency };
}