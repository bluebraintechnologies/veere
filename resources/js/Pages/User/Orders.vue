<script>
import AuthenticatedLayout from "@/Layouts/Authenticated.vue";
import { mapActions } from 'vuex';

export default {
    name: "Dashboard",
    props: {
       orders:Array
    },
    components: {
        AuthenticatedLayout,
        
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
                <h5>Your Orders</h5>
                <div class="ec-header-btn">
                    <Link class="btn btn-lg btn-primary" :href="route('home')">Shop Now</Link>
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
                                <th scope="col">Payment Mode</th>
                                <th scope="col">Status</th>
                                <th scope="col" width="160px">Action</th>
                            </tr>
                        </thead>
                        <tbody>
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

                                <td>
                                    <!-- <Link class="btn btn-success mr-1 mt-2" :href="route('home')" v-if="order.delivery_status == 'confirmed' || order.delivery_status == 'on_delivery'">
                                        <i class="ecicon eci-hourglass-o"></i>
                                    </Link> -->
                                    <Link class="btn btn-primary mr-1 mt-2" :href="route('order_detail', encode(order.invoice_no))">
                                         <i class="ecicon eci-eye"></i> View
                                    </Link>
                                    <!-- <Link class="btn btn-danger mt-2" :href="route('home')" v-if="order.delivery_status == 'delivered'">
                                         <i class="ecicon eci-mail-reply"></i>
                                    </Link> -->
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
