import Authorization from "./Components/Authorization/Authorization.vue";
import UploadAnalyzes from "./Components/Analyzes/UploadAnalyzes/UploadAnalyzes.vue";
import AnalyzesList from "./Components/Analyzes/AnalyzesList/AnalyzesList.vue";

import "../scss/style.scss";

import ComponentFactory from "./Tools/ComponentFactory";

document.addEventListener('DOMContentLoaded', () => {
    const componentFactory = new ComponentFactory();

    componentFactory.makeComponent('#authorization', Authorization);
    componentFactory.makeComponent('#upload-analyzes', UploadAnalyzes);
    componentFactory.makeComponent('#analyzes-list', AnalyzesList);
})
