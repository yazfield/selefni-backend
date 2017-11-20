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
        const url = constants.UPDATE_ITEM({id: id});
        return Vue.$http.put(
            url,
            data
        ).then((response) => Promise.resolve(response.data));
    },
    uploadImage(id, image) {
        const url = constants.UPLOAD_ITEM_IMAGE({id: id});
        let data = new FormData();
        data.append('image', image);
        return Vue.$http.post(
            url,
            data
        ).then((response) => Promise.resolve(response.data));
    },
}

