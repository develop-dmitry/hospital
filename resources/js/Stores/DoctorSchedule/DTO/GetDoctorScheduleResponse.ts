export default class GetDoctorScheduleResponse {
    private readonly _items: Array<Date>;

    private readonly _success: boolean;

    private readonly _message: string;

    constructor(success: boolean, items: Array<Date>, message: string) {
        this._items = items;
        this._success = success;
        this._message = message;
    }

    get items(): Array<Date> {
        return this._items;
    }

    get success(): boolean {
        return this._success;
    }

    get message(): string {
        return this._message;
    }
}
