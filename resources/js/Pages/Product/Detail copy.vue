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
import InnerImageZoom from 'vue-inner-image-zoom';
import 'vue-inner-image-zoom/lib/vue-inner-image-zoom.css';
import {Countdown} from 'vue3-flip-countdown'
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
        'inner-image-zoom': InnerImageZoom,
        Countdown,
    },
    props: {
        product:Object,
        productReviews:Object|Array,
        relatedProduct:Object|Array,
        legal_disclaimer:Object,
        deal_end_date_timestamp:String,
        available_stock:Number,
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
            currentImageGallery:'',
            showPincodeError:false,
            showPincodeError1:false,
        }
    },
    computed:{ 
        ...mapGetters(['currency', 'dealsProduct', 'dealsProductDiscount']),
        stock_available() {
           return 10;
        },
        currentImage(){
            if(this.product.images){
                let images = JSON.parse(this.product.images)
                return this.$media_url + images.slice(0,1)
            }else if(this.product.image){
                return this.$media_url+this.product.image
            } else{
                return this.$media_url+'product-image/50_2.jpg'
            }
        },
        gallery(){
            if(this.product.images){
                return JSON.parse(this.product.images)
            }
            return []
        },
        isdiscountEnabled() {            
            
            if(this.isDealsDiscountEnable || this.isProductDiscountEnable){
                return true;
            }
            return false;
        },
        isDealsDiscountEnable(){
            if(this.dealsProduct.indexOf(this.product.id) >= 0){
                return true
            }
            return false;
        },
        isProductDiscountEnable(){
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
            let up = this.product.price
            let dt = this.product.discount_type
            let pd = this.product.percent_discount
            let fd = this.product.flat_discount
            let fp = 0
            let netPercentDiscount = 0
            let netFlatDiscount = 0
            if(this.isProductDiscountEnable){
                if(dt == 'percent') {
                    netPercentDiscount = pd
                }
                if(dt == 'flat') {
                    netFlatDiscount = fd;
                }
            }
            if(this.isDealsDiscountEnable) {
                if(this.dealsProduct.indexOf(this.product.id) >= 0){
                    //calculate price
                    let d = this.dealsProductDiscount.filter((ele) => {
                        if(ele.id == this.product.id){
                            return ele
                        }
                    })
                    let deals_discount_type = d[0]['discount_type'];
                    let deals_discount_amount = d[0]['discount_amount'];
                    let deals_blend_product_discount = d[0]['blend_product_discount'];
                    if(deals_blend_product_discount == 1){
                        //add with product discount
                        if(deals_discount_type == 'percentage'){
                            netPercentDiscount = deals_discount_amount + netPercentDiscount;                            
                        }else{                            
                            netFlatDiscount = deals_discount_amount + netFlatDiscount;                        
                        }
                    }else{
                        //only deal discount
                        if(deals_discount_type == 'percentage'){
                            netPercentDiscount = deals_discount_amount;
                            netFlatDiscount = 0;
                        }else{
                            netPercentDiscount = 0;
                            netFlatDiscount = deals_discount_amount;
                        }
                    }
                } 
            }
            if(netPercentDiscount > 0){
                up =  up*(100 - netPercentDiscount)/100;
            }
            if(netFlatDiscount){
                up = up - netFlatDiscount;
            }
            // return {netPercentDiscount, netFlatDiscount, up}
            return up
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
            if(f > 0){
                f = f.toFixed(2)
            }else{
                f = ''
            }
            return {star : s, star_net : f}
        },
        tag(){
            if(this.dealsProduct.indexOf(this.product.id) >= 0){
                //calculate price
                return 'Deals'
            } else if(this.product.new_tag == 1){
                return 'New'
            }
            return '';
        },
    },
    setup() {
        return {};
    },
    methods: {
        setCurrentImage(img){
            this.currentImageGallery = this.$media_url +img
        },
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
                    }
                    if(response.data.status == 'fail'){
                        this.delivery_status = 2
                        this.showPincodeError = true
                        setTimeout(() => {
                            this.showPincodeError = false
                        }, 3000); 
                    }
                })
            }else{
                this.showPincodeError1 = true
                setTimeout(() => {
                    this.showPincodeError1 = false
                }, 3000);
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
    .pincode-outer-container{
        display: flex !important;
        flex-direction: column;
    }
    .pincode-container{
        display: flex;
        justify-content: row;
    }
    .pincode-flex-item{
        margin-left:10px;
        height: 100%;
    }
    .pincode-error,.pincode-error-1{
        color: #e70909  !important;
        padding: 0 10px;
        width: fit-content;
        font-weight: 500 !important;
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
                                                    <!-- <product-image :src="(product.image)?$media_url+product.image:$local_media_url+'product-image/50_2.jpg'" className="img-responsive" :alt="product.name"></product-image> -->
                                                    <inner-image-zoom class="main-image img-responsive" :src="currentImageGallery" :zoomSrc="currentImageGallery" v-if="currentImageGallery"/>
                                                    <!-- <img class="main-image img-responsive" :src="currentImageGallery"  v-if="currentImageGallery" /> -->
                                                    <inner-image-zoom class="main-image img-responsive" :src="currentImage" :zoomSrc="currentImage"  v-else></inner-image-zoom>
                                                </div>
                                            </div>
                                            <div class="single-nav-thumb">
                                                <div class="single-slide" v-if="gallery.length > 0">
                                                    <ul style="display: flex;">
                                                        <li class="single-slide gallery-li" style="width:135px"  v-for="(img, index) in gallery" :key="'img-' + index" @click="setCurrentImage(img)">
                                                            <img class="main-image img-responsive" :src="$media_url+img"  />
                                                        </li>
                                                    </ul>                                                    
                                                </div>
                                                <div class="single-slide" style="width:135px" v-else>
                                                    <product-image :src="(product.image)?$media_url+product.image:$local_media_url+'product-image/50_2.jpg'" :alt="product.name" className="img-responsive" ></product-image>
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
                                                    <DiscountPrice  v-if="isdiscountEnabled" :currency="currency" :oldPrice="product.price" :newPrice="final_price"></DiscountPrice>
                                                    <span v-else class="new-price">Rs. <price :price="product.price"></price></span>
                                                </div>
                                                <div class="ec-single-stoke">
                                                    <span class="ec-single-ps-title" v-if="available_stock > 0">IN STOCK</span>
                                                    <div class="out-of-stock" v-else>OUT OF STOCK</div>
                                                    <span class="ec-single-sku">SKU#: {{ product.sku }}</span>
                                                </div>
                                            </div>
                                            <div class="ec-single-qty">
                                                <div class="ec-single-cart mr-2" v-if="available_stock > 0">
                                                    <cart-button :product="product"></cart-button>
                                                </div>
                                                <div class="ec-single-wishlist float-right">
                                                </div>
                                            </div>
                                            <div class="ec-single-qty pincode-outer-container">
                                                <div class="pincode-container">
                                                    <div class="pincode-flex-item">
                                                        <input type="number" :class="[(this.delivery_status == 1) ? 'pincode-success' : '']"  v-model="postal_code" placeholder="Enter pincode"  placelholder="enter pincode/zipcode"/>
                                                    </div>
                                                    <div class="pincode-flex-item">
                                                        <button class="btn btn-primary btn-sm" @click="postalCode()">Check Availability</button>
                                                    </div>
                                                </div>
                                                <p v-if="showPincodeError" class="pincode-error">Delivery is not available at this pincode</p>
                                                <p v-if="showPincodeError1" class="pincode-error-1">Please check pincode!</p>
                                            </div>
                                            <div class="ec-single-sales" v-if="isDealsDiscountEnable">
                                                <div class="ec-single-sales-inner">
                                                    <div class="ec-single-sales-title">sales accelerators</div>
                                                    <div class="ec-single-sales-visitor">real time <span>24</span> visitor
                                                        right now!</div>
                                                    <div class="ec-single-sales-progress">
                                                        <span class="ec-single-progress-desc" v-if="available_stock > 0">Hurry up!left {{ available_stock }} in
                                                            stock</span>
                                                        <span class="ec-single-progressbar"></span>
                                                    </div>
                                                    <div class="ec-single-sales-countdown">
                                                        <div class="ec-single-countdown">
                                                            <Countdown :deadline="deal_end_date_timestamp" :flipAnimation="false" />
                                                        </div>
                                                        <div class="ec-single-count-desc">Time is Running Out!</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="ec-single-social">
                                                <ul class="mb-0">
                                                    <li class="list-inline-item facebook"><a href="#"><i class="ecicon eci-facebook"></i></a></li>
                                                    <li class="list-inline-item twitter"><a href="#"><i class="ecicon eci-twitter"></i></a></li>
                                                    <li class="list-inline-item instagram"><a href="#"><i class="ecicon eci-instagram"></i></a></li>
                                                    <li class="list-inline-item youtube-play"><a href="#"><i class="ecicon eci-youtube-play"></i></a></li>
                                                    <li class="list-inline-item behance"><a href="#"><i class="ecicon eci-behance"></i></a></li>
                                                    <li class="list-inline-item whatsapp"><a href="#"><i class="ecicon eci-whatsapp"></i></a></li>
                                                    <li class="list-inline-item plus"><a href="#"><i class="ecicon eci-plus"></i></a></li>
                                                </ul>
                                            </div>
                                            <div class="ec-single-sales">
                                                <div class="ec-single-sales-inner">
                                                    <div class="ec-single-sales-visitor">Product Description</div>
                                                    <div class="ec-single-pro-tab-desc" v-html="legal_disclaimer.legal_disclaimer"></div>

                                                </div>
                                            </div>
                                            <div class="ec-single-sales">
                                                <div class="ec-single-sales-inner">
                                                    <div class="ec-single-sales-visitor">Legal Disclaimer</div>
                                                    <div class="ec-single-pro-tab-desc" v-html="legal_disclaimer.legal_disclaimer"></div>
                                                    <!-- <div class="ec-single-sales-progress">
                                                        <span class="ec-single-progress-desc">Hurry up!left 29 in
                                                            stock</span>
                                                        <span class="ec-single-progressbar"></span>
                                                    </div>
                                                    <div class="ec-single-sales-countdown">
                                                        <div class="ec-single-countdown"><span id="ec-single-countdown" class="style colorDefinition labelformat"><span class="timerDisplay label4"><span class="displaySection"><span class="numberDisplay">456</span><span class="periodDisplay">Days</span></span><span class="displaySection"><span class="numberDisplay">23</span><span class="periodDisplay">Hours</span></span><span class="displaySection"><span class="numberDisplay">59</span><span class="periodDisplay">Min</span></span><span class="displaySection"><span class="numberDisplay">12</span><span class="periodDisplay">Sec</span></span></span></span></div>
                                                        <div class="ec-single-count-desc">Time is Running Out!</div>
                                                    </div> -->
                                                </div>
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