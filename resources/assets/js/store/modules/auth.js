import * as types from "../mutation-types";
import {auth} from "../../api";
import {Token, User} from "./utils";
import createPersistedState from "vuex-persistedstate";

const state = {
    pending: false,
    user: null,
    token: null,
    error: null,
};

const persist = createPersistedState({
    key: 'auth',
    paths: [
        'auth.token',
        'auth.user'
    ],
    getState: (key, storage) => {
        let value = storage.getItem(key);
        if (!value) {
            return undefined;
        }
        value = JSON.parse(value);
        if (key === 'auth') {
            if (value.auth.token) {
                value.auth.token = new Token(value.auth.token);
            }
            if (value.auth.user) {
                value.auth.user = new User(value.auth.user);
            }
        }
        return value;

    }
});

const getters = {
    isLoggedIn: state => !!state.token,
    isLoggingIn: state => state.pending,
    user: state => state.user,
    getToken: state => state.token,
    authError: state => state.error,
};

const actions = {
    login({dispatch, commit}, credentials) {
        commit(types.LOGIN_REQUEST);
        return new Promise((resolve, reject) => {
            auth.login(credentials)
                .then(rawToken => {
                    commit(types.LOGIN_SUCCESS, rawToken);
                    dispatch('getProfile');
                resolve();
            }).catch(error => {
                commit(types.LOGIN_FAILURE, error);
                reject(error);
            });
        });
    },
    logout({commit}) {
        commit(types.LOGOUT_REQUEST);
        return new Promise((resolve, reject) => {
            auth.logout()
                .then(() => {
                    commit(types.LOGOUT_SUCCESS);
                    resolve();
                }).catch(error => {
                if (error.response && error.response.status === 401) {
                    commit(types.LOGOUT_SUCCESS);
                    resolve();
                    return;
                }
                commit(types.LOGOUT_FAILURE, error);
                reject(error);
            });
        });
    },
    refreshToken({commit, state}) {
        commit(types.REFRESH_TOKEN_REQUEST);
        return new Promise(function(resolve, reject){
            auth.refreshToken(state.token.refreshToken)
                .then(rawToken => {
                    commit(types.REFRESH_TOKEN_SUCCESS, rawToken);
                resolve();
            }).catch(error => {
                commit(types.REFRESH_TOKEN_FAILURE, error);
                reject(error);
            });
        });
    },
    loadToken({commit}, rawToken) {
        if (rawToken) {
            commit(types.SET_TOKEN, rawToken);
        }
    },
    getProfile({dispatch, commit}) {
        commit(types.USER_PROFILE_REQUEST);
        return new Promise(function(resolve, reject){
            auth.getProfile()
            .then(user => {
                commit(types.USER_PROFILE_SUCCESS, user);
                resolve();
            }).catch(error => {
                if (error.response.code === 401) {
                    dispatch('refreshToken').then(function () {
                        dispatch('getProfile');
                    }).catch(function () {
                        commit(types.USER_PROFILE_FAILURE, error);
                        reject(error);
                    });
                    return;
                }
                commit(types.USER_PROFILE_FAILURE, error);
                reject(error);
            });
        });
    }
};

const mutations = {
    [types.LOGIN_REQUEST] (state) {
        state.pending = true;
        state.error = null;
    },
    [types.LOGIN_SUCCESS] (state, rawToken) {
        state.pending = false;
        state.error = null;
        state.token = new Token(rawToken);
    },
    [types.LOGIN_FAILURE] (state, error) {
        state.pending = false;
        state.error = error;
    },
    [types.LOGOUT_REQUEST] (state) {
        state.error = null;
        state.pending = true;
    },
    [types.LOGOUT_SUCCESS] (state) {
        state.pending = false;
        state.token = null;
        state.user = null;
        state.error = null;
    },
    [types.LOGOUT_FAILURE] (state, error) {
        state.pending = false;
        state.error = error;
    },
    [types.REFRESH_TOKEN_REQUEST] (state) {
        //state.auth.pending = true;
        state.error = null;
    },
    [types.REFRESH_TOKEN_SUCCESS] (state, rawToken) {
        //state.auth.pending = false;
        state.error = null;
        state.token = new Token(rawToken);
    },
    [types.REFRESH_TOKEN_FAILURE] (state, error) {
        //state.auth.pending = false;
        state.error = error;
        state.token = null;
    },
    [types.SET_TOKEN] (state, rawToken) {
        state.pending = false;
        state.error = null;
        state.token = new Token(rawToken);
    },
    [types.USER_PROFILE_REQUEST] (state) {
        //state.pending = true;
    },
    [types.USER_PROFILE_SUCCESS] (state, user) {
        //state.pending = false;
        state.user = new User(user);
    },
    [types.USER_PROFILE_FAILURE] (state, error) {
        //state.pending = false;
        state.user = null;
        state.error = error;
    },
};

export default {
    state,
    getters,
    actions,
    mutations,
    persist
}