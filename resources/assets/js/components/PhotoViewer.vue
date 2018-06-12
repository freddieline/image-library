<template>
    <v-container fluid>
        <!--<router-view>O</router-view>-->
        <!--<v-carousel :hide-delimiters="true" :cycle="false" transition="none" style="margin-top:50px;">
           <v-carousel-item v-for="(item ,i) in items" cycle="false" :src="item.src" :key="i"></v-carousel-item>
        </v-carousel> -->

            <img :src="photoSrc" width="960" height="540" style="margin-left:15px"  />

        <br/>
        <div class="buttons-container" style="margin-top:20px;">
            <a  @click.stop="openEmailDialog()" color="red"><img :src="sendButtonSrc" class="ipad-button" style="margin-right:96px;margin-left:15px;" width="110" height="110"/></a>
            <a  @click.stop="confirmPhotoDeleteDialog = true" color="red"><img :src="deleteButtonSrc" class="ipad-button" style="margin-right:97px"  width="110" height="110" /></a>
            <a  @click.stop="getPreviousPhoto()" color="red"><img :src="previousButtonSrc" class="ipad-button" style="margin-right:96px" width="110" height="110" /></a>
            <a  @click.stop="getNextPhoto()" color="red"><img :src="nextButtonSrc" class="ipad-button" style="margin-right:97px" width="110" height="110" /></a>
            <a  @click.stop="getPhotos()" color="red"><img :src="refreshButtonSrc" width="110" height="110" /></a>
        </div>
        <!-- dialog2 -->
        <v-dialog v-model="emailDialog" max-width="550px">

            <v-card>
                <v-layout row wrap>
                <v-flex sm10 offset-sm1>
                    <h1>Bitte Email eintragen:</h1>
                <v-text-field
                    id="testing"
                    name="input-1"
                    label="E-mail"
                    v-model="email"
            ></v-text-field>
                    <h1>Datenschutzerklärung</h1>
                    <div class="terms" id="terms">
                        {{ terms }}
                    </div>
                </v-flex>
                    <v-flex sm1 offset-sm1>
                        <v-checkbox v-model="termsChecked" class="checkbox" style="padding-top:8px"></v-checkbox>
                    </v-flex>
                    <v-flex sm10>
                        <h2>Ich stimme der beschriebenen Verwendung meiner personenbezogenen Daten zu.</h2>
                    </v-flex>
                <v-card-actions>
                    <v-btn :disabled="email === '' || termsChecked === false" color="red" flat @click.stop="sendEmail">Senden</v-btn>
                    <v-btn color="red" flat @click.stop="emailDialog = false">Löschen</v-btn>
                </v-card-actions>

                </v-layout>
            </v-card>
        </v-dialog>

        <v-dialog v-model="emailSendErrorDialog" max-width="500px">
            <v-card>
                <v-flex md10 offset-md1>
                    <h2>{{ emailSendMessageError }}</h2>
                </v-flex>
            </v-card>
        </v-dialog>
        <v-dialog v-model="emailSendSuccessDialog" max-width="250px">
            <v-card>
                <v-flex md10 offset-md1>
                    <h1>{{ emailSendMessageSuccess }}</h1>
                </v-flex>
            </v-card>

            <v-dialog v-model="confirmPhotoDeleteDialog" max-width="250px">
                <v-card>
                    <v-flex md10 offset-md1>
                        <h1>{{ confirmPhotoDeleteMessage }}</h1>
                        <v-card-actions>
                            <v-btn color="red" flat @click.stop="deletePhoto">Ja</v-btn>
                            <v-btn color="red" flat @click.stop="confirmPhotoDeleteDialog = false">Nein</v-btn>
                        </v-card-actions>
                    </v-flex>
                </v-card>
            </v-dialog>
        </v-dialog>
    </v-container>
</template>

<script>
    import axios from 'axios';

    export default {

        props: [ 'composerProp' ],

        created(){
            this.photoDirectory = this.$store.getters.getPhotosDirectory;
            //  this.terms = this.$store.getters.getTerms;
            this.getLatestPhoto();

        },

        data ()
        {
            return {
                photoSrc:"",
                termsChecked: false,
                photoDirectory: '',
                photoName: "",
                email: "" ,
                emailDialog: false,
                emailSendErrorDialog: false,
                emailSendSuccessDialog: false,
                emailSendMessageError: '',
                emailSendMessageSuccess:  "Email wurde gesendet!",
                confirmPhotoDeleteDialog: false,
                confirmPhotoDeleteMessage: "Möchtest du dieses Foto wirklich löschen?",

                sendButtonSrc: "./storage/buttons/send_button.png",
                deleteButtonSrc: "./storage/buttons/delete_button.png",
                previousButtonSrc: "./storage/buttons/previous_button.png",
                nextButtonSrc: "./storage/buttons/next_button.png",
                refreshButtonSrc: "./storage/buttons/refresh_button.png",

                terms:'',
                index: 0,
                items: [ {
                    title: 'Click Me'
                }],
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
                if(error.response.status === 503){
                    this.emailSendMessageError = "Es tut uns leid. ungültige E-Mail";
                }
                if(error.response.status === 111){
                    this.emailSendMessageError = "Es tut uns leid. Dieses Foto wurde bereits gesendet";
                }
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

            getLatestPhoto(){
                console.log('get latest');
                this.index = 0;
                this.photoName = this.$store.getters.getPhotos[ this.index ];
                this.setPhotoSrc();
            },

            setPhotoSrc(){
                this.photoSrc =  this.photoDirectory + this.photoName;
                console.log(this.photoName);
                if(typeof this.photoName === "undefined"){
                    this.photoSrc = "./storage/logo.jpg";
                    console.log(this.photoSrc);
                }
            },

            getNextPhoto(){
                if(this.index < this.$store.getters.getPhotos.length - 1 ) {
                    this.index += 1;
                    this.photoName =  this.$store.getters.getPhotos[ this.index ];
                    console.log(this.photoName);
                    this.setPhotoSrc();
                }
            },

            getPreviousPhoto(){
                console.log("get previous");

                if(this.index !== 0){
                    this.index -= 1;
                    this.photoName = this.$store.getters.getPhotos[ this.index ];
                    console.log(this.photoName);
                    this.setPhotoSrc();
                }
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