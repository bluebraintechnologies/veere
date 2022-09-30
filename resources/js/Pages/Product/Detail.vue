<script>
import { mapGetters } from 'vuex';
import MainLayout from "@/Layouts/Main.vue";
import ProductImage from './Elemants/Image.vue';
import Price from './Elemants/Price.vue';
import WishlistButtonVue from './Elemants/WishlistButton.vue';
import CartButton from './Elemants/CartButton.vue';
import moment from 'moment';
import DiscountPrice from './Elemants/DiscountPrice.vue';
import { useForm } from '@inertiajs/inertia-vue3';
import { Inertia } from '@inertiajs/inertia'
import SingleProduct from "./SingleGrid.vue";

export default {
    name: "Product Detail",
    data() {
        return {
            ec_qtybtn:1
        }
    },
    components: {
        MainLayout,
        CartButton,
        WishlistButtonVue,
        ProductImage,
        Price,
        DiscountPrice,
        SingleProduct,
    },
    props: {
        product:Object,
        productReviews:Object|Array,
        relatedProduct:Object|Array,
    },
    data(){
        return {
            tab : 'more-information',
            review_star : 2,
            form : useForm({
                name : '',
                email : '',
                content : '',                
            }),
            submitBtn : true,
            postal_code : '',
            delivery_status : 0,
        }
    },
    computed:{ 
        ...mapGetters(['currency']),
        stock_available() {
           return 10;
        },
        isdiscountEnabled() {
            let day = moment().date()
            let month = moment().month() + 1
            let year = moment().year()
            let today = year+'-'+month+'-'+day
            
            if(moment(today).isSame(this.product.discount_start_date)){
                return true;
            } 
            if(moment(today).isSame(this.product.discount_end_date)){
                return true;
            }
            
            if(moment(today).isAfter(this.product.discount_start_date) && moment(today).isBefore(this.product.discount_end_date)){
                return true;
            }
            return false;
        },
        final_price() {
            if(this.isdiscountEnabled) {
                let up = parseInt(this.product.variations[0].sell_price_inc_tax) //
                let dt = this.product.discount_type
                let pd = this.product.percent_discount
                let fd = this.product.flat_discount
                
                let fp = 0
                if(dt == 'percent') {
                    fp = up*((100-pd)/100);
                    return fp
                }
                if(dt == 'flat') {
                    fp = up - fd;
                    return fp
                }
                return fp;
            }
        },
        product_reviews(){
            let r = 0;
            let c = 0
            this.productReviews.forEach((ele) => {
                r += ele.star
                ++c
            })
            let s = parseInt(r/c)
            let f = r/c
            return {star : s, star_net : f.toFixed(2)}
            // return parseInt(r/c)
            // return {r,c, star}
            // return parseInt(r/c)
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
        },
        submit_review(){
            this.form.star = this.review_star
            this.form.product_id = this.product.id
            // this.submitBtn = false
            
            axios.post('/api/submit-product-review', {form : this.form, id : this.product.id}).then((response) => {
                if(response.data.status == 'success'){
                    this.$toast.success('Your review has been submitted successfully.')
                    this.form.reset()
                    this.review_star = 2
                    Inertia.reload()
                }else if(response.data.status == 'exist'){
                    this.$toast.warning('This email already exist')
                }else{
                    this.$toast.warning('Please try after some time')
                }
            })

        },
        postalCode(){
            this.delivery_status = 0
            if(this.digits_count(this.postal_code) == 6){
                axios.post('/api/get-postal-code-delivery-status', {postal_code : this.postal_code, id : this.product.id}).then((response) => {
                    if(response.data.status == 'success'){
                        this.delivery_status = 1
                        this.$toast.success('Delivery is available here')
                    }else{
                        this.delivery_status = 2
                    }
                })
            }
        },
        digits_count(n) {
            var count = 0;
            if (n >= 1) ++count;

            while (n / 10 >= 1) {
                n /= 10;
                ++count;
            }

            return count;
        }
    }
};
</script>
<style>
    .pincode-success{
        border: 2px solid green;
    }
    .pincode-danger{
        border: 2px solid green;
    }
</style>
<template>
    <Head :title="product.name" />

    <MainLayout>
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
                                        <!-- <Link :href="route('category', product.category_slug)">{{ product.category }}</Link> -->
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

                                                    <i v-for="f in 5" :key="'start-'+f" class="ecicon" :class="[(f <= product_reviews.star) ? 'eci-star fill' : 'eci-star-o']" ></i>
                                                    <span class="badge " style="color: #000;">{{ product_reviews.star_net }}</span>
                                                </div>
                                            </div>
                                            <div class="ec-single-price-stoke">
                                                <div class="ec-single-price">
                                                    <span class="ec-single-ps-title">As low as</span>
                                                    <DiscountPrice  v-if="isdiscountEnabled" :currency="currency" :oldPrice="product.variations[0].sell_price_inc_tax" :newPrice="final_price"></DiscountPrice>
                                                    <span v-else class="new-price">Rs. <price :price="product.variations[0].sell_price_inc_tax"></price></span>
                                                </div>
                                                <div class="ec-single-stoke">
                                                    <span class="ec-single-ps-title">IN STOCK</span>
                                                    <span class="ec-single-sku">SKU#: {{ product.sku }}</span>
                                                </div>
                                            </div>
                                            <div class="ec-single-qty">
                                                <div class="ec-single-cart mr-2">
                                                    <cart-button :product="product"></cart-button>
                                                </div>
                                                <div class="ec-single-wishlist float-right">
                                                    <input type="number" :class="[(this.delivery_status == 1) ? 'pincode-success' : '']"  v-model="postal_code" @keyup="postalCode()" placelholder="enter pincode"/>                                                    
                                                </div>
                                            </div>
                                            <div class="ec-single-pro-tab-desc" v-html="product.product_description"></div>
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
                                        <!-- <li class="nav-item">
                                            <a class="nav-link" :class="[(tab == 'details') ? 'active' : '']" data-bs-toggle="tab"
                                                data-bs-target="#ec-spt-nav-details" role="tablist" @click="tab = 'details'">Detail</a>
                                        </li> -->
                                        <li class="nav-item">
                                            <a class="nav-link" :class="[(tab == 'more-information') ? 'active' : '']" data-bs-toggle="tab" data-bs-target="#ec-spt-nav-info"
                                                role="tablist" @click="tab = 'more-information'">More Information</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" :class="[(tab == 'reviews') ? 'active' : '']" data-bs-toggle="tab" data-bs-target="#ec-spt-nav-review"
                                                role="tablist" @click="tab = 'reviews'">Reviews</a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="tab-content  ec-single-pro-tab-content">
                                    <!-- <div id="ec-spt-nav-details" class="tab-pane fade show " :class="[(tab == 'details') ? 'active' : '']">
                                        <div class="ec-single-pro-tab-desc" v-html="product.product_description"></div>
                                    </div> -->
                                    <div id="ec-spt-nav-info" class="tab-pane fade" :class="[(tab == 'more-information') ? 'active' : '']">
                                        <div class="ec-single-pro-tab-moreinfo">
                                            <ul>
                                                <li v-if="product.category"><span>Category</span> {{ product.category.name }}</li>
                                                <li v-if="product.sub_category"><span>Sub Category</span>span>{{ product.sub_category.name }}</li>
                                                <li v-if="product.weight"><span>HSN Code</span>{{ product.weight }}</li>
                                                <li v-if="product.media.length > 0"><span>Product brochure</span> <a :href="product.media[0].display_url" target="_blank">{{ product.media[0].display_name }}</a></li>
                                                <li v-if="product.product_custom_field2"><span>HSN Code</span>{{ product.product_custom_field2 }}</li>
                                            </ul>
                                        </div>
                                    </div>

                                     <div id="ec-spt-nav-review" class="tab-pane fade" :class="[(tab == 'reviews') ? 'active' : '']">
                                        <div class="row">
                                            <div class="ec-t-review-wrapper">
                                                <div class="ec-t-review-item" v-for="(review, index) in productReviews" :key="'review-' + index">
                                                    <div class="ec-t-review-content">
                                                        <div class="ec-t-review-top">
                                                            <div class="ec-t-review-name">{{ review.name }}</div>
                                                            <div class="ec-t-review-rating">
                                                                <i v-for="f in 5" :key="'start-'+f" class="ecicon" :class="[(f <= review.star) ? 'eci-star fill' : 'eci-star-o']" ></i>
                                                            </div>
                                                        </div>
                                                        <div class="ec-t-review-bottom">
                                                            <p v-html="review.content">
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
                                                                <i v-for="f in 5" :key="'review-'+f" class="ecicon" :class="[(f <= review_star) ? 'eci-star fill' : 'eci-star-o']" @click="review_star = f"></i>
                                                            </div>
                                                        </div>
                                                        <div class="ec-ratting-input">
                                                            <input name="your-name" placeholder="Name" type="text" v-model="form.name"/>
                                                        </div>
                                                        <div class="ec-ratting-input">
                                                            <input name="your-email" placeholder="Email*" type="email" v-model="form.email"/>
                                                        </div>
                                                        <div class="ec-ratting-input form-submit">
                                                            <textarea name="your-commemt" placeholder="Enter Your Comment" v-model="form.content"></textarea>
                                                            <button class="btn btn-primary" type="button" @click="submit_review()" v-show="submitBtn">Submit</button>
                                                            <button class="btn btn-primary" type="button" disabled v-show="!submitBtn">Submiting...</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- product details description area end -->
                        <!-- related products -->
                        <section class="section ec-releted-product section-space-p">
                            <div class="container">
                                <div class="row">
                                    <div class="col-md-12 text-center">
                                        <div class="section-title">
                                            <h2 class="ec-bg-title">Related products</h2>
                                            <h2 class="ec-title">Related products</h2>
                                            <p class="sub-title">Browse The Collection of Top Products</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="row margin-minus-b-30">
                                    <!-- Related Product Content -->
                                    <div class="col-lg-3 col-md-4 col-sm-6 col-xs-6 " v-for="product in relatedProduct" :key="product.id">
                                        <single-product :product="product"></single-product>
                                    </div>
                                    <!-- 
                                    <div class="col-lg-3 col-md-6 col-sm-6 col-xs-6 mb-6 pro-gl-content">
                                        <div class="ec-product-inner">
                                            <div class="ec-pro-image-outer">
                                                <div class="ec-pro-image">
                                                    <a href="product-left-sidebar.html" class="image">
                                                        <img class="main-image"
                                                            src="assets/images/product-image/6_1.jpg" alt="Product" />
                                                        <img class="hover-image"
                                                            src="assets/images/product-image/6_2.jpg" alt="Product" />
                                                    </a>
                                                    <span class="percentage">20%</span>
                                                    <a href="#" class="quickview" data-link-action="quickview"
                                                        title="Quick view" data-bs-toggle="modal"
                                                        data-bs-target="#ec_quickview_modal"><img
                                                            src="assets/images/icons/quickview.svg" class="svg_img pro_svg"
                                                            alt="" /></a>
                                                    <div class="ec-pro-actions">
                                                        <a href="compare.html" class="ec-btn-group compare"
                                                            title="Compare"><img src="assets/images/icons/compare.svg"
                                                                class="svg_img pro_svg" alt="" /></a>
                                                        <button title="Add To Cart" class=" add-to-cart"><img
                                                                src="assets/images/icons/cart.svg" class="svg_img pro_svg"
                                                                alt="" /> Add To Cart</button>
                                                        <a class="ec-btn-group wishlist" title="Wishlist"><img
                                                                src="assets/images/icons/wishlist.svg"
                                                                class="svg_img pro_svg" alt="" /></a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="ec-pro-content">
                                                <h5 class="ec-pro-title"><a href="product-left-sidebar.html">Round Neck T-Shirt</a></h5>
                                                <div class="ec-pro-rating">
                                                    <i class="ecicon eci-star fill"></i>
                                                    <i class="ecicon eci-star fill"></i>
                                                    <i class="ecicon eci-star fill"></i>
                                                    <i class="ecicon eci-star fill"></i>
                                                    <i class="ecicon eci-star"></i>
                                                </div>
                                                <div class="ec-pro-list-desc">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum is simply dutmmy text ever since the 1500s, when an unknown printer took a galley.</div>
                                                <span class="ec-price">
                                                    <span class="old-price">$27.00</span>
                                                    <span class="new-price">$22.00</span>
                                                </span>
                                                <div class="ec-pro-option">
                                                    <div class="ec-pro-color">
                                                        <span class="ec-pro-opt-label">Color</span>
                                                        <ul class="ec-opt-swatch ec-change-img">
                                                            <li class="active"><a href="#" class="ec-opt-clr-img"
                                                                    data-src="assets/images/product-image/6_1.jpg"
                                                                    data-src-hover="assets/images/product-image/6_1.jpg"
                                                                    data-tooltip="Gray"><span
                                                                        style="background-color:#e8c2ff;"></span></a></li>
                                                            <li><a href="#" class="ec-opt-clr-img"
                                                                    data-src="assets/images/product-image/6_2.jpg"
                                                                    data-src-hover="assets/images/product-image/6_2.jpg"
                                                                    data-tooltip="Orange"><span
                                                                        style="background-color:#9cfdd5;"></span></a></li>
                                                        </ul>
                                                    </div>
                                                    <div class="ec-pro-size">
                                                        <span class="ec-pro-opt-label">Size</span>
                                                        <ul class="ec-opt-size">
                                                            <li class="active"><a href="#" class="ec-opt-sz"
                                                                    data-old="$25.00" data-new="$20.00"
                                                                    data-tooltip="Small">S</a></li>
                                                            <li><a href="#" class="ec-opt-sz" data-old="$27.00"
                                                                    data-new="$22.00" data-tooltip="Medium">M</a></li>
                                                            <li><a href="#" class="ec-opt-sz" data-old="$35.00"
                                                                    data-new="$30.00" data-tooltip="Extra Large">XL</a></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-6 col-xs-6 mb-6 pro-gl-content">
                                        <div class="ec-product-inner">
                                            <div class="ec-pro-image-outer">
                                                <div class="ec-pro-image">
                                                    <a href="product-left-sidebar.html" class="image">
                                                        <img class="main-image"
                                                            src="assets/images/product-image/7_1.jpg" alt="Product" />
                                                        <img class="hover-image"
                                                            src="assets/images/product-image/7_2.jpg" alt="Product" />
                                                    </a>
                                                    <span class="percentage">20%</span>
                                                    <span class="flags">
                                                        <span class="sale">Sale</span>
                                                    </span>
                                                    <a href="#" class="quickview" data-link-action="quickview"
                                                        title="Quick view" data-bs-toggle="modal"
                                                        data-bs-target="#ec_quickview_modal"><img
                                                            src="assets/images/icons/quickview.svg" class="svg_img pro_svg"
                                                            alt="" /></a>
                                                    <div class="ec-pro-actions">
                                                        <a href="compare.html" class="ec-btn-group compare"
                                                            title="Compare"><img src="assets/images/icons/compare.svg"
                                                                class="svg_img pro_svg" alt="" /></a>
                                                        <button title="Add To Cart" class=" add-to-cart"><img
                                                                src="assets/images/icons/cart.svg" class="svg_img pro_svg"
                                                                alt="" /> Add To Cart</button>
                                                        <a class="ec-btn-group wishlist" title="Wishlist"><img
                                                                src="assets/images/icons/wishlist.svg"
                                                                class="svg_img pro_svg" alt="" /></a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="ec-pro-content">
                                                <h5 class="ec-pro-title"><a href="product-left-sidebar.html">Full Sleeve Shirt</a></h5>
                                                <div class="ec-pro-rating">
                                                    <i class="ecicon eci-star fill"></i>
                                                    <i class="ecicon eci-star fill"></i>
                                                    <i class="ecicon eci-star fill"></i>
                                                    <i class="ecicon eci-star fill"></i>
                                                    <i class="ecicon eci-star"></i>
                                                </div>
                                                <div class="ec-pro-list-desc">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum is simply dutmmy text ever since the 1500s, when an unknown printer took a galley.</div>
                                                <span class="ec-price">
                                                    <span class="old-price">$12.00</span>
                                                    <span class="new-price">$10.00</span>
                                                </span>
                                                <div class="ec-pro-option">
                                                    <div class="ec-pro-color">
                                                        <span class="ec-pro-opt-label">Color</span>
                                                        <ul class="ec-opt-swatch ec-change-img">
                                                            <li class="active"><a href="#" class="ec-opt-clr-img"
                                                                    data-src="assets/images/product-image/7_1.jpg"
                                                                    data-src-hover="assets/images/product-image/7_1.jpg"
                                                                    data-tooltip="Gray"><span
                                                                        style="background-color:#01f1f1;"></span></a></li>
                                                            <li><a href="#" class="ec-opt-clr-img"
                                                                    data-src="assets/images/product-image/7_2.jpg"
                                                                    data-src-hover="assets/images/product-image/7_2.jpg"
                                                                    data-tooltip="Orange"><span
                                                                        style="background-color:#b89df8;"></span></a></li>
                                                        </ul>
                                                    </div>
                                                    <div class="ec-pro-size">
                                                        <span class="ec-pro-opt-label">Size</span>
                                                        <ul class="ec-opt-size">
                                                            <li class="active"><a href="#" class="ec-opt-sz"
                                                                    data-old="$12.00" data-new="$10.00"
                                                                    data-tooltip="Small">S</a></li>
                                                            <li><a href="#" class="ec-opt-sz" data-old="$15.00"
                                                                    data-new="$12.00" data-tooltip="Medium">M</a></li>
                                                            <li><a href="#" class="ec-opt-sz" data-old="$20.00"
                                                                    data-new="$17.00" data-tooltip="Extra Large">XL</a></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-6 col-xs-6 mb-6 pro-gl-content">
                                        <div class="ec-product-inner">
                                            <div class="ec-pro-image-outer">
                                                <div class="ec-pro-image">
                                                    <a href="product-left-sidebar.html" class="image">
                                                        <img class="main-image"
                                                            src="assets/images/product-image/1_1.jpg" alt="Product" />
                                                        <img class="hover-image"
                                                            src="assets/images/product-image/1_2.jpg" alt="Product" />
                                                    </a>
                                                    <span class="percentage">20%</span>
                                                    <span class="flags">
                                                        <span class="sale">Sale</span>
                                                    </span>
                                                    <a href="#" class="quickview" data-link-action="quickview"
                                                        title="Quick view" data-bs-toggle="modal"
                                                        data-bs-target="#ec_quickview_modal"><img
                                                            src="assets/images/icons/quickview.svg" class="svg_img pro_svg"
                                                            alt="" /></a>
                                                    <div class="ec-pro-actions">
                                                        <a href="compare.html" class="ec-btn-group compare"
                                                            title="Compare"><img src="assets/images/icons/compare.svg"
                                                                class="svg_img pro_svg" alt="" /></a>
                                                        <button title="Add To Cart" class=" add-to-cart"><img
                                                                src="assets/images/icons/cart.svg" class="svg_img pro_svg"
                                                                alt="" /> Add To Cart</button>
                                                        <a class="ec-btn-group wishlist" title="Wishlist"><img
                                                                src="assets/images/icons/wishlist.svg"
                                                                class="svg_img pro_svg" alt="" /></a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="ec-pro-content">
                                                <h5 class="ec-pro-title"><a href="product-left-sidebar.html">Cute Baby Toy's</a></h5>
                                                <div class="ec-pro-rating">
                                                    <i class="ecicon eci-star fill"></i>
                                                    <i class="ecicon eci-star fill"></i>
                                                    <i class="ecicon eci-star fill"></i>
                                                    <i class="ecicon eci-star fill"></i>
                                                    <i class="ecicon eci-star"></i>
                                                </div>
                                                <div class="ec-pro-list-desc">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum is simply dutmmy text ever since the 1500s, when an unknown printer took a galley.</div>
                                                <span class="ec-price">
                                                    <span class="old-price">$40.00</span>
                                                    <span class="new-price">$30.00</span>
                                                </span>
                                                <div class="ec-pro-option">
                                                    <div class="ec-pro-color">
                                                        <span class="ec-pro-opt-label">Color</span>
                                                        <ul class="ec-opt-swatch ec-change-img">
                                                            <li class="active"><a href="#" class="ec-opt-clr-img"
                                                                    data-src="assets/images/product-image/1_1.jpg"
                                                                    data-src-hover="assets/images/product-image/1_1.jpg"
                                                                    data-tooltip="Gray"><span
                                                                        style="background-color:#90cdf7;"></span></a></li>
                                                            <li><a href="#" class="ec-opt-clr-img"
                                                                    data-src="assets/images/product-image/1_2.jpg"
                                                                    data-src-hover="assets/images/product-image/1_2.jpg"
                                                                    data-tooltip="Orange"><span
                                                                        style="background-color:#ff3b66;"></span></a></li>
                                                            <li><a href="#" class="ec-opt-clr-img"
                                                                    data-src="assets/images/product-image/1_3.jpg"
                                                                    data-src-hover="assets/images/product-image/1_3.jpg"
                                                                    data-tooltip="Green"><span
                                                                        style="background-color:#ffc476;"></span></a></li>
                                                            <li><a href="#" class="ec-opt-clr-img"
                                                                    data-src="assets/images/product-image/1_4.jpg"
                                                                    data-src-hover="assets/images/product-image/1_4.jpg"
                                                                    data-tooltip="Sky Blue"><span
                                                                        style="background-color:#1af0ba;"></span></a></li>
                                                        </ul>
                                                    </div>
                                                    <div class="ec-pro-size">
                                                        <span class="ec-pro-opt-label">Size</span>
                                                        <ul class="ec-opt-size">
                                                            <li class="active"><a href="#" class="ec-opt-sz"
                                                                    data-old="$40.00" data-new="$30.00"
                                                                    data-tooltip="Small">S</a></li>
                                                            <li><a href="#" class="ec-opt-sz" data-old="$50.00"
                                                                    data-new="$40.00" data-tooltip="Medium">M</a></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-6 col-xs-6 mb-6 pro-gl-content">
                                        <div class="ec-product-inner">
                                            <div class="ec-pro-image-outer">
                                                <div class="ec-pro-image">
                                                    <a href="product-left-sidebar.html" class="image">
                                                        <img class="main-image"
                                                            src="assets/images/product-image/2_1.jpg" alt="Product" />
                                                        <img class="hover-image"
                                                            src="assets/images/product-image/2_2.jpg" alt="Product" />
                                                    </a>
                                                    <span class="percentage">20%</span>
                                                    <span class="flags">
                                                        <span class="new">New</span>
                                                    </span>
                                                    <a href="#" class="quickview" data-link-action="quickview"
                                                        title="Quick view" data-bs-toggle="modal"
                                                        data-bs-target="#ec_quickview_modal"><img
                                                            src="assets/images/icons/quickview.svg" class="svg_img pro_svg"
                                                            alt="" /></a>
                                                    <div class="ec-pro-actions">
                                                        <a href="compare.html" class="ec-btn-group compare"
                                                            title="Compare"><img src="assets/images/icons/compare.svg"
                                                                class="svg_img pro_svg" alt="" /></a>
                                                        <button title="Add To Cart" class=" add-to-cart"><img
                                                                src="assets/images/icons/cart.svg" class="svg_img pro_svg"
                                                                alt="" /> Add To Cart</button>
                                                        <a class="ec-btn-group wishlist" title="Wishlist"><img
                                                                src="assets/images/icons/wishlist.svg"
                                                                class="svg_img pro_svg" alt="" /></a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="ec-pro-content">
                                                <h5 class="ec-pro-title"><a href="product-left-sidebar.html">Jumbo Carry Bag</a></h5>
                                                <div class="ec-pro-rating">
                                                    <i class="ecicon eci-star fill"></i>
                                                    <i class="ecicon eci-star fill"></i>
                                                    <i class="ecicon eci-star fill"></i>
                                                    <i class="ecicon eci-star fill"></i>
                                                    <i class="ecicon eci-star"></i>
                                                </div>
                                                <div class="ec-pro-list-desc">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum is simply dutmmy text ever since the 1500s, when an unknown printer took a galley.</div>
                                                <span class="ec-price">
                                                    <span class="old-price">$50.00</span>
                                                    <span class="new-price">$40.00</span>
                                                </span>                                                
                                                <div class="ec-pro-option">
                                                    <div class="ec-pro-color">
                                                        <span class="ec-pro-opt-label">Color</span>
                                                        <ul class="ec-opt-swatch ec-change-img">
                                                            <li class="active"><a href="#" class="ec-opt-clr-img"
                                                                    data-src="assets/images/product-image/2_1.jpg"
                                                                    data-src-hover="assets/images/product-image/2_2.jpg"
                                                                    data-tooltip="Gray"><span
                                                                        style="background-color:#fdbf04;"></span></a></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div> 
                                    -->
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