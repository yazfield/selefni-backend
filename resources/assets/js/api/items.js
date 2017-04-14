/**
 * Created by yzid on 4/10/17.
 */
import Vue from "vue";
import * as constants from "./constants";

export default {
    getUserItems(nextPage) {
        return Vue.$http.get(
            constants.USER_ITEMS,
            {
                params: {page: nextPage}
            }
        ).then((response) => Promise.resolve(response.data));
    },
    updateItem(id, data) {
        return Vue.$http.put(
            url,
            data
        ).then((response) => Promise.resolve(response.data));
    }
}

