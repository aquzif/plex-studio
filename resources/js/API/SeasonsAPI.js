import RequestUtils from "@/Utils/RequestUtils.js";

export default class SeasonsAPI {
    static async  updateSeason(data){
        return await RequestUtils.apiPut(`/api/season/${data.id}`,data);
    }
}
