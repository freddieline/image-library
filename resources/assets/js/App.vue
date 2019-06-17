<template>
    <div>
        <div :style="headerStyle"></div>
        <v-container fluid wrap id="photo-container" justify-center :style='containerStyle'>
            <h1 :style="titleStyle">My Lirary</h1>
            <v-layout justify-start wrap xs10 offset-xs1 :style="layoutStyle">
                <template v-for="photoStyle in photoStyles" >
                    <v-flex sm3 :style="flexElementStyle">
                        <div :style="photoStyle" ></div>
                    </v-flex>
                </template>
            </v-layout>
        </v-container>
    </div>
</template>

<script>
    import axios from 'axios';

    export default {

        props: [ 'composerProp','app', 'composer' ],
        computed:{

        },

        mounted(){
            //
            this.onResize();

            window.addEventListener('resize', this.onResize, { passive: true })
        },
        created(){

            this.defineObjects();

            // set store values
            this.setStoreValues();
        },

        data ()
        {
            return {
                photoStyles: [],
                photoDirectory: '',
                photoName: "",
                headerStyle:
                    "background-color:#ffffff;" +
                    "width:100%;" +
                    "height:300px;" +
                    "position:absolute;" +
                    "z-index:-1;",
                containerStyle:
                    'margin-left:auto;' +
                    'margin-right:auto;' +
                    'max-width:1100px;',
                layoutStyle:
                    'padding-top:200px;',
                titleStyle:
                    'padding-top:100px;',
                flexElementStyle:
                    "min-width:255px;" +
                    "margin-bottom:20px;",

            }
        },

        methods: {

            defineObjects(){
                this.photoContainer = document.getElementById('photo-container');
            },




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