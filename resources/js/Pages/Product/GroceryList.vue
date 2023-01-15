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
import SingleGrid from '@/Pages/Product/SingleGrid.vue';
import SingleThumb from '@/Pages/Product/SingleThumb.vue';

export default {
    
    components: {
        MainLayout,
        CartButton,
        WishlistButtonVue,
        ProductImage,
        Price,
        DiscountPrice,
        'inner-image-zoom': InnerImageZoom,
        Countdown,
        SingleGrid,
        SingleThumb,
    },
    props: {
        isUserLogged: Number,
        // featured_products: Object|Array,
    },
    data(){
        return {
            featured_products : [],
            set_category:'',       
            current_active_category:'',  
            category : [],   
        }
    },
    computed:{ 
        ...mapGetters(['currency', 'dealsProduct', 'dealsProductDiscount']),
        
    },
    setup() {
        return {};
    },
    methods: {
        set_current_category(category){
            this.current_active_category = category.category_id
            if(this.category.indexOf(category.category_id) == -1)
            {
                this.category.push(category.category_id)
            }else{
                this.category.splice(this.category.indexOf(category.category_id), 1)
            }
        },
        getGorceryListProducts(){
            let location = localStorage.getItem("location")
            axios.get('/api/get-grocery-list-products?location=' + location).then((response) => {
                this.featured_products = response.data.featured_products
                this.current_active_category = 1
                if(this.featured_products.length > 0 && this.featured_products[0].category_id){
                    this.category.push(this.featured_products[0].category_id)
                }
            })
        }
    },
    created(){
        this.getGorceryListProducts()
    }
};
</script>
<style>
    .adisplay{
        display:block;
    }
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
                    <div class="ec-faq-container-1">
                        <div class="ec-faq-wrapper"  style="max-width:100%;">
                            <div id="ec-faq-3">
                                <div class="col-sm-12 ec-faq-block" v-for="(featured_product, index) in featured_products" :key="'featured-product-' + index">
                                    <a class="" :href="'/grocery-list#'+'accordion-' + featured_product.category_id" :id="'accordion-' + featured_product.category_id">
                                        <h4 class="ec-faq-title" @click="set_current_category(featured_product)">
                                            {{ featured_product.name }}
                                        </h4>
                                    </a>
                                    <div class="ec-faq-content" v-show="category.indexOf(featured_product.category_id) > -1" :class="[(category.indexOf(featured_product.category_id) > -1) ? 'adisplay' : '']">
                                        <div class="row" >
                                            <div class="ec-product-content" v-if="featured_product.products.length == 0">No product available</div>
                                            <div class="ec-product-content grocery-list" v-else v-for="(pp, pk) in featured_product.products" :key="'fpp'+pk">
                                                <!-- <single-grid :product="pp"></single-grid> -->
                                                <single-thumb :product="pp"></single-thumb>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </MainLayout>
</template>