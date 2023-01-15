<template>
    <a class="ec-btn-group wishlist-icon" title="Wishlist" @click="removeWishlistItem(wished[0])" v-if="($page.props.auth.user) && (wished.length >= 1)">
        <img src="/images/icons/checklist-checked-box.png" class="svg_img checklist_svg" alt="" />
    </a>
    <a class="ec-btn-group wishlist-icon" title="Wishlist" @click="addWishlistItem(product)" v-else-if="($page.props.auth.user) && (wished.length == 0)">
        <!-- <i class="ecicon eci-heart-o eci-1x"></i> -->
        <img src="/images/icons/check-box.png" class="svg_img checklist_svg" alt="" />
    </a>
    <a class="ec-btn-group wishlist-icon" title="Wishlist" @click="showAlert()" v-else>
        <!-- <i class="ecicon eci-heart-o eci-1x"></i> -->
        <img src="/images/icons/check-box.png" class="svg_img checklist_svg" alt="" />
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
<style>
.wishlist-icon{
    position:absolute;
    right:7px;
    top: 7px;
}
</style>