<script>
    import MainLayout from "@/Layouts/Main.vue";

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
                sideCartStatus: false,
            };
        },
        props: {
            sliders: [Array, Object],
            categories: [Array, Object],
            featured_category: [Array, Object],
            featured_products: [Array, Object],
            top_selling: [Array, Object],
            top_rated: [Array, Object],
            best_sellers: [Array, Object],
            new_arrivals: [Array, Object],
        },

        methods: {
            displaySideCart() {
                this.sideCartStatus = !this.sideCartStatus;
            },
        },
    };
</script>
<template>
    <head title="Home" />
    <MainLayout>
        <MainSlider :sliders="sliders" />
        <section class="section ec-product-tab section-space-p">
            <div class="container">
                <FeaturedProducts :products="featured_products" :categories="featured_category" />
                <ProductsGroup :top-selling="top_selling" :top-rated="top_rated" :best-sellers="best_sellers" :new-arrivals="new_arrivals" />
            </div>
        </section>
        <Banners />
        <section class="section ec-product-tab section-space-p">
            <div class="container">
                <Deal />
            </div>
        </section>
        <Offers />
        <Blogs />
        <Categories :slides="categories" />
        <section class="section ec-ser-spe-section">
            <div class="d-flex ec_ser_block justify-content-center">
                <div class="ec_ser_content">
                    <div class="ec_ser_inner">
                        <div class="ec-service-image">
                            <img src="/images/icons/service_4_2.svg" class="svg_img ser_svg" alt="" />
                        </div>
                        <div class="ec-service-desc">
                            <h2>Fast delivery</h2>
                            <p>NCR Orders Only</p>
                        </div>
                    </div>
                </div>
                <div class="ec_ser_content">
                    <div class="ec_ser_inner">
                        <div class="ec-service-image">
                            <img src="/images/icons/service_4_3.svg" class="svg_img ser_svg" alt="" />
                        </div>
                        <div class="ec-service-desc">
                            <h2>Make in india</h2>
                            <p>All products are made in india</p>
                        </div>
                    </div>
                </div>
                <div class="ec_ser_content">
                    <div class="ec_ser_inner">
                        <div class="ec-service-image">
                            <img src="/images/icons/service_4_4.svg" class="svg_img ser_svg" alt="" />
                        </div>
                        <div class="ec-service-desc">
                            <h2>Uncompromising quality</h2>
                            <p>We believe in quality</p>
                        </div>
                    </div>
                </div>
                <div class="ec_ser_content">
                    <div class="ec_ser_inner">
                        <div class="ec-service-image">
                            <img src="/images/icons/service_4_5.svg" class="svg_img ser_svg" alt="" />
                        </div>
                        <div class="ec-service-desc">
                            <h2>Secure Payment</h2>
                            <p>All cards accepted</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </MainLayout>
</template>