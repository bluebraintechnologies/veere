import axios from 'axios';
const state = {
    assetItems: []
}
const mutations = {
    UPDATE_ASSET_ITEMS(state, payload) {
        state.assetItems = payload;
    }
}
const actions = {
    getAssetItems({ commit }) {
        axios.get('/api/get-assets').then((response) => {
            commit('UPDATE_ASSET_ITEMS', response.data)
        });
    },
}
const getters = {
    assetItems: state => state.assetItems,
}
const assetModule = {
    state,
    mutations,
    actions,
    getters
}
export default assetModule;