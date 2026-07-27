/** CSS selectors for ScholarshipsAds.com */

export const BASE_URL = "https://www.scholarshipsads.com";

export const SELECTORS = {
    // ============================
    // LISTING PAGE (cartes)
    // ============================

    // Carte d'une bourse
    card: ".scholarship-card",

    // Lien vers la page de détail (Learn More)
    cardLink: ".card-more a",

    // Titre de la bourse sur la carte
    cardTitle: ".card-title",

    // Résumé / extrait
    cardExcerpt: ".card-text",

    // Image (background-image)
    cardImage: ".cover-img",

    // Badge de financement
    fundedBadge: ".icon-dollor",

    // Pagination (page suivante)
    nextPage: ".pagination-nav a.page-link",

    // ============================
    // DETAIL PAGE
    // ============================

    // Contenu principal
    mainContent: ".scholarship-item",

    // Titre
    pageTitle: ".title-heading h1",

    // Pays
    countryBadge: ".card-info .icon-map",

    // Université
    university: ".card-info .icon-place",

    // Domaine
    domain: ".icon-book",

    // Niveau
    level: ".icon-Bachelor2",

    // Date limite
    deadline: ".icon-calendar",

    // Type de financement
    fundingType: ".icon-dollor",

    // Description courte = premier paragraphe
    description: ".entry-content p:first-of-type",

    // Détails complets (texte long)
    details: ".entry-content.scholarship-item",

    // ============================
    // LISTES (benefits, requirements, documents)
    // ============================

    // Les listes sont dans .entry-content ul
    // On les différenciera par leur position dans le scraper
    benefits: ".entry-content ul",
    requirements: ".entry-content ul",
    requiredDocuments: ".entry-content ul",

    // ============================
    // APPLY CARD (liens)
    // ============================

    // Carte de candidature
    applyCard: ".scholarship-card",

    // Lien "Apply Now" / formulaire
    applyButton: "ul li a",

    // Lien vers le site officiel
    officialWebsiteButton: "ol li p a",

    // Date limite (fallback)
    deadlineDate: ".deadline-date",
};

export const CARD_DATA = {
    country: "data-country",
    degree: "data-degree",
    deadlineDays: "data-deadline-days",
    eligibility: "data-eligibility",
    text: "data-text",
};