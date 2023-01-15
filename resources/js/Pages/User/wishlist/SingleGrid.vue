<script>
import { mapActions, mapGetters } from "vuex";
import CartButton from '@/Pages/User/Common/CartButton.vue';
import WishlistButton from '@/Pages/User/Common/WishlistButton.vue';
import Price from './Price.vue';
import ProductImage from "./ProductImage.vue";
import { Inertia } from '@inertiajs/inertia';
import DiscountPrice from './DiscountPrice.vue'
import moment from 'moment'

export default {
    name: "Product Single Grid",
    components: { ProductImage, Price, WishlistButton, CartButton, DiscountPrice },
    props: {
        product: [Object, Array],
    },
    setup() {
        return {};
    },
    computed: {
        ...mapGetters(["currency", "categoryItems", 'dealsProduct', 'dealsProductDiscount', 'stockDetails']),
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
                p = p + this.currency + netFlatDiscount
            }
            return {up : up, netPercentDiscount : netPercentDiscount, netFlatDiscount : netFlatDiscount, p : p}
            // return {netPercentDiscount, netFlatDiscount, up}
            return up
        },
        emits:[
            'referesh-checklist'
        ],
        tag(){
            if(this.dealsProduct.indexOf(this.product.id) >= 0){
                //calculate price
                return 'Deals'
            } else if(this.product.new_tag == 1){
                return 'New'
            }
            return '';
        },
        all_colors() {
            if (this.product.colors) {
                return JSON.parse(this.product.colors);
            } else {
                return [];
            }
        },
        all_options() {
            if (this.product.attributes) {
                return JSON.parse(this.product.attributes);
            } else {
                return [];
            }
        },
        mainImage() {
            if(this.product.thumbnail_img) {
                let pi = this.product.thumbnail_img.split(',');
                return pi[0]
            } else {
                return 0
            }
        },
        hoverImage() {
            if(this.product.thumbnail_img)
            {
                let pi = this.product.thumbnail_img.split(',');
                return pi[pi.length - 1]
            } else {
                return 0
            }
        },
        // currency() {
        //     return (this.currency.active_currency)?this.currency.active_currency.symbol+' ':'Rs. ';
        // },
        category() {
            let ct = this.categoryItems.filter(ele => {
                return ele.id == this.product.category_id
            })
            return ct[0]
        }
    },
    methods: {
        ...mapActions(["addCartItem"]),
        deleteWishlistItem(id){
            this.$swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.post('/api/delete-wishlist-item',{id:id}).then((response) => {
                        this.$toast.success('Item deleted successfully');
                        this.$emit('referesh-checklist')
                    })
                }
            })
            
        }
    },
};
</script>
<template>
    <div class="ec-product-inner">
        <div class="ec-pro-image-outer">
            <a :href="route('product',product.slug)" target="_blank">
                <div class="ec-pro-image">
                    <!-- <Link :href="route('product', product.slug)" class="image"> -->
                        <product-image :src="(product.image)?$media_url+product.image:$local_media_url+'product-image/50_2.jpg'" :alt="product.name"></product-image>
                    <!-- </Link> -->
                    <span class="flags" v-if="final_price.p">
                        <span class="new">
                            {{ final_price.p }}
                        </span>
                    </span>
                    <span class="flags" v-else-if="tag != ''">
                        <span class="new">{{ tag }}</span>
                    </span>
                    <div class="ec-pro-actions">
                        <a href="javascript:void(0)" title="Delete" @click="deleteWishlistItem(product.id)"><img style="height:25px;" src="/assets/images/icons/icon-trash.png" alt="track_order"></a>
                    </div>
                </div>
            </a>
        </div>
        <div class="ec-pro-content">
            <h5 class="ec-pro-title">
                <Link :href="route('category',product.category_slug)"> {{ product.category }} </Link>
            </h5>
            <a :href="route('product',product.slug)" target="_blank">
                <h6 class="ec-pro-stitle" :title="product.name">{{ productname }}</h6>
            </a>
            <DiscountPrice  v-if="final_price.p" :currency="currency" :oldPrice="product.price" :newPrice="final_price.up"></DiscountPrice>
            <price :currency="currency" :price="product.price" v-else></price>
            <div class="grid-card-btn oos">
                <cart-button :product="product" v-if="stockDetails[product.id] > 0"></cart-button>
                <span class="oos_btn" v-else>Out of stock</span> 
            </div>
        </div>
    </div>
</template>
<style>
    .ec-pro-actions{
        opacity:1 !important;
        visibility: visible !important;
        top: -36px !important;
    }
</style>