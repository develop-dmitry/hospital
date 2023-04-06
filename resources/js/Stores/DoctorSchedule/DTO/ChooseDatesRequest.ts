export default class ChooseDatesRequest {
    private readonly _dates: Array<Date>;

    constructor(dates: Array<Date>) {
        this._dates = dates;
    }

    get dates(): Array<Date> {
        return this._dates;
    }

    public toRequest() {
        return {
            dates: this.dates.map(date => date.toDateString())
        }
    }
}
