import axios from 'axios';
const state = {
    newProducts: [],
    dealsOfDay : [],
    allbestSellerProducts : [],
    newArrivals : [],
    topSellings : [],
    topRated : [],
    allProducts : [],
    allCategories : [] ,
    authUser : [],
}
const mutations ={
    GET_NEW_PRODUCTS(state, payload){
        state.newProducts = payload
    },
    GET_DEALS_OF_DAY(state, payload){
        state.dealsOfDay = payload
    },
    GET_BEST_SELLER_PRODUCTS_ALL(state, payload){
        state.allbestSellerProducts = payload
    },
    GET_NEW_ARRIVALS(state, payload){
        state.newArrivals = payload
    },
    GET_TOP_SELLINGS(state, payload){
        state.topSellings = payload
    },
    GET_TOP_RATED(state, payload){
        state.topRated = payload
    },
    GET_ALL_PRODUCTS(state, payload){
        state.allProducts = payload
    },
    GET_ALL_CATEGORIES(state, payload){
        state.allCategories = payload
    },
    AUTH_USER(state, payload){
        state.authUser = payload
    }
}
const actions = {
    getNewProducts({ commit }) {
        axios.get('/api/get-new-products').then((response) => {
            commit('GET_NEW_PRODUCTS', response.data)
        });
    },
    getDealsOfDay({ commit }) {
        axios.get('/api/get-deals-of-day').then((response) => {
            commit('GET_DEALS_OF_DAY', response.data)
        });
    },
    getBestSellerProductsAll({ commit }) {
        axios.get('/api/get-best-seller-products-all').then((response) => {
            commit('GET_BEST_SELLER_PRODUCTS_ALL', response.data)
        });
    },
    getNewArrivals({ commit }) {
        axios.get('/api/get-new-arrivals').then((response) => {
            commit('GET_NEW_ARRIVALS', response.data)
        });
    },
    getTopSellings({ commit }) {
        axios.get('/api/get-top-sellings').then((response) => {
            commit('GET_TOP_SELLINGS', response.data)
        });
    },
    getTopRated({ commit }) {
        axios.get('/api/get-top-rated').then((response) => {
            commit('GET_TOP_RATED', response.data)
        });
    },
    getAllProducts({ commit }) {
        axios.get('/api/get-all-products').then((response) => {
            commit('GET_ALL_PRODUCTS', response.data)
        });
    },
    getAllCategories({ commit }) {
        axios.get('/api/get-all-categories').then((response) => {
            commit('GET_ALL_CATEGORIES', response.data)
        });
    },
    getAuthUser({ commit }) {
        axios.get('/api/get-auth-user').then((response) => {
            commit('AUTH_USER', response.data)
        });
    },
}
const getters = {
    newProducts : state => state.newProducts,
    dealsOfDay : state => state.dealsOfDay,
    allbestSellerProducts : state => state.allbestSellerProducts,
    newArrivals : state => state.newArrivals,
    topSellings : state => state.topSellings,
    topRated : state => state.topRated,
    allProducts : state => state.allProducts,
    allCategories : state => state.allCategories,
    authUser: state => state.authUser,
}
const homeModule = {
    state,
    mutations,
    actions,
    getters
}
export default homeModule;