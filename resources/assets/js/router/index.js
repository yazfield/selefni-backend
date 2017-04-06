import VueRouter from 'vue-router';
import {routes} from './routes';
import store from '../store';

export const router = new VueRouter({
    mode: 'history',
    linkActiveClass: 'active',
    routes
});


router.beforeEach((to, from, next) => {
  // TODO: i dont want to access local storage here
  // wonder if there's another solution
  // checks if user is logged in
  if (router.app.$store.getters.isLoggedIn) {  
    if(to.meta.requiresAuth) {
      return next();
    } else if(to.meta.guestOnly){
        console.log('guest only', to);
      return next({name: 'dashboard'});
    }
  } else {
    if(to.meta.requiresAuth) {
      return next({name: 'login'});
    } else if(to.meta.guestOnly){
      return next();
    }
  }
  return next();
})