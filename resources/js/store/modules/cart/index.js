import axios from 'axios';
const state = {
    cartItems: []
}
const mutations = {
    UPDATE_CART_ITEMS(state, payload) {
        state.cartItems = payload;
    }
}
const actions = {
    getCartItems({ commit }) {
        axios.get('/api/cart').then((response) => {
            commit('UPDATE_CART_ITEMS', response.data.items)
        });
    },
    addCartItem({ commit }, [vm, cartItem]) {
        axios.post('/api/cart', cartItem).then((response) => {
            if(response.data.status == 'failed') {
                vm.$toast.info(response.data.message);
            } else if(response.data.status == 'success') {
                vm.$toast.success('Item successfully added in cart.');
                commit('UPDATE_CART_ITEMS', response.data.items)
            } else {
                vm.$toast.error('Some unexpected happen. Please refresh the page and try again!');
            }
        });
    },
    decreaseCartItem({ commit }, [vm, cartItem]) {
        axios.put('/api/cart/'+cartItem).then((response) => {
            if(response.data.status == 'failed') {
                vm.$toast.info(response.data.message);
            } else if(response.data.status == 'success') {
                vm.$toast.success('Quantity updated in the cart.');
                commit('UPDATE_CART_ITEMS', response.data.items)
            } else {
                vm.$toast.error('Some unexpected happen. Please refresh the page and try again!');
            }
        });
    },
    updateCartAddress({ commit }, [vm, aid]) {
        axios.post('/api/cart/update-address', {address_id:aid}).then((response) => {
            if(response.data.status == 'failed') {
                vm.$toast.info(response.data.message);
            } else if(response.data.status == 'success') {
                vm.$toast.success('Shipping Address updated in the cart.');
                commit('UPDATE_CART_ITEMS', response.data.items)
            } else {
                vm.$toast.error('Some unexpected happen. Please refresh the page and try again!');
            }
        });
    },
    updateDeliveryTiming({ commit }, [vm, timing]) {
        axios.post('/api/cart/update-delivery-timing', {timing:timing}).then((response) => {
            if(response.data.status == 'failed') {
                vm.$toast.info(response.data.message);
            } else if(response.data.status == 'success') {
                vm.$toast.success('Delivery Timing updated .');
                commit('UPDATE_CART_ITEMS', response.data.items)
            } else {
                vm.$toast.error('Some unexpected happen. Please refresh the page and try again!');
            }
        });
    },
    updateCartBillingAddress({ commit }, [vm, aid]) {
        axios.post('/api/cart/update-billing-address', {address_id:aid}).then((response) => {
            if(response.data.status == 'failed') {
                vm.$toast.info(response.data.message);
            } else if(response.data.status == 'success') {
                vm.$toast.success('Billing Address updated in the cart.');
                commit('UPDATE_CART_ITEMS', response.data.items)
            } else {
                vm.$toast.error('Some unexpected happen. Please refresh the page and try again!');
            }
        });
    },
    removeCartItem({ commit }, [vm, cartItem]) {
        axios.delete('/api/cart/'+cartItem.id, cartItem).then((response) => {
            vm.$toast.success('Item removed from the cart.');
            commit('UPDATE_CART_ITEMS', response.data.items)
        });
    },

    removeAllCartItems({ commit }) {
        axios.delete('/api/cart/delete/all').then((response) => {
            vm.$toast.success('Your cart is empty now.');
            commit('UPDATE_CART_ITEMS', response.data.items)
        });
    },
    addCouponCode({ commit }, [vm, cartItem]) {
        axios.post('/api/apply_coupon_code', cartItem).then((response) => {
            if(response.data.status == 'failed') {
                vm.$toast.info(response.data.message);
            } else if(response.data.status == 'success') {
                vm.$toast.success(response.data.message);
                commit('UPDATE_CART_ITEMS', response.data.items)
            } else {
                vm.$toast.error(response.data.message);
            }
        });
    },
    removeCouponCode({ commit }, [vm, cartItem]) {
        axios.post('/api/remove_coupon_code', cartItem).then((response) => {
            vm.$toast.success(response.data.message);
            commit('UPDATE_CART_ITEMS', response.data.items)
        });
    },
}
const getters = {
    cartItems: state => state.cartItems,
    shipAddress: state => {
        return (state.cartItems && state.cartItems.length == 0)?0: state.cartItems.reduce((acc, cartItem) => {
            return cartItem.address_id;
        }, 0);
    },
    billingAddress : state => {
        return (state.cartItems && state.cartItems.length == 0)?0: state.cartItems.reduce((acc, cartItem) => {
            return cartItem.billing_address_id;
        }, 0);
    },
    deliveryTime : state => {
        return (state.cartItems && state.cartItems.length == 0)?0: state.cartItems.reduce((acc, cartItem) => {
            return cartItem.delivery_time;
        }, 0);
    },
    cartTotal: state => {
        return (state.cartItems && state.cartItems.length == 0)?0: state.cartItems.reduce((acc, cartItem) => {
            return (Number(cartItem.quantity) * Number(cartItem.price)) + acc;
        }, 0).toFixed(2);
    },
    cartItemsPrice: state => {
        return (state.cartItems && state.cartItems.length == 0)?0: state.cartItems.reduce((acc, cartItem) => {
            return (Number(cartItem.quantity) * Number(cartItem.oldPrice)) + acc;
        }, 0).toFixed(2);
    },
    cartTotalDiscount: state => {
        return (state.cartItems && state.cartItems.length == 0)?0: state.cartItems.reduce((acc, cartItem) => {
            return Number(cartItem.quantity) * Number(cartItem.discount) + acc;
        }, 0);
    },
    cartTotalTax: state => {
        return (state.cartItems && state.cartItems.length == 0)?0: state.cartItems.reduce((acc, cartItem) => {
            return Number(cartItem.tax) + acc;
        }, 0);
    },
    cartTotalShipping: state => {
        return (state.cartItems && state.cartItems.length == 0)?0: state.cartItems.reduce((acc, cartItem) => {
            return Number(cartItem.shipping_cost) + acc;
        }, 0);
    },
    cartQuantity: state => {
        return (state.cartItems && state.cartItems.length == 0)?0: state.cartItems.reduce((acc, cartItem) => {
            return Number(cartItem.quantity) + acc;
        }, 0);
    },
    grandTotal: state => {
        return (state.cartItems.length == 0)?0: state.cartItems.reduce((acc, cartItem) => {
            return ((Number(cartItem.quantity) * cartItem.oldPrice) + Number(cartItem.shipping_cost) + Number(cartItem.tax) - Number(cartItem.quantity) * Number(cartItem.discount) ) + acc;
        }, 0).toFixed(2);
    },
}
const cartModule = {
    state,
    mutations,
    actions,
    getters
}
export default cartModule;