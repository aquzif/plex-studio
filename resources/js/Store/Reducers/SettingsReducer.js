import {createSlice} from "@reduxjs/toolkit";
import UpdateSearchbarAction from "@/Store/Actions/SearchBar/UpdateSearchbarAction.js";
import UpdateSettingsAction from "@/Store/Actions/SettingsReducer/UpdateSettingsAction.js";
import LocalStorageUtils from "@/Utils/LocalStorageUtils.js";


const initialState = {
    //main show list
    showOnlyFavorites: false,
    showOnlyNotCompleted: false,
    sortBy: 'name',
    sortDirection: 'asc',
    activeMainTab: 0,

    //episodes
    hideDownloadedEpisodes: false,

    ...LocalStorageUtils.getData('settings')
}

export const settingsSlice = createSlice({
    name: 'settings',
    initialState,
    reducers: {
        updateSettings: UpdateSettingsAction,
    }
})

export const {updateSettings} = settingsSlice.actions;

export default settingsSlice.reducer;
