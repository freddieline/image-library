<template>
    	<router-view></router-view>
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

            // set store valueshjhjj
            this.setStoreValues();
        },

        data ()
        {
            return {
      

            }
        },

        methods: {

            /**
             * set photo values in vue-store
             */
            setStoreValues(){
                console.log('hi');
                console.log(this.composer.food_ingredients);
                this.$store.commit(  'addPhotos', this.composer.photos  );
                this.$store.commit(  'addPhotosDirectory', this.composer.photosDirectory  );
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
}
h2{
	font-size:17px;
	margin:8px;
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
	color: #2c3e50;
	margin-top: 0px;
	background-color:transparent;
}
</style>
