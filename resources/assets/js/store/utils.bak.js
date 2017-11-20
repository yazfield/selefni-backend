import {includes, omit, has} from "lodash";
import moment from 'moment';

class BasicOrm {
    constructor(properties) {
        this._init(properties);
        return new Proxy(this, this);
    }

    _init(properties) {
        if (!properties) {
            throw new Error('Properties must not be null');
        }
        if (typeof properties === 'string') {
            properties = JSON.parse(properties);
        }
        /*
         * Original properties of the object, this will only contain clean
         * properties which are not modified.
         */
        this._properties = properties;
        /*
         * Object containing modified properties.
         */
        this._dirtyProperties = {};
    }

    /*
     * Return current dirty property or fallback to unmodified proerty or method.
     */
    get(target, property) {
        // Resolving property
        // FIXME: these are private properties
        if(property === '_dirtyProperties' || property === '_properties') {
            return target[property];
        }
        let prop = target._dirtyProperties[property] || target._properties[property];
        if(prop) {
            return prop;
        }

        // Resolving method
        // FIXME: we could add private methods by putting methods inside an object
        let method = target[property];
        if(typeof method === 'function') {
            return method.bind(target);
        }
    }

    /*
     * Set a property with a new value if it exists
     */
    set(target, property, value) {
        // FIXME: avoid adding new properties?
        if(has(this._properties, property)) {
            target._dirtyProperties[property] = value;
        }
    }

    /*
     * Returns all dirty properties.
     */
    dirty() {
        return this._dirtyProperties;
    }

    /*
     * Returns all original properties.
     */
    original() {
        return this._properties;
    }

    /*
     * Returns only clean properties.
     * @return Object clean properties
     */
    clean() {
        return omit(this._properties, Object.keys(this._dirtyProperties));
    }

    /*
     * Checks wether a property or object is dirty.
     * @param string|undefined property property name
     * @return boolean returns true if property is dirty or object is dirty
     */
    isDirty(property) {
        if(property) {
            return has(this._dirtyProperties, property);
        }
        return this._dirtyProperties && Object.keys(this._dirtyProperties).length > 0;
    }

    /*
     * Checks wether a property or object is clean.
     * @param string|undefined property property name
     * @return boolean returns true if property is clean or object is clean
     */
    isClean(property) {
        return !this.isDirty(property);
    }

    has(property) {
        return has(this._properties, property);
    }

}

export class Token extends BasicOrm {
    constructor(rawToken) {
        super(rawToken);
        const keys = ['token_type', 'expires_in', 'access_token', 'refresh_token'];
        const originalProperties = this.original();
        for(const key of keys) {
            if(!has(originalProperties, key)) {
                throw new Error('Uknown token key ' + key);
            }
        }
        return this;
    }

    getAuthorizationHeader() {
        console.log(this.original());
        return this.get(this, 'token_type') + ' ' + this.get(this, 'access_token');
    }

    getRefreshToken() {
        return this.get(this, 'refresh_token');
    }

    isValid() {
        return Date.now() + this.get(this, 'expires_in') > Date.now();
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

        this.attributes = rawItem;
        this.original = rawItem;

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
    const keys = ['name', 'type', 'details', 'returned_at', 'return_at',
        'amount', 'borrowed_at',];

    for (let [k, v] of Object.entries(item)) {
        if (includes(keys, k)) {
            data[k] = v;
        }
    }
    return data;
}