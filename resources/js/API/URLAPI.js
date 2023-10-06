import RequestUtils from "@/Utils/RequestUtils.js";

export default class URLAPI{

    static insertURL = async (data) => {
        return await RequestUtils.apiPost('/api/url',data);
    }

    static updateURL = async (data) => {
        return await RequestUtils.apiPut(`/api/url/${data.id}`,data);
    }

    static deleteURL = async (id) => {
        return await RequestUtils.apiDelete(`/api/url/${id}`);
    }

}

