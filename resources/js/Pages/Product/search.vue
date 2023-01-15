<script>
    import MainLayout from "@/Layouts/Main.vue";
    import SingleProduct from "./SingleGrid.vue";
    export default {
        name: "Search",
        data() {
            return {
                
            }
        },
        components: {
            MainLayout,
            SingleProduct,
        },
        props: {            
            submenu:String,
            keyword : String,
        },
        data(){
            return {
                products:[],
            }
        },
        computed:{ 
            
        },
        setup() {
            return {};
        },
        methods: {
            getSearchProducts(){
                let location
                if(localStorage.getItem("location")){
                    location = localStorage.getItem("location")
                }else if(localStorage.getItem("temp_location")){
                    location = localStorage.getItem("temp_location")
                }
                axios.get('/api/get-search-products?keyword=' + this.keyword + '&location=' + location).then((response) => {
                    this.products = response.data.products
                })
            }
        },
        created(){
            this.getSearchProducts()
        }
    };
    </script>
    <style>
        .ec-pro-content .er-price div.old-price {
            font-size: 15px;
            margin-right: 15px;
            text-decoration: line-through;
            color: #777777;
        }
        .pincode-success{
            border: 2px solid green;
        }
        .pincode-danger{
            border: 2px solid green;
        }
        .gallery-li {
            max-height:100px;
            cursor: pointer;
        }
    </style>
    <template>
        <Head title="Search" />
    
        <MainLayout>
            <div class="sticky-header-next-sec  ec-breadcrumb section-space-mb">
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <div class="row ec_breadcrumb_inner">
                                <div class="col-md-6 col-sm-12">
                                    <h2 class="ec-breadcrumb-title">Search</h2>
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <!-- ec-breadcrumb-list start -->
                                    <ul class="ec-breadcrumb-list">
                                        <li class="ec-breadcrumb-item">
                                            <Link :href="route('home')">Home</Link>
                                        </li>
                                        <li class="ec-breadcrumb-item active">{{ submenu }}</li>
                                    </ul>
                                    <!-- ec-breadcrumb-list end -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <section class="ec-page-content section-space-p">
                <div class="container">
                    <div class="row">
                        <div class="ec-pro-rightside ec-common-rightside col-lg-12 col-md-12">
                            
                            <section class="section ec-releted-product section-space-p">
                                <div class="container">
                                    <div class="row">
                                        <div class="col-md-12 text-center">
                                            <div class="section-title">
                                                <h2 class="ec-bg-title"><span v-if="keyword">{{ keyword }} : </span> {{ products.length }} products found</h2>
                                                <h2 class="ec-title"><span v-if="keyword">{{ keyword }} : </span> {{ products.length }} products found</h2>
                                                <!-- <p class="sub-title">Browse The Collection of Top Products</p> -->
                                            </div>
                                        </div>
                                    </div>
    
                                    <div class="row margin-minus-b-30">
                                        <!-- Related Product Content -->
                                        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-6 " v-for="product in products" :key="product.id">
                                            <single-product :product="product"></single-product>
                                        </div>
                                    </div>
                                </div>
                            </section>
                            <!-- related products -->
                        </div>
                    </div>
                </div>
            </section>
        </MainLayout>
    </template>