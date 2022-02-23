import React, {useContext, useEffect, useState} from "react";
import {useParams} from "react-router-dom";
import FormContentLoader from "../Component/loaders/FormContentLoader";
import moment from "moment";
import {toast} from "react-toastify";
import {Navigate} from "react-router-dom";
import PostAPI from "../Services/PostAPI";
import Textarea from "../Component/forms/Textarea";
import CommentAPI from "../Services/CommentAPI";
import AuthContext from "../Context/AuthContext";

const PostPage = () => {
    const {isAuthenticated} = useContext(AuthContext);
    const {param: postId} = useParams()
    const formatDate = (str) => moment(str).format("DD/MM/YYYY")
    const [post, setPost] = useState(null);
    const [loading, setLoading] = useState(true)
    const [comment, setComment] = useState({
        content: "",
        createdAt: new Date(),
        isEnabled: true,
        post: "/api/posts/" + postId,
        author: "/api/users/19"
    })

    const [errors, setErrors] = useState({
        content: "",
    })

    const fetchPost = async (id) => {
        try {
            setPost(await PostAPI.find(id))
            setLoading(false)
            toast.success("Les informations de l'article on bien été récuperer")
        } catch (error) {
            toast.error("Échec lors de la récuperation du client")
            Navigate('/posts')
        }
    }

    //Récuperation du post en fonction de l'identité
    useEffect(() => {
        fetchPost(postId)
        console.log()
    }, [postId]);

    const handleChange = ({currentTarget}) => {
        const {name, value} = currentTarget;
        setComment({...comment, [name]: value})
    }

    const handleSubmit = async () => {
        await CommentAPI.add(comment)
        toast.success("Création du commentaire reussi")
        Navigate("/post/" + post.id)
    }

    return (
        <>
            {loading && <FormContentLoader/>}
            {!loading && (<div className="contenant">
                <img className="book-img" src="../../img/book-open-4-mono.png" alt="image"/>
                <em className="book-chapter-title">{post.chapter.title}</em>
                <div className="texte_centrer w-100">
                    <div className="book-content row">
                        <div className="left-page col-md-4">
                            <div className="post-title">
                                <h1 className="section-heading text-center">{post.title}</h1>
                            </div>
                            <div className="book-text">
                                <p className="text1">{post.content}</p>
                            </div>
                        </div>
                        <div className="right-page col-md-5 mt-5">
                            {post.pictures.length > 0 && (
                                <img src={post.pictures[0].name} width="500"/>)}
                        </div>
                    </div>
                </div>
                <div className="btn btn-success nextpage">Suivant</div>
                <div className="text-center user-rate">
                    <a id="star-1" className="text-decoration-none text-warning far fa-star fa-3x"/>
                    <a id="star-2" className="text-decoration-none text-warning far fa-star fa-3x"/>
                    <a id="star-3" className="text-decoration-none text-warning far fa-star fa-3x"/>
                    <a id="star-4" className="text-decoration-none text-warning far fa-star fa-3x"/>
                    <a id="star-5" className="text-decoration-none text-warning far fa-star fa-3x"/>
                </div>
                <div className="col-md-9 m-auto">
                    <em className="h5 d-flex">
                        <i className="text-warning fas fa-star"/>
                        2/5&nbsp;
                        <div className="h6 d-flex mt-1 fw-bold">
                            <div className="rateLength">2</div>
                        </div>
                    </em>
                    <h4 className="mt-5 mb-5">{post.comments ? post.comments.count : ""}
                        Commentaire{post.comments.length > 1 && ("s")}
                    </h4>
                    {post.comments.map((comment, index) =>
                        <div key={index}>
                            <h6>
                                <em>{comment.author.fullname}</em>
                                <em> le {formatDate(comment.createdAt)}</em>
                            </h6>
                            <p>{comment.content}</p>
                            <hr/>
                            <br/>
                        </div>)}
                </div>
                {isAuthenticated === true ?
                    <form onSubmit={handleSubmit} className="container">
                        <Textarea
                            error={errors.content}
                            className="form-control"
                            placeholder="Contenu de l'article"
                            label="commentaire"
                            type="text"
                            onChange={handleChange}
                            name="content"
                            value={comment.content}
                        />
                        <button type="submit" className="btn btn-success btn-sm">Poster le commentaire</button>
                    </form> :
                    <h3 className="m-auto text-danger text-center mb-5">Vous devez etre connecté avec un compte pour
                        poster un commentaires</h3>}
            </div>)}
        </>
    )
}
export default PostPage
