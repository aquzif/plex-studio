import RequestUtils from "@/Utils/RequestUtils.js";

export default class EpisodesAPI {
    static updateEpisode = async (data) => {
        return await RequestUtils.apiPut(`/api/episode/${data.id}`, data);
    }
}
