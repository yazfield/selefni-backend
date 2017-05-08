import Vue from "vue";
import Vuex from "vuex";
import {actions} from "./actions";
import {mutations} from "./mutations";
import {getters} from "./getters";
import * as modules from "./modules";
import {templates} from "../constants";

Vue.use(Vuex);

const persists = [
    modules.auth.persist
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
    modules,
    strict: debug,
    plugins: [...persists]
});