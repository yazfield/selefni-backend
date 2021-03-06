import {includes} from "lodash";
import moment from "moment";

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

        if(rawItem.returned_at) {
            this.returned_at = new Date(rawItem.returned_at);
        } else {
            this.returned_at = null;
        }

        const dates = ['created_at', 'updated_at', 'return_at', 'borrowed_at'];
        const keys = ['id', 'name', 'type', 'details', 'amount', 'image', 'borrowed_to', 'borrowed_from', 'owner_id'];
        for (let [k, v] of Object.entries(rawItem)) {
            if (includes(dates, k)) {
                this[k] = new Date(v);
                continue;
            }
            if (!includes(keys, k)) {
                continue;
                //throw new Error('Uknown item key ' + k);
            }
            this[k] = v;
        }
    }

    isOwner(id) {
        return id === this.owner_id;
    }

}

export function extractItemData(item) {
    if (!item) {
        throw new Error('Item must not be null');
    }

    let data = {};
    data['borrowed_to'] = item.borrowed_to.id;
    data['borrowed_from'] = item.borrowed_from.id;
    const keys = ['name', 'type', 'details', 'amount'];
    const dates = ['borrowed_at', 'returned_at', 'return_at'];

    for (let [k, v] of Object.entries(item)) {
        if(includes(dates, k)) {
            const date = moment.utc(v);
            data[k] = date.isValid() ? date.format('YYYY-M-D HH:mm:ss') : null;
            console.log(k, data[k]);
            continue;
        }
        if (includes(keys, k)) {
            data[k] = v;
        }
    }
    return data;
}