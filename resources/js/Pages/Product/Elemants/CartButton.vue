<template>
        <div class="qty-plus-minus" v-if="productQty >= 1">
            <div class="dec ec_qtybtn" @click="decreaseInCartItem(cid)">
                <MinusIcon />
            </div>
            <input class="qty-input" type="text" readonly name="ec_qtybtn" v-model="productQty">
            <div class="inc ec_qtybtn" @click="addInCartItem({id:product.id, quantity:1, dealsProduct: dealsProduct, dealsProductDiscount: dealsProductDiscount})">
                <PlusIcon />
            </div>
        </div>
        <div class="qty-plus-minus" v-else>
            <a href="javascript:void(0)" class=" d-block cart-button" @click="addInCartItem({id:product.id, quantity:1, dealsProduct: dealsProduct, dealsProductDiscount: dealsProductDiscount})">
                ADD TO CART <span class="qty-nav-icon"><PlusIcon height="26px" width="26px" /></span>
            </a>
        </div>
</template>
<script>
import MinusIcon from '@/Components/MinusIcon.vue';
import PlusIcon from '@/Components/PlusIcon.vue'
import { mapGetters, mapActions } from 'vuex'

export default {
    props: {
        product: Object,
        qty: {
            default: 1,
            type: Number
        }
    },
    data() {
        return {};
    },
    computed: {
        ...mapGetters(["cartItems", "dealsProduct", "dealsProductDiscount", 'showCustomerLocationFormA']),
        productQty() {
            let pr = this.cartItems.find(prdct => parseInt(prdct.product_id) === this.product.id);
            return (pr) ? pr.quantity : 0;
        },
        cid() {
            let pr = this.cartItems.find(prdct => parseInt(prdct.product_id) === this.product.id);
            return (pr) ? pr.id : 0;
        }
    },
    methods: {
        ...mapActions(["addCartItem", "decreaseCartItem"]),
        addInCartItem(cid) {
            let location
            if(localStorage.getItem("location")){
                location = localStorage.getItem("location")
                this.addCartItem([this, cid]);
            }else{
                // this.showCustomerLocationFormA = true
                this.$store.commit('PINCODE_FORM', false)
            }
        },
        decreaseInCartItem(cid) {
            this.decreaseCartItem([this, cid]);
        },
    },
    mounted() {
    },
    components: { PlusIcon, MinusIcon }
}
</script>