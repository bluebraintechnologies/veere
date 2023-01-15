<script>
    import { mapGetters } from 'vuex';
    import MainLayout from "@/Layouts/Main.vue";
    import { Inertia } from '@inertiajs/inertia'
    import SingleOfferCarusel from '@/Pages/Product/SingleOfferCarusel.vue';

    export default {
        name: "Offers",
        data() {
            return {
            
            }
        },
        components: {
            MainLayout,
            SingleOfferCarusel
        },
        props: {

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
                        
        },
        created(){
            axios.get('/api/get-all-offers').then((response) => {
                this.offers = response.data.offers
            })
        }
    };
    </script>
    <template>
        <Head title="Offers" />
    
        <MainLayout>
            <section class="labels section-space-p">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <div class="section-title">
                                <h2 class="ec-bg-title">Offers</h2>
                                <h2 class="ec-title">Offers</h2>
                            </div>
                        </div>
                    </div>
                    <div v-for="(offer, index) in offers" :key="'offer-single-' + index">
                        <SingleOfferCarusel  :offer="offer"></SingleOfferCarusel>
                    </div>
                </div>
            </section>
        </MainLayout>
    </template>