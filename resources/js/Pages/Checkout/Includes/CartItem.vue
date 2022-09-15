<template>
    <tr>
        <td data-label="Product" class="ec-cart-pro-name">
            <Link :href="route('product', item.slug)" class="sidekka_pro_img">
                <product-image :src="(item.image)?$media_url+item.image:$local_media_url+'display-bg-0.png'" :alt="item.name" class-name="ec-cart-pro-img mr-4" ></product-image>
                {{ item.name }} <br> (SKU: {{item.sku}})
            </Link>
        </td>
        <td data-label="Price" class="ec-cart-pro-price">
            <span class="amount">{{ currencyIcon+item.price }}</span> 
        </td>
        <td data-label="Quantity" class="ec-cart-pro-qty" style="text-align: center;">
            <div class="cart-qty-plus-minus">
                <input class="cart-plus-minus" type="text" name="cartqtybutton" readonly v-model="item.quantity">
                <div class="ec_cart_qtybtn">
                   <div class="inc ec_qtybtn"  @click="addInCartItem({id:item.product_id, quantity:1})">+</div>
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
        ...mapGetters(['currency', 'cartItems']),
        currencyIcon() {
             return (this.currency)?this.currency:'Rs. ';
        },
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