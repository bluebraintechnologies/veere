<script>
import {Countdown} from 'vue3-flip-countdown'
import SingleGrid from '@/Pages/Product/SingleGridDeal.vue'
import { Carousel, Slide, Pagination, Navigation } from 'vue3-carousel'
import 'vue3-carousel/dist/carousel.css'

export default {
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
            deals:[],
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
        getTodayDeals(){
            let location = 1
            if(localStorage.getItem("location")){
                location = localStorage.getItem("location")
            }
            axios.get('/api/get-today-deals?location=' +location ).then((response) => {
                this.deals = response.data.products
            })
        }
    },
    mounted(){
    },
    beforeMount(){
        this.getTodayDeals()
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
                        <!-- <Link :href="route('search', {ot: 'deal'})" >Hot Deal</Link> -->
                        Hot Deal
                    </h2>
                </div>
            </div>
            <div class="ec-spe-products no-border row m-0" v-if="deals.length > 0">
                <carousel :wrap-around="true" :transition="200" :breakpoints="breakpoints">
                    <slide v-for="(pp, pk) in deals" :key="'slide'+pk" >
                        <single-grid :product="pp" :id="pk"></single-grid>
                    </slide>
                    <template #addons>
                        <navigation />
                        <!-- <pagination /> -->
                    </template>
                </carousel>
                <!-- 
                <div class="ec-spe-product col-md-3 col-sm-6 col-12" v-for="(pp, pk) in deals" :key="'fpp'+pk">
                    <single-grid :product="pp"></single-grid>
                </div> -->
            </div>
        </div>
    </div>
</template>