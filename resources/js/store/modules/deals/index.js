import axios from 'axios';
const state = {
    // cartItems: [],
    dealsProduct: [],
    dealsProductDiscount: [],
}
const mutations = {
    UPDATE_Deals_ITEMS(state, payload) {
        state.dealsProduct = payload.products;
        state.dealsProductDiscount = payload.toDealProduct;
    }
}
const actions = {
    getDealItems({ commit }) {
        let location
        if(localStorage.getItem("location")){
            location = localStorage.getItem("location")
        }else if(localStorage.getItem("temp_location")){
            location = localStorage.getItem("temp_location")
        }   
        axios.get('/api/get-deal-items?location=' + location).then((response) => {
            commit('UPDATE_Deals_ITEMS', response.data)
        });
    },
    
    
}
const getters = {
    dealsProduct: state => state.dealsProduct,
    dealsProductDiscount: state => state.dealsProductDiscount,
    // shipAddress: state => {
    //     return (state.cartItems && state.cartItems.length == 0)?0: state.cartItems.reduce((acc, cartItem) => {
    //         return cartItem.address_id;
    //     }, 0);
    // },
    
}
const cartModule = {
    state,
    mutations,
    actions,
    getters
}
export default cartModule;