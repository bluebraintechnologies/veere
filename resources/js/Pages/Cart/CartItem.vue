<template>
    <tr>
        <td data-label="Product" class="ec-cart-pro-name">
            <Link :href="route('product', product.slug)">
                <product-image class-name="ec-cart-pro-img mr-4" :imgid="mainImage" :alt="product.name" />
                {{ product.name }}
            </Link>
        </td>
        <td data-label="Price" class="ec-cart-pro-price">
            <span class="amount">{{ currencyIcon+item.price }}</span> 
        </td>
        <td data-label="Quantity" class="ec-cart-pro-qty" style="text-align: center;">
            <div class="cart-qty-plus-minus">
                <input class="cart-plus-minus" type="text" name="cartqtybutton" readonly v-model="productQty">
                <div class="ec_cart_qtybtn">
                    <div class="inc ec_qtybtn"  @click="addInCartItem({id:product.id, quantity:1})">+</div>
                    <div class="dec ec_qtybtn"  @click="decreaseInCartItem(item.id)">-</div>
                </div>
            </div>
        </td>
        <td data-label="Total" class="ec-cart-pro-subtotal">
            <span>{{ currencyIcon }} {{ item.price * item.quantity }}</span>
        </td>
        <td data-label="Remove" class="ec-cart-pro-remove">
            <a href="javascript:;" @click="removeInCartItem(item)"><i class="ecicon eci-trash-o"></i></a>
        </td>
    </tr>
</template>
<script>
import ProductImage from '@/Pages/Product/Elemants/Image.vue';
import { mapActions, mapGetters } from 'vuex';

export default {
    name: 'Cart Item',
    components: {
        ProductImage
    },
    props: {
        item: [Object, Array],
    },
    computed: {
        ...mapGetters(['productItems', 'currency', 'cartItems']),
        currencyIcon() {
            return (this.currency)?this.currency:'Rs. ';
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