import axios from 'axios';
const state = {
    wishlistItems: []
}
const mutations = {
    UPDATE_WISHLIST_ITEMS(state, payload) {
        state.wishlistItems = payload;
    }
}
const actions = {
    getWishlistItems({ commit }) {
        axios.get('/api/wishlist').then((response) => {
            commit('UPDATE_WISHLIST_ITEMS', response.data)
        });
    },
    addWishlistItem({ commit }, wishlistItem) {
        axios.post('/api/wishlist', wishlistItem).then((response) => {
            commit('UPDATE_WISHLIST_ITEMS', response.data)
        });
    },
    removeWishlistItem({ commit }, wishlistItem) {
        axios.delete('/api/wishlist/'+wishlistItem.id, wishlistItem).then((response) => {
            commit('UPDATE_WISHLIST_ITEMS', response.data)
        });
    },
}
const getters = {
    wishlistItems: state => state.wishlistItems,
    wishlistItemQty: state => {
        return (state.wishlistItems && state.wishlistItems.length == 0)?0: state.wishlistItems.reduce((acc, wishlishItem) => {
            return 1 + acc;
        }, 0);
    },
}
const wishlistModule = {
    state,
    mutations,
    actions,
    getters
}
export default wishlistModule;