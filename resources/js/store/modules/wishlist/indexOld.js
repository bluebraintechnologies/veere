import axios from 'axios';
const state = {
    wishlistItems : []
}
const mutations = {
  UPDATE_WISHLIST_ITEM(state, payload){
    state.wishlistItems = payload
  },
  ADD_PRODUCT_TO_WISHITEM(state, payload){
    state.wishlistItems = payload
  }
}
const actions = {
  getWishlistItems( { commit } ){
    axios.get('/api/get-wishlist-items').then((response) => {
      commit('UPDATE_WISHLIST_ITEM', response.data)
    })
  },
  addProductInWishlist( { commit }, product ){
    axios.post('/api/add-product-to-wishlist', product).then((response) => {
      commit('ADD_PRODUCT_TO_WISHITEM', response.data)
    })
  },
  removeFromWishList( {commit}, product ){
    axios.post('/api/remove-product-from-wishlist', product).then((response) => {
      commit('UPDATE_WISHLIST_ITEM', response.data)
    })
  }
}
const getters = {
    wishlistItems: state => state.wishlistItems,
}
const wishlistModule = {
    state,
    mutations,
    actions,
    getters
}
export default wishlistModule;