<script>
import AuthenticatedLayout from "@/Layouts/Authenticated.vue";
import { mapActions, mapGetters } from 'vuex';
import SingleGridProduct from "@/Pages/User/wishlist/SingleGrid.vue";
import { Inertia } from '@inertiajs/inertia';
import Pagination from './Common/Pagination'

export default {
    name: "Wishlist",
    components: {
        AuthenticatedLayout,
        SingleGridProduct,
        Pagination,
    },
    props:{
        // wishlists:Object|Array,
    },
    data(){
        return {
            allWishlistItems : [],
        }
    },
    computed: {
        ...mapGetters(['wishlistItems'])
    },
    methods: {
        ...mapActions(['getWishlistItems', 'removeWishlistItem', 'addCartItem', 'decreaseCartItem']),
        getWishlistItems(){
            axios.get('/api/get-wishlist-items').then((response) => {
                this.allWishlistItems = response.data
            })
        },
        checklistToCart(){
            this.allWishlistItems.forEach((ele) => {
                this.addCartItem([this, {id : ele.id, quantity : 1}])
                
            })
        },
        refreshList(){
            this.getWishlistItems()
        }
    },
    mounted() {
        this.getWishlistItems()
    }

};
</script>
<template>
    <Head title="User Wishlist" />
    <AuthenticatedLayout>
        <div class="ec-vendor-dashboard-card">
            <div class="ec-vendor-card-header">
                <h5>Your Wishlist</h5>
                <div class="ec-header-btn">
                    <Link class="btn btn-lg btn-primary" :href="route('home')">Shop Now</Link>
                    <!-- <button type="button" class="btn btn-lg btn-primary" @click="checklistToCart()">Reorder</button> -->
                </div>
            </div>
            <div class="ec-vendor-card-body">
                <div class="row">
                    <div class="col-lg-4 col-md-6 col-sm-6 col-xs-6 ec-product-content"  v-for="(pc,pk) in allWishlistItems" :key="'fproduct'+pk">
                        <single-grid-product @referesh-checklist="refreshList()" :product="pc" ></single-grid-product>
                    </div>
                    <!-- <pagination class="mt-6" :wishlists="wishlists" /> -->
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
<style>
    .ec-product-content{
        width:25%;
    }
</style>
