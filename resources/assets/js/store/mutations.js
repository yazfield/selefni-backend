import * as types from "./mutation-types";
import {templates} from "../constants";

export const mutations = {
    [types.CHANGE_TEMPLATE] (state, template) {
        if(template in templates) {
            state.template = template;
        } else {
            state.template = templates.default;
        }
    },
    [types.SET_LOCALE] (state, locale) {
        state.locale = locale;
    }
};