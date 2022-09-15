import axios from 'axios';
const state = {
    productItems: [],
    categoryItems:[]
}
const getters = {
    productItems: state => state.productItems,
    categoryItems: state => state.categoryItems,
    productItemById: (state) => (id) => {
        return state.productItems.find(productItem => productItem.id === id)
    },
    categoryItemById: (state) => (id) => {
        return state.categoryItems.find(categoryItem =>categoryItem.id === id)
    }
}
const mutations = {
    UPDATE_PRODUCT_ITEMS(state, payload) {
        state.productItems = payload;
    },
    UPDATE_CATEGORY_ITEMS(state, payload) {
        state.categoryItems = payload;
    }
}
const actions = {
    getProductItems({ commit }) {
        axios.get(`/api/products`).then((response) => {
            commit('UPDATE_PRODUCT_ITEMS', response.data)
        });
    },
    getCategoryItems({ commit }) {
        axios.get(`/api/categories`).then((response) => {
            commit('UPDATE_CATEGORY_ITEMS', response.data)
        });
    }
}

const productModule = {
    state,
    mutations,
    actions,
    getters
}

export default productModule;
