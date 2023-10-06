import {BrowserRouter, Navigate, Route, Routes} from "react-router-dom";
import SeriesList from "@/Views/SeriesList.jsx";
import DashboardView from "@/Views/DashboardView.jsx";
import {useSelector} from "react-redux";
import {useEffect, useState} from "react";
import AuthAPI from "@/API/AuthAPI.js";
import LoginView from "@/Views/LoginView.jsx";
import ShowView from "@/Views/ShowView.jsx";
import UrlsView from "@/Views/UrlsView.jsx";

const Router = () => {

    const authReducer = useSelector(state => state.authReducer);
    const [loggedIn,setLoggedIn] = useState(authReducer.token !== null && authReducer.token !== undefined && authReducer.token !== '');

    useEffect(() => {
        setLoggedIn(authReducer.token !== null && authReducer.token !== undefined && authReducer.token !== '');

        const result = AuthAPI.getUser();

    }, [authReducer.token]);

    return (
        <BrowserRouter>
            <Routes>
                {
                    loggedIn ? (
                        <>
                            <Route path="/" element={<DashboardView />} >
                                <Route path="/" element={<SeriesList />} />
                                <Route path="/show/:id/season/:season" element={<UrlsView />} />
                                <Route path="/show/:id" element={<ShowView />} />
                                <Route path="*" element={<Navigate to="/" />} />
                            </Route>
                        </>
                    ):(
                        <Route path="*" element={<LoginView />} />
                    )
                }
            </Routes>
        </BrowserRouter>
    )
}

export default Router;
