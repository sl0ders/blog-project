import React from 'react';
import moment from "moment";
import {Link} from "react-router-dom";

const PostList = ({posts}) => {
    const formatDate = (str) => moment(str).format("DD/MM/YYYY")
    return (
        <>
            <div className="container">
                {posts.map(post =>
                    <Link to={`/post/${post.id}`} key={post.id} className="text-decoration-none text-black-50">
                        <div className="shadow p-5 mb-5 bg-white rounded scale">
                            <h1 className="section-heading text-black d-flex justify-content-between">{post.title}
                                {post.pictures[0] && (<img src={post.pictures[0]?.name} alt="" width="200"/>)}</h1>
                            <p><em>{post.chapter.number} - {post.chapter.name}</em></p>
                            <p>
                                {post.content.length > 200 ? (
                                    post.content.slice(0, 200)
                                ) : post.content}
                            </p>
                            <em className="float-end d-flex justify-content-between">
                                <span className="fw-bold text-black">{post.author.fullname}</span>
                                <span className="fw-bold text-black">{formatDate(post.createdAt)}</span>
                            </em>
                        </div>
                    </Link>)}
            </div>
        </>
    )
}

export default PostList