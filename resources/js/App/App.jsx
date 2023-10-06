import React from 'react';
import Router from "@/Routes/Router.jsx";
import '@fontsource/roboto/300.css';
import '@fontsource/roboto/400.css';
import '@fontsource/roboto/500.css';
import '@fontsource/roboto/700.css';
import {createTheme, ThemeProvider} from "@mui/material";
import {Provider} from "react-redux";
import store from "@/Store/store.js";
import {Toaster} from "react-hot-toast";

const darkTheme = createTheme({
    palette: {
        mode: 'dark',

    },
    typography: {
        allVariants: {
            color: "white"
        },
    },
});
const App = () => {
    return(
        <Provider store={store}>
            <ThemeProvider theme={darkTheme}>
                <Toaster
                    toastOptions={{
                        style: {
                            background: '#333',
                            color: '#fff',
                        }
                    }}
                />
                <Router />
            </ThemeProvider>
        </Provider>
    );
}

export default App;
