
import {createSlice} from "@reduxjs/toolkit";
import StartLoadingShowsAction from "@/Store/Actions/ShowReducer/StartLoadingShowsAction.js";
import LoadingEndAction from "@/Store/Actions/ShowReducer/LoadingEndAction.js";

const initialState ={
    data: [],
    isLoading: false,

};

export const showSlice = createSlice({
    name: 'show',
    initialState,
    reducers: {
        loadShows: StartLoadingShowsAction,
        endLoading: LoadingEndAction
    }
});

export const {loadShows, endLoading} = showSlice.actions;
export default showSlice.reducer;
