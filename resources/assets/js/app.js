
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

// next line of code registers only one component App.vue for <app> element 
// Vue.component('app', require('./components/App.vue'));

// import component and router to use them inside Vue object
import App from './components/App.vue';
import router from './router.js'

// create Vue object
const app = new Vue({
	
    el: '#app',				// <app> is the html element associated with Vue object
    components: { App },	// here we can register many components for <app> element
    router					// Vue router
});
