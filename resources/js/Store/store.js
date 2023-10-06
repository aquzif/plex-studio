import { configureStore } from '@reduxjs/toolkit'
import authReducer from "./Reducers/AuthReducer";
import searchbarReducer from "./Reducers/SearchBarReducer.js";
import LocalStorageUtils from "@/Utils/LocalStorageUtils.js";
import showReducer from "@/Store/Reducers/ShowReducer.js";
import settingsReducer from "@/Store/Reducers/SettingsReducer.js";

const store = configureStore({
    reducer: {
        authReducer,
        showReducer,
        searchbarReducer,
        settingsReducer,
    },
});

store.subscribe(LocalStorageUtils.saveState);

export default store;
