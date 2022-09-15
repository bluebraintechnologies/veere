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
        wishlists:Object|Array,
    },
    computed: {
        ...mapGetters(['wishlistItems'])
    },

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
                </div>
            </div>
            <div class="ec-vendor-card-body">
                <div class="row">
                    <div class="col-lg-4 col-md-6 col-sm-6 col-xs-6 ec-product-content"  v-for="(pc,pk) in wishlists.data" :key="'fproduct'+pk">
                        <single-grid-product :product="pc.product" ></single-grid-product>
                    </div>
                    <pagination class="mt-6" :wishlists="wishlists" />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
