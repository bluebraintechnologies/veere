import axios from 'axios';
const state = {
    navItems: [],
    routePaths : [],
    business : Object,
    cookieStatus:false,
}
const mutations = {
    UPDATE_NAV_ITEMS(state, payload) {
        state.navItems = payload;
    },
    UPDATE_ROUTE_PATHS(state, payload) {
        state.routePaths = payload;
    },
    UPDATE_BUSINESS(state, payload) {
        state.business = payload;
    },
    UPDATE_COOKIE_STATUS(state, payload) {
        state.cookieStatus = payload;
    }
}
const actions = {
    getnavItems({ commit }) {
        axios.get('/api/get-category-list').then((response) => {
            commit('UPDATE_NAV_ITEMS', response.data)
        });
    },
    setRoutePath({ commit }, [vm, r]) {
        routePaths.push(r)
        commit('UPDATE_ROUTE_PATHS', routePaths)        
    },
    getBusinessDetails({ commit }) {
        axios.get('/api/get-business-detail').then((response) => {
            commit('UPDATE_BUSINESS', response.data)
        });
    },
    setCookieStatus({ commit }) {
        commit('UPDATE_COOKIE_STATUS', true)        
    },
}
const getters = {
    navItems: state => state.navItems,
    business: state => state.business,
    cookieStatus: state => state.cookieStatus,
}
const navModule = {
    state,
    mutations,
    actions,
    getters
}
export default navModule;