<template>
    <div class="col-lg-3 col-md-4 col-sm-6 col-xs-6 mb-6 pro-gl-content">
        <div class="ec-product-inner">
            <div class="ec-pro-content">
                <h5 class="ec-pro-title">
                    <Link :href="route('product',product.slug)" :title="product.name">{{ productname }}</Link>
                </h5>
                <DiscountPrice  v-if="isdiscountEnabled" :currency="currency" :oldPrice="product.price" :newPrice="final_price.up" ></DiscountPrice>
                <price v-else :currency="currency" :price="product.price"></price>
                <div class="grid-card-btn oos">     
                    <cart-button :product="product" v-if="stockDetails[product.id] > 0"></cart-button>
                    <span class="oos_btn" v-else>Out of stock</span>           
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import { mapGetters } from 'vuex'
import CartButton from '@/Pages/Product/Elemants/CartButton.vue'
import ProductImage from '@/Pages/Product/Elemants/Image.vue'
import Price from '@/Pages/Product/Elemants/Price.vue'
import DiscountPrice from '@/Pages/Product/Elemants/DiscountPrice.vue'
import WishlistButton from '@/Pages/Product/Elemants/WishlistButton.vue'
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
        product: [Array, Object],
        product_stock_detail:[Array, Object],
    },
    computed: {
        ...mapGetters(['currency', 'dealsProduct', 'dealsProductDiscount','stockDetails']),
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
            let up = this.product.price
            let dt = this.product.discount_type
            let pd = Number(this.product.percent_discount)
            let fd = Number(this.product.flat_discount)
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
            }else{
                if(Number(this.product.standard_discount) > 0){
                    netFlatDiscount = Number(this.product.standard_discount) + Number(netFlatDiscount);
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
                p = p + this.currency +'. ' + netFlatDiscount
            }
            return {up : up, netPercentDiscount : netPercentDiscount, netFlatDiscount : netFlatDiscount, p : p}
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
        }
    }
}
</script>
