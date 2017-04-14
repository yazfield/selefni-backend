import Dashboard from "../components/Items/Dashboard";
import Home from "../components/Home";
import Login from "../components/Auth/Login";
import ForgotPassword from "../components/Auth/ForgotPassword";
import Item from "../components/Items/Item";

export const routes = [
    { path: '/', name: 'home', component: Home},
    { path: '/login', name: 'login', component: Login, meta: {'guestOnly': true}},
    {path: '/forgot_password', name: 'forgotPassword', component: ForgotPassword, meta: {'guestOnly': true}},
    {
        path: '/items', name: 'dashboard', component: Dashboard, meta: {'requiresAuth': true},
        children: [
            {path: ':id', component: Item, name: 'item', props: true}
        ]
    },
];

