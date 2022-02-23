import React, {useEffect, useState} from "react";
import ChapterAPI from "../Services/ChapterAPI";
import {toast} from "react-toastify";
import {Link, Navigate, useParams} from "react-router-dom";
import PostAPI from "../Services/PostAPI";
import Fields from "../Component/forms/Fields";
import Select from "../Component/forms/Select";
import Textarea from "../Component/forms/Textarea";
import FormContentLoader from "../Component/loaders/FormContentLoader";

const PostEditPage = () => {
    const {id: postId} = useParams()
    const [loading, setLoading] = useState(true)
    const [editing, setEditing] = useState(false)
    const [post, setPost] = useState({
        title: '',
        content: '',
        createdAt: new Date(),
        author: "",
        chapter: '',
        is_enabled: 1
    })

    const [chapters, setChapters] = useState([])

    const [error, setError] = useState({
        title: '',
        content: '',
        createdAt: new Date(),
        chapter: '',
        is_enabled: 1
    })

    const fetchChapters = async () => {
        try {
            const data = await ChapterAPI.findAll()
            toast.success("Récupération des chapitre éffectué")
            setChapters(data)
            setLoading(false)
            // Si aucun customer n'a été selectionné, on prend le premier de la liste par default
            if (!post.chapter) {
                setPost({...post, chapter: data[0].id})
            }
        } catch (error) {
            toast.error("Échec de la récuperation du chapitre")
            console.log(error)
        }
    }

    const fetchPost = async id => {
        try {
            const {title, content, created_at, is_enabled, author} = await PostAPI.find(id)
            toast.success("Récuperation de l'article éffectuée")
            setPost({title, content, created_at, is_enabled, post: author.id})
            setLoading(false)
        } catch (error) {
            toast.error("Échec de la récuperation de l'article")
            Navigate('/posts')
        }
    }

    const handleChange = ({currentTarget}) => {
        const {name, value} = currentTarget;
        setPost({...post, [name]: value})
    }

    const handleSubmit = (e) => {

    }

    useEffect(() => {
        fetchChapters()
    }, [])

    useEffect(() => {
        if(postId !== "new") {
            setEditing(true)
            fetchPost(postId)
        }

    }, [post])



    return (
        <>
            {editing && <h1>Modification de la facture</h1> || <h1>Création d'une facture</h1>}
            {loading && <FormContentLoader/>}
            {!loading && (<form onSubmit={handleSubmit}>
                <Fields
                    name="title"
                    type="text"
                    placeholder="Titre de l'article"
                    label="Titre"
                    onChange={handleChange}
                    value={post.title}
                    error={error.title}/>

                <Textarea
                    name="content"
                    label="Contenu"
                    value={post.content}
                    error={error.content}
                    onChange={handleChange}>
                </Textarea>
                <Select
                    name="chapter"
                    label="Chapitre"
                    value={post.chapter}
                    error={error.chapter}
                    onChange={handleChange}
                >
                    {chapters.map(chapter => (
                        <option key={chapter.id} value={chapter.id}>
                            {chapter.number} - {chapter.name}
                        </option>
                    ))}
                </Select>
                <div className="form-group">
                    <button type="submit" className="btn btn-success">Enregistrer</button>
                    <Link to="/posts" className="btn btn-link">
                        Retour aux articles
                    </Link>
                </div>
            </form>)}
        </>
    )
}

export default PostEditPage