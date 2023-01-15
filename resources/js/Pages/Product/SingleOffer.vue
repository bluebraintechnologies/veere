<template>
    
    <div class="ec-offer-coupon">
        <div class="ec-cpn-brand">
            <!-- <img class="ec-brand-img" src="assets/images/offer-image/4.jpg" alt="" /> -->
            <product-image :className="'ec-brand-img'" :src="(item.photo)?$offer_token+item.photo:$media_url+'product-image/50_2.jpg'" :alt="item.name"></product-image>
        </div>
        <div class="ec-cpn-title">
            <h2 class="coupon-title">{{ item.name }}</h2>
        </div>
        <div class="ec-cpn-desc">
            <p class="coupon-text" v-html="item.title"></p>
        </div>
        <div class="ec-cpn-code">
            <Link :href="route('offer-detail', item.code)" class="btn btn-secondary">Shop Now</Link>
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
            offerUrl(){
                console.log(this.$media_url.split('/'))
            }
        },
        created(){
        }
    }
    </script>