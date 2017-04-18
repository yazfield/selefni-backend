import {template, templateSettings} from "lodash";
templateSettings.interpolate = /{{([\s\S]+?)}}/g;

// api
export const REGISTER_ROUTE = '/api/register';
export const LOGIN_ROUTE = '/oauth/token';
export const PROFILE = '/api/profile';
export const LOGOUT = '/api/logout';

// items
export const USER_ITEMS = '/api/user_items';
export const UPDATE_ITEM = template('/api/items/{{ id }}');
export const UPLOAD_ITEM_IMAGE = template('/api/items/{{ id }}/media');