export default class DateCompare {
    public static compareDate(first: Date, second: Date, withoutTime: boolean = true, strict: boolean = false) {
        if (withoutTime) {
            first.setHours(0);
            second.setHours(0);
        }

        if (strict) {
            return first.getTime() < second.getTime();
        } else {
            return first.getTime() <= second.getTime();
        }
    }

    public static equalDate(first: Date, second: Date, withoutTime: boolean = true) {
        if (withoutTime) {
            first.setHours(0);
            second.setHours(0);
        }

        return first.getTime() === second.getTime();
    }
}
