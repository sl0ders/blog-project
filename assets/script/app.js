import {Routes, Route, HashRouter} from "react-router-dom";
import React, {useState} from "react";
import '../styles/app.scss';
import ReactDOM from "react-dom";
import AuthContext from "./Context/AuthContext";
import HomePage from "./Pages/HomePage";
import {Navbar} from "./Component/Navbar";
import UsersPage from "./Pages/UsersPage";
import PostsPage from "./Pages/PostsPage";
import PrivateRoute from "./Component/PrivateRoute";
import PostPage from "./Pages/PostPage";
import {LoginPage} from "./Pages/LoginPage";
import AuthAPI from "./Services/AuthAPI";
import {toast, ToastContainer} from "react-toastify";
import RegisterPage from "./Pages/RegisterPage";
import "react-toastify/dist/ReactToastify.css"
import NewPostPage from "./Pages/NewPostPage";
import $ from "jquery"
import 'datatables.net'
import 'datatables.net-bs4'
import PostEditPage from "./Pages/PostEditPage";


$("#datatable").DataTable({
    language: {
        url: '//cdn.datatables.net/plug-ins/1.10.15/i18n/French.json'
    },
    pageLength: 50,
    autoWidth: false
})


let star1 = $("#star-1")
let star2 = $("#star-2")
let star3 = $("#star-3")
let star4 = $("#star-4")
let star5 = $("#star-5")
star1.hover(() => {
    star1.toggleClass("fas")
})
star2.hover(() => {
    star1.toggleClass("fas")
    star2.toggleClass("fas")
})
star3.hover(() => {
    star1.toggleClass("fas")
    star2.toggleClass("fas")
    star3.toggleClass("fas")
})
star4.hover(() => {
    star1.toggleClass("fas")
    star2.toggleClass("fas")
    star3.toggleClass("fas")
    star4.toggleClass("fas")
})
star5.hover(() => {
    star1.toggleClass("fas")
    star2.toggleClass("fas")
    star3.toggleClass("fas")
    star4.toggleClass("fas")
    star5.toggleClass("fas")
})


function App() {
    const [isAuthenticated, setIsAuthenticated] = useState(AuthAPI.isAuthenticated())

    return (
        <AuthContext.Provider value={{isAuthenticated, setIsAuthenticated}}>
            <HashRouter>
                <Navbar isAuthenticated={isAuthenticated} onLogout={setIsAuthenticated}/>
                <Routes>
                    <Route path="/login" element={<LoginPage onLogin={setIsAuthenticated}/>}/>
                    <Route path="/register" element={<RegisterPage onRegister/>}/>
                    <Route exact path="/post/:param" element={<PostPage isAuthenticated={isAuthenticated}/>}/>
                    <Route path="/" element={<HomePage/>}/>
                    <Route exact path='/' element={<PrivateRoute/>}>
                        <Route path="/users" element={<UsersPage/>}/>
                        <Route path="/posts" element={<PostsPage/>}/>
                        <Route exact path="/post/edit/:id" element={<PostEditPage/>}/>
                        <Route exact path="/post/new" element={<NewPostPage/>}/>
                    </Route>
                </Routes>
                <ToastContainer position={toast.POSITION.BOTTOM_RIGHT}/>
            </HashRouter>
        </AuthContext.Provider>
    );
}

const rootElement = document.querySelector("#app");
ReactDOM.render(<App/>, rootElement)
