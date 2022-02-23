import axios from "axios";
import {CHAPTER_API, POST_API} from "./config";
import Cache from "./Cache";

// On va chercher les invoices present dans le cache

async function findOne(id) {
    const cachedPost = await Cache.get("chapters." + id)
    if (cachedPost) return cachedPost


    return axios
        .get(CHAPTER_API + "/" + id)
        .then(response => {
            let chapter =  response.data
            Cache.set("posts." + id, chapter)
            return chapter
        })

}

async function findAll() {
    // On va chercher les invoices present dans le cache

    const cachedChapters = await cache.get("chapters")

    if (cachedChapters) {
        return cachedChapters
    }

    return axios.get(CHAPTER_API)
        .then(response => {
            const chapters = response.data["hydra:member"]
            cache.set("chapters", chapters)
            return chapters
        })
}

export default {
    findAll,
    findOne
}