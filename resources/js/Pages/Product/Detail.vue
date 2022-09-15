<script>
import GuestLayout from "@/Layouts/Main.vue";
import ProductImage from './Elemants/Image.vue';

import Price from './Elemants/Price.vue';
import WishlistButtonVue from './Elemants/WishlistButton.vue';
import CartButtonVue from './Elemants/CartButton.vue';

export default {
    name: "Product Detail",
    data() {
        return {
            ec_qtybtn:1
        }
    },
    components: {
        GuestLayout,
        CartButtonVue,
        WishlistButtonVue,
        ProductImage,
        Price
    },
    props: {
        product:Object,
    },
    computed:{ 
        stock_available() {
           return 10;
        }
    },
    setup() {
        return {};
    },
    methods: {
        qtyDown() {
            if(this.ec_qtybtn == this.product.min_qty || this.ec_qtybtn == 1) {
                this.$toast.warning('You have to select atleast 1 quantity')
            }
            else if(this.ec_qtybtn > 1) {
                this.ec_qtybtn = this.ec_qtybtn - 1
            }
        },
        qtyUp() {
            if(this.ec_qtybtn == this.stock_available) {
                this.$toast.warning('You can select max '+ this.stock_available +' quantity')
            }
            else {
                this.ec_qtybtn = this.ec_qtybtn + 1
            }
        }
    }
};
</script>

<template>
    <Head :title="product.name" />

    <GuestLayout>
        <div class="sticky-header-next-sec  ec-breadcrumb section-space-mb">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="row ec_breadcrumb_inner">
                            <div class="col-md-6 col-sm-12">
                                <h2 class="ec-breadcrumb-title">{{ product.name }}</h2>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <!-- ec-breadcrumb-list start -->
                                <ul class="ec-breadcrumb-list">
                                    <li class="ec-breadcrumb-item">
                                        <Link :href="route('home')">Home</Link>
                                    </li>
                                    <li class="ec-breadcrumb-item">
                                        <Link :href="route('category', product.category_slug)">{{ product.category }}</Link>
                                    </li>
                                    <li class="ec-breadcrumb-item active">{{ product.name }}</li>
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
                        <div class="single-pro-block">
                            <div class="single-pro-inner">
                                <div class="row">
                                    <div class="single-pro-img single-pro-img-no-sidebar">
                                        <div class="single-product-scroll">
                                            <div class="single-product-cover">
                                                <div class="single-slide zoom-image-hover">
                                                    <product-image :src="(product.image)?$media_url+product.image:$local_media_url+'product-image/50_2.jpg'" className="img-responsive" :alt="product.name"></product-image>
                                                </div>
                                            </div>
                                            <div class="single-nav-thumb">
                                                <div class="single-slide" style="width:135px">
                                                    <product-image :src="(product.image)?$media_url+product.image:$local_media_url+'product-image/50_2.jpg'" :alt="product.name" className="img-responsive"></product-image>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="single-pro-desc single-pro-desc-no-sidebar">
                                        <div class="single-pro-content">
                                            <h5 class="ec-single-title">{{ product.name }}
                                                <span class="float-right">
                                                    <wishlist-button-vue :product="product"></wishlist-button-vue>
                                                </span>
                                            </h5>
                                            <div class="ec-single-rating-wrap">
                                                <div class="ec-single-rating">
                                                    <i class="ecicon eci-star fill"></i>
                                                    <i class="ecicon eci-star fill"></i>
                                                    <i class="ecicon eci-star fill"></i>
                                                    <i class="ecicon eci-star fill"></i>
                                                    <i class="ecicon eci-star-o"></i>
                                                </div>
                                            </div>
                                            <div class="ec-single-desc">{{ product.meta_description }}</div>

                                            <!-- <div class="ec-single-sales">
                                                <div class="ec-single-sales-inner">
                                                    <div class="ec-single-sales-title">sales accelerators</div>
                                                    <div class="ec-single-sales-visitor">real time <span>24</span> visitor
                                                        right now!</div>
                                                    <div class="ec-single-sales-progress">
                                                        <span class="ec-single-progress-desc">Hurry up!left 29 in
                                                            stock</span>
                                                        <span class="ec-single-progressbar"></span>
                                                    </div>
                                                    <div class="ec-single-sales-countdown">
                                                        <div class="ec-single-countdown"><span
                                                                id="ec-single-countdown"></span></div>
                                                        <div class="ec-single-count-desc">Time is Running Out!</div>
                                                    </div>
                                                </div>
                                            </div> -->
                                            <div class="ec-single-price-stoke">
                                                <div class="ec-single-price">
                                                    <span class="ec-single-ps-title">As low as</span>
                                                    <span class="new-price">Rs. <price :price="product.price"></price></span>
                                                </div>
                                                <div class="ec-single-stoke">
                                                    <span class="ec-single-ps-title">IN STOCK</span>
                                                    <span class="ec-single-sku">SKU#: {{ product.sku }}</span>
                                                </div>
                                            </div>
                                            <!--<div class="ec-pro-variation">
                                                <div class="ec-pro-variation-inner ec-pro-variation-size">
                                                    <span>SIZE</span>
                                                    <div class="ec-pro-variation-content">
                                                        <ul>
                                                            <li class="active"><span>7</span></li>
                                                            <li><span>8</span></li>
                                                            <li><span>9</span></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                 <div class="ec-pro-variation-inner ec-pro-variation-color">
                                                    <span>Color</span>
                                                    <div class="ec-pro-variation-content">
                                                        <ul>
                                                            <li class="active"><span
                                                                    style="background-color:#23839c;"></span></li>
                                                            <li><span style="background-color:#000;"></span></li>
                                                        </ul>
                                                    </div>
                                                </div> 
                                            </div>-->
                                            <div class="ec-single-qty">
                                     
                                                <div class="ec-single-cart mr-2">
                                                    <cart-button-vue :qty="ec_qtybtn" :product="product"></cart-button-vue>
                                                </div>
                                                <div class="ec-single-wishlist float-right">
                                                    
                                                </div>
                                                <!-- <div class="ec-single-quickview">
                                                    <a href="#" class="ec-btn-group quickview" data-link-action="quickview"
                                                        title="Quick view" data-bs-toggle="modal"
                                                        data-bs-target="#ec_quickview_modal"><img
                                                            src="assets/images/icons/quickview.svg" class="svg_img pro_svg"
                                                            alt="" /></a>
                                                </div> -->
                                            </div>
                                            <div class="ec-single-social">
                                                <ul class="mb-0">
                                                    <li class="list-inline-item facebook">
                                                        <a href="#">
                                                            <i class="ecicon eci-facebook"></i>
                                                        </a>
                                                    </li>
                                                    <li class="list-inline-item twitter"><a href="#"><i
                                                                class="ecicon eci-twitter"></i></a></li>
                                                    <li class="list-inline-item instagram"><a href="#"><i
                                                                class="ecicon eci-instagram"></i></a></li>
                                                    <li class="list-inline-item youtube-play"><a href="#"><i
                                                                class="ecicon eci-youtube-play"></i></a></li>
                                                    <li class="list-inline-item behance"><a href="#"><i
                                                                class="ecicon eci-behance"></i></a></li>
                                                    <li class="list-inline-item whatsapp"><a href="#"><i
                                                                class="ecicon eci-whatsapp"></i></a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--Single product content End -->
                        <!-- Single product tab start -->
                        <div class="ec-single-pro-tab">
                            <div class="ec-single-pro-tab-wrapper">
                                <div class="ec-single-pro-tab-nav">
                                    <ul class="nav nav-tabs">
                                        <li class="nav-item">
                                            <a class="nav-link active" data-bs-toggle="tab"
                                                data-bs-target="#ec-spt-nav-details" role="tablist">Detail</a>
                                        </li>
                                        <!-- <li class="nav-item">
                                            <a class="nav-link" data-bs-toggle="tab" data-bs-target="#ec-spt-nav-info"
                                                role="tablist">More Information</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-bs-toggle="tab" data-bs-target="#ec-spt-nav-review"
                                                role="tablist">Reviews</a>
                                        </li> -->
                                    </ul>
                                </div>
                                <div class="tab-content  ec-single-pro-tab-content">
                                    <div id="ec-spt-nav-details" class="tab-pane fade show active">
                                        <div class="ec-single-pro-tab-desc" v-html="product.description"></div>
                                    </div>
                                    <!--<div id="ec-spt-nav-info" class="tab-pane fade">
                                        <div class="ec-single-pro-tab-moreinfo">
                                            <ul>
                                                <li><span>Weight</span> 1000 g</li>
                                                <li><span>Dimensions</span> 35 × 30 × 7 cm</li>
                                                <li><span>Color</span> Black, Pink, Red, White</li>
                                            </ul>
                                        </div>
                                    </div>

                                     <div id="ec-spt-nav-review" class="tab-pane fade">
                                        <div class="row">
                                            <div class="ec-t-review-wrapper">
                                                <div class="ec-t-review-item">
                                                    <div class="ec-t-review-avtar">
                                                        <img src="assets/images/review-image/1.jpg" alt="" />
                                                    </div>
                                                    <div class="ec-t-review-content">
                                                        <div class="ec-t-review-top">
                                                            <div class="ec-t-review-name">Jeny Doe</div>
                                                            <div class="ec-t-review-rating">
                                                                <i class="ecicon eci-star fill"></i>
                                                                <i class="ecicon eci-star fill"></i>
                                                                <i class="ecicon eci-star fill"></i>
                                                                <i class="ecicon eci-star fill"></i>
                                                                <i class="ecicon eci-star-o"></i>
                                                            </div>
                                                        </div>
                                                        <div class="ec-t-review-bottom">
                                                            <p>Lorem Ipsum is simply dummy text of the printing and
                                                                typesetting industry. Lorem Ipsum has been the industry's
                                                                standard dummy text ever since the 1500s, when an unknown
                                                                printer took a galley of type and scrambled it to make a
                                                                type specimen.
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="ec-t-review-item">
                                                    <div class="ec-t-review-avtar">
                                                        <img src="assets/images/review-image/2.jpg" alt="" />
                                                    </div>
                                                    <div class="ec-t-review-content">
                                                        <div class="ec-t-review-top">
                                                            <div class="ec-t-review-name">Linda Morgus</div>
                                                            <div class="ec-t-review-rating">
                                                                <i class="ecicon eci-star fill"></i>
                                                                <i class="ecicon eci-star fill"></i>
                                                                <i class="ecicon eci-star fill"></i>
                                                                <i class="ecicon eci-star-o"></i>
                                                                <i class="ecicon eci-star-o"></i>
                                                            </div>
                                                        </div>
                                                        <div class="ec-t-review-bottom">
                                                            <p>Lorem Ipsum is simply dummy text of the printing and
                                                                typesetting industry. Lorem Ipsum has been the industry's
                                                                standard dummy text ever since the 1500s, when an unknown
                                                                printer took a galley of type and scrambled it to make a
                                                                type specimen.
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="ec-ratting-content">
                                                <h3>Add a Review</h3>
                                                <div class="ec-ratting-form">
                                                    <form action="#">
                                                        <div class="ec-ratting-star">
                                                            <span>Your rating:</span>
                                                            <div class="ec-t-review-rating">
                                                                <i class="ecicon eci-star fill"></i>
                                                                <i class="ecicon eci-star fill"></i>
                                                                <i class="ecicon eci-star-o"></i>
                                                                <i class="ecicon eci-star-o"></i>
                                                                <i class="ecicon eci-star-o"></i>
                                                            </div>
                                                        </div>
                                                        <div class="ec-ratting-input">
                                                            <input name="your-name" placeholder="Name" type="text" />
                                                        </div>
                                                        <div class="ec-ratting-input">
                                                            <input name="your-email" placeholder="Email*" type="email"
                                                                required />
                                                        </div>
                                                        <div class="ec-ratting-input form-submit">
                                                            <textarea name="your-commemt"
                                                                placeholder="Enter Your Comment"></textarea>
                                                            <button class="btn btn-primary" type="submit"
                                                                value="Submit">Submit</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div> -->
                                </div>
                            </div>
                        </div>
                        <!-- product details description area end -->
                    </div>

                </div>
            </div>
        </section>
    </GuestLayout>
</template>