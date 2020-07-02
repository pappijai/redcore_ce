/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

// for date
import moment from 'moment'

// vform for errors
import { Form, HasError, AlertError } from 'vform'
window.Form = Form;
Vue.component(HasError.name, HasError)
Vue.component(AlertError.name, AlertError)

// sweet alert
import swal from 'sweetalert2'
window.swal = swal;
const toast = swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000
});

window.toast = toast;

import VueRouter from 'vue-router';
Vue.use(VueRouter);

// store all the paths
let routes = [
	{path: '/', component: require('./components/Welcome.vue').default},
	{path: '/dashboard', component: require('./components/Dashboard.vue').default},
	{path: '/blogs', component: require('./components/Blogs.vue').default},
	{path: '/recycle_bin', component: require('./components/RecycleBin.vue').default},
    {path: '/read_more', component: require('./components/ReadMore.vue').default},
    
    {path: '*', component: require('./components/NotFound.vue').default},
]

// register all the routes
const router = new VueRouter({
    mode: 'history',
	routes
})

// filters
Vue.filter('myDate', function(created){
    return moment(created).format('MMMM Do YYYY');
})

// fire for creating custom event
window.Fire = new Vue();

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

Vue.component('example-component', require('./components/ExampleComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    router,
    data: {
        search: '',
    },
    methods: {
        search_it(){
            Fire.$emit('searching');
        }
    }
});