import {PlaywrightCrawler} from 'crawlee';

export function createCrawler(requestHandler){
    return new PlaywrightCrawler({
        maxConcurrency: 1, //nbr max de page à traité en meme temps
        maxRequestRetries: 2, //si 1 page plante, il essai 2 avant de laisser
        //maxRequestsPerCrawl: 5,
        requestHandler,//fonction qui sera exécuté
        failedRequestHandler({request}) {
            console.log(`Impossible de scraper: ${request.url}`);
        }
    });
}