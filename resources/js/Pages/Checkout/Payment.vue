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
                                    <div v-if="pay_reward_points && cartItemsPrice > reward_point_setting.rc_order_max_value">
                                        <div class="checkout-page-header" :class="[block?'active':'']">
                                            Pay with {{ reward_point_setting.rp_name }} {{ vp }}
                                            <span class="float-right">
                                                <i class="ecicon eci-minus cursor-pointer" v-if="step == 'payment'"></i>
                                                <i class="ecicon eci-plus cursor-pointer" v-else></i>
                                            </span>
                                        </div>
                                        <div class="checkout-page-body" :class="[(step == 'payment')?'active':'']" style="min-height: 100px;">
                                            <div class="row m-0">
                                                <div class="col-6 col-md-4 mb-3">
                                                    <button type="button" @click="pay_with_reward_points()" class="btn btn-success" v-if="paidRewardPoint">Pay with {{ reward_point_setting.rp_name }}</button>
                                                    <button type="button" @click="pay_with_reward_points()" class="btn btn-primary" v-else>Pay with {{ reward_point_setting.rp_name }}</button>
                                                </div>
                                            </div>
                                            <p>You can use {{ pay_reward_points }} {{ reward_point_setting.rp_name }} for this order.</p>
                                        </div>
                                    </div>
                                    <div class="checkout-page-header" :class="[(step == 'payment')?'active':'']">
                                        Select Mode of Payment
                                        <span class="float-right">
                                            <i class="ecicon eci-minus cursor-pointer" v-if="step == 'payment'"></i>
                                            <i class="ecicon eci-plus cursor-pointer" v-else></i>
                                        </span>
                                    </div>
                                    <div class="checkout-page-body" :class="[(step == 'payment')?'active':'']">
                                        <div class="row m-0">
                                            <div class="col-6 col-md-4 mb-3" v-for="pm in payment_methods" :key="'paymode'+pm.value" >
                                                <label class="aiz-megabox border d-block paysec payment-1" :class="[(payment_option == pm.value)?'selected_pay':'']" @click="selectPaymentMode(pm)" >
                                                    <span class="d-block p-3 aiz-megabox-elem">
                                                        <img :src="pm.image" class="img-fluid mb-2">
                                                        <span class="d-block text-center">
                                                            <span class="d-block fw-600 fs-15">{{pm.label}}</span>
                                                        </span>
                                                    </span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                               </div>
                               <Popup></Popup>
                               <div class="row mt-4">
                                    <div class="col-6">
                                        <Link :href="route('checkout')" class="btn btn-primary">
                                            Back To Address
                                        </Link>
                                    </div>
                                    <div class="col-6 text-right">
                                        <button types="button" @click="checkPaymentMode()" class="btn btn-primary">
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
                                        <div>
                                            <b>Shipping Address:</b>
                                            <span class="float-right">
                                                <i class="ecicon eci-minus cursor-pointer" v-if="showShippingAddress" @click="showShippingAddress = false"></i>
                                                <i class="ecicon eci-plus cursor-pointer" v-else  @click="showShippingAddress = true"></i>
                                            </span>
                                            <div class="p-2 border mb-4" v-if="address" v-show="showShippingAddress">
                                                <h6>{{ address.name }} </h6>
                                                <ul>
                                                    <li class="pb-0">{{ address.address }}</li>
                                                    <li class="pb-0"><b>Landmark - </b> {{ address.landmark }}</li>
                                                    <li class="pb-0"><b>Mob No. </b> {{ address.phone }}</li>
                                                    <li class="pb-0"><b>City </b> {{ address.city }}</li>
                                                    <li class="pb-0"><b>Zip Code </b> {{ address.postal_code }}</li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div>
                                            <b>Billing Address:</b>
                                            <span class="float-right">
                                                <i class="ecicon eci-minus cursor-pointer" v-if="showBillingAddress" @click="showBillingAddress = false"></i>
                                                <i class="ecicon eci-plus cursor-pointer" v-else  @click="showBillingAddress = true"></i>
                                            </span>
                                            <div class="p-2 border mb-4" v-if="billing_address" v-show="showBillingAddress">
                                                <h6>{{ billing_address.name }} </h6>
                                                <ul>
                                                    <li class="pb-0">{{ billing_address.address }}</li>
                                                    <li class="pb-0"><b>Landmark - </b> {{ billing_address.landmark }}</li>
                                                    <li class="pb-0"><b>Mob No. </b> {{ billing_address.phone }}</li>
                                                    <li class="pb-0"><b>City </b> {{ billing_address.city }}</li>
                                                    <li class="pb-0"><b>Zip Code </b> {{ billing_address.postal_code }}</li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="ec-cart-summary">
                                            <div>
                                                <span class="text-left">Sub-Total</span>
                                                <span class="text-right">
                                                    <price :price="cartItemsPrice" :currency="currencyIcon"></price>
                                                </span>
                                            </div>
                                            <!-- <div>
                                                <span class="text-left">Tax (GST)</span>
                                                <span class="text-right">
                                                    <price :price="cartTotalTax" :currency="currencyIcon"></price>
                                                </span>
                                            </div> -->
                                            <div>
                                                <span class="text-left">Delivery Charges</span>
                                                <span class="text-right">
                                                    <price :price="deliveryCharges" :currency="currencyIcon"></price>
                                                </span>
                                            </div>
                                            <div v-if="cartTotalDiscount >= 1">
                                                <span class="text-left">Coupan Discount</span>
                                                <span class="text-right">
                                                    <price :price="cartTotalDiscount" :currency="currencyIcon"></price> 
                                                </span>
                                            </div>
                                            <div v-if="paidRewardPoint > 0">
                                                <span class="text-left">{{ reward_point_setting.rp_name }}</span>
                                                <span class="text-right">
                                                    <price :price="paidRewardPoint" :currency="currencyIcon"></price> 
                                                </span>
                                            </div>
                                            <div class="ec-cart-summary-total">
                                                <span class="text-left">Total Amount</span>
                                                <span class="text-right">
                                                    <price :price="grandTotalCart-paidRewardPoint" :currency="currencyIcon"></price>
                                                </span>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade bd-example-modal-lg" id="addproductModal"  data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="addproductModalTitle" aria-hidden="true">
                    <div class="modal-dialog modal-sm" role="document" style="width: 523px;">
                        <div class="modal-content">
                            <form >
                                <div class="modal-header">
                                    <h5 class="modal-title" id="addproductModalTitle">OTP</h5>
                                    
                                </div>
                                <div class="modal-body">
                                    <input type="text" class="form-control" v-model="orderOtp" placeholder="Enter OTP"/>
                                    <div class="form-err-msg" v-if="otpError" v-html="otpErrorMsg" />   
                                    <small v-else style="font-size: smaller;">Please enter an otp send at your registered mobile</small>

                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-primary" @click="checkOtp()"> Submit </button>
                                    <button type="button" class="btn btn-primary" data-dismiss="modal" @click="hideModal()"> Cancel </button>
                                    <!-- <button v-show="editmode" type="submit" class="btn btn-wide btn-success"> Update </button> -->
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </GuestLayout>
</template>
<style>
.modal-header{
    justify-content: start !important;
}
#addproductModal{
    background-color: #adafb1;
}
</style>
<script>
import GuestLayout from "@/Layouts/Main.vue";
import Popup from "@/Layouts/Elements/Popup.vue";
import { mapGetters, mapActions } from 'vuex';
import Price from '@/Pages/Product/Elemants/Price.vue';
import CartItem from './Includes/CartItem.vue';
import { Inertia } from '@inertiajs/inertia';

export default {
    emits: ["closeSidebar"],
    data() {
        return {
            loading:false,
            payment_option:'',
            showShippingAddress:false,
            showBillingAddress:false,
            orderOtp:'',
            otpError: false,
            otpErrorMsg: '',
            cod_allowed:false,
            otp:'',
            paymentModesCartlength:0,
        }
    },
    components: {
        Price,
        GuestLayout,
        CartItem,
        Popup
    },
    props: {
        step:[String],
        showCart: [Boolean],
        addresses:[Array],
        vp:[Number],
        business:Object,
        payment_methods:[Array]
    },
    computed: {
        ...mapGetters(['cartItems', 'currency', 'cartTotal', 'cartQuantity', 'grandTotal', 'cartTotalShipping', 'cartTotalTax', 'cartTotalDiscount', 'shipAddress', 'billingAddress', 'paymentModes', 'reward_point_setting', 'paidRewardPoint', 'cartItemsPrice']),
        grandTotalCart(){
            if(parseFloat(this.grandTotal) > parseFloat(this.business.delivery_cap)){
                return parseFloat(this.grandTotal)
            }else{
                return parseFloat(this.grandTotal) + parseFloat(this.business.delivery_charges)
            }
        },
        deliveryCharges(){
            if(parseFloat(this.grandTotal) > parseFloat(this.business.delivery_cap)){
                return 0
            }else{
                return parseFloat(this.business.delivery_charges)
            }
        },
        paymentModesCart(){
            let pmc = [];
            let pm = this.paymentModes
            let c = 0
            for(const key in pm){
                if(pm[key].value == "cash_on_delivery" && this.business.cod_enable == 1){
                    pmc[c] = {
                        value : pm[key].value,
                        image : pm[key].image,
                        label : pm[key].label,
                    }
                    ++c
                }else if(pm[key].value != "cash_on_delivery"){
                    pmc[c] = {
                        value : pm[key].value,
                        image : pm[key].image,
                        label : pm[key].label,
                    }
                    ++c
                }
            }
            this.paymentModesCartlength = c
            return pmc
        },
        currencyIcon() {
            return this.currency;
        },
        address() {
            let ads = this.addresses.filter(ele => {
                if(this.shipAddress){
                    return this.shipAddress == ele.id
                }
            })
            if(this.shipAddress){
                return ads[0]
            }
        },
        billing_address() {
            let ads = this.addresses.filter(ele => {
                if(this.billingAddress){
                    return this.billingAddress == ele.id
                }
            })
            if(this.billingAddress){
                return ads[0]
            }
        },
        block(){
            return true
        },
        pay_reward_points(){
            if( this.reward_point_setting.rc_max_order_reward_point && this.vp > this.reward_point_setting.rc_max_order_reward_point ){
                return this.reward_point_setting.rc_max_order_reward_point
            }
            return this.vp
        }
    },
    mounted(){
        this.getRewardPoints()
    },
    methods: {
        ...mapActions(['payWithRewardPoint', 'getRewardPoints']),
        pay_with_reward_points(){
            this.payWithRewardPoint([this, {pay_reward_points :this.pay_reward_points}])
            // axios.post('/api/pay-wth-reward-points', {pay_reward_points : this.pay_reward_points}).then((response) => {
            //     this.getCartItems()
            // })
        },
        checkPaymentMode(){
            if(this.payment_option == 'cash_on_delivery'){
                axios.post('/api/generate-cod-otp').then((response) => {
                    if(response.data.status == 'success'){
                        $('#addproductModal').modal('show');
                        // this.orderOtp = response.data.otp
                    }
                })
            }else{
                this.finalCheckout()
            }
        },
        selectPaymentMode(pm){   
            this.payment_option = pm.value         
            // if(pm.value == "cash_on_delivery"){
            //     axios.post('/api/generate-cod-otp').then((response) => {
            //         if(response.data.status == 'success'){
            //             $('#addproductModal').modal('show');
            //             this.orderOtp = response.data.otp
            //         }
            //     })
            // }else {
                
            // }
        },
        checkOtp(){
            this.cod_allowed = false
            this.otpError = false
            this.otpErrorMsg = ''
            axios.post('/api/check-cod-otp', {orderOtp : this.orderOtp }).then((response) => {
                if(response.data.status == 'success'){
                    this.cod_allowed = true
                    $('#addproductModal').modal('hide');
                    this.orderOtp = ''
                    this.finalCheckout()
                } else {
                    this.otpError = true
                    this.otpErrorMsg = response.data.msg 
                    this.$toast.error(response.data.msg)
                }
            })
        },
        hideModal(){
            this.orderOtp = ''
            this.payment_option = ''
            $('#addproductModal').modal('hide');
        },
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