import {includes} from "lodash";

export class Token {
    constructor(rawToken) {
        if(!rawToken) {
            throw new Error('Token must not be null');
        }
        if(typeof rawToken === 'string') {
            rawToken = JSON.parse(rawToken);
        }

        const keys = ['token_type', 'expires_in', 'access_token', 'refresh_token'];

        for (let [k, v] of Object.entries(rawToken)) {
            if (!k in keys) {
                throw new Error('Uknown token key ' + k);
            }
            this[k] = v;
        }
    }

    getAuthorizationHeader() {
        return this.token_type + ' ' + this.access_token;
    }

    getRefreshToken() {
        return this.refresh_token;
    }

    isValid() {
        return Date.now() + this.expires_in > Date.now();
    }
}

export class User {
    constructor(rawUser) {
        if (!rawUser) {
            throw new Error('User must not be null');
        }
        if (typeof rawUser === 'string') {
            rawUser = JSON.parse(rawUser);
        }

        const keys = ['id', 'name', 'email', 'phone_number', 'avatar', 'created_at', 'updated_at'];

        for (let [k, v] of Object.entries(rawUser)) {
            if (!includes(keys, k)) {
                throw new Error('Uknown user key ' + k);
            }
            this[k] = v;
        }
    }

    fullName() {
        return this.name;
    }
}

export class Item {
    constructor(rawItem) {
        if (!rawItem) {
            throw new Error('Item must not be null');
        }
        if (typeof rawItem === 'string') {
            rawItem = JSON.parse(rawItem);
        }

        const keys = ['id', 'name', 'type', 'details', 'created_at', 'updated_at', 'returned_at',
            'to_return_at', 'amount', 'image', 'borrowed_at', 'borrowed_to', 'borrowed_from'];

        for (let [k, v] of Object.entries(rawItem)) {
            if (!includes(keys, k)) {
                throw new Error('Uknown item key ' + k);
            }
            this[k] = v;
        }
    }
}

export function extractItemData(item) {
    if (!item) {
        throw new Error('Item must not be null');
    }

    let data = {};
    data['borrowed_to'] = item.borrowed_to.id;
    data['borrowed_from'] = item.borrowed_from.id;
    const keys = ['name', 'type', 'details', 'returned_at', 'return_at',
        'amount', 'borrowed_at',];

    for (let [k, v] of Object.entries(item)) {
        if (includes(keys, k)) {
            data[k] = v;
        }
    }
    return data;
}