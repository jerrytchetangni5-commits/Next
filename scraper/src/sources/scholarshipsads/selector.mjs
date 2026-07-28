export const BASE_URL = "https://www.scholarshipsads.com";

export const SELECTORS = {
    // Landing page(cartes)
    card: ".scholarship-card",
    cardLink: ".card-more a",
    cardTitle: ".card-title",
    cardExcerpt: ".card-text",
    cardImage: ".cover-img",
    nextPage: ".pagination-nav a.page-link",

    // Page de détails
    mainContent: ".scholarship-item",
    pageTitle: ".title-heading h1",
    
    // Champs avec icônes (gérés par la fonction getListItemText)
    countryBadge: ".icon-map",
    university: ".icon-place",
    domain: ".icon-book",
    level: ".icon-Bachelor2",
    deadline: ".icon-calendar",
    fundingType: ".icon-dollor",

    description: ".entry-content p:first-of-type",
    details: ".entry-content", // Simplifié pour prendre tout le contenu

    // Liens
    applyButton: "a[href*='apply'], a[href*='postuler'], .apply-btn a", // Sélecteur un peu plus robuste
    officialWebsiteButton: "a[href*='http']", 
};