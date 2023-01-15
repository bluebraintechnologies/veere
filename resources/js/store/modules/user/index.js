import axios from 'axios';
const state = {
    // productItems: [],
    // categoryItems:[],
    // stockDetails:[],
    // reward_point_setting:Object,
    user_reward_points_earned:Number,
    user_reward_points_ontheway:Number,
}
const getters = {
    user_reward_points_earned: state => state.user_reward_points_earned,
    user_reward_points_ontheway: state => state.user_reward_points_ontheway,
    // productItems: state => state.productItems,
    // reward_point_setting: state => state.reward_point_setting,
    // stockDetails: state => state.stockDetails,
    // categoryItems: state => state.categoryItems,
    // productItemById: (state) => (id) => {
    //     return state.productItems.find(productItem => productItem.id === id)
    // },
    // categoryItemById: (state) => (id) => {
    //     return state.categoryItems.find(categoryItem =>categoryItem.id === id)
    // }
}
const mutations = {
    UPDATE_REWARD_POINTS_EARNED(state, payload) {
        state.user_reward_points_earned = payload;
    },
    UPDATE_REWARD_POINTS_ONTHEWAY(state, payload) {
      state.user_reward_points_ontheway = payload;
    },
    // UPDATE_PRODUCT_ITEMS(state, payload) {
    //     state.productItems = payload;
    // },
    // UPDATE_CATEGORY_ITEMS(state, payload) {
    //     state.categoryItems = payload;
    // },
    // UPDATE_STOCK_DETAILS(state, payload) {
    //     state.stockDetails = payload;
    // },
    // UPDATE_REWARD_POINT(state, payload) {
    //     state.reward_point_setting = payload;
    // }
}
const actions = {
    getUserRewardPointsEarned({ commit }) {
        axios.get(`/api/get-user-reward-points-earned`).then((response) => {
            commit('UPDATE_REWARD_POINTS_EARNED', response.data)
        });
    },
    getUserRewardPointsOntheway({ commit }) {
      axios.get(`/api/get-user-reward-points-ontheway`).then((response) => {
          commit('UPDATE_REWARD_POINTS_ONTHEWAY', response.data)
      });
    },
    // getProductItems({ commit }) {
    //     axios.get(`/api/products`).then((response) => {
    //         commit('UPDATE_PRODUCT_ITEMS', response.data)
    //     });
    // },
    // getCategoryItems({ commit }) {
    //     axios.get(`/api/categories`).then((response) => {
    //         commit('UPDATE_CATEGORY_ITEMS', response.data)
    //     });
    // },
    // getStockDetails({ commit }) {
    //     axios.get(`/api/get-stock-details`).then((response) => {
    //         commit('UPDATE_STOCK_DETAILS', response.data)
    //     });
    // },
    // getRewardPoints({ commit }) {
    //     axios.get(`/api/get-reward-point-setting`).then((response) => {
    //         commit('UPDATE_REWARD_POINT', response.data)
    //     });
    // }
}

const productModule = {
    state,
    mutations,
    actions,
    getters
}

export default productModule;
