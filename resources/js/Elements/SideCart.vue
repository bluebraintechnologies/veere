<template>
    <div class="ec-side-cart-overlay" style="display:block" v-if="cartStatus"></div>
    <div id="ec-side-cart" class="ec-side-cart" :class="{'ec-open':cartStatus}">
        <div class="ec-cart-inner">
            <div class="ec-cart-top">
                <div class="ec-cart-title">
                    <span class="cart_title">My Cart</span>
                    <button class="ec-close" type="button" @click="closeSidebar">×</button>
                </div>
                <ul class="eccart-pro-items">
                    <cart-item :item="citem" v-for="(citem, ck) in cartItems" :key="'cart-item-'+ck" />
                </ul>
            </div>
            <div class="ec-cart-bottom">
                <div class="cart-sub-total">
                    <table class="table cart-table">
                        <tbody>
                            <tr>
                                <td class="text-left">Total :</td>
                                <td class="text-right primary-color">
                                    <price :price="cartTotal" :currency="currencyIcon"></price>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="cart_btn">
                    <Link :href="route('cart')" class="btn btn-primary">View Cart</Link>
                    <Link :href="route('checkout')" class="btn btn-secondary">Checkout</Link>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import { mapGetters } from 'vuex';
import Price from '@/Pages/Product/Elemants/Price.vue';
import CartItem from './MiniCartItem.vue';

export default {
    emits: ["closeSidebar"],
    components: {
        Price,
        CartItem
    },
    props: {
        showCart: [Boolean],
    },
    computed: {
        ...mapGetters(['cartItems', 'currency', 'cartTotal']),
        currencyIcon() {
            return this.currency;
        },
        cartStatus() {
            return this.showCart;
        },
    },
    setup(props, { emit }) {
        const closeSidebar = () => {
            emit("closeSidebar");
        };
        return {
            closeSidebar,
        };
    },
};
</script>