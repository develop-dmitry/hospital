import {defineStore} from "pinia";
import AuthRequest from "./DTO/AuthRequest";
import Ajax from "../../Tools/Ajax";
import AuthResponse from "./DTO/AuthResponse";
import SearchUserRequest from "./DTO/SearchUserRequest";
import SearchUserResponse from "./DTO/SearchUserResponse";

export const useUserStore = defineStore('userStore', {
    actions: {
        async auth(request: AuthRequest) {
            const response = await Ajax.post('/user/auth', request.toRequest());

            return new AuthResponse(response.success, response.message);
        },

        async search(request: SearchUserRequest) {
            const response = await Ajax.post('/user/search', request.toRequest());
            const users: Array<{id: number, name: string}> = [];

            if (response.success) {
                response.users.forEach((user: {id: any, name: string}) => {
                    users.push({
                        id: Number.parseInt(user.id),
                        name: user.name
                    })
                })
            }

            return new SearchUserResponse(response.success, users);
        }
    }
})
