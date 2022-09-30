<template>
    <div class="ec-product-inner">
        <div class="ec-pro-image-outer">
            <div class="ec-pro-image">
                <Link :href="route('product',product.slug)" class="image">
                    <product-image :src="(product.image)?$media_url+product.image:$local_media_url+'product-image/50_2.jpg'" :alt="product.name"></product-image>
                </Link>
                <span class="flags" v-if="product.new_tag == 1">
                    <span class="new">New</span>
                </span>
                <!-- <div class="ec-pro-actions">
                    <wishlist-button :product="product"></wishlist-button>
                </div> -->
            </div>
        </div>
        <div class="ec-pro-content">
            <h5 class="ec-pro-title">
                <Link :href="route('category',product.category_slug)"> {{ product.category }} </Link>
            </h5>
            <Link :href="route('product',product.slug)">
                <h6 class="ec-pro-stitle">{{ product.name }}</h6>
            </Link>
            <DiscountPrice  v-if="isdiscountEnabled" :currency="currency" :oldPrice="product.price" :newPrice="final_price"></DiscountPrice>
            <div class="ec-pro-rat-price" v-else>
                <price :currency="currency" :price="product.price"></price>
            </div>
            <div class="grid-card-btn">
                <cart-button :product="product"></cart-button>
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

export default {
    components: {
        CartButton,
        ProductImage,
        Price,
        WishlistButton,
        DiscountPrice,
    },
    props: {
        product: [Array, Object]
    },
    computed: {
        ...mapGetters(['currency']),
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
                let up = this.product.price
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
    }
}
</script>
<style>
    .ec-pro-actions{
        opacity: 1 !important;
        visibility: visible !important;
    }
    
</style>