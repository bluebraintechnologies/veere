<script>
    import MainLayout from "@/Layouts/Main.vue";
    import { mapGetters, mapActions } from 'vuex'
    import Categories from "./Includes/Categories.vue";
    import MainSlider from "./Includes/MainSlider.vue";
    import Blogs from "./Includes/Blogs.vue";
    import FeaturedProducts from "./Includes/FeaturedProducts.vue";
    import ProductsGroup from "./Includes/ProductsGroup.vue";
    import Deal from "./Includes/Deal.vue";
    import Offers from "./Includes/Offers.vue";
    import Banners from "./Includes/Banners.vue";

    export default {
        components: {
            MainLayout,
            Blogs,
            FeaturedProducts,
            Categories,
            MainSlider,
            Deal,
            Offers,
            Banners,
            ProductsGroup,
        },
        data() {
            return {
                featured_category: [],
                featured_products: [],
                best_sellers: [],
                top_selling: [],
                top_rated: [],
                new_arrivals: [],
                sideCartStatus: false,
                sliders: [],
                pbanner1_detail:'',
                pbanner2_detail:'',
                home_offer:[],
            };
        },
        props: {
            // sliders: [Array, Object],
            categories: [Array, Object],
            // best_sellers: [Array, Object],
            // top_selling: [Array, Object],
            // top_rated: [Array, Object],
            // new_arrivals: [Array, Object],
            // home_offer: [Array, Object],
            // pbanner1_detail:Object,
            // pbanner2_detail:Object,
            footerImages: [Array, Object],
        },
        computed: {
            ...mapGetters(['dealsProduct']),
        },
        methods: {
            displaySideCart() {
                this.sideCartStatus = !this.sideCartStatus;
            },
            getFeaturedProducts(){
                let location = 1
                if(localStorage.getItem("location")){
                    location = localStorage.getItem("location")
                }
                axios.post('/api/get-featured-products/'+ location).then((response) => {
                    this.featured_category = response.data.featured_category
                    this.featured_products = response.data.featured_products
                })                
            },
            getBestSellerProducts(){
                let location = 1
                if(localStorage.getItem("location")){
                    location = localStorage.getItem("location")
                }
                axios.post('/api/get-best-seller-product-mul-location/'+ location).then((response) => {
                    this.best_sellers = response.data.best_sellers
                })
            },
            getTopSellingProducts(){
                let location = 1
                if(localStorage.getItem("location")){
                    location = localStorage.getItem("location")
                }
                axios.post('/api/get-top-selling-product-mul-location/'+ location).then((response) => {
                    this.top_selling = response.data.top_selling
                })
                //top_selling
            }
            ,
            getTopRatedgProducts(){
                let location = 1
                if(localStorage.getItem("location")){
                    location = localStorage.getItem("location")
                }
                axios.post('/api/get-top-rated-product-mul-location/'+ location).then((response) => {
                    this.top_rated = response.data.top_rated
                })
                //top_rated
            },
            getNewArrivalProducts(){
                let location = 1
                if(localStorage.getItem("location")){
                    location = localStorage.getItem("location")
                }
                axios.post('/api/get-new-arrival-product-mul-location/'+ location).then((response) => {
                    this.new_arrivals = response.data.new_arrivals
                })
                //new_arrivals
            },
            getSliders(){
                let location = 1
                if(localStorage.getItem("location")){
                    location = localStorage.getItem("location")
                }
                axios.get('/api/get-sliders-mul-location?location=' + location).then((response) => {
                    this.sliders = response.data.sliders
                })
            },
            getTopBanner(){
                let location = 1
                if(localStorage.getItem("location")){
                    location = localStorage.getItem("location")
                }
                axios.get('/api/get-top-banner-mul-location?location=' + location).then((response) => {
                    this.pbanner1_detail = response.data.pbanner1_detail
                })
            },
            getBottomBanner(){
                let location = 1
                if(localStorage.getItem("location")){
                    location = localStorage.getItem("location")
                }
                axios.get('/api/get-bottom-banner-mul-location?location=' + location).then((response) => {
                    this.pbanner2_detail = response.data.pbanner2_detail
                })
            },
            getHomeOffer(){
                let location = 1
                if(localStorage.getItem("location")){
                    location = localStorage.getItem("location")
                }
                axios.get('/api/get-home-offers-mul-location?location=' + location).then((response) => {
                    this.home_offer = response.data.home_offer
                })
            }
        },
        created(){
            this.getSliders()
            this.getTopBanner()
            this.getFeaturedProducts()
            this.getBestSellerProducts()
            this.getTopSellingProducts()
            this.getTopRatedgProducts()
            this.getNewArrivalProducts()
            this.getHomeOffer()
            this.getBottomBanner()
        }
    };
</script>
<template>
    <head title="Home" />
    <MainLayout>
        <MainSlider :sliders="sliders" />
            <Offers :pdetails="pbanner1_detail" v-if="pbanner1_detail && pbanner1_detail.status"/>
            <section class="section ec-product-tab section-space-p" v-if="dealsProduct.length > 0" >
                <div class="container">
                    <Deal />
                </div>
            </section>
            <section class="section ec-product-tab section-space-p">
                <div class="container">
                    <FeaturedProducts :products="featured_products" :categories="featured_category" v-if="featured_products.length > 0 && featured_category.length > 0"/>
                    <ProductsGroup  :top-selling="top_selling" :top-rated="top_rated" :best-sellers="best_sellers" :new-arrivals="new_arrivals" />
                </div>
            </section>
            <Banners v-if="home_offer.length > 0" :banners="home_offer"/>
            
            <Offers :pdetails="pbanner2_detail" v-if="pbanner2_detail && pbanner2_detail.status"/>
            <!-- <Blogs /> -->
            <Categories :slides="categories" />
            <section class="section ec-ser-spe-section">
                <div class="d-flex ec_ser_block justify-content-center">
                    <div class="ec_ser_content" v-for="(footer, index) in footerImages" :key="'footer-'+footer.id">
                        <div class="ec_ser_inner">
                            <div class="ec-service-image" style="width:50px;">
                                <img :src="$media_url+footer.image" class="svg_img ser_svg" alt="" v-if="footer.image"/>
                                <img src="/images/icons/service_4_2.svg" class="svg_img ser_svg" alt="" v-else/>
                            </div>
                            <div class="ec-service-desc">
                                <h2>{{ footer.heading }}</h2>
                                <p>{{ footer.title }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
    </MainLayout>
</template>