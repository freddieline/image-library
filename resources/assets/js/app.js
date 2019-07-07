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
import SearchDishes from './components/SearchDishes.vue'
import MakeADish from './components/MakeADish.vue'
import 'material-design-icons-iconfont/dist/material-design-icons.css'



Vue.use( Vuetify, {
    iconfont: 'md',
    theme: {
        primary: '#7000BB'
        
    },
} );
Vue.use( Vuex );
Vue.use( VueRouter );

Vue.component( 'app', App );

const routes = [
    { path: '/results/:id', component: Results },
    { path: '/', component: Menu },
    { path: '/search-dishes', component: SearchDishes },
    { path: '/make-a-dish', component: MakeADish }
]

const router = new VueRouter({
    routes // short for `routes: routes
});
//assas
const store = new Vuex.Store({
    state: {
        meals: [],
        food_ingredients: [],
        meals_with_ingredients: [],

    },
    mutations: {
        meals ( state, meals ) {
            state.meals  = meals;
        },
        food_ingredients ( state, food_ingredients ) {
            state.food_ingredients  = food_ingredients;
        },
        meals_with_ingredients (state, payload){
            state.meals_with_ingredients = payload;
        }

    },
    getters:
        {
            getMeals(state){
                return state.meals;
            },
            getFoodIngredients(state){
                return state.food_ingredients;
            },
            getMealsWithIngredients(state){
                return state.meals_with_ingredients;
            }
        }
});

// Initialise Vue JS!
const app = new Vue( {

    el : '#app',
    router,
    store

} );





