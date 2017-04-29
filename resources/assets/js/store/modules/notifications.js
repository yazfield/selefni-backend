/**
 * Created by yzid on 4/27/17.
 */
/**
 * Created by yzid on 4/10/17.
 */
import * as types from "../mutation-types";
import {notifications} from "../../api";
import Echo from "laravel-echo";
import {echoConfig} from "../../constants";
import {includes} from "lodash";

const state = {
    notifications: []
};

const getters = {
    notifications: state => state.notifications,
    hasNotifications: state => state.notifications.length > 0,
};

let echo;

const actions = {
    initNotifications: function ({commit}, {authHeader, userId}) {
        commit(types.LOAD_NOTIFICATIONS_REQUEST);
        return new Promise((resolve, reject) => {
            notifications.getUserNotifications().then((data) => {
                commit(types.LOAD_NOTIFICATIONS_SUCCESS, data);
                resolve();
            }).catch((error) => {
                commit(types.LOAD_NOTIFICATIONS_FAILURE, error);
                reject(error);
            });
        }).then(() => {
            echo = new Echo({
                ...echoConfig,
                auth: {
                    headers: {
                        'Authorization': authHeader
                    }
                }
            });
            echo.private(`App.User.${userId}`)
                .notification((notification) => {
                    commit(types.ADD_NOTIFICATION, notification);
                });
        });
    },
    setNotificationsRead: function ({commit}, ids) {
        return new Promise((resolve, reject) => {
            notifications.setNotificationsRead(ids).then((data) => {
                commit(types.SET_NOTIFICATIONS_READ_SUCCESS, data.notifications);
                resolve();
            }).catch((error) => {
                //commit(types.LOAD_NOTIFICATIONS_FAILURE, error);
                reject(error);
            });
        });
    },
    dismissNotifications: function ({commit}, ids) {
        console.log('action', ids);
        return new Promise((resolve, reject) => {
            notifications.dismissNotifications(ids).then((data) => {
                commit(types.DISMISS_NOTIFICATION_SUCCESS, data);
                resolve();
            }).catch((error) => {
                //commit(types.LOAD_NOTIFICATIONS_FAILURE, error);
                reject(error);
            });
        });
    }
};

const mutations = {
    [types.LOAD_NOTIFICATIONS_REQUEST](state) {
        //state.pending = true;
    },
    [types.LOAD_NOTIFICATIONS_SUCCESS](state, data) {
        state.notifications = data;
    },
    [types.LOAD_NOTIFICATIONS_FAILURE](state, error) {
        //state.pending = false;
    },
    [types.ADD_NOTIFICATION](state, notification) {
        state.notifications.unshift(notification);
    },
    [types.SET_NOTIFICATIONS_READ_SUCCESS](state, data) {
        let notifications = Object.assign([], state.notifications);
        notifications.forEach((notification) => {
            if(includes(data, notification.id) && !notification.read_at) {
                notification.read_at = new Date();
            }
            return notification;
        });
    },
    [types.DISMISS_NOTIFICATION_SUCCESS](state, data) {
        state.notifications = state.notifications.filter(function (notification) {
            return !includes(data.notifications, notification.id);
        });
    },
};

export default {
    state,
    getters,
    actions,
    mutations,
}