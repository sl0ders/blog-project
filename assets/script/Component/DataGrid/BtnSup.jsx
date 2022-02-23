import React, {Component, useState} from 'react';
import PostAPI from "../../Services/PostAPI";
import {toast} from "react-toastify";

export default class BtnSup extends Component {
    constructor(props) {
        super(props);

        this.state = {
            cellValue: BtnSup.getValueToDisplay(props)
        }
    }

    // update cellValue when the cell's props are updated
    static getDerivedStateFromProps(nextProps) {
        return {
            cellValue: BtnSup.getValueToDisplay(nextProps)
        };
    }

    handleDelete = async (id) => {
        const [posts, setPosts] = useState([])
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

    render() {
        return (
            <span>
             <span>{this.state.cellValue}</span>&nbsp;
                <button className="btn btn-danger btn-sm" onClick={() => this.handleDelete()}>Push For Total</button>
           </span>
        );
    }

    static getValueToDisplay(params) {
        return params.valueFormatted ? params.valueFormatted : params.value;
    }
};