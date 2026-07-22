//elle sert à rendre le texre propre. cleanText est réutilisable partout ailleurs
export function cleanText(text) {

    if (!text) {
        return null;
    }

    return text
        .trim()
        .replace(/\s+/g, " ");
}
