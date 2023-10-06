import {createSlice} from "@reduxjs/toolkit";
import UpdateSearchbarAction from "@/Store/Actions/SearchBar/UpdateSearchbarAction.js";


const initialState = '';

export const searchbarSlice = createSlice({
    name: 'searchbar',
    initialState,
    reducers: {
        searchbarUpdate: UpdateSearchbarAction,
    }
})


export const {searchbarUpdate} = searchbarSlice.actions;
export default searchbarSlice.reducer;
