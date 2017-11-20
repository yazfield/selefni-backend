/**
 * Created by yzid on 4/10/17.
 */
import Vue from "vue";
import * as constants from "./constants";

export default {
    getUserNotifications() {
        return Vue.$http.get(
            constants.USER_NOTIFICATIONS
        ).then((response) => Promise.resolve(response.data));
    },
    setNotificationsRead(ids) {
        return Vue.$http.put(
            constants.USER_NOTIFICATIONS,
            {
                notifications: ids
            }
        ).then((response) => Promise.resolve(response.data));
    },
    dismissNotifications(ids) {
        console.log('request', {
            notifications: ids
        });
        return Vue.$http.delete(
            constants.USER_NOTIFICATIONS,
            {
                data: {notifications: ids}
            }
        ).then((response) => Promise.resolve(response.data));
    },
}

