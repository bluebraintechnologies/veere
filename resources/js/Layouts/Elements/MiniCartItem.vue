<template>
    <li>
        <Link :href="route('product', item.slug)" class="sidekka_pro_img">
             <product-image :src="(item.image)?$media_url+item.image:$local_media_url+'product-image/50_2.jpg'" :alt="item.name"></product-image>
        </Link>
        <div class="ec-pro-content">
            <Link :href="route('product', item.slug)" class="cart_pro_title">{{ item.name }}</Link>
            <span class="cart-price"><span>{{ currencyIcon+item.price }}</span> x {{ item.quantity }}</span>
            <div class="qty-plus-minus">
                <div class="dec ec_qtybtn"  @click="decreaseInCartItem(item.id)">-</div>
                <input class="qty-input" type="text" name="cartqtybutton" readonly v-model="productQty">
                <div class="inc ec_qtybtn" @click="addInCartItem({id:item.product_id, quantity:1})">+</div>
            </div>
            <a href="javascript:void(0)" @click="removeInCartItem(item)" class="remove">×</a>
        </div>
    </li>
</template>
<script>
import ProductImage from '@/Pages/Product/Elemants/Image.vue';
import { mapActions, mapGetters } from 'vuex';

export default {
    components: {
        ProductImage
    },
    props: {
        item: [Object, Array],
    },
    computed: {
        ...mapGetters(['currency', 'cartItems']),
        currencyIcon() {
             return (this.currency)?this.currency:'Rs. ';
        },
        productQty() {
           return this.item.quantity
        }
    },
    methods: {
        ...mapActions(['removeCartItem', 'addCartItem', 'decreaseCartItem']),
        addInCartItem(cid) {
            this.addCartItem([this, cid])
        },
        decreaseInCartItem(cid) {
            this.decreaseCartItem([this, cid])
        },
        removeInCartItem(cid) {
            this.removeCartItem([this, cid])
        },
    }
}
</script>