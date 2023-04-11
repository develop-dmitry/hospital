export default class AuthResponse {
    private readonly _success: boolean;

    private readonly _users: Array<{id: number, name: string}>

    constructor(success: boolean, users: Array<{ id: number; name: string }>) {
        this._success = success;
        this._users = users;
    }

    get success(): boolean {
        return this._success;
    }

    get users(): Array<{ id: number; name: string }> {
        return this._users;
    }
}
