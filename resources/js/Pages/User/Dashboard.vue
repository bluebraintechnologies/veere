<script>
import AuthenticatedLayout from "@/Layouts/Authenticated.vue";
import { mapActions } from 'vuex';

export default {
    name: "Dashboard",
    props: {
        user:Object,
       orders:Object|Array,
       synop:Object|Array
    },
    components: {
        AuthenticatedLayout,
        
    },
    methods: {
        ...mapActions(['getWishlistItems', 'getCartItems']),
        paymode(dev) {
            return dev.replace(/[^a-zA-Z0-9]/g, ' '); 
        },
        goBack() {
            // if (window.history.state.back === '/reset-password') {
            //     this.$router.push({ name: 'Home' })
            // } else {
            //     this.$router.go(-1)
            // }
        }
    },
    mounted() {
        this.getWishlistItems();
        this.getCartItems();
        this.goBack()
    }
    
};
</script>
<template>
    <Head title="User Dashboard" />

    <AuthenticatedLayout>
        <div class="row">
            <div class="col-lg-3 col-md-6">
                <div class="ec-vendor-dashboard-sort-card color-blue">
                    <h5>Total Orders</h5>
                    <h3>{{ synop.orders }}</h3>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="ec-vendor-dashboard-sort-card color-pink">
                    <h5>Total Saving</h5>
                    <h3><small>Rs </small> {{ synop.saving }}</h3>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="ec-vendor-dashboard-sort-card color-green">
                    <h5>Offers Claimed</h5>
                    <h3>{{ synop.offers }}</h3>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="ec-vendor-dashboard-sort-card color-orange">
                    <h5>Wishlist</h5>
                    <h3>{{ synop.wishlists }}<span> products</span></h3>
                </div>
            </div>
        </div>
       <div class="ec-vendor-dashboard-card space-bottom-30">
            <div class="ec-vendor-card-header">
                <h5>Upcoming Orders</h5>
                <div class="ec-header-btn">
                    <Link class="btn btn-lg btn-primary" :href="route('orders')">View All</Link>
                </div>
            </div>
            <div class="ec-vendor-card-body">
                <div class="ec-vendor-card-table">
                    <table class="table ec-table">
                        <thead>
                            <tr>
                                <th scope="col">Order No</th>
                                <th scope="col">Products</th>
                                <th scope="col">Total</th>
                                <th scope="col">Payment Status</th>
                                <th scope="col">Status</th>
                            </tr>
                        </thead>
                        <tbody v-if="orders.length">
                            <tr v-for="order in orders" :key="'order'+order.id">
                                <th scope="row"><span>{{order.invoice_no}}</span></th>
                                <td>
                                    <span v-if="order.sell_lines.length == 1">
                                        <label class="m-0">{{ (order.sell_lines[0].product)?order.sell_lines[0].product.name:'Product' }}</label> 
                                    </span>
                                    <span v-if="order.sell_lines.length > 1">
                                        <label class="m-0">{{ (order.sell_lines[0].product)?order.sell_lines[0].product.name:'Product' }}</label> + {{ order.sell_lines.length - 1 }}
                                    </span>
                                </td>
                                <td><span>Rs. {{ order.final_total }}</span></td>
                                <td><span class="text-capitalize">{{ paymode(order.payment_status) }}</span></td>
                                <td><span class="text-capitalize">{{ order.shipping_status }}</span></td>
                            </tr>
                        </tbody>
                        <tbody v-else>
                            <tr>
                                <td colspan="5">
                                    <p>Your orderbox is empty</p>
                                    <p> <a class="text-primary" :href="route('home')">Click Here </a> to start filling your cart. </p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
