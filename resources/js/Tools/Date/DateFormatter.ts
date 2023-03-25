export default class DateFormatter {
    public static formatDate(date: Date) {
        let year = date.getFullYear();
        let month: string|number = date.getMonth();
        let day: string|number = date.getDate();

        if (month < 10) {
            month = `0${month}`;
        }

        if (day < 10) {
            day = `0${day}`;
        }

        return `${day}.${month}.${year}`;
    }

    public static formatTime(date: Date) {
        let hour: string|number = date.getHours();
        let minute: string|number = date.getMinutes();

        if (hour < 10) {
            hour = `0${hour}`;
        }

        if (minute < 10) {
            minute = `0${minute}`;
        }

        return `${hour}:${minute}`;
    }
}
