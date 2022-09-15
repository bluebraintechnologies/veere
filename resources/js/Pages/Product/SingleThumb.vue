<template>
    <div class="ec-all-product-inner">
        <div class="ec-pro-image-outer">
            <div class="ec-pro-image">
                <Link :href="route('product',product.slug)" class="image">
                     <product-image :src="(product.image)?$media_url+product.image:$local_media_url+'product-image/50_2.jpg'" :alt="product.name"></product-image>
                </Link>
            </div>
        </div>
        <div class="ec-pro-content">
            <h5 class="ec-pro-title">
                <Link :href="route('product',product.slug)">{{ product.name }}</Link>
            </h5>
            <h6 class="ec-pro-stitle">
                <Link :href="route('category',product.category_slug)"> {{ product.category }} </Link>
            </h6>
            <div class="ec-pro-rat-price">
                <div class="ec-pro-rat-pri-inner">
                    <price :currency="currency" :price="product.price"></price>
                </div>
                 <div class="grid-card-btn thumb-cart-btn">
                    <cart-button :product="product"></cart-button>
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
import WishlistButton from './Elemants/WishlistButton.vue'

export default {
    components: {
        CartButton,
        ProductImage,
        Price,
        WishlistButton
    },
    props: {
        product: [Array, Object]
    },
    computed: {
        ...mapGetters(['currency'])
    }
}
</script>