
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

import VueRouter from 'vue-router'
import Notifications from 'vue-notification'

Vue.use(VueRouter)
Vue.use(Notifications)


let routes = [
    { name: 'dashboard', path: '/dashboard', component: require('./components/Dashboard.vue') },

    { name: 'profile', path: '/profile', component: require('./components/Users/Profile.vue') },

    { name: 'all_users', path: '/users', component: require('./components/Users/Users.vue') },
    { name: 'edit_user', path: '/users/:id', component: require('./components/Users/EditUser.vue') },
    { name: 'new_user', path: '/users/new', component: require('./components/Users/NewUser.vue') },

    { name: 'all_roles', path: '/roles', component: require('./components/Roles/Roles.vue') },
    { name: 'new_role', path: '/roles/new', component: require('./components/Roles/NewRole.vue') },
    { name: 'edit_role', path: '/roles/:id', component: require('./components/Roles/EditRole.vue') }
]

const router = new VueRouter({
  mode: 'history',
  routes // short for `routes: routes`
})

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
  el: '#app',
  router
});
