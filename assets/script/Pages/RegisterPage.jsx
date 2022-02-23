import * as React from 'react';
import {useState} from "react";
import Fields from "../Component/forms/Fields";
import UserAPI from "../Services/UserAPI";
import {toast} from "react-toastify";
import {Link, useNavigate} from "react-router-dom";

const RegisterPage = () => {

    const [user, setUser] = useState({
        firstname: "",
        lastname: "",
        state: 'registered',
        email: "",
        password: "",
        passwordConfirm: ""
    })

    const [errors, setErrors] = useState({
        firstname: "",
        lastname: "",
        email: "",
        password: "",
        passwordConfirm: ""
    })

    const handleChange = ({currentTarget}) => {
        setErrors({})
        const {name, value} = currentTarget;
        setUser({...user, [name]: value})
    }

    const handleSubmit = async event => {
        event.preventDefault();
        const apiErrors = {}
        /** verification de la concordance password -> passwordConfirm; */
        if (user.password !== user.passwordConfirm) {
            apiErrors.passwordConfirm = "Votre confirmation de mot de passe est invalide"
            setErrors(apiErrors)
            return
        }
        try {
            /** Test d'une creation d'utilisateur avec le user donné */
            const response = await UserAPI.add(user)
            toast.success("Felicitation votre compte a bien été créé")
            useNavigate("/login")
            console.log(response)
        } catch (error) {
            /** Si l'insertion a echoué */
            toast.error("Echec de l'enregistrement, une erreur a eu lieu")
            console.log(error.response)
            /** On recupere les differente erreur qui on eu lieu */
            const {violations} = error.response.data
            if (violations) {
                /** On boucle sur celle ci */
                violations.forEach((violation) => {
                    /** On recupere les message de chacune d'entre elle et on les insert dans la variable apiError [] */
                    apiErrors[violation.propertyPath] = violation.message
                })
                /** Et on affiche le tout */
                setErrors(apiErrors)
            }
        }
    }

    return (
        <div className="Container m-5 border p-5">
            <form onSubmit={handleSubmit}>
                <Fields
                    name="firstname"
                    type="text"
                    label="Prénom"
                    placeholder="Prénom"
                    className="form-control"
                    onChange={handleChange}
                    value={user.firstname}
                    error={errors.firstname}
                />

                <Fields
                    name="lastname"
                    type="text"
                    label="Nom de famille"
                    placeholder="Nom de famille"
                    className="form-control"
                    onChange={handleChange}
                    value={user.lastname}
                    error={errors.lastname}
                />

                <Fields
                    name="email"
                    type="email"
                    label="Email"
                    placeholder="Email"
                    className="form-control"
                    onChange={handleChange}
                    value={user.email}
                    error={errors.email}
                />

                <Fields
                    name="password"
                    type="password"
                    label="Mot de passe"
                    placeholder="Mot de passe"
                    className="form-control"
                    onChange={handleChange}
                    value={user.password}
                    error={errors.password}
                />

                <Fields
                    name="passwordConfirm"
                    type="password"
                    label="Confirmation du mot de passe"
                    placeholder="Confirmation du mot de passe"
                    className="form-control"
                    onChange={handleChange}
                    value={user.passwordConfirm}
                    error={errors.passwordConfirm}
                />

                <button type='submit' className='btn btn-success'>Vous enregistrer</button>

            </form>
            <Link to="/login" className="btn btn-link">J'ai deja un compte</Link>
        </div>
    );
};

export default RegisterPage;
