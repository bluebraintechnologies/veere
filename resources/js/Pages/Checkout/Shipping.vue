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
                                   <div class="checkout-page-header active">
                                       Select Shipping Address or Create New Address
                                       <span class="float-right">
                                           <i class="ecicon eci-minus cursor-pointer" v-if="step == 'shipping'"></i>
                                           <i class="ecicon eci-plus cursor-pointer" @click="step = 'shipping'" v-else></i>
                                       </span>
                                   </div>
                                   <div class="checkout-page-body active">
                                        <div class="row">
                                            <div class="col-md-4 col-sm-12 mb-4" v-for="(address, index) in addresses" :key="'address-' + index">
                                                <div class="p-2 border">
                                                    <h6>{{ address.name }}
                                                        <button type="button" class="btn select-btn btn-primary" v-if="shipAddress == address.id">
                                                            selected
                                                        </button>
                                                        <button type="button" class="btn select-btn btn-warning" @click="selectAddress(address)" v-else>
                                                            select
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
                                            <div class="col-md-4 col-sm-12 mb-4">
                                                <div class="p-2 border checkout-address">
                                                    <h6> 
                                                        <input v-model="form.name" type="text" name="name" placeholder="Enter your name" required />
                                                        <div class="form-err-msg" v-if="form.errors.has('name')" v-html="form.errors.get('name')" />
                                                    </h6>
                                                    <ul>
                                                        <li>
                                                            <input v-model="form.address" name="address" type="text" placeholder="Enter your address" />
                                                            <div class="form-err-msg" v-if="form.errors.has('address')" v-html="form.errors.get('address')" />
                                                        </li>
                                                        <li>
                                                            <input v-model="form.landmark" name="landmark" type="text" placeholder="Enter your landmark" />
                                                            <div class="form-err-msg" v-if="form.errors.has('landmark')" v-html="form.errors.get('landmark')" />
                                                        </li>
                                                        <li>
                                                            <input v-model="form.phone" type="text" name="phonenumber" placeholder="Enter your phone number" required />
                                                            <div class="form-err-msg" v-if="form.errors.has('phone')" v-html="form.errors.get('phone')" />
                                                        </li>
                                                        <li>
                                                            <input v-model="form.city" type="text" placeholder="Enter your city" required />
                                                            <div class="form-err-msg" v-if="form.errors.has('city')" v-html="form.errors.get('city')" />
                                                        </li>
                                                        <li>
                                                            <input v-model="form.postal_code" type="text" name="postalcode" placeholder="Post Code" />
                                                            <div class="form-err-msg" v-if="form.errors.has('postal_code')" v-html="form.errors.get('postal_code')" />
                                                        </li>
                                                    </ul>
                                                    <button type="button" class="btn btn-sm btn-warning" @click="saveAddress()">Save Address
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
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
                        </div>
                        <a :href="route('checkout.get_payment')" class="btn btn-primary mt-4" v-if="shipAddress >= 1">
                            Procees To Payment Mode
                        </a>
                        <button type="button" class="btn btn-primary mt-4" @click="gotoPayment" v-else>
                            Procees To Payment Mode
                        </button>
                    </div>
                </div>
            </div>
        </section>
    </GuestLayout>
</template>
<script>
import GuestLayout from "@/Layouts/Main.vue";
import Form from "vform";
import { mapGetters, mapActions } from 'vuex';
import Price from '@/Pages/Product/Elemants/Price.vue';
import CartItem from './Includes/CartItem.vue';
import { Inertia } from '@inertiajs/inertia';

export default {
    emits: ["closeSidebar"],
    data() {
        return {
            loading:false,
            showCoupon:false,
            couponcode:'',
            pincode:'',
            form: new Form({
                name:'',
                phone:'',
                address:'',
                city:'',
                postal_code:'',
                landmark:''
            })
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
        },
        saveAddress(){
            this.form.post('/api/address').then((response) => {
                this.$toast.success('address saved successfully.')
                this.form.reset()
                Inertia.reload()
            })
        },
        gotoPayment() {

                this.$toast.error('Please select shipping address');
            
        }
    },
   
};
</script>