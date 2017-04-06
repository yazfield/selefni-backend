import * as types from '../mutation-types';
import auth from '../../api/auth';
import {router} from '../../router';
import {Token} from './utils';

const state = {
    auth: { 
        pending: false,
        user: null,
        token: localStorage.getItem('token') ? new Token(localStorage.getItem('token')) : null,
        error: null
    },
}

const getters = {
    isLoggedIn: state => state.auth.token && state.auth.token.isValid(),
    isLoggingIn: state => state.auth.pending,
    user: state => state.auth.user,
    getToken: state => state.auth.token,
    authError: state => state.auth.error
}

const actions = {
    login({commit, state}, credentials) {
        commit(types.LOGIN_REQUEST);
        return new Promise((resolve, reject) => {
            auth.login(credentials)
            .then(token => {
                commit(types.LOGIN_SUCCESS, token);
                localStorage.setItem('token', JSON.stringify(token));
                resolve();
            }).catch(error => {
                commit(types.LOGIN_FAILURE, error);
                reject(error);
            });
        });
    },
    refreshToken({commit, state}, token) {
        commit(types.REFRESH_TOKEN_REQUEST);
        return new Promise(function(resolve, reject){
            auth.refreshToken(state.auth.token.refreshToken)
            .then(token => {
                commit(types.REFRESH_TOKEN_SUCCESS, token);
                resolve();
            }).catch(error => {
                commit(types.REFRESH_TOKEN_FAILURE, error);
                localStorage.removeItem('token');
                reject(error);
            });
        });
    }, 
    loadToken({commit, state}, token) {
        token = token || localStorage.getItem('token');
        if(token) {
            commit(types.SET_TOKEN, token);
        }
    },
    getProfile({commit, state}) {
        commit(types.USER_PROFILE_REQUEST);
        return new Promise(function(resolve, reject){
            auth.getProfile(state.auth.token)
            .then(user => {
                commit(types.USER_PROFILE_SUCCESS, user);
                resolve(user);
            }).catch(error => {
                commit(types.USER_PROFILE_FAILURE, error);
                reject(error);
            });
        });
    }
}

const mutations = {
    [types.LOGIN_REQUEST] (state) {
        state.auth.pending = true;
        state.auth.error = null;
    },
    [types.LOGIN_SUCCESS] (state, rawToken) {
        state.auth.pending = false;
        state.auth.error = null;
        state.auth.token = new Token(rawToken);
    },
    [types.LOGIN_FAILURE] (state, error) {
        state.auth.pending = false;
        state.auth.error = error;
    },
    [types.REFRESH_TOKEN_REQUEST] (state) {
        state.auth.pending = true;
        state.auth.error = null;
    },
    [types.REFRESH_TOKEN_SUCCESS] (state, rawToken) {
        state.auth.pending = false;
        state.auth.error = null;
        state.auth.token = new Token(rawToken);
    },
    [types.REFRESH_TOKEN_FAILURE] (state, error) {
        state.auth.pending = false;
        state.auth.error = error;
        state.auth.token = null;
    },
    [types.SET_TOKEN] (state, rawToken) {
        state.auth.pending = false;
        state.auth.error = null;
        state.auth.token = new Token(rawToken);
    },
    [types.USER_PROFILE_SUCCESS] (state, user) {
        state.auth.user = user;
    },
}

export default {
    state,
    getters,
    actions,
    mutations
}