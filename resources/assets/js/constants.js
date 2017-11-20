export const baseDomain = 'http://localhost:8000/';
export const templates = {
    clean: 'clean',
    dashboard: 'dashboard',
    default: 'dashboard'
};
export const itemTypes = {
    object: 'object',
    book: 'book',
    money: 'money'
};

export const echoConfig = {
    broadcaster: 'pusher',
    key: 'b8916ff1958671f01876',
    cluster: 'eu',
    encrypted: true
};

export const notificationTypes = {
    item: {
        amountChanged: 'App\\Notifications\\ItemAmountChanged'
    }
};