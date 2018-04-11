
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
import router from './router.js';
import slick from './slick.js';

var Slick = Vue.component('Slick',{
  template : '#slick',
  data : function(){
      return this.slick; //Notice: in components data should return an object. For example "return { someProp: 1 }"
  },
  props : ['slick']
});


// create Vue object
const app = new Vue({
	
    el: '#app',				// <app> is the html element associated with Vue object
    components: { 	App 
    				Slick: Slick},	// here we can register many components for <app> element
    slick,					// slick
    router					// Vue router
});
