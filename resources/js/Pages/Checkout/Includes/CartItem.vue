<template>
    <tr>
        <td data-label="Product" class="ec-cart-pro-name">
            <Link :href="route('product', item.slug)" class="sidekka_pro_img">
                <product-image :src="(item.image)?$media_url+item.image:$local_media_url+'display-bg-0.png'" :alt="item.name" class-name="ec-cart-pro-img mr-4" ></product-image>
                {{ item.name }} <br> (SKU: {{item.sku}})
            </Link>
            
            <span class="total-discount text-success" v-if="item.discount + item.coupan_discount > 0">
                <!-- {{ currencyIcon }}{{ item.discount + item.coupan_discount }} off -->
                <price :price="item.discount + item.coupan_discount" :currency="currencyIcon"></price> off
            </span>
            <span class="offer-discount text-light item-offer-btn-success" v-if="item.coupan_discount > 0"> 1 Offer </span>
        </td>
        <td data-label="Price" class="ec-cart-pro-price" style="display:flex; flex-direction: column;">                
            <span class="item-old-price text-light" v-if="item.discount > 0">
                <!-- <small>{{ currencyIcon }} {{ item.oldPrice }}</small> -->
                <price :price="item.oldPrice" :currency="currencyIcon"></price>
            </span>
                <!-- <span class="amount">{{ currencyIcon }} {{ item.price - item.coupan_discount }}</span>  -->
            <price :price="item.price - item.coupan_discount" :currency="currencyIcon"></price>
        </td>
        <td data-label="Quantity" class="ec-cart-pro-qty" style="text-align: center;">
            <div class="cart-qty-plus-minus">
                <input class="cart-plus-minus" type="text" name="cartqtybutton" readonly v-model="item.quantity">
                <div class="ec_cart_qtybtn">
                   <div class="inc ec_qtybtn"  @click="addInCartItem({id:item.product_id, quantity:1})">+</div>
                    <div class="dec ec_qtybtn" v-if="item.quantity > 0" @click="decreaseInCartItem(item.id)">-</div>
                </div>
            </div>
        </td>
        <td data-label="Total" class="ec-cart-pro-subtotal">
            <!-- <span>{{ currencyIcon }} {{ item.price * item.quantity }}</span> -->
            <price :price="item.price * item.quantity" :currency="currencyIcon"></price>
        </td>
        <td data-label="Remove" class="ec-cart-pro-remove">
            <a href="javascript:;" @click="removeInCartItem(item)"><i class="ecicon eci-trash-o"></i>
                <i class="fa-thin fa-xmark"></i>
            </a>
        </td>
    </tr>
</template>
<script>
import ProductImage from '@/Pages/Product/Elemants/Image.vue';
import { mapActions, mapGetters } from 'vuex';
import Price from '@/Pages/Product/Elemants/Price.vue';
export default {
    name: 'Cart Item',
    components: {
        ProductImage,
        Price,
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
        ...mapActions(['removeCartItem', 'addCartItem', 'decreaseCartInItem']),
        addInCartItem(cid) {
            this.addCartItem([this, cid])
        },
        decreaseInCartItem(cid) {
            
            this.decreaseCartInItem([this, cid])
            
        },
        removeInCartItem(cid) {
            this.$swal.fire({
                title: 'Are you sure?',
                text: "You want to remove this item from cart",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, remove it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.removeCartItem([this, cid])
                }
            })
        },
    }
}
</script>
<style>
    .total-discount{
        color:#12d612 !important;
    }
    .item-old-price{
        background: #f7bdbd;
        border: 1px solid #ff4b4b;
        padding: 2px 5px;
        border-radius: 5px;
        /* margin: 0 10px; */
        width: fit-content;
        text-decoration: line-through;
    }
    .item-offer-btn-success{
        background: #2ce38e;
        border-radius: 3px;
        padding: 2px 5px;
        margin: 2px 5px;
    }
    .old-price{
        text-decoration: line-through;
        display:block;
    }
    .amount{    
        display:block;
    }
</style>