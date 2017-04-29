import Vue from "vue";
import Vuex from "vuex";
import VueRouter from "vue-router";
import {sync} from "vuex-router-sync";
import axios from "axios";
import VueMaterial from "vue-material";
import infiniteScroll from "vue-infinite-scroll";
import VueI18n from "vue-i18n";
import AppTheme from "../sass/AppTheme";
import App from "./components/App";
import {baseDomain} from "./constants";
import store from "./store";
import {router} from "./router";
import SlideTransition from "./components/BasicSlideTransition";
import locales from "./locales";

Vue.use(Vuex);
Vue.use(VueRouter);
Vue.use(VueMaterial);
Vue.use(infiniteScroll);
Vue.use(VueI18n);

Vue.material.registerTheme('default', AppTheme);

Vue.component('App', App);
Vue.component('slide-transition', SlideTransition);

const instance = axios.create();
instance.defaults.baseUrl = baseDomain;
Vue.prototype.$http = instance;
Vue.$http = instance;
window.Vue = Vue;
window.axios = axios;

Vue.$http.interceptors.request.use(function (config) {
    if (store.getters.getToken) {
        config.headers.Authorization = store.getters.getToken.getAuthorizationHeader();
    }
    return config;
}, function (error) {
    return Promise.reject(error);
});

// FIXME: not sure if useful
sync(store, router);

const i18n = new VueI18n({
    locale: store.state.locale,
    fallbackLocale: 'en',
    messages: locales // set locale messages
});

const app = new Vue({
    el: '#app',
    store,
    router,
    i18n
});