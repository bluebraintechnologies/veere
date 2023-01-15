<script>
import AuthenticatedLayout from "@/Layouts/Authenticated.vue";
import { mapGetters, mapActions } from 'vuex';
import moment from 'moment';
import { Inertia } from '@inertiajs/inertia';
import { useForm } from '@inertiajs/inertia-vue3';

export default {
    name: "Order Details",
    props: {
       order:Object,
    },
    components: {
        AuthenticatedLayout,
    },
    data() {
        return {
            order_css: {
                ordered : 'text-secondary',
                processing : 'text-info',
                picking : 'text-info',
                packed : 'text-info',
                shipped : 'text-primary',
                delivered : 'text-success',
                cancelled : 'text-warning',
            },
            form : useForm({
                name : '',
                email : '',
                content : '',                
            }),
        }
    },
    computed: {
        ...mapGetters([ 'currency']),
        saddress() {
            return JSON.parse(this.order.shipping_address)
        },
        baddress() {
            return JSON.parse(this.order.billing_address)
        },
        odate() {
            return moment(this.order.created_at).format('Do MMM YYYY')
        },
        pdate() {
            return moment(this.order.created_at).format('Do MMM YYYY')
        },
        invNov() {
            let inv = this.order.id.toString();
            return 'IN'+inv.padStart(6, '0')
        }
    },
    methods: {
        ...mapActions(['getWishlistItems', 'getCartItems']),
        paymode(dev) {
            return dev.replace(/[^a-zA-Z0-9]/g, ' '); 
        },
        encode(rid) {
            return window.btoa(rid)
        },
    },
    mounted() {
        this.getWishlistItems();
        this.getCartItems();
    }
    
};
</script>
<template>
    <Head title="User Orders" />

    <AuthenticatedLayout>
         <div class="ec-vendor-dashboard-card">
            <div class="ec-vendor-card-header">
                <h5>Order #{{order.id}}</h5>
                <!-- <div class="ec-header-btn">
                    <Link class="btn btn-lg btn-primary" :href="route('orders')"><i class="bi bi-arrow-left"></i> Back To List</Link>
                    <Link :href="route('track-order', encode(order.invoice_no))" type="button" class="btn btn-lg btn-primary" v-if="order.shipping_status == 'ordered' || order.shipping_status == 'processing' || order.shipping_status == 'picking' || order.shipping_status == 'packed' || order.shipping_status == 'shipped' ">Track Order</Link>
                    <a v-if="order.shipping_status != 'delivered' && order.shipping_status != 'cancelled'" class="btn btn-warning" @click="cancelOrder()" href="javascript:void(0)"><i class="bi bi-x-circle"></i> Cancel this order</a>
                    <a href="javascript:void(0)" v-if="order.return_request == 'return'" class="btn btn-success"><i class="bi bi-bag-x"></i> Return this order</a>
                    <a v-else-if="order_return && order.shipping_status == 'delivered'" class="btn btn-primary" @click="orderReturn()">Return this order</a>
                    <a class="btn btn-success" v-if="order.invoice_token" :href="$invoice_token+order.invoice_token"><i class="bi bi-box-arrow-down"></i> Invoice</a>
                </div> -->
            </div>
            <div class="ec-vendor-card-body">
                <div class="ec-vendor-card-table">
                    <div class="row mx-0 pb-2 border-bottom mb-2">
                        <div class="col-12 col-md-4 mb-4">
                            <h6 class="mb-3">Delivery Address</h6>
                            <div class="ec-vendor-detail-block ec-vendor-block-address border" v-if="saddress">
                                <h6>{{ (saddress.name)?saddress.name:'--' }}</h6>
                                <ul>
                                    <li>{{ (saddress.address)?saddress.address:'--' }}</li>
                                    <li><b>Landmark - </b> {{ (saddress.landmark)?saddress.landmark:'--' }}</li>
                                    <li><b>Mob No. </b> {{ (saddress.phone)?saddress.phone:'--' }}</li>
                                    <li><b>City </b> {{ (saddress.city)?saddress.city:'--' }}</li>
                                    <li><b>Zip Code </b> {{ (saddress.postal_code)?saddress.postal_code:'--' }}</li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-12 col-md-4 mb-4">
                            <h6 class="mb-3">Billing Address</h6>
                            <div class="ec-vendor-detail-block ec-vendor-block-address border" v-if="baddress">
                                <h6>{{ (baddress.name)?baddress.name:'--' }}</h6>
                                <ul>
                                    <li>{{ (baddress.address)?baddress.address:'--' }}</li>
                                    <li><b>Landmark - </b> {{ (baddress.landmark)?baddress.landmark:'--' }}</li>
                                    <li><b>Mob No. </b> {{ (baddress.phone)?baddress.phone:'--' }}</li>
                                    <li><b>City </b> {{ (baddress.city)?baddress.city:'--' }}</li>
                                    <li><b>Zip Code </b> {{ (baddress.postal_code)?baddress.postal_code:'--' }}</li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12 mb-4">
                            <h6 class="mb-3">Payment Details</h6>
                            <div class="ec-vendor-detail-block ec-vendor-block-address border">
                                <h6>Payment Status - <span class="text-uppercase text-success"> {{ order.payment_status }} </span> </h6>
                                <ul>
                                    <li><b>Order Date - </b> {{ odate }}</li>
                                    <li><b>Payment Type - </b> {{ order.payment_type }}</li>
                                    <!-- <li><b>Shipping Status - </b> {{ order.sinpping_status_status }}</li> -->
                                    
                                    <li v-if="order.shipping_status == 'delivered'">
                                        <b>Invoice No - </b>{{ order.invoice_no }}<br>
                                        <b>Order Status - </b> <span style="text-transform: uppercase;" :class="[(order_css[order.shipping_status])]">{{ order.shipping_status }}</span>
                                    </li>
                                    <li v-else>
                                        <b>Order Status - </b> <span style="text-transform: uppercase;" :class="[(order_css[order.shipping_status])]">{{ order.shipping_status }}</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <p class="delivery-time mt-2"><b>Preferred Delivery Time</b> : {{ order.delivery_time }}</p>
                   <table class="table ec-table">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Product</th>
                                <th scope="col">Rating</th>
                                <th scope="col">Comment</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(op, opk) in order.sell_lines" :key="op.id">
                                <th scope="row"><span>{{opk+1}}</span></th>
                                <td>
                                    <span><label class="m-0">{{ (op.product)?op.product.name:'-' }}</label></span>
                                </td>
                                <td>
                                    <div class="ec-t-review-rating">                                                                
                                        <i v-for="f in 5" :key="'review-'+f" class="ecicon" :class="[(f <= review_star) ? 'eci-star fill' : 'eci-star-o']" @click="review_star = f"></i>
                                    </div>
                                </td>
                                <td><textarea name="your-commemt" placeholder="Enter Your Comment" v-model="form.content"></textarea></td>
                            </tr>
                        </tbody>
                    </table>
                    
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
