export default class GetBusyDatesResponse {
    private readonly _dates: Array<Date>;

    private readonly _success: boolean;

    private readonly _message: string;

    constructor(dates: Array<Date>, success: boolean, message: string) {
        this._dates = dates;
        this._success = success;
        this._message = message;
    }

    get dates(): Array<Date> {
        return this._dates;
    }

    get success(): boolean {
        return this._success;
    }

    get message(): string {
        return this._message;
    }
}
