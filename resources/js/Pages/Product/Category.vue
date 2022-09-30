<script>
import GuestLayout from "@/Layouts/Main.vue";
import { mapGetters } from "vuex";
import SingleProduct from "./SingleGrid.vue";
import moment from 'moment';

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
            selectedPrices : [],
            loader : false,
            searchCategories:[],
            products : [],
            weights : [],
            price_range : '',
            price_filter:{ 
                0 : 'Rs. 1 to RS. 100', 
                1 : 'Rs. 101 to Rs. 200',  
                2 : 'Rs. 201 to Rs. 300', 
                3 : 'Rs. 301 to Rs. 400', 
                4 : 'Rs. 401 to Rs. 500', 
                5 : 'Rs. 501 to Rs. 600', 
                6 : 'Rs. 601 to Rs. 700',
                7 : 'Rs. 701 to Rs. 800',
                8 : 'Rs. 801 to Rs. 900',
                9 : 'Rs. 901 to Rs. 1000',
            }
        };
    },
    props: {
        // products: [Array, Object],
        // weights : [Array, Object],
        categories : [Array, Object]
    },
    computed: {
        ...mapGetters(["navItems"]),
        select_category() {
            return this.$page.props.category;
        },
        sorted_products(){
            if(this.products.length > 0){
                this.newProducts = this.products
                this.newProducts.forEach((ele) => {
                    let day = moment().date()
                    let month = moment().month() + 1
                    let year = moment().year()
                    let today = year+'-'+month+'-'+day
                    let discountEnabel = false
                    if(moment(today).isSame(ele.discount_start_date)){
                        discountEnabel = true;
                    } 
                    if(moment(today).isSame(ele.discount_end_date)){
                        discountEnabel = true;
                    }                    
                    if(moment(today).isAfter(ele.discount_start_date) && moment(today).isBefore(ele.discount_end_date)){
                        discountEnabel = true;
                    }
                    ele.latest = ele.price
                    if(discountEnabel){
                        let up = ele.price
                        let dt = ele.discount_type
                        let pd = ele.percent_discount
                        let fd = ele.flat_discount
                        let fp = 0
                        if(dt == 'percent') {
                            ele.latest = up*((100-pd)/100);
                        }
                        if(dt == 'amount') {
                            ele.latest = up - fd;
                        }
                    }
                })
                if(this.selectedWeights.length > 0){
                    let newProducts = this.newProducts.filter((ele) => {
                        if(this.selectedWeights.indexOf(parseFloat(ele.weight)) >= 0){
                            return ele
                        }
                    })
                    this.newProducts = newProducts;
                }
                if(this.selectedPrices.length > 0){
                    let newProducts = this.newProducts.filter((ele) => {
                        if(this.selectedPrices.indexOf(parseInt(ele.latest/100)) >= 0){
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
                return pro;
            }else{
                return [];
            }
            
        },
        priceRange(){
            return this.price_range
        }
    },
    methods: {
        getSelectedCategroyProduct(){
            if(this.searchCategories.length > 1)
            {
                this.loader = true;
            }
            axios.post('/api/get-selected-category-product', {categories : this.searchCategories}).then((response) => {
                this.products = response.data.products
                this.weights = response.data.weights
                this.price_range = response.data.price_range
                this.loader = false;
            })
        }
    },
    created(){
        this.searchCategories[0] = this.select_category.id
        this.getSelectedCategroyProduct()
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
                        <div class="ec-pro-list-top d-flex">
                            <div class="col-md-6 ec-grid-list">
                                <div class="ec-gl-btn">
                                    {{ products.length }} items found
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
                            <div class="shop-pro-inner text-center" v-if="loader">
                                <img src="/images/loader.gif" />
                            </div>
                            <div class="shop-pro-inner" :class="{'list-view': !gridView}" v-else>
                                <div class="row">
                                    <div class="col-lg-3 col-md-4 col-sm-6 col-xs-6 ec-product-content" v-for="product in sorted_products" :key="product.id">
                                        <single-product :product="product"></single-product>
                                    </div>
                                </div>
                            </div>
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
                                                <div class="ec-sidebar-block-item" v-for="(category, index) in categories" :key="'category-' + index">
                                                    <input readonly type="checkbox" v-model="searchCategories" :value="category.id" :id="'category_name-'+category.id" @change="getSelectedCategroyProduct()"/> 
                                                    <a href="#"><label  :for="'category_name-'+category.id">{{ category.name }}</label></a>
                                                    <span class="checked"></span>
                                                    <div class="ec-sidebar-block-item sub-category" v-if="category.children_categories" v-for="(subcategory, ind) in category.children_categories" :key="'sub-category-' + ind">
                                                        <input readonly type="checkbox" v-model="searchCategories" :value="subcategory.id" :id="'sub_category_name-'+subcategory.id" @change="getSelectedCategroyProduct()"/> 
                                                        <a href="#"><label  :for="'sub_category_name-'+subcategory.id">{{ subcategory.name }}</label></a>
                                                        <span class="checked"></span>
                                                    </div>
                                                </div>
                                            </li>
                                            
                                        </ul>
                                    </div>
                                </div>
                                <div class="ec-sidebar-block">
                                    <div class="ec-sb-title">
                                        <h3 class="ec-sidebar-title">Price</h3>
                                    </div>
                                    <div class="ec-sb-block-content">
                                        <ul class="weight-filter">
                                            <li v-for="(price, key, index) in price_filter" :key="'price-'+index" >
                                                <div class="ec-sidebar-block-item" v-if="parseInt(key) <= priceRange">
                                                    <input type="checkbox" v-model="selectedPrices" :value="parseInt(key)" :id="'price-' + index" />
                                                    <label class="ml-5 mb-0" href="#" :for="'price-' + index" >
                                                        {{ price }}
                                                    </label>
                                                    <span class="checked"></span>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="ec-sidebar-block">
                                    <div class="ec-sb-title">
                                        <h3 class="ec-sidebar-title">Weight</h3>
                                    </div>
                                    <div class="ec-sb-block-content">
                                        <ul class="weight-filter">
                                            <li v-for="(weight, index) in weights" :key="'weight-'+index">
                                                <div class="ec-sidebar-block-item">
                                                    <input type="checkbox" v-model="selectedWeights" :value="weight" :id="'weight-' + index" />
                                                    <label class="ml-5 mb-0" href="#" :for="'weight-' + index" >
                                                        {{ (weight < 1) ? parseFloat(weight)*1000 + 'gm' : weight + 'KG' }}
                                                    </label>
                                                    <span class="checked"></span>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </GuestLayout>
</template>

<style>
    label{
        margin-bottom:0px;
    }
    .weight-filter li{
        padding-bottom:0px !important ;
    }
    .sub-category{
        margin-left: 20px;
    }
    .ec-product-content{
        width:25%;
    }
</style>