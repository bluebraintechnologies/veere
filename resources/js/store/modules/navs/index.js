import axios from 'axios';
const state = {
    navItems: []
}
const mutations = {
    UPDATE_NAV_ITEMS(state, payload) {
        state.navItems = payload;
    }
}
const actions = {
    getnavItems({ commit }) {
        axios.get('/api/get-category-list').then((response) => {
            commit('UPDATE_NAV_ITEMS', response.data)
        });
    },
}
const getters = {
    navItems: state => state.navItems,
}
const navModule = {
    state,
    mutations,
    actions,
    getters
}
export default navModule;