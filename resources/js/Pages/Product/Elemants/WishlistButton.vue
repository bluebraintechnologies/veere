<template>
    <a class="ec-btn-group wishlist-icon" title="Wishlist" @click="removeWishlistItem(wished[0])" v-if="($page.props.auth.user) && (wished.length >= 1)">
        <i class="ecicon eci-heart text-danger eci-1x"></i>
    </a>
    <a class="ec-btn-group wishlist-icon" title="Wishlist" @click="addWishlistItem(product)" v-else-if="($page.props.auth.user) && (wished.length == 0)">
        <!-- <i class="ecicon eci-heart-o eci-1x"></i> -->
        <i class="ecicon eci-heart-o eci-1x"></i>
    </a>
    <a class="ec-btn-group wishlist-icon" title="Wishlist" @click="showAlert()" v-else>
        <!-- <i class="ecicon eci-heart-o eci-1x"></i> -->
        <i class="ecicon eci-heart-o eci-1x"></i>
    </a>
</template>
<script>
import { mapActions, mapGetters } from 'vuex'

export default {
    props: {
        product:Object
    },
    computed:{
        ...mapGetters(['wishlistItems']),
        wished() {
            return this.wishlistItems.filter((ele) => {
                return ele.product_id == this.product.id
            })
        }
    },
    methods:{
        ...mapActions(['getWishlistItems', 'addWishlistItem', 'removeWishlistItem']),
        showAlert() {
            this.$toast.open({
                message: 'Please login to add this product in your wishlist',
                type: 'error',
            });
        },
    },
}
</script>