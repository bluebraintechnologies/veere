<template>
    <Head title="Cart" />

    <GuestLayout>
        <div class="sticky-header-next-sec  ec-breadcrumb section-space-mb">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="row ec_breadcrumb_inner">
                            <div class="col-md-6 col-sm-12">
                                <h2 class="ec-breadcrumb-title">Checkout</h2>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <ul class="ec-breadcrumb-list">
                                   <li class="ec-breadcrumb-item">
                                        <Link :href="route('home')">Home</Link>
                                    </li>
                                    <li class="ec-breadcrumb-item active">Checkout</li>
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
                    <div class="ec-cart-leftside col-lg-8 col-md-12">
                        <div class="ec-cart-content">
                            <div class="ec-cart-inner">
                               <div class="checkout-page">
                                   <div class="checkout-page-header" :class="[(step == 'shipping')?'active':'']">
                                       Select Shipping Address or Create New Address
                                       <span class="float-right">
                                           <i class="ecicon eci-minus cursor-pointer" v-if="step == 'shipping'"></i>
                                           <i class="ecicon eci-plus cursor-pointer" @click="step = 'shipping'" v-else></i>
                                       </span>
                                   </div>
                                   <div class="checkout-page-body" :class="[(step == 'shipping')?'active':'']">
                                        <div class="row">
                                            <div class="col-md-4 col-sm-12 mb-4" v-for="(address, index) in addresses" :key="'address-' + index">
                                                <div class="p-2 border">
                                                    <h6>{{ address.name }}
                                                        <button type="button" class="btn select-btn btn-primary" v-if="shipAddress == address.id">
                                                            selected
                                                        </button>
                                                        <button type="button" class="btn select-btn btn-warning" @click="selectAddress(address)" v-else>
                                                            selected
                                                        </button>
                                                    </h6>
                                                    <ul>
                                                        <li>{{ address.address }}</li>
                                                        <li><b>Landmark - </b> {{ address.landmark }}</li>
                                                        <li><b>Mob No. </b> {{ address.phone }}</li>
                                                        <li><b>City </b> {{ address.city }}</li>
                                                        <li><b>Zip Code </b> {{ address.postal_code }}</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                   </div>
                               </div>
                               <div class="checkout-page">
                                   <div class="checkout-page-header" :class="[(step == 'billing')?'active':'']">
                                       Select Billing Addres or Create New Address
                                       <span class="float-right">
                                           <i class="ecicon eci-minus cursor-pointer" v-if="step == 'billing'"></i>
                                           <i class="ecicon eci-plus cursor-pointer" v-else></i>
                                       </span>
                                   </div>
                                   <div class="checkout-page-body" :class="[(step == 'billing')?'active':'']">
                                       
                                   </div>
                               </div>
                               <div class="checkout-page">
                                   <div class="checkout-page-header" :class="[(step == 'payment')?'active':'']">
                                       Select Mode of Payment
                                       <span class="float-right">
                                           <i class="ecicon eci-minus cursor-pointer" v-if="step == 'payment'"></i>
                                           <i class="ecicon eci-plus cursor-pointer" v-else></i>
                                       </span>
                                   </div>
                                   <div class="checkout-page-body" :class="[(step == 'payment')?'active':'']">
                                       
                                   </div>
                               </div>
                            </div>
                        </div>
                    </div>
                    <div class="ec-cart-rightside col-lg-4 col-md-12">
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
                                                <span class="text-left">Tax (GST)</span>
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
            loading:false,
            showCoupon:false,
            couponcode:'',
            pincode:''
        }
    },
    components: {
        Price,
        GuestLayout,
        CartItem
    },
    props: {
        step:[String],
        showCart: [Boolean],
        addresses:[Array]
    },
    computed: {
        ...mapGetters(['cartItems', 'currency', 'cartTotal', 'cartQuantity', 'grandTotal', 'cartTotalShipping', 'cartTotalTax', 'cartTotalDiscount', 'shipAddress']),
        currencyIcon() {
            return this.currency;
        },
    },
    methods: {
        ...mapActions(['addCouponCode', 'removeCouponCode', 'updateCartAddress']),
        addInCouponCode() {
            this.addCouponCode([this, {code:this.couponcode}])
        },
        removeInCouponCode(code) {
            this.removeCouponCode([this, {code:code}])
        },
        selectAddress(aid) {
            this.updateCartAddress([this, aid.id])
        }
    },
   
};
</script>