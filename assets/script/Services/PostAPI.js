import axios from "axios";
import Axios from "axios";
import {POST_API} from "./config";
import cache from "./Cache";
import Cache from "./Cache";

async function findAll() {
    // On va chercher les Utilisateurs present dans le cache

    const cachedUsers = await cache.get("posts")

    if (cachedUsers) {
        return cachedUsers
    }

    return axios.get(POST_API).then(response => {
        const posts = response.data["hydra:member"]
        Cache.set("posts", posts)
        return posts
    })
}

async function deletePost(id) {
    return Axios.delete(POST_API + "/" + id)
        .then(async response => {
            const cachedUsers = await Cache.get("posts")
            if (cachedUsers) {
                Cache.set("posts", cachedUsers.filter(u => u.id !== id))
            }
            //Dans tout les cas on renvoi la reponse de la requete
            return response
        })
}

async function find(id) {
    const cachedPost = await Cache.get("posts." + id)
    if (cachedPost) return cachedPost


    return axios
        .get(POST_API + "/" + id)
        .then(response => {
            let post = response.data
            Cache.set("posts." + id, post)
            return post
        })
}

function findByChapter(id) {
    return axios.get(POST_API + "/" + "chapter" + "/" + id).then(response => {
        return response.data["hydra:member"]
    })
}

export default {
    findAll,
    find,
    deletePost,
    findByChapter
}