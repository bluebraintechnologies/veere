<template>
    <div class="ec-main-slider section section-space-pb-30">
        <div class="container">
            <div class="ec-slider swiper-container main-slider-nav main-slider-dot">
                <div class="swiper-wrapper">
                    <Carousel>
                        <Slide v-for="(slide,index) in sliders" :key="'slide-'+index">
                            <div class="ec-slide-item swiper-slide d-flex slide-2">
                                <div class="container align-self-center">
                                    <div class="row">
                                        <div class="col-sm-12 align-self-center">
                                            <div class="ec-slide-content " style="padding-left:0px;">
                                                <!-- <div class="ec-slide-content slider-animation"> -->
                                                <h2 class="ec-slide-stitle">Organic & healthy</h2>
                                                <h1 class="ec-slide-title">fresh vegetables</h1>
                                                <div class="ec-slide-desc">
                                                    <p>starting at $ <b>20</b>.00</p>
                                                    <a href="#" class="btn btn-lg btn-primary">Shop Now <i
                                                            class="ecicon eci-angle-double-right" aria-hidden="true"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </Slide>
                        <template #addons>
                            <!-- <Navigation /> -->
                            <Pagination />
                        </template>
                    </Carousel>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import 'vue3-carousel/dist/carousel.css';
import { Carousel, Slide, Pagination, Navigation } from 'vue3-carousel';

export default {
    // emits: ["closeSidebar"],
    data() {
        return {
            sliders : [
                {
                    image : 'assets/images/main-slider-banner/18.jpg',
                    title : 'Vegetables',
                    count : 25, 
                },
                {
                    image : 'assets/images/main-slider-banner/18.jpg',
                    title : 'bakery',
                    count : 25, 
                },
                {
                    image : 'assets/images/main-slider-banner/18.jpg',
                    title : 'Vegetables',
                    count : 25, 
                },
                {
                    image : 'assets/images/main-slider-banner/18.jpg',
                    title : 'bakery',
                    count : 25, 
                }
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
        // cartStatus() {
        //     return this.showCart;
        // },
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
