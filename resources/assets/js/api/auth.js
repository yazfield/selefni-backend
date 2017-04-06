import Vue from 'vue';
import * as constants from './constants';
import {oauthParameters} from './config';

export default {
    register(data) {
        return Vue.$http.post(constants.REGISTER_ROUTE, data);
        /*.then(response => {
            console.log(response.data);
            context.success = true
        }, response => {
            console.log(response.data);
            context.response = response.data;
            context.error = true;
        })*/
    },
    login(credentials) {
        let data = {
            ...oauthParameters,
            ...credentials
        }
        return Vue.$http.post(
            constants.LOGIN_ROUTE,
            data,
        ).then(response => Promise.resolve(response.data));
        /*.then(response => {
            context.errors = {};
            localStorage.setItem('token', response.data);
            context.error = false;
            localStorage.setItem('id_token', response.data.meta.token);
            context.$http.headers.common['Authorization'] = 'Bearer ' + localStorage.getItem('id_token');
            context.user.authenticated = true;
            context.user.profile = response.data.data;
        })*/
    }
}