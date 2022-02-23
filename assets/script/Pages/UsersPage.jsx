import React, {useEffect, useState} from "react";
import UserAPI from "../Services/UserAPI";
import {Link} from "react-router-dom";
import {toast} from "react-toastify"

const UsersPage = () => {
    const [users, setUsers] = useState([])

    useEffect(async () => {
        try {
            const data = await UserAPI.findAll()
            setUsers(data)
        } catch (error) {
            console.log(error)
        }
    }, [])

    /**
     * @param id
     * Function de suppresion d'un user
     */
    const handleDelete = async id => {
        /** Je créer une copie du tableau de user original */
        const usersOriginal = [...users]
        /** je modifie le tableau de users en filtrant l'id de l'utilisateur passé en parametre pour le supprimer visuelement */
        setUsers(users.filter(user => user.id !== id))
        /** et je tente de supprimer le l'utilisateur de la base de donnée */
        try {
            await UserAPI.deleteUsers(id)
            toast.success("Suppression de l'utilisateur effectuée")
        } catch (error) {
            console.log(error)
            /** si la suppression a echoué on renvoi le tavbleau d'utilisateur d'origine */
            toast.error("Suppression de l'utilisateur échoué")
            setUsers(usersOriginal)
        }
    }

    return (
        <div>
            <table className="table table-hover">
                <thead>
                <tr>
                    <th>Id</th>
                    <th>Nom complet</th>
                    <th>Email</th>
                    <th>Actions</th>
                    <th/>
                </tr>
                </thead>
                <tbody>
                {users.map(user =>
                    <tr key={user.id}>
                        <td>{user.id}</td>
                        <td>
                            <Link to={"/user/" + user.id}>{user.firstname} {user.lastname}</Link>
                        </td>
                        <td>{user.email}</td>
                        <td className="btn-link">
                            <Link to={"/user/" + user.id} className="btn btn-primary btn-sm mr-2">Éditer</Link>
                            <button
                                onClick={() => handleDelete(user.id)}
                                className="btn btn-sm btn-danger"
                                disabled={user.posts.length > 0}>
                                Supprimer
                            </button>
                        </td>
                    </tr>)}
                </tbody>
            </table>
        </div>
    );
}

export default UsersPage