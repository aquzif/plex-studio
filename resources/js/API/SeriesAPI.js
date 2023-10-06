import RequestUtils from "@/Utils/RequestUtils.js";
import store from "@/Store/store.js";
import {endLoading, loadShows} from "@/Store/Reducers/ShowReducer.js";

export default class SeriesAPI {

    static async getSeries(){
        return await RequestUtils.apiGet('/api/show');
    }

    static async addNewShow(data){
        return await RequestUtils.apiPost('/api/show',data);
    }

    static async  updateShow(data){
        return await RequestUtils.apiPut(`/api/show/${data.id}`,data);
    }

    static async deleteShow(id){
        return await RequestUtils.apiDelete(`/api/show/${id}`);
    }

    static refreshSeries(){
        store.dispatch(loadShows());
        SeriesAPI.getSeries().then(data => {
            store.dispatch(endLoading(data.data));
        });
    }



}
