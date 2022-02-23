import React, {useEffect, useState} from 'react';
import Fields from "../Component/forms/Fields";
import Textarea from "../Component/forms/Textarea";
import Select from "../Component/forms/Select";
import {CHAPTER_API} from "../Services/config";
import {toast} from "react-toastify";

const NewPostPage = () => {
    const [chapters, setChapters] = []
    const [post, setPost] = useState({
        chapter: "",
        title: "",
        content: "",
        created: new Date,
        is_enabled: 1
    })
    const [error, setError] = useState({
        chapter: "",
        title: "",
        content: ""
    })

    const fetchChapters = async () => {
        try {
            const data = await CHAPTER_API.findAll()
            toast.success("Les chapitres ont bien été récuperer")
            setChapters(data)
        } catch (e) {
            toast.error("Echec de la recuperation des chapitres")
        }
    }

    useEffect(() => {
        fetchChapters()
    }, [])


    const handleChange = ({currentTarget}) => {
        const {name, value} = currentTarget;
        setPost({...post, [name]: value})
    }

    const handleSubmit = (e) => {
        e.preventDefault();
    }
    return (
        <>
            <form handleSubmit={handleSubmit}>
                <Fields label="Titre" value={post.title} onChange={handleChange} error={error.title} name="title"
                        placeholder={"Titre"}/>
                <Textarea label="Contenue" value={post.content} onChange={handleChange} error={error.content}
                          name="content" placeholder={"Contenu"}/>
                <Select label="Titre" value={post.title} onChange={handleChange} error={error.title} name="title"
                        type="text" placeholder={"Titre"}>
                    {chapters.map(chapter => (
                        <option key={chapter.id} value={chapter.id}>
                            {chapter.number} - {chapter.name}
                        </option>
                    ))}
                </Select>
                <Fields label="Titre" value={post.title} onChange={handleChange} error={error.title} name="title"
                        type="text" placeholder={"Titre"}/>
            </form>
        </>
    )
}

export default NewPostPage