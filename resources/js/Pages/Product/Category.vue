<script>
import GuestLayout from "@/Layouts/Main.vue";
import { mapGetters } from "vuex";
import SingleProduct from "./SingleGrid.vue";
export default {
    name: "Category",
    components: {
        GuestLayout,
        SingleProduct,
    },
    data() {
        return {
            gridView: true,
            order_by:1,
            newProducts : [],
            selectedWeights : [],
            loader : false
        };
    },
    props: {
        products: [Array, Object],
        weights : [Array, Object],
    },
    computed: {
        ...mapGetters(["navItems"]),
        select_category() {
            return this.$page.props.category;
        },
        sorted_products(){
            this.loader = true;
            this.newProducts = this.products
            if(this.selectedWeights.length > 0){
                let newProducts = this.newProducts.filter((ele) => {
                    if(this.selectedWeights.indexOf(parseFloat(ele.weight)) >= 0){
                        return ele
                    }
                })
                this.newProducts = newProducts;
            }
            var pro = [];
            if(Number(this.order_by) == 1){
                pro = this.newProducts.sort((a,b) => Number(a.new_tag) < Number(b.new_tag) ? 1 : -1)
            } else if(Number(this.order_by) == 2){
                pro = this.newProducts.sort((a,b) => a.name > b.name ? 1 : -1)
            } else if(Number(this.order_by) == 3){
                pro = this.newProducts.sort((a,b) => a.name < b.name ? 1 : -1)
            } else if(Number(this.order_by) == 4){
                pro = this.newProducts.sort((a,b) => parseFloat(a.price) > parseFloat(b.price) ? 1 : -1)
            } else if(Number(this.order_by) == 5){
                pro = this.newProducts.sort((a,b) => parseFloat(a.price) < parseFloat(b.price) ? 1 : -1)
            }
            this.loader = false;
            return pro;
        }
    },
    methods: {
    },
    setup() {
        return {};
    },
};
</script>

<template>
    <Head title="Category Page" />

    <GuestLayout>
        <div class="sticky-header-next-sec  ec-breadcrumb section-space-mb">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="row ec_breadcrumb_inner">
                            <div class="col-md-6 col-sm-12">
                                <h2 class="ec-breadcrumb-title">Category: {{ (select_category)?select_category.name:'-' }}</h2>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <ul class="ec-breadcrumb-list">
                                    <li class="ec-breadcrumb-item">
                                        <Link :href="route('home')">Home</Link>
                                    </li>
                                     <li class="ec-breadcrumb-item">
                                        Category
                                    </li>
                                    <li class="ec-breadcrumb-item active">{{ (select_category)?select_category.name:'-' }}</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <section class="ec-page-content section-space-p">
            <div class="container">
                <div class="row">
                    <div class="ec-shop-rightside col-lg-9 col-md-12 order-lg-last order-md-first margin-b-30">
                        <!-- Shop Top Start -->
                        <div class="ec-pro-list-top d-flex">
                            <div class="col-md-6 ec-grid-list">
                                <div class="ec-gl-btn">
                                    
                                </div>
                            </div>
                            <div class="col-md-6 ec-sort-select">
                                <span class="sort-by">Sort by</span>
                                <div class="ec-select-inner">
                                    <select name="ec-select" id="ec-select" v-model="order_by">                                        
                                        <option value="1" selected>Newest First</option>
                                        <option value="2">Name, A to Z</option>
                                        <option value="3">Name, Z to A</option>
                                        <option value="4">Price, low to high</option>
                                        <option value="5">Price, high to low</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="shop-pro-content">
                            <div class="shop-pro-inner" v-if="loader">
                                <img src="/images/loader.gif" />
                            </div>
                            <div class="shop-pro-inner" :class="{'list-view': !gridView}" v-else>
                                <div class="row">
                                    <div class="col-lg-3 col-md-4 col-sm-6 col-xs-6 ec-product-content" v-for="product in sorted_products" :key="product.id">
                                        <single-product :product="product"></single-product>
                                    </div>
                                </div>
                            </div>
                            <!-- <div class="ec-pro-pagination">
                                <span>Showing 1-12 of 21 item(s)</span>
                                <ul class="ec-pro-pagination-inner">
                                    <li><a class="active" href="#">1</a></li>
                                    <li><a href="#">2</a></li>
                                    <li><a href="#">3</a></li>
                                    <li><a href="#">4</a></li>
                                    <li><a href="#">5</a></li>
                                    <li><a class="next" href="#">Next <i class="ecicon eci-angle-right"></i></a></li>
                                </ul>
                            </div> -->
                        </div>
                    </div>
                    <div class="ec-shop-leftside col-lg-3 col-md-12 order-lg-first order-md-last">
                        <div id="shop_sidebar">
                            <div class="ec-sidebar-heading">
                                <h1>Filter Products By</h1>
                            </div>
                            <div class="ec-sidebar-wrap">
                                <div class="ec-sidebar-block">
                                    <div class="ec-sb-title">
                                        <h3 class="ec-sidebar-title">Category</h3>
                                    </div>
                                    <div class="ec-sb-block-content">
                                        <ul>
                                            <li>
                                                <div class="ec-sidebar-block-item">
                                                    <input readonly type="checkbox" checked /> <a href="#">{{ (select_category)?select_category.name:'-' }}</a><span
                                                        class="checked"></span>
                                                </div>
                                            </li>
                                            
                                            <!-- <li>
                                                <div class="ec-sidebar-block-item ec-more-toggle">
                                                    <span class="checked"></span><span id="ec-more-toggle">More
                                                        Categories</span>
                                                </div>
                                            </li> -->

                                        </ul>
                                    </div>
                                </div>
                                <div class="ec-sidebar-block">
                                    <div class="ec-sb-title">
                                        <h3 class="ec-sidebar-title">Weight</h3>
                                    </div>
                                    <div class="ec-sb-block-content">
                                        <ul>
                                            <li v-for="(weight, index) in weights" :key="index">
                                                <div class="ec-sidebar-block-item">
                                                    <input type="checkbox" v-model="selectedWeights" :value="weight" :id="'weight-' + index" />
                                                    <label class="ml-5 mb-0" href="#" :for="'weight-' + index" >
                                                        {{ (weight < 1) ? parseFloat(weight)*1000 + 'gm' : weight + 'KG' }}
                                                    </label>
                                                    <span class="checked"></span>
                                                </div>
                                            </li>
                                            <!-- <li>
                                                <div class="ec-sidebar-block-item">
                                                    <input type="checkbox" value="" /><a href="#">M</a><span
                                                        class="checked"></span>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="ec-sidebar-block-item">
                                                    <input type="checkbox" value="" /> <a href="#">L</a><span
                                                        class="checked"></span>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="ec-sidebar-block-item">
                                                    <input type="checkbox" value="" /><a href="#">XL</a><span
                                                        class="checked"></span>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="ec-sidebar-block-item">
                                                    <input type="checkbox" value="" /><a href="#">XXL</a><span
                                                        class="checked"></span>
                                                </div>
                                            </li> -->
                                        </ul>
                                    </div>
                                </div>
                                <!-- Sidebar Color item -->
                                <!-- <div class="ec-sidebar-block ec-sidebar-block-clr">
                                    <div class="ec-sb-title">
                                        <h3 class="ec-sidebar-title">Color</h3>
                                    </div>
                                    <div class="ec-sb-block-content">
                                        <ul>
                                            <li>
                                                <div class="ec-sidebar-block-item"><span
                                                        style="background-color:#c4d6f9;"></span></div>
                                            </li>
                                            <li>
                                                <div class="ec-sidebar-block-item"><span
                                                        style="background-color:#ff748b;"></span></div>
                                            </li>
                                            <li>
                                                <div class="ec-sidebar-block-item"><span
                                                        style="background-color:#000000;"></span></div>
                                            </li>
                                            <li class="active">
                                                <div class="ec-sidebar-block-item"><span
                                                        style="background-color:#2bff4a;"></span></div>
                                            </li>
                                            <li>
                                                <div class="ec-sidebar-block-item"><span
                                                        style="background-color:#ff7c5e;"></span></div>
                                            </li>
                                            <li>
                                                <div class="ec-sidebar-block-item"><span
                                                        style="background-color:#f155ff;"></span></div>
                                            </li>
                                            <li>
                                                <div class="ec-sidebar-block-item"><span
                                                        style="background-color:#ffef00;"></span></div>
                                            </li>
                                            <li>
                                                <div class="ec-sidebar-block-item"><span
                                                        style="background-color:#c89fff;"></span></div>
                                            </li>
                                            <li>
                                                <div class="ec-sidebar-block-item"><span
                                                        style="background-color:#7bfffa;"></span></div>
                                            </li>
                                            <li>
                                                <div class="ec-sidebar-block-item"><span
                                                        style="background-color:#56ffc1;"></span></div>
                                            </li>
                                        </ul>
                                    </div>
                                </div> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </GuestLayout>
</template>
