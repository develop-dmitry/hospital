import {defineStore} from "pinia";
import AuthRequest from "./DTO/AuthRequest";
import Ajax from "../../Tools/Ajax";
import AuthResponse from "./DTO/AuthResponse";

export const useUserStore = defineStore('userStore', {
    actions: {
        async auth(request: AuthRequest) {
            const response = await Ajax.post('/user/auth', request.toRequest());

            return new AuthResponse(response.success, response.message);
        }
    }
})
