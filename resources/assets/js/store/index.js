import Vue from "vue";
import Vuex from "vuex";
import {actions} from "./actions";
import {mutations} from "./mutations";
import {getters} from "./getters";
import auth from "./modules/auth";
import items from "./modules/items";
import notifications from "./modules/notifications";
import {templates} from "../constants";

Vue.use(Vuex);

const persists = [
    auth.persist
];

const debug = process.env.NODE_ENV !== 'production';

const state = {
    template: templates.default,
    locale: 'en'
};
export default new Vuex.Store({
    state,
    actions,
    mutations,
    getters,
    modules: {
        auth,
        items,
        notifications
    },
    strict: debug,
    plugins: [...persists]
});