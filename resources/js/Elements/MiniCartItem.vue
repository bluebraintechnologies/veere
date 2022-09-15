<template>
    <li v-if="product && cartItems.length">
        <Link :href="route('product', product.slug)" class="sidekka_pro_img">
            <product-image class-name="main-image" :imgid="mainImage" :alt="product.name" />
        </Link>
        <div class="ec-pro-content">
            <Link :href="route('product', product.slug)" class="cart_pro_title">{{ product.name }}</Link>
            <span class="cart-price"><span>{{ currencyIcon+item.price }}</span> x {{ productQty }}
            
            <!-- <div class="cart-qty-plus-minus">
                <input class="cart-plus-minus" type="text" name="cartqtybutton" readonly v-model="productQty">
                <div class="ec_cart_qtybtn">
                    <div class="inc ec_qtybtn" >+</div>
                    <div class="dec ec_qtybtn" >-</div>
                </div>
            </div> -->
            </span>
            <div class="qty-plus-minus">
                <div class="dec ec_qtybtn"  @click="decreaseInCartItem(item.id)">-</div>
                <input class="qty-input" type="text" name="cartqtybutton" readonly v-model="productQty">
                <div class="inc ec_qtybtn" @click="addInCartItem({id:product.id, quantity:1})">+</div>
            </div>
            <a href="javascript:void(0)" @click="removeInCartItem(item)" class="remove">×</a>
        </div>
    </li>
</template>
<script>
import ProductImage from '@/Pages/Product/ProductImage.vue';
import { mapActions, mapGetters } from 'vuex';

export default {
    components: {
        ProductImage
    },
    props: {
        item: [Object, Array],
    },
    computed: {
        ...mapGetters(['productItems', 'currency', 'cartItems']),
        currencyIcon() {
            return this.currency;
        },
        product() {
            let prct = this.productItems.filter(ele => {
               return ele.id == this.item.product_id
            })
            return prct[0]
        },
        mainImage() {
            if(this.product.thumbnail_img) {
                let pi = this.product.thumbnail_img.split(',');
                return pi[0]
            } else {
                return 0
            }
        },
        productQty() {
           let pr = this.cartItems.find(prdct => parseInt(prdct.product_id) === this.product.id)
           return (pr)?pr.quantity:0
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