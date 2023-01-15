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
                                            <a href="javascript:void(0)" class="badge badge-warning badge-button" @click="setShippingDefault()">Set as Default </a>
                                            <i class="ecicon eci-minus cursor-pointer" v-if="step == 'shipping'" @click="step = 'billing'"></i>
                                            <i class="ecicon eci-plus cursor-pointer" @click="step = 'shipping'" v-else></i>
                                       </span>
                                   </div>
                                    <div class="checkout-page-body address-container" :class="[(step == 'shipping') ? 'active' : '']">
                                        <div class="address-flex-item m-2" v-for="(address, index) in addresses" :key="'address-' + index" :class="[(shipAddress == address.id) ? 'border border-success' : 'border border-light']" @click="selectAddress(address)">
                                            <div class="address-item bg-light">
                                                <h6>{{ address.name }}</h6>
                                                <ul>
                                                    <li>{{ address.address }}</li>
                                                    <li><b>Landmark - </b> {{ address.landmark }}</li>
                                                    <li><b>Mob No. </b> {{ address.phone }}</li>
                                                    <li><b>City </b> {{ address.city }}</li>
                                                    <li><b>Zip Code </b> {{ address.postal_code }}</li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="address-flex-item m-2">
                                            <div class="address-item bg-light">
                                                    <ul>
                                                        <li>
                                                            <input v-model="form.name" type="text" name="name" placeholder="Enter your name" required />
                                                            <div class="form-err-msg" v-if="form.errors.has('name')" v-html="form.errors.get('name')" />
                                                        </li>
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
                                    <p style="color:red;" v-html="'Please select shipping address !!'" v-if="showShippingError"></p>
                               </div>
                            </div>
                        </div>
                        <!--  -->
                        <div class="ec-cart-content">
                            <div class="ec-cart-inner">
                               <div class="checkout-page">
                                   <div class="checkout-page-header active">
                                       Select Billing Address or Create New Address
                                       <span class="float-right">
                                            <a href="javascript:void(0)" class="badge badge-warning badge-button" @click="setBillingDefault()">Set as Default </a>
                                            <i class="ecicon eci-minus cursor-pointer" v-if="step == 'billing'" @click="step = 'shipping'"></i>
                                            <i class="ecicon eci-plus cursor-pointer" @click="step = 'billing'" v-else></i>
                                       </span>
                                   </div>
                                    <div class="checkout-page-body address-container" :class="[(step == 'billing') ? 'active' : '']">
                                        <div class="address-flex-item m-2" v-for="(address, index) in addresses" :key="'address-' + index" :class="[(billingAddress == address.id) ? 'border border-success' : 'border border-light']" @click="selectBillingAddress(address)">
                                            <div class="address-item bg-light">
                                                <h6>{{ address.name }}</h6>
                                                <ul>
                                                    <li>{{ address.address }}</li>
                                                    <li><b>Landmark - </b> {{ address.landmark }}</li>
                                                    <li><b>Mob No. </b> {{ address.phone }}</li>
                                                    <li><b>City </b> {{ address.city }}</li>
                                                    <li><b>Zip Code </b> {{ address.postal_code }}</li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="address-flex-item m-2">
                                            <div class="address-item bg-light">
                                                    <ul>
                                                        <li>
                                                            <input v-model="form.name" type="text" name="name" placeholder="Enter your name" required />
                                                            <div class="form-err-msg" v-if="form.errors.has('name')" v-html="form.errors.get('name')" />
                                                        </li>
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
                                    <p style="color:red;" v-html="'Please select shipping address !!'" v-if="showBillingError"></p>
                               </div>
                            </div>
                        </div>
                    </div>
                    <div class="ec-cart-rightside col-lg-4 col-md-12">
                        <div class="ec-sidebar-wrap ec-sidebar-delivery">
                            <div class="ec-sidebar-block ">
                                <h3 class="ec-sidebar-title">Prefered Delivery Time</h3>
                                <div class="delivery-time">
                                    <button type="button" class="btn btn-sm delivery-time-block " :class="[(timing == time) ? ' btn-delivery-success' : 'btn-warning btn-delivery-warning']" v-for="(time, index) in timings" :key="'time-'+index" @click="updateTiming(time)">{{ time }}</button>
                                </div>
                            </div>
                            <p style="color:red;" v-if="showDeliveryTimeError">Please select expected delivery time</p>
                        </div>
                        <!-- 
                        <div class="ec-sidebar-wrap" v-if="shipAddress && billingAddress">
                            <div class="ec-sidebar-block">
                                <div class="row">
                                    <div class="col-6 col-md-6 col-sm-12">
                                        <h3 class="ec-sidebar-title">Make Selected Shipping/Billing Address As Default</h3>
                                    </div>
                                    <div class="col-6 col-md-6 col-sm-12 pt-3">                                        
                                            <input @click="setShippingBilingDefault()" class=" shipping-billing-address w-1 h-1 text-blue-600 bg-gray-100 rounded border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600" type="checkbox" value="" id="shipping-billing-address" >                                        
                                    </div>
                                </div>
                            </div>
                        </div> -->
                        
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
                                                    <price :price="cartItemsPrice" :currency="currencyIcon"></price>
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
                                                <span class="text-left">Discount</span>
                                                <span class="text-right" v-if="cartTotalDiscount >= 1">
                                                    <price :price="cartTotalDiscount" :currency="currencyIcon"></price>
                                                </span>
                                            </div>
                                            <div>
                                                <span class="text-left">Coupan Discount</span>
                                                <span class="text-right" v-if="cartTotalCoupanDiscount >= 1">
                                                    <price :price="cartTotalCoupanDiscount" :currency="currencyIcon"></price> 
                                                    <a class="text-danger" href="javascript:;" @click="removeInCouponCode()"> X </a>
                                                </span>
                                                <span class="text-right" v-else>
                                                    <a class="ec-cart-coupan" @click="showCoupon = !showCoupon">Apply Coupan</a>
                                                </span>
                                            </div>
                                            <div class="ec-cart-coupan-content" :style="(showCoupon && cartTotalCoupanDiscount == 0)?'display:block':'display:none'">
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
                                        <div class="discount-container" v-if="cartTotalDiscount + cartTotalCoupanDiscount > 0">
                                            <div class="discount-flex-item">You will save 
                                                <price :price="cartTotalDiscount + cartTotalCoupanDiscount" :currency="currencyIcon"></price>
                                                on this order</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <a :href="route('checkout.get_payment')" class="btn btn-primary mt-4" v-if="shipAddress >= 1 && billingAddress >= 1 && timing != ''">
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
            form: new Form({
                name:'',
                phone:'',
                address:'',
                city:'',
                postal_code:'',
                landmark:''
            }),
            timing:'',
            timings:[
                '07AM TO 09AM',
                '09AM TO 11AM',
                '11AM TO 01PM',
                '01AM TO 03PM',
                '03AM TO 05PM',
                '05AM TO 07PM',
                '07AM TO 09PM',
            ],
            step:'shipping',
            showShippingError:false,
            showBillingError:false,
            showDeliveryTimeError : false,
        }
    },
    components: {
        GuestLayout,
        Price,
        CartItem
    },
    props: {
        // step:[String],
        // showCart: [Boolean],
        addresses:[Array]
    },
    computed: {
        ...mapGetters(['cartItems', 'currency', 'cartTotal', 'cartQuantity', 'grandTotal', 'cartTotalShipping', 'cartTotalTax', 'cartTotalDiscount', 'shipAddress', 'billingAddress', 'deliveryTime', 'cartItemsPrice', 'cartTotalCoupanDiscount']),
        currencyIcon() {
            return this.currency;
        },
        
    },
    methods: {
        ...mapActions(['addCouponCode', 'removeCouponCode', 'updateCartAddress', 'updateCartBillingAddress', 'updateDeliveryTiming']),
        addInCouponCode() {
            if(this.couponcode == ''){
                this.$toast.warning('Please enter the coupan first')
                return false
            }
            this.addCouponCode([this, {code:this.couponcode}])
        },
        removeInCouponCode(code) {
            this.removeCouponCode([this, {code:code}])
        },
        selectAddress(aid) {
            this.updateCartAddress([this, aid.id])
            this.showShippingError = false
        },
        selectBillingAddress(aid){
            this.updateCartBillingAddress([this, aid.id])
            this.showBillingError = false
        },
        saveAddress(){
            this.form.post('/api/address').then((response) => {
                this.$toast.success('address saved successfully.')
                this.form.reset()
                Inertia.reload()
            })
        },
        gotoPayment() {
            
            if(typeof this.shipAddress === 'undefined' || this.shipAddress === null){
                this.step = 'shipping'
                this.showShippingError = true
                // this.$toast.error('Please select shipping <i class="bi bi-cart"></i> address  ');            
                return false    
            }
            if(typeof this.billingAddress === 'undefined' || this.billingAddress === null){
                this.step = 'billing'
                this.showBillingError = true
                // this.$toast.error('Please select billing <i class="bi bi-file-earmark-easel"></i> address  ');            
                return false    
            }
            if(this.timing == ''){
                this.showDeliveryTimeError = true
                // this.$toast.error('Please select expected delivery time ');     
                return false           
            }
        },
        updateTiming(time){
            this.timing = time
            this.showDeliveryTimeError = false
            this.updateDeliveryTiming([this, this.timing])
        },
        setShippingBilingDefault(){
            if (document.getElementById('shipping-billing-address').checked){
                axios.post('/api/set-default-shipping-billing-address', {shipAddress: this.shipAddress, billingAddress: this.billingAddress})
            }else{
                axios.post('/api/un-set-default-shipping-billing-address')
            }
        },
        setShippingDefault(){
            axios.post('/api/set-default-shipping-address', {shipAddress: this.shipAddress}).then((response) => {
                this.$toast.success('Selected address has been set as default shipping address')
            })
        },
        setBillingDefault(){
            axios.post('/api/set-default-billing-address', {billingAddress: this.billingAddress}).then((response) => {
                this.$toast.success('Selected address has been set as default billing address')
            })
        }
    },
    mounted() {        
        let setDefault = this.addresses.filter((ele) => {
            if(ele.set_default){
                return ele
            }
        })
        if(setDefault.length == 1){
            this.selectAddress(setDefault[0])
        }
        let setDefaultBilling = this.addresses.filter((ele) => {
            if(ele.set_default_billing){
                return ele
            }
        })
        if(setDefaultBilling.length == 1){
            this.selectBillingAddress(setDefaultBilling[0])
        }
    },
};
</script>
<style>
    .floatting-cart{
        display:none;
    }
    .checkout-page-body{
        overflow-y:auto;
    }
    .ec-sidebar-delivery h3{
        display:inline;
    }
    .shipping-billing-address{
        height: 20px;
    }
    .discount-container{
        display:flex;
        justify-content: center;
        flex-direction: row;
        border-top: 1px solid #e7e7e7;
    }
    .discount-flex-item{
        font-weight: 700;
        color: #22d381;
        padding-top: 10px;
        margin: unset;
    }
    .delivery-time{
        /* display:flex;
        justify-content: flex-start;
        flex-direction: row; */
        height:200px;

    }
    .delivery-time-block{
        margin:1px 1px;
        width: max-content;
        border-radius: 5px;
    }    
    .badge-button{
        color: #258fe5;
    }
    .address-container{
        display:flex;
        flex-direction: row;
        justify-content: flex-start;
    }
    .address-flex-item{
        min-width: 270px;
        max-width: 270px;
        height: fit-content;
        padding:0;
        margin:2px !important;        
    }
    .address-item{
        min-height: 270px;
        cursor: pointer;
        padding:5px;
        margin:2px;
    }
    .address-item input{
        height:35px;
        margin-bottom:2px;
    }
    .address-item button{
        height:35px;
        margin-bottom:2px;
        width:100%;
    }
    .btn-delivery-warning{
        background-color: #ffc926a6;
    }
    .btn-delivery-warning:hover{
        background-color: #ffc926a6;
    }
    .btn-delivery-success{
        background-color: #4de79f;
    }
    .btn-delivery-success:hover{
        background-color: #4de79f;
    }
</style>