import axios from "axios";

export default class Ajax {
    private static api = '/api/v1';

    public static async post(url: string, params: any) {
        const response = await axios.post(this.api + url, params);

        return response.data;
    }
}
