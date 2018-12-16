<template>
    <div>
        <div :style="headerStyle"></div>
        <v-container fluid wrap id="photo-container" justify-center :style='containerStyle'>
            <h1 :style="titleStyle">My Library</h1>
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

            // set photo directory
            this.setPhotoDirectory();

            // set photo srcs
            this.setPhotoStyles();


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

            onResize(){
                var width = window.innerWidth;
                console.log(width);

                if(width < 600){
                    console.log('1 blocks');
                    document.getElementById('photo-container').style.width = "275px";
                }
                else if (width < 800){
                    console.log('2 blocks');
                    document.getElementById('photo-container').style.width = "550px";
                }
                else if (width < 1050){
                    console.log('3 blocks');
                    document.getElementById('photo-container').style.width = "825px";
                }
                else document.getElementById('photo-container').style.width = "1100px";
            },

            getPhotos(){
                axios.get('/photos')
                    .then(
                        this.setPhotos
                    )
                    .catch(
                        this.error
                    );
            },

            /**
             * set photo directory
             */
             setPhotoDirectory(){
                this.photoDirectory = this.$store.getters.getPhotosDirectory;
            },

            /**
             * set photo srcs
             * */
            setPhotoStyles(){
                this.photoStyles = this.$store.getters.getPhotos.map((photo) => {
                    return "height: 138px;" +
                        "overflow: hidden;" +
                        "width: 245px;" +
                        "background-image: url(" + this.photoDirectory + photo + ");" +
                        "-ms-background-position-x: center;" +
                        "-ms-background-position-y: bottom;" +
                        "background-position: center center;" +
                        "background-size: cover";
                });
                console.log(this.photoStyles);
            },

            /**
             * set photo values in vue-store
             */
            setStoreValues(){
                this.$store.commit(  'addPhotos', this.composer.photos  );
                this.$store.commit(  'addPhotosDirectory', this.composer.photosDirectory  );
            },

            setPhotos( response ){
                this.$store.commit(  'addPhotos', response.data.photos  );
                this.getLatestPhoto();
            },

            openEmailDialog(){
                this.emailDialog = true;
                document.getElementById( 'terms' ).innerHTML = this.$store.getters.getTerms;
            },

            sendEmail(){
                this.emailDialog = false;
                if( this.emailValidation ){
                    axios.post('/sendEmail', {
                        'email': this.email, 'photo': this.photoName
                    } )
                        .then(
                            this.messageSent
                        ).catch(
                            this.emailSentError
                    );
                }
            },

            emailSentError( error ){
                this.emailSendSuccessDialog = false;
                console.log(error.response);
            },

            emailValidation( email ){
            },

            messageSent(){
                this.email = "";
                this.termsChecked = false;
                this.emailSendSuccessDialog = false;
                console.log("sent");
                this.getPhotos;
            },

            setPhotoSrc(){
                this.photoSrc =  this.photoDirectory + this.photoName;
                console.log( this.photoName );
            },

            confirmDelete(){
                this.confirmPhotoDeleteDialog = true;
            },

            deletePhoto(){
                this.confirmPhotoDeleteDialog = false;
                axios.post( '/deletePhoto', {
                    "photo": this.photoName
                })
                    .then(
                        this.photoDeleted
                    )
                    .catch(
                        this.deleteError
                    );
            },

            photoDeleted( response ){
                this.$store.commit(  'addPhotos', response.data  );
                this.getLatestPhoto();
                this.confirmPhotoDeleteDialog = false;
            },

            deleteError(error){
                console.log(error);
                this.confirmPhotoDeleteDialog = false;
                console.log('delete failed');
            },

            mounted(){

            }


        }
    }
    </script>