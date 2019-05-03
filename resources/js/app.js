import Vue from 'vue';
import VueRouter from 'vue-router';
import axios from 'axios';
import VueAxios from 'vue-axios';
import VeeValidate from 'vee-validate';
import App from './App.vue';
import Dashboard from './components/Dashboard.vue';
import Profile from './components/Profile';
import Home from './components/Home.vue';
import ForgottenPassword from './components/ForgottenPassword';
import ResetPassword from './components/ResetPassword';
import Register from './components/Register.vue';
import Login from './components/Login.vue';
Vue.use(VueRouter);
Vue.use(VueAxios, axios);
Vue.use(VeeValidate, {events: 'input|blur'});
axios.defaults.baseURL = 'http://rappel.me/api';
const router = new VueRouter({
    routes: [{
        path: '/',
        name: 'home',
        component: Home
    },{
        path: '/register',
        name: 'register',
        component: Register,
        meta: {
            auth: false
        }
    },{
        path: '/login',
        name: 'login',
        component: Login,
        meta: {
            auth: false
        }
    }, {
        path: '/forgotten-password',
        name: 'forgotten-password',
        component: ForgottenPassword,
    },{
        path: '/reset-password/:token',
        name: 'reset-password',
        component: ResetPassword
    }, {
        path: '/dashboard',
        name: 'dashboard',
        component: Dashboard,
        meta: {
            auth: true
        }
    },{
        path: '/profile',
        name: 'profile',
        component: Profile,
        meta: {
            auth: true
        }
    }]
});
Vue.router = router
Vue.use(require('@websanova/vue-auth'), {
    auth: require('@websanova/vue-auth/drivers/auth/bearer.js'),
    http: require('@websanova/vue-auth/drivers/http/axios.1.x.js'),
    router: require('@websanova/vue-auth/drivers/router/vue-router.2.x.js'),
});
App.router = Vue.router
new Vue(App).$mount('#app');