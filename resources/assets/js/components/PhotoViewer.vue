<template>
    <v-container fluid>
        <!--<router-view>O</router-view>-->
        <!--<v-carousel :hide-delimiters="true" :cycle="false" transition="none" style="margin-top:50px;">
           <v-carousel-item v-for="(item ,i) in items" cycle="false" :src="item.src" :key="i"></v-carousel-item>
        </v-carousel> -->
        <img :src="photoDirectory + photoName" width="960" height="540" />
        <br/>
        <v-btn  @click.stop="openEmailDialog()" color="red">Send email</v-btn>
        <v-btn  @click.stop="confirmPhotoDeleteDialog = true" color="red">Delete photo</v-btn>
        <v-btn  @click.native="getPreviousPhoto()" color="red">Get previous</v-btn>
        <v-btn  @click.native="getNextPhoto()" color="red">Get next photo</v-btn>
        <v-btn  @click.native="getPhotos()" color="red">Refresh</v-btn>

        <!-- dialog2 -->
        <v-dialog v-model="emailDialog" max-width="550px">

            <v-card>
                <v-layout row wrap>
                <v-flex sm10 offset-sm1>
                    <h1>Please enter email:</h1>
                <v-text-field
                    id="testing"
                    name="input-1"
                    label="E-mail"
                    v-model="email"
            ></v-text-field>
                    <h1>Terms and Conditions</h1>
                    <div class="terms" id="terms">
                        {{ terms }}
                    </div>
                </v-flex>
                    <v-flex sm1 offset-sm1>
                        <v-checkbox v-model="termsChecked" class="checkbox" ></v-checkbox>
                    </v-flex>
                    <v-flex sm6>
                        <h2>I agree to the terms listed above</h2>
                    </v-flex>
                <v-card-actions>
                    <v-btn :disabled="email === ''" color="red" flat @click.stop="sendEmail">Send</v-btn>
                    <v-btn color="red" flat @click.stop="emailDialog = false">Cancel</v-btn>
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
                            <v-btn color="red" flat @click.stop="deletePhoto">Yes</v-btn>
                            <v-btn color="red" flat @click.stop="confirmPhotoDeleteDialog = false">No</v-btn>
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
                termsChecked: false,
                photoDirectory: '',
                photoName: "",
                email: "" ,
                emailDialog: false,
                emailSendErrorDialog: false,
                emailSendSuccessDialog: false,
                emailSendMessageError: '',
                emailSendMessageSuccess:  "Your email has been sent!",
                confirmPhotoDeleteDialog: false,
                confirmPhotoDeleteMessage: "Are you sure you want to delete this photo?",
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
                document.getElementById('terms').innerHTML = this.$store.getters.getTerms;
            },

            sendEmail(){

                this.emailDialog = false;
                this.emailSendSuccessDialog = true;
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
                if(error.response.status === 503){
                    this.emailSendMessageError = "Sorry! Invalid email";
                }
            },

            emailValidation( email ){
            },

            messageSent(){
                this.emailSendSuccessDialog = false;
            },

            getLatestPhoto(){
                this.index = 0;
                this.photoName = this.$store.getters.getPhotos[ this.index ];
            },

            getNextPhoto(){
                if(this.index < this.$store.getters.getPhotos.length - 1 ) {
                    this.index += 1;
                    this.photoName =  this.$store.getters.getPhotos[ this.index ];
                }
            },

            getPreviousPhoto(){
                if(this.index !== 0){
                    this.index -= 1;
                    this.photoName = this.$store.getters.getPhotos[ this.index ];
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