export default class ChooseDatesResponse {
    private readonly _success: boolean;

    private readonly _message: string;

    constructor(success: boolean, message: string) {
        this._success = success;
        this._message = message;
    }

    get success(): boolean {
        return this._success;
    }

    get message(): string {
        return this._message;
    }
}
