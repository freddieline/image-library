<template>
    <v-container fluid>
            <template v-for="photoStyle in photoStyles" >
                <div :style="photoStyle" >
                </div>
            </template>
    </v-container>
</template>

<script>
    import axios from 'axios';

    export default {

        props: [ 'composerProp','app', 'composer' ],

        created(){

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
                photoSrc:"",
                photoStyles:[],
                photoDirectory: '',
                photoName: "",
                terms:'',
                index: 0,
            }
        },

        methods: {

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
                            "float: left;" +
                        "overflow: hidden;" +
                        "width: 245px;" +
                        "margin: 20px;" +
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
            }


        }
    }
    </script>