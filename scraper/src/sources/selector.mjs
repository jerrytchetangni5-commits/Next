// //CSS selectors and constants for scholyhub.com.
// export const BASE_URL = 'https://scholyhub.com';

// export const SELECTORS = {
//   //Landing page
//   card: '.sh-listing-card-wrap',
//   cardLink: 'a.sh-listing-card',
//   cardTitle: '.sh-listing-card__title',
//   cardExcerpt: '.sh-listing-card__excerpt',
//   cardImage: '.sh-listing-card__media img',
//   cardFundedBadge: '.sh-card-badge--funded',
//   nextPage: 'nav.sh-pagination a.next.page-numbers',

//   //Details page
//   detailTitle: '.sh-detail-hero__title',
//   detailCountry: '.sh-detail-hero__badges .sh-pill--country',
//   detailFundedBadge: '.sh-detail-hero__badges .sh-pill--funded',
//   // Degree-level pills: plain .sh-pill badges (excluding the country/funded ones).
//   detailLevelPills:
//     '.sh-detail-hero__badges .sh-pill:not(.sh-pill--country):not(.sh-pill--funded)',
//   detailUniversity: '.sh-detail-hero__meta-item strong',
//   deadlineDate: '.sh-apply-card__deadline-date',
//   applyPrice: '.sh-apply-card__price',

//   // Quick-facts grid (label/value pairs: Total reward, Level, Duration, Location).
//   factLabel: '.sh-fact__label',
//   factValue: '.sh-fact__value',

//   // Info-card sections, matched by their heading text (About / Benefits / ...).
//   infoCard: '.sh-info-card',
//   infoCardTitle: '.sh-info-card__title',
//   infoCardBody: '.sh-info-card__body',
// };

// // data-* attributes present on the listing card wrapper.
// export const CARD_DATA = {
//   country: 'data-sh-country',
//   degree: 'data-sh-degree',
//   deadlineDays: 'data-sh-deadline-days',
//   text: 'data-sh-text',
// };


/** CSS selectors for ScholyHub */

export const BASE_URL = "https://scholyhub.com";

export const SELECTORS = {
    //Landing page
    card: ".sh-listing-card-wrap",
    cardLink: "a.sh-listing-card",
    cardTitle: ".sh-listing-card__title",
    cardExcerpt: ".sh-listing-card__excerpt",
    cardImage: ".sh-listing-card__media img",
    fundedBadge: ".sh-card-badge--funded",
    nextPage: "a.next.page-numbers",

    //Detail page
    mainContent: ".sh-detail-main",
    pageTitle: "h1.sh-detail-hero__title",
    countryBadge: ".sh-pill--country",
    university: ".sh-detail-hero__meta-text strong",
    aboutBody: "#sh-about .sh-info-card__body",

    //Quick facts
    fact: ".sh-fact",
    factLabel: ".sh-fact__label",
    factValue: ".sh-fact__value",

    //sections
    sections: {
        about: "#sh-about",
        benefits: "#sh-benefits",
        eligibility: "#sh-eligibility",
        documents: "#sh-documents",
    },
    listItems: ".sh-detail-list li",
    
    //apply card
    applyCard: ".sh-apply-card",
    deadlineDate: ".sh-apply-card__deadline-date",
    applyButton: ".sh-apply-card .sh-btn--coral",
    officialWebsiteButton: ".sh-apply-card .sh-btn--ghost",
};

export const CARD_DATA = {
    country: "data-sh-country",
    degree: "data-sh-degree",
    deadlineDays: "data-sh-deadline-days",
    eligibility: "data-sh-eligibility",
    text: "data-sh-text"
};