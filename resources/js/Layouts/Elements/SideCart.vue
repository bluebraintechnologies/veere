<template>
    <!-- Ekka Cart Start -->
    <div class="ec-side-cart-overlay" style="display:block" v-if="cartStatus"></div>
    <div id="ec-side-cart" class="ec-side-cart" :class="{'ec-open':cartStatus}">
        <div class="ec-cart-inner">
            <div class="ec-cart-top">
                <div class="ec-cart-title m-0 ">
                    <span class="cart_title">My Cart</span>
                    <button class="ec-close" type="button" @click="closeSidebar">×</button>
                </div>
                <ul class="eccart-pro-items">
                    <cart-item :item="citem" v-for="(citem, ck) in cartItems" :key="'cart-item-'+ck" />
                    <!-- <li v-for="item in cartItems.product" :key="'catr-item-' + item.id">
                        <a href="product-left-sidebar.html" class="sidecart_pro_img"><img
                                src="assets/images/product-image/55_1.jpg" alt="product"></a>
                        <div class="ec-pro-content">
                            <a href="single-product-left-sidebar.html" class="cart_pro_title">{{ item.product }}</a>
                            <span class="cart-price"><span>{{ item.min_price }}</span> x 1</span>
                            <div class="qty-plus-minus">
                                <input class="qty-input" type="text" name="ec_qtybtn" value="1" />
                            </div>
                            <a href="#" class="remove">×</a>
                        </div>
                    </li> -->
                    
                </ul>
            </div>
            <div class="ec-cart-bottom">
                <div class="cart-sub-total  m-0">
                    <table class="table cart-table  m-0">
                        <tbody>
                            <tr>
                                <td class="text-left">Cart Total :</td>
                                <td class="text-right">
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
    <!-- Ekka Cart End -->
</template>
<script>
import { mapGetters } from "vuex";
import { mapActions } from 'vuex'
import Price from '@/Pages/Product/Elemants/Price.vue';
import CartItem from './MiniCartItem.vue';
export default {
    emits: ["closeSidebar"],
    components:{
      Price, CartItem
    },
    props: {
        showCart: [Boolean],
    },
    computed: {
        cartStatus() {
            return this.showCart;
        },
         currencyIcon() {
             return (this.currency)?this.currency:'Rs. ';
        },
        ...mapGetters(["cartItems", 'cartTotal', 'currency'])
    },
    methods: {
        ...mapActions(["addProductToCart"])
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