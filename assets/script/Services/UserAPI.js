import Axios from "axios"
import Cache from "./Cache";
import {USERS_API} from "./config";

async function findAll() {
    // On va chercher les Utilisateurs present dans le cache

    const cachedUsers = await Cache.get("users")

    if (cachedUsers) {
        return cachedUsers
    }

    return Axios.get(USERS_API).then(response => {
        const users = response.data["hydra:member"]
        Cache.set("users", users)
        return users
    })
}

async function deleteUsers(id) {
    return Axios.delete(USERS_API + "/" + id).then(async response => {
        const cachedUsers = await Cache.get("users")
        if (cachedUsers) {
            Cache.set("users", cachedUsers.filter(u => u.id !== id))
        }
        //Dans tout les cas on renvoi la reponse de la requete
        return response
    })
}

async function add(user) {
    return Axios.post(USERS_API, user).then(response => response.data)
}

export default {
    findAll,
    deleteUsers,
    add
}
