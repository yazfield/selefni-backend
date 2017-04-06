import Dashboard from '../components/Dashboard';
import Home from '../components/Home';
import Register from '../components/Register';
import Login from '../components/Login';
import Logout from '../components/Logout';

export const routes = [
    { path: '/', name: 'home', component: Home},
    { path: '/login', name: 'login', component: Login, meta: {'guestOnly': true}},
    { path: '/logout', name: 'logout', component: Logout, meta: {'requiresAuth': true}},
    { path: '/register', name: 'register', component: Register, meta: {'guestOnly': true}},
    { path: '/items', name: 'dashboard', component: Dashboard, meta: {'requiresAuth': true}},
];

