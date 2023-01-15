<script>
import {Countdown} from 'vue3-flip-countdown'
import SingleGrid from '@/Pages/Product/SingleOfferCaruselItem.vue'
import { Carousel, Slide, Pagination, Navigation } from 'vue3-carousel'
import 'vue3-carousel/dist/carousel.css'

export default {
    props:{
        offer:Object
    },
    components: {
        Countdown,
        SingleGrid,
        Carousel,
        Slide,
        Pagination,
        Navigation,
    },
    computed: {
        // ...mapGetters(['dealsProduct', 'dealsProductDiscount'])
    },
    data() {
        return {
            products: [],
            offerProducts: [],
            breakpoints: {
                // 700px and up
                700: {
                    itemsToShow: 3,
                    snapAlign: 'start',
                },
                // 1024 and up
                1200: {
                    itemsToShow: 4,
                    snapAlign: 'start',
                },
            },
        }
    },
    methods: {
        getOfferDetails(){
            axios.post('/api/get-offer-details', {offer : this.offer} ).then((response) => {
                this.products = response.data.products
                this.offerProducts = response.data.offerProducts
            })
        }
    },
    mounted(){
    },
    beforeMount(){
        this.getOfferDetails()
    },
    created(){
    }
}
</script>
<style>
    
    .carousel__slide{
        margin-left: 4px;
        margin-right: -1px;
    }
    .carousel__prev,
    .carousel__next {
        background: #ff909d;
    }
</style>
<template>
    <div class="row">
        <div class="ec-spe-section col-lg-12 col-md-12 col-sm-12 sectopn-spc-mb">
            <div class="col-md-12">
                <div class="section-title">
                    <h2 class="ec-title text-uppercase text-muted">
                        Offer : {{  offer.name }}
                    </h2>
                </div>
            </div>

            <div class="ec-spe-products no-border row m-0" v-if="products.length > 0">
                <carousel :wrap-around="true" :transition="200" :breakpoints="breakpoints">
                    <slide v-for="(pp, pk) in offerProducts" :key="'slide'+pk" >
                        <single-grid :product="pp" :offer="offer" :id="pk"></single-grid>
                    </slide>

                    <template #addons>
                        <navigation />
                        <!-- <pagination /> -->
                    </template>
                </carousel>                
            </div>
        </div>
    </div>
</template>