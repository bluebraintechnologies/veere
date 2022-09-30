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
                                   <div class="checkout-page-header" :class="[(step == 'payment')?'active':'']">
                                       Select Mode of Payment
                                       <span class="float-right">
                                           <i class="ecicon eci-minus cursor-pointer" v-if="step == 'payment'"></i>
                                           <i class="ecicon eci-plus cursor-pointer" v-else></i>
                                       </span>
                                   </div>
                                   <div class="checkout-page-body" :class="[(step == 'payment')?'active':'']">
                                        <div class="row m-0">
                                            <div class="col-6 col-md-4 mb-3" v-for="pm in paymentModes" :key="'paymode'+pm.value">
                                                <label class="aiz-megabox border d-block paysec" :class="[(payment_option == pm.value)?'selected_pay':'']" @click="payment_option = pm.value">
                                                    <span class="d-block p-3 aiz-megabox-elem">
                                                        <img :src="pm.image" class="img-fluid mb-2">
                                                        <span class="d-block text-center">
                                                            <span class="d-block fw-600 fs-15">{{pm.label}}</span>
                                                        </span>
                                                    </span>
                                                </label>
                                            </div>
                                            <!-- <div class="col-6 col-md-4 mb-3"  v-if="currency.cash_payment == 1">
                                                <label class="aiz-megabox border d-block paysec" :class="[(payment_option == 'cash_on_delivery')?'selected_pay':'']" @click="payment_option = 'cash_on_delivery'">
                                                    <span class="d-block p-3 aiz-megabox-elem">
                                                        <img src="/images/cod.png" class="img-fluid mb-2">
                                                        <span class="d-block text-center">
                                                            <span class="d-block fw-600 fs-15">Cash on Delivery</span>
                                                        </span>
                                                    </span>
                                                </label>
                                            </div> -->
                                        </div>
                                   </div>
                               </div>
                               <div class="row mt-4">
                                    <div class="col-6">
                                        <Link :href="route('checkout')" class="btn btn-primary">
                                            Back To Address
                                        </Link>
                                    </div>
                                    <div class="col-6 text-right">
                                        <button types="button" @click="finalCheckout" class="btn btn-primary">
                                            Place Order
                                        </button>
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
                                        <b>Shipping Address:</b>
                                        <div class="p-2 border mb-4" v-if="address">
                                            <h6>{{ address.name }} </h6>
                                            <ul>
                                                <li class="pb-0">{{ address.address }}</li>
                                                <li class="pb-0"><b>Landmark - </b> {{ address.landmark }}</li>
                                                <li class="pb-0"><b>Mob No. </b> {{ address.phone }}</li>
                                                <li class="pb-0"><b>City </b> {{ address.city }}</li>
                                                <li class="pb-0"><b>Zip Code </b> {{ address.postal_code }}</li>
                                            </ul>
                                        </div>
                                        <b>Billing Address:</b>
                                        <div class="p-2 border mb-4" v-if="billing_address">
                                            <h6>{{ billing_address.name }} </h6>
                                            <ul>
                                                <li class="pb-0">{{ billing_address.address }}</li>
                                                <li class="pb-0"><b>Landmark - </b> {{ billing_address.landmark }}</li>
                                                <li class="pb-0"><b>Mob No. </b> {{ billing_address.phone }}</li>
                                                <li class="pb-0"><b>City </b> {{ billing_address.city }}</li>
                                                <li class="pb-0"><b>Zip Code </b> {{ billing_address.postal_code }}</li>
                                            </ul>
                                        </div>
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
                                            <div v-if="cartTotalDiscount >= 1">
                                                <span class="text-left">Coupan Discount</span>
                                                <span class="text-right">
                                                    <price :price="cartTotalDiscount" :currency="currencyIcon"></price> 
                                                </span>
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
import { Inertia } from '@inertiajs/inertia';

export default {
    emits: ["closeSidebar"],
    data() {
        return {
            loading:false,
            payment_option:''
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
        ...mapGetters(['cartItems', 'currency', 'cartTotal', 'cartQuantity', 'grandTotal', 'cartTotalShipping', 'cartTotalTax', 'cartTotalDiscount', 'shipAddress', 'billingAddress', 'paymentModes']),
        currencyIcon() {
            return this.currency;
        },
        address() {
            let ads = this.addresses.filter(ele => {
                return this.shipAddress == ele.id
            })
            return ads[0]
        },
        billing_address() {
            let ads = this.addresses.filter(ele => {
                return this.billingAddress == ele.id
            })
            return ads[0]
        }
    },
    methods: {
        finalCheckout() {
            if(this.payment_option == '') {
                this.$toast.error('Please select payment mode.');
                return false;
            } else if(this.payment_option == 'cash_on_delivery') {
                Inertia.post(route('payment.checkout'), { payment_option: this.payment_option });
            } else {
                 var formElement = document.createElement("form");
                formElement.setAttribute("method", "post");
                formElement.setAttribute("action", '/checkout/final-checkout');
                formElement.setAttribute("target", "_parent");
                document.body.appendChild(formElement);
                var hiddenField = document.createElement("input");
                hiddenField.setAttribute("name", '_token');
                hiddenField.setAttribute("value", this.$csrf_token);
                formElement.appendChild(hiddenField);
                var hiddenField2 = document.createElement("input");
                hiddenField2.setAttribute("name", 'payment_option');
                hiddenField2.setAttribute("value", this.payment_option);
                formElement.appendChild(hiddenField2);
                formElement.submit();

            }
        },
    },
   
};
</script>