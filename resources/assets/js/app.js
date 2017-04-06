import Vue from 'vue';
import Vuex from 'vuex';
import VueRouter from 'vue-router';
import { sync } from 'vuex-router-sync'
import axios from 'axios';
import VueMaterial from 'vue-material';
import App from './components/App';
import Header from './components/Header';
import NewItem from './components/NewItem';
import SideBar from './components/SideBar';
import {baseDomain} from './constants';
import store from './store';
import {router} from './router';

Vue.use(Vuex);
Vue.use(VueRouter);
Vue.use(VueMaterial);

Vue.component('App', App);
Vue.component('Header', Header);
Vue.component('NewItem', NewItem);
Vue.component('SideBar', SideBar);

axios.defaults.headers.common = {
    'X-CSRF-TOKEN': window.Laravel.csrfToken,
    'X-Requested-With': 'XMLHttpRequest'
 };
axios.defaults.baseUrl = baseDomain;
Vue.prototype.$http = axios;
Vue.$http = axios;

sync(store, router);

const app = new Vue({
  el: '#app',
  store,
  router
})