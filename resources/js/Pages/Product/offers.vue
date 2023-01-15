<script>
    import { mapGetters } from 'vuex';
    import MainLayout from "@/Layouts/Main.vue";
    import ProductImage from './Elemants/Image.vue';
    import Price from './Elemants/Price.vue';
    import moment from 'moment';
    import { Inertia } from '@inertiajs/inertia'
    import SingleOffer from './SingleOffer.vue';
    import SingleOfferFull from './SingleOfferFull.vue';
    import InnerImageZoom from 'vue-inner-image-zoom';
    import 'vue-inner-image-zoom/lib/vue-inner-image-zoom.css';
    export default {
        name: "Offers",
        data() {
            return {
                ec_qtybtn:1
            }
        },
        components: {
            MainLayout,
            ProductImage,
            Price,
            'inner-image-zoom': InnerImageZoom,
            SingleOffer,
            SingleOfferFull,
        },
        props: {
            // offers:Object|Array,
        },
        data(){
            return {
                offers : [],
            }
        },
        computed:{ 
            ...mapGetters(['currency']),
            
        },
        setup() {
            return {};
        },
        methods: {
            getOffers(){
                let location
                if(localStorage.getItem("location")){
                    location = localStorage.getItem("location")
                }else if(localStorage.getItem("temp_location")){
                    location = localStorage.getItem("temp_location")
                }
                axios.get('/api/get-offers-mul-location?location=' + location).then((response) => {
                    this.offers = response.data.offers
                })
            }
        },
        created(){
            this.getOffers()
        }
    };
    </script>
    <template>
        <Head title="Offers" />
    
        <MainLayout>
            
            <section class="labels section-space-p mobile-page">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <div class="section-title">
                                <h2 class="ec-bg-title">Offers</h2>
                                <h2 class="ec-title">Offers</h2>
                                <!-- <p class="sub-title">Browse The Collection of Top Categories</p> -->
                            </div>
                        </div>
                    </div>
                    <!-- <single-offer-full v-for="(offer, index) in offers" :key="'offer-' + index" :item="offer" class="offer-full-pad"></single-offer-full>                     -->
                    <div class="ec-line-offer banner-full-margin page-view" v-for="(item, index) in offers" :key="'offer-' + index">
                        <div class="ec-line-offer-info" style="padding:0px; height:auto;">
                            <div class="container">
                                <img :src="(item.photo)?this.$offer_token+item.photo:this.$media_url+'product-image/50_2.jpg'" />                          
                            </div>
                        </div>
                    </div>
                    <div class="ec-line-offer banner-full-margin mobile-view" >
                        <div class="ec-line-offer-info" style="padding:0px; height:auto;">
                            <div class="container">
                                <div class="row">
                                    <div class="col-md-4" v-for="(item, index) in offers" :key="'offer-' + index">
                                        <img style="padding:5px;" :src="(item.photo_small)?this.$offer_token+item.photo_small:this.$media_url+'product-image/50_2.jpg'" />
                                    </div>
                                </div>                                
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </MainLayout>
    </template>