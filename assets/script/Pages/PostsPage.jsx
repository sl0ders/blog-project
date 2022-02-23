import React, {useEffect, useMemo, useState, useRef} from 'react';
import PostAPI from "../Services/PostAPI";
import {toast} from "react-toastify";
import 'ag-grid-community/dist/styles/ag-grid.css';
import 'ag-grid-community/dist/styles/ag-theme-alpine.css';
import {POST_API} from "../Services/config";
import axios from "axios";
import {Link} from "react-router-dom";

const PostsPage = () => {
    const [posts, setPosts] = useState([])

    useEffect(() => {
        axios.get(POST_API).then(response => {
            const data = response.data["hydra:member"]
            setPosts(data)
        })
    }, [])

    const handleDelete = async (id) => {
        const postOriginal = [...posts]
        setPosts(posts.filter(post => post.id !== id))
        try {
            await PostAPI.deletePost(id)
            toast.success("Suppression de l'article effectué")
        } catch (error) {
            toast.error("La suppression de l'article a echoué")
            setPosts(postOriginal)
        }
    }

    return (
        <div className="container mt-5">
            <div className="col-md-12">
                <table className="table table-hover table-responsive table-striped">
                    <thead>
                    <tr>
                        <td className="text-center">Id<i className="fas fa-arrow-down-arrow-up"/></td>
                        <td className="text-center">Titre<i className="fas fa-arrow-down-arrow-up"/></td>
                        <td className="text-center">Auteur<i className="fas fa-arrow-down-arrow-up"/></td>
                        <td className="text-center">Chapitre<i className="fas fa-arrow-down-arrow-up"/></td>
                        <td className="text-center">Extrait Contenu</td>
                        <td className="text-center">Actions</td>
                    </tr>
                    </thead>
                    <tbody>
                    {posts.map(post =>
                        <tr key={post.id}>
                            <td>{post.id}</td>
                            <td>{post.title}</td>
                            <td>{post.author.fullname}</td>
                            <td>{post.chapter.name}</td>
                            <td>{post.content.substring(0,300)}...</td>
                            <td className="btn-link">
                                <Link to={"/post/edit/" + post.id} className="btn btn-warning btn-sm mr-2"> <i className="fas fa-edit"/></Link>
                                <button
                                    onClick={() => handleDelete(post.id)}
                                    className="btn btn-danger btn-sm"
                                    disabled={post.pictures.length > 0}
                                    >
                                    <i className="fas fa-minus"/>
                                </button>
                                <Link to={"/post/" + post.id} className="btn btn-primary btn-sm mr-2"> <i className="fas fa-eye"/></Link>
                            </td>
                        </tr>
                    )}
                    </tbody>
                </table>
            </div>
        </div>
    )
}

export default PostsPage