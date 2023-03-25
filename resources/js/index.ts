import Authorization from "./Components/Authorization/Authorization.vue";
import UploadAnalyzes from "./Components/Analyzes/UploadAnalyzes/UploadAnalyzes.vue";
import AnalyzesList from "./Components/Analyzes/AnalyzesList/AnalyzesList.vue";
import ScheduleList from "./Components/Schedule/ScheduleList/ScheduleList.vue";
import ChooseSchedule from "./Components/Schedule/ChooseSchedule/ChooseSchedule.vue";

import "../scss/style.scss";

import ComponentFactory from "./Tools/ComponentFactory";

document.addEventListener('DOMContentLoaded', () => {
    const componentFactory = new ComponentFactory();

    componentFactory.makeComponent('#authorization', Authorization);
    componentFactory.makeComponent('#upload-analyzes', UploadAnalyzes);
    componentFactory.makeComponent('#analyzes-list', AnalyzesList);
    componentFactory.makeComponent('#schedule-list', ScheduleList);
    componentFactory.makeComponent('#schedule-choose', ChooseSchedule);
})
