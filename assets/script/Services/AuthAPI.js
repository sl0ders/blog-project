import Axios from "axios";
import jwtDecode from "jwt-decode";
import {LOGIN_API} from "./config";

function logout() {
    window.localStorage.removeItem("authToken");
    delete Axios.defaults.headers["Authorization"];
}

function authenticate(credentials) {
    return Axios.post(LOGIN_API, credentials)
        .then((response) => {
            const token = response.data.token
            window.localStorage.setItem("authToken", token)
            Axios.defaults.headers['Authorization'] = "Bearer " + token
            const user = credentials.username
            localStorage.setItem('user', user)
        })
}

function setup() {
    // 1. Verification de l'existence d'un token
    const token = window.localStorage.getItem("authToken")
    // 2. Verification de la validité du token
    if (token) {
        const jwtData = jwtDecode(token)
        if (jwtData.exp * 1000 > new Date().getTime()) {
            Axios.defaults.headers['Authorization'] = "Bearer " + token
            console.log("Connexion avec axios établie")
        }
    }
}

function isAuthenticated() {
    const token = window.localStorage.getItem("authToken")
    if (token) {
        const jwtData = jwtDecode(token)
        if (jwtData.exp * 1000 > new Date().getTime()) {
            Axios.defaults.headers['Authorization'] = "Bearer " + token
            return true
        } else {
            return false
        }
    } else {
        return false
    }
}

export default {
    authenticate,
    logout,
    setup,
    isAuthenticated
}

