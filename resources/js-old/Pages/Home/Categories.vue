<template>
    <section class="section ec-category-section section-space-p">
        <div class="container">
            <div class="row d-none">
                <div class="col-md-12">
                    <div class="section-title">
                        <h2 class="ec-title">Top Category</h2>
                    </div>
                </div>
            </div>
            <div class="row margin-minus-b-15 margin-minus-t-15" data-animation="fadeIn">
                <div id="ec-cat-slider">
                    <Carousel :items-to-show="4.5" :wrap-around="true">
                        <Slide v-for="(navItem,index) in navItems" :key="'slide-' + index">
                            <div class="ec_cat_content ec_cat_content_{{ index }} col-sm-12 col-md-6 col-lg-3" style="width:300px; padding:20px;">
                                <div class="ec_cat_inner ec_cat_inner-{{ index }}">
                                    <div class="ec-category-image">
                                        <img src="assets/images/icons/drink-7.svg" class="svg_img" alt="drink" />
                                    </div>
                                    <div class="ec-category-desc">
                                        <h3>{{ navItem.name }}</h3>
                                        <a href="javascript:void(0)" class="cat-show-all">Show All <i class="ecicon eci-angle-double-right"
                                            aria-hidden="true"></i></a>
                                    </div>
                                </div>
                            </div>
                        </Slide>
                        <!-- <template #addons>
                        <Navigation />
                        </template> -->
                    </Carousel>
                </div>
            </div>
        </div>
    </section>
</template>
<script>
import { mapGetters } from "vuex";
import 'vue3-carousel/dist/carousel.css';
import { Carousel, Slide, Pagination, Navigation } from 'vue3-carousel';

export default {
    // emits: ["closeSidebar"],
    data() {
        return {
            sliders : [
                {
                    image : 'assets/images/icons/vegetable-7.svg',
                    title : 'Vegetables',
                    count : 25, 
                },
                {
                    image : 'assets/images/icons/bakery-7.svg',
                    title : 'bakery',
                    count : 25, 
                },
                {
                    image : 'assets/images/icons/vegetable-7.svg',
                    title : 'Vegetables',
                    count : 25, 
                },
                {
                    image : 'assets/images/icons/bakery-7.svg',
                    title : 'bakery',
                    count : 25, 
                },
                {
                    image : 'assets/images/icons/vegetable-7.svg',
                    title : 'Vegetables',
                    count : 25, 
                },
                {
                    image : 'assets/images/icons/bakery-7.svg',
                    title : 'bakery',
                    count : 25, 
                },
                {
                    image : 'assets/images/icons/vegetable-7.svg',
                    title : 'Vegetables',
                    count : 25, 
                },
                {
                    image : 'assets/images/icons/bakery-7.svg',
                    title : 'bakery',
                    count : 25, 
                },
                {
                    image : 'assets/images/icons/vegetable-7.svg',
                    title : 'Vegetables',
                    count : 25, 
                },
                {
                    image : 'assets/images/icons/bakery-7.svg',
                    title : 'bakery',
                    count : 25, 
                },
                {
                    image : 'assets/images/icons/vegetable-7.svg',
                    title : 'Vegetables',
                    count : 25, 
                },
                {
                    image : 'assets/images/icons/bakery-7.svg',
                    title : 'bakery',
                    count : 25, 
                },
            ]
        }
    },
    components:{
        Carousel,
        Slide,
        Pagination,
        Navigation,
    },
    props: {
        // showCart: [Boolean],
    },
    computed: {
        ...mapGetters(["navItems"]),
    },
    setup(props, { emit }) {
        // const closeSidebar = () => {
        //     emit("closeSidebar");
        // };
        // return {
        //     closeSidebar,
        // };
    },
};
</script>