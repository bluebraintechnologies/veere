<template>
    <button title="Add To Cart" class="add-to-cart btn btn-primary" @click="addInCartItem({id:product.id, quantity:qty})">
        <img src="/images/icons/plus.png" width="18" class="mr-1" alt="+" /> Add To Cart
    </button>
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
    computed: {
        ...mapGetters(['cartQuantity'])
    },
    
    methods:{
        ...mapActions(['addCartItem']),
        addInCartItem(cid) {
            this.addCartItem([this, cid])
        },

    },
    mounted() {
        
    }
}
</script>