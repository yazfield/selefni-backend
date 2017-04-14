import Vue from "vue";
import Vuex from "vuex";
import VueRouter from "vue-router";
import {sync} from "vuex-router-sync";
import axios from "axios";
import VueMaterial from "vue-material";
import infiniteScroll from "vue-infinite-scroll";
import AppTheme from "../sass/AppTheme";
import App from "./components/App";
import {baseDomain} from "./constants";
import store from "./store";
import {router} from "./router";

Vue.use(Vuex);
Vue.use(VueRouter);
Vue.use(VueMaterial);
Vue.use(infiniteScroll);


Vue.material.registerTheme('default', AppTheme);

Vue.component('App', App);

axios.defaults.baseUrl = baseDomain;
Vue.prototype.$http = axios;
Vue.$http = axios;
window.Vue = Vue;

axios.interceptors.request.use(function (config) {
    if (store.getters.getToken) {
        config.headers.Authorization = store.getters.getToken.getAuthorizationHeader();
    }
    return config;
}, function (error) {
    return Promise.reject(error);
});

// FIXME: not sure if useful
sync(store, router);

const app = new Vue({
    el: '#app',
    store,
    router
});