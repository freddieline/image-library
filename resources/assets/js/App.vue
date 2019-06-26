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
                this.$store.commit( 'food_ingredients' );
                this.$store.commit( 'meals', this.composer.meals );
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
	font-weight:normal;
    font-size:27px;
    margin-top:8px;
  
}
h2{
	font-size:17px;
	margin:8px;
      text-align:left;
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
	font-family: 'Avenir', Helvetica, Arial, sans-serif;
	-webkit-font-smoothing: antialiased;
	-moz-osx-font-smoothing: grayscale;
	text-align: center;
	color: #111111;
	margin-top: 0px;
	background-color:transparent;
}
</style>
