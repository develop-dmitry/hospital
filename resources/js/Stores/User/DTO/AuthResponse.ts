export default class AuthResponse {
    private readonly _success: boolean;

    private readonly _message: string;

    constructor(success: boolean, message: string) {
        this._success = success;
        this._message = '';

        if (message) {
            this._message = message;
        } else if (!this._success) {
            this._message = 'При выполнении запроса произошла ошибка, попробуйте позднее';
        }
    }

    get success(): boolean {
        return this._success;
    }

    get message(): string {
        return this._message;
    }
}
