<template>
    <!-- <div class="ec-spe-product col-md-3 col-sm-6 col-12"> -->
        <div class="ec-spe-product">
        <div class="ec-spe-pro-inner border">
            <div class="ec-spe-pro-image-outer">
                <a class="mb-3 d-block" :href="route('product',product.slug)" target="_blank">
                    <div class="ec-spe-pro-image">
                        <!-- <img class="img-responsive" src="/images/product-image/77_1.jpg" alt="" /> -->
                        <product-image className="'img-responsive'" :src="(product.image)?$media_url+product.image:$local_media_url+'product-image/50_2.jpg'" :alt="product.name"></product-image>
                    </div>
                </a>
                
                <span class="flags" v-if="final_price.p">
                    <span class="new">
                        {{ final_price.p }}
                    </span>
                </span>
                <span class="flags" v-else-if="tag != ''">
                    <span class="new">{{ tag }}</span>
                </span>
                <div class="checklist-icon">
                    <wishlist-button :product="product"></wishlist-button>
                </div>
            </div>
            <div class="ec-spe-pro-content">
                <h5 class="ec-spe-pro-title">
                    <Link :href="route('category',product.category_slug)"> {{ product.category }} </Link>
                </h5>
                <a class="mb-3 d-block" :href="route('product',product.slug)" target="_blank">
                    <h6 class="ec-pro-stitle" :title="product.name">{{ productname }}</h6>
                </a>
                <DiscountPrice  v-if="final_price.p" :currency="currency" :oldPrice="product.unit_price" :newPrice="final_price.up" ></DiscountPrice>
                <div class="grid-card-btn-deal oos" >
                    <cart-button :product="product" v-if="product.stock > 0"></cart-button>
                    <div class="out-of-stock-single-deal" v-else>OUT OF STOCK</div>
                </div>
                <!-- <div class="ec-spe-pro-progress mb-2">
                    <span class="ec-spe-pro-progress-desc"><span>Sold:
                    <b>15</b></span><span>Available: <b>40</b></span></span>
                    <span class="ec-spe-pro-progressbar"></span>
                </div> -->
                <div class="countdowntimer" >
                    <Countdown :deadline="product.deal_end_date_timestamp" :flipAnimation="false" />
                </div>
                
            </div>
        </div>
    </div>
</template>
<script>
import { mapGetters } from 'vuex'
import CartButton from './Elemants/CartButton.vue'
import ProductImage from './Elemants/Image.vue'
import Price from './Elemants/Price.vue'
import DiscountPrice from './Elemants/DiscountPrice.vue'
import WishlistButton from './Elemants/WishlistButton.vue'
import moment from 'moment'
import {Countdown} from 'vue3-flip-countdown'

export default {
    components: {
        CartButton,
        ProductImage,
        Price,
        WishlistButton,
        DiscountPrice,
        Countdown,
    },
    props: {
        product: [Array, Object],
        id:Number,
    },
    computed: {
        ...mapGetters(['currency', 'dealsProduct', 'dealsProductDiscount', 'stockDetails']),
        productname(){
            let pn = this.product.name
            return pn.substr(0, 30)
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
            let up = this.product.unit_price
            let dt = this.product.discount_type
            let pd = Number(this.product.percent_discount)
            let fd = Number(this.product.flat_discount)
            let fp = 0
            let netPercentDiscount = 0
            let netFlatDiscount = 0
            let standard_product_discount_type = '';
            let standard_discount = 0
            if(this.isProductDiscountEnable){
                if(dt == 'percent') {
                    netPercentDiscount = pd
                }
                if(dt == 'flat') {
                    netFlatDiscount = fd;
                }
            }
            //standard discount
            
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
                            netPercentDiscount = Number(deals_discount_amount) + Number(netPercentDiscount);                            
                        }else{                            
                            netFlatDiscount = Number(deals_discount_amount) + Number(netFlatDiscount);                        
                        }
                    }else{
                        //only deal discount
                        if(deals_discount_type == 'percentage'){
                            netPercentDiscount = Number(deals_discount_amount);
                            netFlatDiscount = 0;
                        }else{
                            netPercentDiscount = 0;
                            netFlatDiscount = Number(deals_discount_amount);
                        }
                    }
                } 
            }
            if(this.product.standard_product_discount_type == 'percent' && Number(this.product.standard_discount) > 0){                
                netPercentDiscount = Number(this.product.standard_discount) + Number(netPercentDiscount)
                standard_product_discount_type = 'percent'
                standard_discount = this.product.standard_discount + '%'
            }else{
                if(Number(this.product.standard_discount) > 0){
                    netFlatDiscount = Number(this.product.standard_discount) + Number(netFlatDiscount);
                    standard_product_discount_type = 'fixed'
                    standard_discount =  'Rs. ' + this.product.standard_discount 
                }
            }
            if(netPercentDiscount > 0){
                up =  up*(100 - Number(netPercentDiscount))/100;
            }
            if(netFlatDiscount){
                up = up - Number(netFlatDiscount);
            }
            if(netPercentDiscount > parseInt(netPercentDiscount)){
                netPercentDiscount = Number(netPercentDiscount).toFixed(2)
            }else{
                netPercentDiscount = parseInt(netPercentDiscount)
            }
            let p = '';
            if(netPercentDiscount > 0){
                p = netPercentDiscount + '%'
            }
            if(netFlatDiscount > 0){
                if(netPercentDiscount > 0){
                    p = netPercentDiscount + '% +'
                }
                p = p + this.currency + netFlatDiscount
            }
            return {up : up, netPercentDiscount : netPercentDiscount, netFlatDiscount : netFlatDiscount, p : p, standard_discount:standard_discount, standard_product_discount_type:standard_product_discount_type}
            // return {netPercentDiscount, netFlatDiscount, up}
            return up
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
    }
}
</script>