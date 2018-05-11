<template>
            <v-container fluid fill-height>
                <v-layout align-center justify-center>
                    <v-flex xs12 sm8 md4>
                        <v-card class="elevation-12">
                            <v-toolbar dark color="primary">
                                <v-toolbar-title>Photo viewer</v-toolbar-title>
                                <v-spacer></v-spacer>
                                <v-tooltip bottom>

                                </v-tooltip>
                            </v-toolbar>
                            <v-card-text>
                                <v-form>
                                    <v-text-field prepend-icon="person" name="login" label="Login" type="text" v-model="data.email"></v-text-field>
                                    <v-text-field prepend-icon="lock" name="password" label="Password" id="password" type="password" v-model="data.password"></v-text-field>
                                </v-form>
                            </v-card-text>
                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn @click.native="sendLoginRequest( data )" color="primary">Login</v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-flex>
                </v-layout>
            </v-container>
    </template>

<script>
    import axios from 'axios';
    export default {

        data(){
            return{
                data: {
                    email: '',
                    password: ""
                }
            }
        },

        mounted(){

            console.log( 'login mounted' );
        },



        methods:{
            sendLoginRequest( data ) {

                // Enable loading flag
                this.loading ++;
                console.log( this.email );
                console.log( this.password );
                // Make a post request
                axios
                    .post( '/auth/login', data )
                    .then(
                        this.handleFetchTokensSuccess
                    )
                    .catch( this.handleError )
                ;

            },
            /**
             * Upon successful response, stop loading and redirect to home page.
             * @param response
             */
            handleFetchTokensSuccess: function( response ) {

                // Redirect to the path provided in the response
                window.location.replace( response.data.redirect )

            },

            /*

             */
            handleError: function( response ){
                console.log( response );
            },

        }


    }
</script>