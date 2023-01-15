<script>
import AuthenticatedLayout from "@/Layouts/Authenticated.vue";
import { mapActions } from 'vuex';

export default {
    name: "Dashboard",
    props: {
        records:Array,
        total_reward_points:Number,
        reward_points_received:Number,
        reward_points_on_the_way:Number,
        order_invoice:Array,
    },
    components: {
        AuthenticatedLayout,
        
    },
    methods: {
        encode(rid) {
            return window.btoa(rid)
        },
        paymode(dev) {
            return dev.replace(/[^a-zA-Z0-9]/g, ' '); 
        },
        
    },
    mounted() {
    }
    
};
</script>
<template>
    <Head title="User Orders" />

    <AuthenticatedLayout>
        <!-- <div class="row">
            <div class="col-lg-4 col-md-6">
                <div class="ec-vendor-dashboard-sort-card color-blue">
                    <h5>Total Reward Points</h5>
                    <h3>{{ total_reward_points }}</h3>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="ec-vendor-dashboard-sort-card color-pink">
                    <h5>Total Reward Points Received</h5>
                    <h3>{{ reward_points_received }}</h3>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="ec-vendor-dashboard-sort-card color-green">
                    <h5>Total Reward Points on the war</h5>
                    <h3>{{ reward_points_on_the_way }}</h3>
                </div>
            </div>
        </div> -->
         <div class="ec-vendor-dashboard-card">
            <div class="ec-vendor-card-header">
                <h5>Reward Points Used</h5>
                <div class="ec-header-btn">
                    <!-- <Link class="btn btn-lg btn-primary" :href="route('home')">Shop Now</Link> -->
                </div>
            </div>
            <div class="ec-vendor-card-body">
                <div class="ec-vendor-card-table">
                   <table class="table ec-table">
                        <thead>
                            <tr>
                                <th scope="col">Reward Points</th>
                                <th scope="col">Order No</th>
                                <th scope="col">Total</th>
                                <th scope="col">Payment Mode</th>
                                <th scope="col">Status</th>
                                <!-- <th scope="col" width="160px">Action</th> -->
                            </tr>
                        </thead>
                        <tbody>
                                <tr v-for="(order,index) in records.data" :key="'reward-points' + index">
                                    <th>{{ order.reward_payment }}</th>
                                    <th scope="row"><span>{{order.invoice_no}}</span></th>                                    
                                    <td><span>Rs. {{ order.final_total }}</span></td>
                                    <td><span class="text-capitalize">{{ paymode(order.payment_status) }}</span></td>
                                    <td><span class="text-capitalize">{{ order.shipping_status }}</span></td>
                                </tr>
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
<style>
    .order-row{
        display: contents;
    }
    .order-row:hover{
        background-color: yellow;
        border:1px solid #4d4a4a;
    }
</style>