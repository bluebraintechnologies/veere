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
                                                <tbody v-if="(cartQuantity >= 0)">
                                                    <cart-item :item="citem" v-for="(citem, ck) in cartItems" :key="'cart-item-'+ck" />
                                                </tbody>
                                                <tbody v-else>
                                                    <p class="p-4" style="height:200px"> Your cart is empty </p>
                                                </tbody>
                                            </table>
                                        </div>
                                        <!-- <p>Note : Order above {{ currency }} {{ reward_point_setting.rc_order_max_value }}, will give you {{ reward_point_setting.rp_name }}</p> -->
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
                                                    <price :price="cartItemsPrice" :currency="currency"></price>
                                                </span>
                                            </div>
                                            <!-- <div>
                                                <span class="text-left">Tax (GST)</span>
                                                <span class="text-right">
                                                    <price :price="cartTotalTax" :currency="currency"></price>
                                                </span>
                                            </div> -->
                                            <div>
                                                <span class="text-left">Delivery Charges</span>
                                                <span class="text-right" >
                                                    <price :price="deliveryCharges" :currency="currency"></price>
                                                </span>
                                            </div>
                                            <div>
                                                <span class="text-left">Discount</span>
                                                <span class="text-right" v-if="cartTotalDiscount >= 1">
                                                    <price :price="cartTotalDiscount" :currency="currency"></price>
                                                </span>
                                            </div>
                                            <div v-if="cartTotalCoupanDiscount >= 1">
                                                <span class="text-left">Coupan Discount</span>
                                                <span class="text-right" >
                                                    <price :price="cartTotalCoupanDiscount" :currency="currency"></price> 
                                                </span>
                                            </div>
                                            <div class="ec-cart-summary-total">
                                                <span class="text-left">Total Amount</span>
                                                <span class="text-right">                                                    
                                                    <price :price="grandTotalCart" :currency="currency"></price>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="discount-container" v-if="cartTotalDiscount + cartTotalCoupanDiscount > 0">
                                            <div class="discount-flex-item">You will save 
                                                <price :price="cartTotalDiscount + cartTotalCoupanDiscount" :currency="currency"></price>
                                                on this order</div>
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
        }
    },
    components: {
        Price,
        GuestLayout,
        CartItem,
        
    },
    props: {
        business:[Object]
    },
    computed: {
        ...mapGetters(['cartItems', 'currency', 'cartTotal', 'cartQuantity', 'grandTotal', 'cartTotalShipping', 'cartTotalTax', 'cartTotalDiscount', 'shipAddress', 'billingAddress', 'deliveryTime', 'cartItemsPrice', 'cartTotalCoupanDiscount', 'reward_point_setting']),
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
        }
    },
    methods: {
        ...mapActions(['addCouponCode', 'removeCouponCode', 'updateCartAddress', 'updateCartBillingAddress', 'updateDeliveryTiming']),
    },
};
</script>
<style>
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
</style>