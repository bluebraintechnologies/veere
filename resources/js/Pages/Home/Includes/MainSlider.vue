<template>
    <div class="ec-main-slider section">
        <div class="ec-slider swiper-container main-slider-nav main-slider-dot">
            <Carousel :settings='settings' :breakpoints='breakpoints' class="swiper-wrapper">
                <Slide class="ec-slide-item swiper-slide"  v-for="(sdata,index) in sliders" :key="'slide-'+index">
                    <div class='carousel__item'>
                        <img :src="sdata.image" />    
                    </div>
                </Slide>
                <template #addons>
                    <Pagination />
                    <Navigation />
                </template>
            </Carousel>
        </div>
    </div>
</template>
<script>
import { Carousel, Navigation, Slide, Pagination } from 'vue3-carousel';
import 'vue3-carousel/dist/carousel.css';
export default {
    props: {
        sliders: [Array, Object],
    },
    components: {
        Pagination,
        Carousel,
        Slide,
        Navigation,
    },
    data() {
        return {
            settings: {
                itemsToShow: 1,
                snapAlign: 'center',
                wrapAround:true,
                autoplay: 8000,
                transition:3000
            },
            breakpoints: {
                700: {
                    itemsToShow: 1,
                    snapAlign: 'center',
                },
                1024: {
                    itemsToShow: 1,
                    snapAlign: 'start',
                },
            }
        }
    },
};
</script>