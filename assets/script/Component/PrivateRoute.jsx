import React, {useContext} from 'react';
import { Navigate, Outlet } from 'react-router-dom';
import AuthContext from "../Context/AuthContext";

const PrivateRoute = () => {
    const {isAuthenticated} = useContext(AuthContext);

    // If authorized, return an outlet that will render child elements
    // If not, return element that will navigate to login page
    return isAuthenticated ? <Outlet /> : <Navigate to="/login" />;
}
export default PrivateRoute