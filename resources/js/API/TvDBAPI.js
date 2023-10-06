import RequestUtils from "@/Utils/RequestUtils.js";

export default class TvDBAPI {

    static searchForShows = async query => {

        let result = await RequestUtils.apiGet(`/api/tvdb/search/?query=${query}`);

        return result.data;
        //return await
    }

    static getSeasonsTypes = async (id) => {

        let result = await RequestUtils.apiGet(`/api/tvdb/show/${id}/seasons`);

        return result.data;

    }

}
