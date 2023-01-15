<script>
    import MainLayout from "@/Layouts/Main.vue";
    import {Countdown} from 'vue3-flip-countdown'
    import SingleGrid from '@/Pages/Product/SingleGridDeal.vue'
    import 'vue3-carousel/dist/carousel.css'
    
    export default {
        name: "Search Deal",
        components: {
            MainLayout,
            Countdown,
            SingleGrid,
        },
        props: {
            products:Object,
            submenu:String,
        },
        computed: {
        },
        data() {
            return {
            }
        },
        methods: {
        },
        mounted(){
        },
        created(){
        }
    }
    </script>
    <template>
        <MainLayout>
            <div class="sticky-header-next-sec  ec-breadcrumb section-space-mb">
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <div class="row ec_breadcrumb_inner">
                                <div class="col-md-6 col-sm-12">
                                    <h2 class="ec-breadcrumb-title">Search</h2>
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <!-- ec-breadcrumb-list start -->
                                    <ul class="ec-breadcrumb-list">
                                        <li class="ec-breadcrumb-item">
                                            <Link :href="route('home')">Home</Link>
                                        </li>                                        
                                        <li class="ec-breadcrumb-item active">{{ submenu }}</li>
                                    </ul>
                                    <!-- ec-breadcrumb-list end -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <section class="ec-page-content section-space-p">
                <div class="container">
                    <div class="row">
                        <div class="ec-pro-rightside ec-common-rightside col-lg-12 col-md-12">
                            
                            <section class="section ec-releted-product section-space-p">
                                <div class="container">
                                    <div class="row">
                                        <div class="col-md-12 text-center">
                                            <div class="section-title">
                                                <h2 class="ec-bg-title">{{ products.length }} products found</h2>
                                                <h2 class="ec-title">{{ products.length }} products found</h2>
                                                <!-- <p class="sub-title">Browse The Collection of Top Products</p> -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row margin-minus-b-30">
                                    <div class="ec-spe-product col-md-3 col-sm-6 col-12" v-for="(pp, pk) in products" :key="'fpp'+pk">
                                        <single-grid :product="pp"></single-grid>
                                    </div>
                                </div>
                            </section>
                        </div>
                    </div>
                </div>
            </section>
        
        </MainLayout>
    </template>