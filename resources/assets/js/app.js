/**
 * First, we will load all of this project's Javascript utilities and other
 * dependencies. Then, we will be ready to develop a robust and powerful
 * application frontend using useful Laravel and JavaScript libraries.
 */
require('./bootstrap');

// index.js or main.js
import 'vuetify/dist/vuetify.min.css';
import Vue      from 'vue';
import VueRouter from 'vue-router';
import Vuex      from 'vuex';
import App      from './App.vue';
import Vuetify from 'vuetify';
import PhotoViewer from './components/PhotoViewer.vue';
import Login from './components/Login.vue';

Vue.use( Vuex );
Vue.use( Vuetify, {
    theme: {
        primary: '#f44336'
    },
    darkTheme: {
        background: '#000000',
    },
} );
Vue.use( VueRouter );

Vue.component( 'app', App );

const routes = [
    { path: '/login', component: Login },
    { path: '/view', component: PhotoViewer }
]

const router = new VueRouter({
    routes // short for `routes: routes`
});

const store = new Vuex.Store({
    state: {
        photos: "",
        terms: "",
        photosDirectory:""
    },
    mutations: {

        addPhotos ( state, photos ) {
            state.photos  = photos;
        },

        addTerms ( state, terms ) {
            state.terms  = terms;
        },

        addPhotosDirectory ( state, photosDirectory ) {
            state.photosDirectory  = photosDirectory;
        }

    },
    getters:
        {
            getPhotos(state){
                return state.photos;
            },
            getTerms(state){
                return state.terms;
            },
            getPhotosDirectory(state){
                return state.photosDirectory;
            }
        }
});

// Initialise Vue JS!
const app = new Vue( {

    el : '#app',
    router,
    store

} );





