//(le disque dur)
import fs from "fs-extra";
import path from "path";

//sauvegarder les data dans filePath
export async function saveToJson(filePath, data) {
    const dir = path.dirname(filePath);
    if (dir) {
        await fs.ensureDir(path.dirname(filePath));
    }
    //ensureDir() crée le dossier si il n'existe pas
    await fs.writeJson(filePath, data, { spaces: 2});
    //writeJson écrit directement le json
}

//loadFromJson lit un fichier
export async function loadFromJson(filePath) {
    try {
        return await fs.readJson(filePath);
    } catch(error) {
        console.error(error);
        return [];
    }
}


export async function fileExists(filePath){
    return await fs.pathExists(filePath);
}
