<template>
    <Head title="Cart" />

    <GuestLayout>
        <div class="sticky-header-next-sec  ec-breadcrumb section-space-mb">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="row ec_breadcrumb_inner">
                            <div class="col-md-6 col-sm-12">
                                <h2 class="ec-breadcrumb-title">Cart</h2>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <ul class="ec-breadcrumb-list">
                                   <li class="ec-breadcrumb-item">
                                        <Link :href="route('home')">Home</Link>
                                    </li>
                                    <li class="ec-breadcrumb-item active">Cart</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <section class="ec-page-content section-space-p">
            <div class="container">
                <div class="row">
                    <div class="ec-cart-leftside col-lg-8 col-md-12" :class="[(cartQuantity >= 1)?'col-lg-8':'col-lg-12']">
                        <div class="ec-cart-content">
                            <div class="ec-cart-inner">
                                <div class="row">
                                    <form action="#">
                                        <div class="table-content cart-table-content">
                                            <table>
                                                <thead>
                                                    <tr>
                                                        <th>Product</th>
                                                        <th>Price</th>
                                                        <th style="text-align: center;">Quantity</th>
                                                        <th>Total</th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody v-if="cartQuantity >= 1">
                                                    <cart-item :item="citem" v-for="(citem, ck) in cartItems" :key="'cart-item-'+ck" />
                                                </tbody>
                                                <tbody v-else>
                                                    <p class="p-4" style="height:200px"> Your cart is empty </p>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="ec-cart-update-bottom">
                                                    <Link :href="route('home')">Continue Shopping</Link>
                                                    <Link v-if="cartQuantity >= 1" :href="route('checkout')" as="button" class="btn btn-primary">Checkout</Link>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="ec-cart-rightside col-lg-4 col-md-12"  :class="[(cartQuantity >= 1)?'col-lg-4':'d-none']">
                        <div class="ec-sidebar-wrap">
                            <div class="ec-sidebar-block">
                                <div class="ec-sb-title">
                                    <h3 class="ec-sidebar-title">Cart Summery</h3>
                                </div>
                                <div class="ec-sb-block-content m-0 p-0 border-bottom-0 border-top pt-3">
                                    <div class="ec-cart-summary-bottom">
                                        <div class="ec-cart-summary">
                                            <div>
                                                <span class="text-left">Sub-Total</span>
                                                <span class="text-right">
                                                    <price :price="cartTotal" :currency="currencyIcon"></price>
                                                </span>
                                            </div>
                                            <div>
                                                <span class="text-left">Tax (CGST + SGST)</span>
                                                <span class="text-right">
                                                    <price :price="cartTotalTax" :currency="currencyIcon"></price>
                                                </span>
                                            </div>
                                            <div>
                                                <span class="text-left">Delivery Charges</span>
                                                <span class="text-right">
                                                    <price :price="cartTotalShipping" :currency="currencyIcon"></price>
                                                </span>
                                            </div>
                                            <div>
                                                <span class="text-left">Coupan Discount</span>
                                                <span class="text-right" v-if="cartTotalDiscount >= 1">
                                                    <price :price="cartTotalDiscount" :currency="currencyIcon"></price> 
                                                    <a class="text-danger" href="javascript:;" @click="removeInCouponCode()"> X </a>
                                                </span>
                                                <span class="text-right" v-else>
                                                    <a class="ec-cart-coupan" @click="showCoupon = !showCoupon">Apply Coupan</a>
                                                </span>
                                            </div>
                                            <div class="ec-cart-coupan-content" :style="(showCoupon && cartTotalDiscount == 0)?'display:block':'display:none'">
                                                <div class="ec-cart-coupan-form ">
                                                    <input class="ec-coupan" type="text" placeholder="Enter Coupan Code" name="ec-coupan" v-model="couponcode">
                                                    <button class="ec-coupan-btn button btn-primary" type="button" @click="addInCouponCode()">Apply</button>
                                                </div>
                                            </div>
                                            <div class="ec-cart-summary-total">
                                                <span class="text-left">Total Amount</span>
                                                <span class="text-right">
                                                    <price :price="grandTotal" :currency="currencyIcon"></price>
                                                </span>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <!-- Sidebar Summary Block -->
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </GuestLayout>
</template>
<script>
import GuestLayout from "@/Layouts/Main.vue";

import { mapGetters, mapActions } from 'vuex';
import Price from '@/Pages/Product/Elemants/Price.vue';
import CartItem from './Includes/CartItem.vue';


export default {
    emits: ["closeSidebar"],
    data() {
        return {
            showCoupon:false,
            couponcode:'',
            loading:false,
            pincode:''
        }
    },
    components: {
        Price,
        GuestLayout,
        CartItem
    },
    props: {
        showCart: [Boolean],
    },
    computed: {
        ...mapGetters(['cartItems', 'currency', 'cartTotal', 'cartQuantity', 'grandTotal', 'cartTotalShipping', 'cartTotalTax', 'cartTotalDiscount', 'currency']),
        currencyIcon() {
            return this.currency;
        },
    },
    methods: {
        ...mapActions(['addCouponCode', 'removeCouponCode']),
        addInCouponCode() {
            this.addCouponCode([this, {code:this.couponcode}])
        },
        removeInCouponCode(code) {
            this.removeCouponCode([this, {code:code}])
        }
    },
   
};
</script>