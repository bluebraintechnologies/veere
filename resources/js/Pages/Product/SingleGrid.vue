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
                <div class="ec-pro-actions">
                    <wishlist-button :product="product"></wishlist-button>
                </div>
            </div>
        </div>
        <div class="ec-pro-content">
            <h5 class="ec-pro-title">
                <Link :href="route('category',product.category_slug)"> {{ product.category }} </Link>
            </h5>
            <Link :href="route('product',product.slug)">
                <h6 class="ec-pro-stitle">{{ product.name }}</h6>
            </Link>
            <div class="ec-pro-rat-price">
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