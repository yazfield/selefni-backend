/**
 * Created by yzid on 4/10/17.
 */
import * as types from "../mutation-types";
import {items} from "../../api";
import {extractItemData} from "./utils";

const state = {
    items: {
        total: null,
        per_page: null,
        current_page: 0,
        last_page: null,
        next_page_url: null,
        prev_page_url: null,
        from: null,
        to: null,
        data: []
    },
    pending: false,
    showingItem: null,
};

const getters = {
    items: state => state.items,
    areItemsLoaded: state => state.items.total !== null,
    showingItem: state => state.showingItem
};

const actions = {
    loadItems: function ({commit}, nextPage) {
        commit(types.LOAD_ITEMS_REQUEST);
        return new Promise((resolve, reject) => {
            items.getUserItems(nextPage).then((data) => {
                commit(types.LOAD_ITEMS_SUCCESS, data);
                resolve();
            }).catch((error) => {
                commit(types.LOAD_ITEMS_FAILURE, error);
                reject(error);
            });
        });
    },
    showItem: function ({commit}, itemId) {
        commit(types.SHOW_ITEM, itemId);
    },
    hideItem: function ({commit}) {
        commit(types.HIDE_ITEM);
    },
    updateItem: function ({commit}, dirtyItem) {
        commit(types.UPDATE_ITEM_REQUEST);
        const itemId = dirtyItem.id;
        let preparedItem = extractItemData(dirtyItem);
        return new Promise((resolve, reject) => {
            items.updateItem(itemId, preparedItem).then((data) => {
                commit(types.UPDATE_ITEM_SUCCESS, data);
                resolve();
            }).catch((error) => {
                commit(types.UPDATE_ITEM_FAILURE, error);
                reject(error);
            });
        });
    },
    uploadItemImage: function ({commit}, {id, image}) {
        commit(types.UPLOAD_ITEM_IMAGE_REQUEST);
        return new Promise((resolve, reject) => {
            items.uploadImage(id, image).then((data) => {
                commit(types.UPLOAD_ITEM_IMAGE_SUCCESS, data);
                resolve();
            }).catch((error) => {
                commit(types.UPLOAD_ITEM_IMAGE_FAILURE, error);
                reject(error);
            });
        });
    }
};

const mutations = {
    [types.LOAD_ITEMS_REQUEST](state) {
        state.pending = true;
    },
    [types.LOAD_ITEMS_SUCCESS](state, data) {
        state.pending = false;
        if (state.items.total === null) {
            state.items = data;
        } else {
            state.items.data = state.items.data.concat(data.data);
            state.items.total = data.total;
            state.items.to = data.to;
            state.items.current_page = data.current_page;
            state.items.last_page = data.last_page;
            state.items.next_page_url = data.next_page_url;
            state.items.prev_page_url = data.prev_page_url;
        }
    },
    [types.LOAD_ITEMS_FAILURE](state, error) {
        state.pending = false;
    },
    [types.RELOAD_ITEMS](state) {
        state.pending = false;
        state.items = {
            total: null,
            per_page: null,
            current_page: 0,
            last_page: null,
            next_page_url: null,
            prev_page_url: null,
            from: null,
            to: null,
            data: []
        };
    },
    [types.SHOW_ITEM](state, itemId) {
        state.showingItem = itemId;
    },
    [types.HIDE_ITEM](state) {
        state.showingItem = null;
    },
    [types.UPDATE_ITEM_REQUEST](state) {
        //state.pending = true;
    },
    [types.UPDATE_ITEM_SUCCESS](state, data) {
        const idx = state.items.data.findIndex(item => item.id === data.id);
        state.items.data.splice(idx, 1);
        state.items.data.push(data);
    },
    [types.UPDATE_ITEM_FAILURE](state, error) {
        //state.pending = false;
    },
    [types.UPLOAD_ITEM_IMAGE_REQUEST](state) {
        //state.pending = true;
    },
    [types.UPLOAD_ITEM_IMAGE_SUCCESS](state, data) {
        const idx = state.items.data.findIndex(item => item.id === data.id);
        let item = Object.assign({}, state.items.data[idx]);
        item.image = data.image;
        state.items.data.splice(idx, 1);
        state.items.data.push(item);
    },
    [types.UPLOAD_ITEM_IMAGE_FAILURE](state, error) {
        //state.pending = false;
    },
};

export default {
    state,
    getters,
    actions,
    mutations,
}