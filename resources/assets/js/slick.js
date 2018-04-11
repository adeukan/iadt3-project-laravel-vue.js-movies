import Vue from 'vue'
import Slick from 'vue-slick';

// import Index from './components/Index.vue'
import Profile from './components/Profile.vue'
import Lists from './components/lists.vue'
import Home from './components/Home.vue'
// import MyWishlist from './components/MyWishlist.vue'

// declare the using of Slick
Vue.use(Slick)

const slick = new Vue({
    components: { Slick },
    data() {
            return {
            	slickOptions: {
                        //options can be used from the plugin documentation
                        slidesToShow: 4,
                        infinite: true,
                        accessibility: true,
                        adaptiveHeight: false,
                        arrows: true,
                        dots: true,
                        draggable: true,
                        edgeFriction: 0.30,
                        swipe: true
                }
            }
    },
    // All slick methods can be used too, example here
    methods: {
            next() {
                    this.$refs.slick.next()
            },
            prev() {
                    this.$refs.slick.prev()
            },
            reInit() {
                    // Helpful if you have to deal with v-for to update dynamic lists
                    this.$refs.slick.reSlick()
            }
    }
})

export default slick