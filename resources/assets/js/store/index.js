import Vue from 'vue';
import Vuex from 'vuex';
import {actions} from './actions';
import {mutations} from './mutations';
import {getters} from './getters';
import auth from './modules/auth';
import {templates} from '../constants';
import {Token} from './modules/utils';

Vue.use(Vuex);

const debug = process.env.NODE_ENV !== 'production';

const state = {
    template: templates.default
};
export default new Vuex.Store({
    state,
    actions,
    mutations,
    getters,
    modules: {
        auth
    },
    strict: debug
});