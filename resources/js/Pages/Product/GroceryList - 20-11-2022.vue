<script>
import { mapGetters } from 'vuex';
import MainLayout from "@/Layouts/Main.vue";
import ProductImage from './Elemants/Image.vue';
import Price from './Elemants/Price.vue';
import WishlistButtonVue from './Elemants/WishlistButton.vue';
import CartButton from './Elemants/CartButton.vue';
import moment from 'moment';
import DiscountPrice from './Elemants/DiscountPrice.vue';
import { useForm } from '@inertiajs/inertia-vue3';
import { Inertia } from '@inertiajs/inertia'
import InnerImageZoom from 'vue-inner-image-zoom';
import 'vue-inner-image-zoom/lib/vue-inner-image-zoom.css';
import {Countdown} from 'vue3-flip-countdown';
import SingleGrid from '@/Pages/Product/SingleGrid.vue'

export default {
    
    data() {
        return {
            ec_qtybtn:1
        }
    },
    components: {
        MainLayout,
        CartButton,
        WishlistButtonVue,
        ProductImage,
        Price,
        DiscountPrice,
        'inner-image-zoom': InnerImageZoom,
        Countdown,
        SingleGrid
    },
    props: {
        isUserLogged: Number,
        featured_products: Object,
        active_category: String,
    },
    data(){
        return {
            
            
        }
    },
    computed:{ 
        ...mapGetters(['currency', 'dealsProduct', 'dealsProductDiscount']),
        
    },
    setup() {
        return {};
    },
    methods: {
        
    }
};
</script>
<style>
</style>
<template>
    <Head title="Grocery List" />

    <MainLayout>
        <div class="sticky-header-next-sec  ec-breadcrumb section-space-mb">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="row ec_breadcrumb_inner">
                            <div class="col-md-6 col-sm-12">
                                <h2 class="ec-breadcrumb-title">Grocery List</h2>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <!-- ec-breadcrumb-list start -->
                                <ul class="ec-breadcrumb-list">
                                    <li class="ec-breadcrumb-item">
                                        <Link :href="route('home')">Home</Link>
                                    </li>
                                    <li class="ec-breadcrumb-item active">Grocery List</li>
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
                    <!-- <div class="col-md-12 text-center">
                        <div class="section-title">
                            <h2 class="ec-bg-title">Classic</h2>
                            <h2 class="ec-title">Classic</h2>
                            <p class="sub-title mb-3">Customer service management</p>
                        </div>
                    </div> -->
                    <div class="ec-faq-container-1">
                        <div class="ec-faq-wrapper">
                            <!-- <h2 class="ec-faq-heading">What is ekka services?</h2> -->
                            <div id="ec-faq-3">
                                <div class="col-sm-12 ec-faq-block" v-for="(featured_product, index) in featured_products" :key="'featured-product-' + index">
                                    <h4 class="ec-faq-title" @click="active_category = featured_product.slug">{{ featured_product.name }}</h4>
                                    <div class="ec-faq-content" v-show="active_category == featured_product.slug" style="display: block !important;">
                                        <div class="row">
                                            <div class="ec-product-content" v-if="featured_product.products.length == 0">No product available</div>
                                            <div v-else class="ec-product-content" v-for="(pp, pk) in featured_product.products" :key="'fpp'+pk">
                                                <single-grid :product="pp"></single-grid>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- <div class="col-sm-12 ec-faq-block">
                                    <h4 class="ec-faq-title">How to buy many products at a time?</h4>
                                    <div class="ec-faq-content">
                                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem
                                            Ipsum has been the industry's standard dummy text ever since the 1500s, when an
                                            unknown printer took a galley of type and scrambled it to make a type specimen
                                            book. It has survived not only five centuries, but also the leap into electronic
                                            typesetting, remaining essentially unchanged. </p>
                                    </div>
                                </div>
                                <div class="col-sm-12 ec-faq-block">
                                    <h4 class="ec-faq-title">Refund policy for customer</h4>
                                    <div class="ec-faq-content">
                                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem
                                            Ipsum has been the industry's standard dummy text ever since the 1500s, when an
                                            unknown printer took a galley of type and scrambled it to make a type specimen
                                            book. It has survived not only five centuries, but also the leap into electronic
                                            typesetting, remaining essentially unchanged. </p>
                                    </div>
                                </div>
                                <div class="col-sm-12 ec-faq-block">
                                    <h4 class="ec-faq-title">Exchange policy for customer</h4>
                                    <div class="ec-faq-content">
                                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem
                                            Ipsum has been the industry's standard dummy text ever since the 1500s, when an
                                            unknown printer took a galley of type and scrambled it to make a type specimen
                                            book. It has survived not only five centuries, but also the leap into electronic
                                            typesetting, remaining essentially unchanged. </p>
                                    </div>
                                </div>
                                <div class="col-sm-12 ec-faq-block">
                                    <h4 class="ec-faq-title">Give a way products available</h4>
                                    <div class="ec-faq-content">
                                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem
                                            Ipsum has been the industry's standard dummy text ever since the 1500s, when an
                                            unknown printer took a galley of type and scrambled it to make a type specimen
                                            book. It has survived not only five centuries, but also the leap into electronic
                                            typesetting, remaining essentially unchanged. </p>
                                    </div>
                                </div> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </MainLayout>
</template>