import React, {useEffect, useState} from "react"
import axios from "axios";
import {CHAPTER_API, POST_API} from "../Services/config";
import Select from "../Component/forms/Select";
import PostList from "../Component/PostList";

export default function HomePage() {
    const [posts, setPosts] = useState([])
    const [chapters, setChapters] = useState([])


    const handleChange = (event) => {
        axios.get(CHAPTER_API + "/" + event.target.value + "/posts").then(response => {
            const data = response.data["hydra:member"]
            setPosts(data)
        })
    }

    useEffect(() => {
        axios.get(CHAPTER_API).then(response => {
            const data = response.data["hydra:member"]
            setChapters(data)
        })
    }, [])

    return (
        <>
            <div className="container">
                Selectionnez votre chapitre
                <div className="col-md-3">
                    <Select id="chaptersSelect" onChange={(event) => handleChange(event)}>
                        {chapters.map(chapter =>
                            <option
                                key={chapter.id}
                                className="list-unstyled"
                                value={chapter.id}
                            >
                                Chapitre n°{chapter.number} - {chapter.name}
                            </option>
                        )}
                    </Select>
                </div>
            </div>
            <PostList posts={posts}/>
        </>

    );
}