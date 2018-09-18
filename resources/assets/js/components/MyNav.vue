<template>
    <div class="navbar">
        <div class="container">
            <h2 class="navbar-brand"> Movie Recommendation Site </h2>
            <ul class="nav navbar-nav navbar-right navStyle">
                <li><router-link to="/" exact>Home</router-link></li>
                <li><router-link to="/profile">Profile</router-link></li>
                <li><router-link to="/lists">Lists</router-link></li>
                <li><a href="" @click="makeLogout()">Logout</a></li>
            </ul>
        </div>
    </div>
</template>

<script>

	export default {

		components: {
		},

	    data(){
	        return {
	            new: {
	                newVar: ''
	            }
	        }
        },
        
        methods: {
            // when logout link is clicked in the navigation menu
            makeLogout() {
                // when first, use 
                axios.post("logout").then(axios.post("login"));
            }
        }
	}
</script>