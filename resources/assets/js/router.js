import Vue from 'vue'
import VueRouter from 'vue-router'

// import Index from './components/Index.vue'
import Profile from './components/Profile.vue'
import Home from './components/Home.vue'
// import MyWishlist from './components/MyWishlist.vue'

// declare the using of VueRouter
Vue.use(VueRouter)

// define the VueRouter object
const router = new VueRouter({
	mode: 'history',
	routes: [
	{ path: '/', component: Home },
	{ path: '/profile', component: Profile },

    // { path: '/popular', component: PopularMovies },
    // { path: '/wishlist', component: MyWishlist },

		// { path: '/register', component: Register },
		// { path: '/login', component: Login },
		// { path: '/not-found', component: NotFound },
		// { path: '*', component: NotFound }
	]
})

export default router