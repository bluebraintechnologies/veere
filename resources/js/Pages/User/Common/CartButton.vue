<template>
        <div class="qty-plus-minus" v-if="productQty >= 1">
            <div class="dec ec_qtybtn" @click="decreaseInCartItem(cid)">
                <i class="ecicon eci-minus" aria-hidden="true"></i>
            </div>
            <input class="qty-input" type="text" readonly name="ec_qtybtn" v-model="productQty">
            <div class="inc ec_qtybtn" @click="addInCartItem({id:product.id, quantity:1})">
                <i class="ecicon eci-plus" aria-hidden="true"></i>
            </div>
        </div>
        <div class="qty-plus-minus" v-else>
            <a href="javascript:void(0)" class="text-white d-block" @click="addInCartItem({id:product.id, quantity:1})">
                <i class="ecicon eci-plus" aria-hidden="true"></i> Add
            </a>
        </div>
</template>
<script>
import { mapGetters, mapActions } from 'vuex'

export default {
    props: {
        product:Object,
        qty: {
            default: 1,
            type: Number
        }
    },
    data() {
        return {
            
        }
    },
    computed: {
        ...mapGetters(['cartItems']),
        productQty() {
           let pr = this.cartItems.find(prdct => parseInt(prdct.product_id) === this.product.id)
           return (pr)?pr.quantity:0
        },
        cid() {
           let pr = this.cartItems.find(prdct => parseInt(prdct.product_id) === this.product.id)
           return (pr)?pr.id:0
        }
    },
    
    methods:{
        ...mapActions(['addCartItem', 'decreaseCartItem']),
        addInCartItem(cid) {
            this.addCartItem([this, cid])
        },
         decreaseInCartItem(cid) {
            this.decreaseCartItem([this, cid])
        },
    },
    mounted() {
       
    }
}
</script>