<template>
       
    <div class="ec-line-offer banner-full-margin" >
        <div class="ec-line-offer-info" style="padding:0px; height:auto;">
            <div class="container">
                <img :src="backgroundImage" />
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
            item:Object
        },
        computed: {
            ...mapGetters(['currency', 'dealsProduct', 'dealsProductDiscount']),
            backgroundImage(){
                return encodeURI((this.item.photo)?this.$offer_token+this.item.photo:this.$media_url+'product-image/50_2.jpg')
            },
            discountMessage(){
                if(this.item.discount_type == 'percentage'){
                    return 'Upto <span>'+this.item.discount_amount+'%</span> OFF'
                }else{
                    return 'Upto <span>'+this.currency+' '+this.item.discount_amount+'</span> OFF'
                }
            }
        },
        created(){
            console.log('offer_token', this.$offer_token)
        }
    }
    </script>