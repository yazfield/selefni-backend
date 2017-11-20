import Vue from "vue";
import * as constants from "./constants";
import {oauthParameters} from "./config";

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
        };
        return Vue.$http.post(
            constants.LOGIN_ROUTE,
            data,
        ).then(response => Promise.resolve(response.data));
    },
    logout() {
        return Vue.$http.post(
            constants.LOGOUT,
        ).then(response => Promise.resolve(response.data));
    },
    getProfile() {
        return Vue.$http.get(
            constants.PROFILE,
        ).then(response => Promise.resolve(response.data));
    }
}