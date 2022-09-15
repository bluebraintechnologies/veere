<script>
import AuthenticatedLayout from "@/Layouts/Authenticated.vue";
import { mapActions } from 'vuex';
import moment from 'moment';

export default {
    name: "Order Details",
    props: {
       order:Object
    },
    components: {
        AuthenticatedLayout,
    },
    computed: {
        saddress() {
            return JSON.parse(this.order.shipping_address)
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
        }
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
                <h5>Order #{{order.code}}</h5>
                <div class="ec-header-btn">
                    <Link class="btn btn-lg btn-primary" :href="route('orders')">Back To List</Link>
                </div>
            </div>
            <div class="ec-vendor-card-body">
                <div class="ec-vendor-card-table">
                    <div class="row mx-0 pb-2 border-bottom mb-2">
                        <div class="col-12 col-md-6 mb-4">
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
                        <div class="col-md-6 col-sm-12 mb-4">
                            <h6 class="mb-3">Payment Details</h6>
                            <div class="ec-vendor-detail-block ec-vendor-block-address border">
                                <h6>Payment Status - <span class="text-uppercase text-success"> {{ order.payment_status }} </span> </h6>
                                <ul>
                                    <li><b>Order Date - </b> {{ odate }}</li>
                                    <li><b>Payment Type - </b> {{ order.payment_type }}</li>
                                    <li><b>Shipping Status - </b> {{ order.sinpping_status_status }}</li>
                                    <li><b>Invoice No - </b> {{ order.invoice_no }}</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                   <table class="table ec-table">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Product</th>
                                <th scope="col">Qty</th>
                                <th scope="col">Unit Price</th>
                                <th scope="col">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(op, opk) in order.sell_lines" :key="op.id">
                                <th scope="row"><span>{{opk+1}}</span></th>
                                <td>
                                    <span><label class="m-0">{{ (op.product)?op.product.name:'-' }}</label></span>
                                </td>
                                <td><span> {{ op.quantity }}</span></td>
                                <td><span>Rs. {{ Number(op.unit_price).toFixed(2) }}</span></td>
                                <td><span>Rs. {{ Number(op.unit_price*op.quantity).toFixed(2) }}</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
