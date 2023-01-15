<script>
import AuthenticatedLayout from "@/Layouts/Authenticated.vue";
import { mapActions, mapGetters } from 'vuex';
import { Inertia } from '@inertiajs/inertia';
import SingleChecklistItem from './Common/SingleChecklistItem.vue'
export default {
    name: "Wishlist",
    components: {
        AuthenticatedLayout,
        SingleChecklistItem,
    },
    props:{
        // wishlists:Object|Array,
    },
    data(){
        return {
            products : [],
            search: '',
        }
    },
    computed: {
        ...mapGetters(['wishlistItems'])
    },
    methods: {
        ...mapActions(['removeWishlistItem', 'addCartItem', 'decreaseCartItem']),
        search_product(){
            axios.post('/api/get-product-list', {search : this.search}).then((response) => {
                this.products = response.data.products
            })
        }
    },
    mounted() {
        // this.search = 'atta'
        // this.search_product()
    }

}
</script>
<template>
    <Head title="User Checklist" />
    <AuthenticatedLayout>
        <div class="ec-vendor-dashboard-card">
            <div class="ec-vendor-card-header">
                <h5>Your Grocery-list</h5>
                <div class="ec-header-btn">
                    <Link class="btn btn-lg btn-primary" :href="route('home')">Shop Now</Link>
                </div>
            </div>
            <div class="ec-vendor-card-body">
                <div class="row">
                    <div class="col-12">
                        <div class="input-group flex-nowrap">
                            <input type="text" v-model="search" class="form-control" />
                            <button class="input-group-text" @click="search_product()">Search</button>
                        </div>
                    </div>
                </div>
                <div class="shop-pro-content mt-6">
                    <div class="shop-pro-inner list-view">
                        <div class="row" v-if="products.length > 0">
                            <SingleChecklistItem v-for="(product , index) in products" :key="'pro-' + index" :product="product"></SingleChecklistItem>                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
