<template>
       
    <div class="ec-line-offer banner-full-margin" :style="'background-image:url('+backgroundImage+');'" >
        <div class="ec-line-offer-info">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <!-- <h6 v-if="item.category">{{ item.category.name }}</h6>
                        <h2 class="offer-upto" v-html="discountMessage"></h2>
                        <h2 class="offer-upto" v-html="item.code"></h2>
                        <p class="offer-desc" v-html="item.title"></p> -->
                        <!-- <div class="offer-btn"><a class="btn-shop-now">SHOP NOW!</a></div> -->
                    </div>
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