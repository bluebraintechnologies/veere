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
                    <h5>Order #{{order.invoice_no}}</h5>
                    <div class="ec-header-btn">
                        <Link class="btn btn-lg btn-primary" :href="route('order_detail', encode(order.invoice_no))">Back To Order Detail</Link>
                        <!-- <Link :href="route('track-order', encode(order.invoice_no))" type="button" class="btn btn-lg btn-primary" >Track Order</Link> -->
                    </div>
                </div>
                <div class="ec-vendor-card-body">
                    <div class="ec-trackorder-content col-md-12">
                        <div class="ec-trackorder-inner">
                            <div class="ec-trackorder-top">
                                <h2 class="ec-order-id">order #{{order.invoice_no}}</h2>
                                <div class="ec-order-detail">
                                    <div>Expected arrival 14-02-2021-2022</div>
                                    <!-- <div>Grasshoppers <span>v534hb</span></div> -->
                                </div>
                            </div>
                            <div class="ec-trackorder-bottom">
                                <div class="ec-progress-track">
                                    <ul id="ec-progressbar">
                                        <li class="step0 active">
                                            <span class="ec-track-icon"> 
                                                <img src="/assets/images/icons/track_1.png" alt="track_order">
                                            </span>
                                            <span class="ec-progressbar-track"></span>
                                            <span class="ec-track-title">order<br>processed</span>
                                        </li>
                                        <li class="step0 active">
                                            <span class="ec-track-icon"> 
                                                <img src="/assets/images/icons/track_2.png" alt="track_order">
                                            </span>
                                            <span class="ec-progressbar-track"></span>
                                            <span class="ec-track-title">order<br>designing</span>
                                        </li>
                                        <li class="step0 active">
                                            <span class="ec-track-icon"> 
                                                <img src="/assets/images/icons/track_3.png" alt="track_order">
                                            </span>
                                            <span class="ec-progressbar-track"></span>
                                            <span class="ec-track-title">order<br>shipped</span>
                                        </li>
                                        <li class="step0">
                                            <span class="ec-track-icon"> 
                                                <img src="/assets/images/icons/track_4.png" alt="track_order">
                                            </span>
                                            <span class="ec-progressbar-track"></span>
                                            <span class="ec-track-title">order <br>enroute</span></li>
                                        <li class="step0">
                                            <span class="ec-track-icon"> 
                                                <img src="/assets/images/icons/track_5.png" alt="track_order">
                                            </span>
                                            <span class="ec-progressbar-track"></span>
                                            <span class="ec-track-title">order<br>arrived</span>
                                        </li>

                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    </template>
    