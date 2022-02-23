import axios from "axios";
import {COMMENT_API, POST_API} from "./config";

async function findByPost(id) {
    await axios.get(POST_API + "/" + id + "/comments")
        .then(Response.data)
}

async function add(comment) {
    return axios.post(COMMENT_API, comment)
        .then((response) => {
            response.data
        })
}

export default {
    findByPost,
    add
}