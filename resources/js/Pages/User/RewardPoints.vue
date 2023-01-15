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
        }
        
    },
    mounted() {
    }
    
};
</script>
<template>
    <Head title="User Orders" />

    <AuthenticatedLayout>
        <div class="row">
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
                    <h5>Total Reward Points on the way</h5>
                    <h3>{{ reward_points_on_the_way }}</h3>
                </div>
            </div>
        </div>
         <div class="ec-vendor-dashboard-card">
            <div class="ec-vendor-card-header">
                <h5>Reward Points</h5>
                <div class="ec-header-btn">
                    <!-- <Link class="btn btn-lg btn-primary" :href="route('home')">Shop Now</Link> -->
                </div>
            </div>
            <div class="ec-vendor-card-body">
                <div class="ec-vendor-card-table">
                   <table class="table ec-table">
                        <thead>
                            <tr>
                                <th scope="col">Sno</th>
                                <th scope="col">Order No</th>
                                <th scope="col">Description</th>
                                <th scope="col">Points</th>
                                <th scope="col">Unused Points</th>
                                <th scope="col">Expiry</th>
                                <th scope="col">Status</th>
                                <!-- <th scope="col" width="160px">Action</th> -->
                            </tr>
                        </thead>
                        <tbody>
                                <tr v-for="(record,index) in records.data" :key="'reward-points' + index">
                                    <td>{{ index+1 }}</td>
                                    <!-- 
                                     -->
                                    <td>
                                        <Link :href="route('order_detail', encode(order_invoice[record.order_id]))" class="order-row">
                                            {{ order_invoice[Number(record.order_id)] }}
                                        </Link>
                                    </td>
                                    <td>{{ record.description }}</td>
                                    <td>{{ record.amt }}</td>
                                    <td>{{ record.remaining }}</td>
                                    <td>{{ record.expiry_date }}</td>
                                    <td>
                                        <button class="reward-point-received bg bg-success text-light p-2 " v-if="record.status == 1">Received</button>
                                        <button class="reward-point-on-th-way bg-warning text-light p-2" v-else>On the way</button>
                                    </td>
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