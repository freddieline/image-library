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
import Results from './components/Results.vue'
import Menu from './components/Menu.vue'
import SearchEatOut from './components/SearchEatOut.vue'
import SearchHomePrepared from './components/SearchHomePrepared.vue'
import SearchMakeARecipe from './components/SearchMakeARecipe.vue'
import 'material-design-icons-iconfont/dist/material-design-icons.css'


Vue.use( Vuex );
Vue.use( Vuetify, {
    iconfont: 'md',
    theme: {
        primary: '#7000bb'
        
    },
} );
Vue.use( VueRouter );

Vue.component( 'app', App );

const routes = [
    { path: '/results', component: Results },
    { path: '/', component: Menu },
    { path: '/search-eating-out', component: SearchEatOut },
    { path: '/search-home-prepared', component: SearchHomePrepared },
    { path: '/search-make-a-recipe', component: SearchMakeARecipe }
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





