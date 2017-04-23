import * as types from "./mutation-types";

export const actions = {
    changeTemplate({commit}, template) {
        commit(types.CHANGE_TEMPLATE, template);
    },
    setLocale({commit}, locale) {
        commit(types.SET_LOCALE, locale);
    }
};
