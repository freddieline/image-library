<template>
        <v-app>
    	    <router-view></router-view>
        </v-app>
</template>

<script>
    import axios from 'axios';
    import Results from './components/Results'

    export default {

        name: 'App',
		components: {
		    Results
	    },

        props: [ 'composerProp','app', 'composer' ],

        created(){

            // set store values
            this.setStoreValues();
        },

        data ()
        {
            return {}
        },

        methods: {

            /**
             * set photo values in vue-store
             */
            setStoreValues(){
                console.log(this.composer.meals_with_ingredients );
                this.$store.commit( 'food_ingredients' );
                this.$store.commit( 'meals', this.composer.meals );
                this.$store.commit( 'meals_with_ingredients', this.composer.meals_with_ingredients );
            },

        }
    }
    </script>

    <style>
html{
	height:100%;
	box-sizing:border-box;
}
h1{
	font-weight:300;
    font-size:30px;
    margin-top:8px;
    line-height:1.2em;
  
}
.logo{
	font-weight:500;
    font-size:30px;
    margin-top:8px;
    color:#2c3e50;
}
body{
	user-select: none; /* supported by Chrome and Opera */
	-webkit-user-select: none; /* Safari */
	-khtml-user-select: none; /* Konqueror HTML */
	-moz-user-select: none; /* Firefox */
	-ms-user-select: none; /* Internet Explorer/Edge */
	-webkit-tap-highlight-color: rgba(0,0,0,0);
}
#app{  
	font-family:  Helvetica, Arial, sans-serif;
	-webkit-font-smoothing: antialiased;
	-moz-osx-font-smoothing: grayscale;
	text-align: center;
	color: #111111;
	margin-top: 0px;
	background-color:transparent;
}
.theme--light.v-label{
    text-align: left;
    color: #444444;
    font-size:15px;
     line-height:26px;
}

</style>
