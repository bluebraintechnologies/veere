<script>
import { mapActions, mapGetters } from "vuex";
import CartButton from '@/Pages/User/Common/CartButton.vue';
import WishlistButton from '@/Pages/User/Common/WishlistButton.vue';
import Price from './Price.vue';
import ProductImage from "./ProductImage.vue";
import { Inertia } from '@inertiajs/inertia';

export default {
    name: "Product Single Grid",
    components: { ProductImage, Price, WishlistButton, CartButton },
    props: {
        product: [Object, Array],
    },
    setup() {
        return {};
    },
    computed: {
        ...mapGetters(["currency", "categoryItems"]),
        final_price() {
            var today = new Date();
            var now_time = today.getTime();
            if (this.product) {
                if (
                    now_time <= this.product.discount_end_date &&
                    now_time >= this.product.discount_start_date
                ) {
                    if (this.product.discount_type == "percent") {
                        let fp =
                            (this.product.unit_price * this.product.discount) /
                            100;
                        return this.product.unit_price - fp;
                    } else if (this.product.discount_type == "flat") {
                        return this.product.unit_price - this.product.discount;
                    } else {
                        return this.product.unit_price;
                    }
                } else {
                    return this.product.unit_price;
                }
            } else {
                return "0.00";
            }
        },
        all_colors() {
            if (this.product.colors) {
                return JSON.parse(this.product.colors);
            } else {
                return [];
            }
        },
        all_options() {
            if (this.product.attributes) {
                return JSON.parse(this.product.attributes);
            } else {
                return [];
            }
        },
        mainImage() {
            if(this.product.thumbnail_img) {
                let pi = this.product.thumbnail_img.split(',');
                return pi[0]
            } else {
                return 0
            }
        },
        hoverImage() {
            if(this.product.thumbnail_img)
            {
                let pi = this.product.thumbnail_img.split(',');
                return pi[pi.length - 1]
            } else {
                return 0
            }
        },
        // currency() {
        //     return (this.currency.active_currency)?this.currency.active_currency.symbol+' ':'Rs. ';
        // },
        category() {
            let ct = this.categoryItems.filter(ele => {
                return ele.id == this.product.category_id
            })
            return ct[0]
        }
    },
    methods: {
        ...mapActions(["addCartItem"]),
        deleteWishlistItem(id){
            axios.post('/api/delete-wishlist-item',{id:id}).then((response) => {
                location.reload()
            })
        }
    },
};
</script>
<template>
    <div class="ec-product-inner">
        <div class="ec-pro-image-outer">
            <div class="ec-pro-image">
                <Link :href="route('product', product.slug)" class="image">
                    <product-image :src="(product.image)?$media_url+product.image:$local_media_url+'product-image/50_2.jpg'" :alt="product.name"></product-image>
                </Link>
                <span class="flags" v-if="product.new_tag == 1">
                    <span class="new">New</span>
                </span>
                <div class="ec-pro-actions">
                    <a href="javascript:void(0)" @click="deleteWishlistItem(product.id)"><i class="ecicon eci-trash text-danger"></i></a>
                </div>
                <!-- <div class="ec-pro-actions">
                    <wishlist-button :product="product"></wishlist-button>
                </div> -->
            </div>
        </div>
        <div class="ec-pro-content">

            <h5 class="ec-pro-title">
                <Link :href="route('category',product.category_slug)"> {{ product.category }} </Link>
            </h5>
            <Link :href="route('product',product.slug)">
                <h6 class="ec-pro-stitle">{{ product.name }}</h6>
            </Link>
            <div class="ec-pro-rat-price">
                <price :currency="currency" :price="product.price"></price>
            </div>
            <div class="grid-card-btn">
                <cart-button :product="product"></cart-button>
            </div>
        </div>
    </div>
</template>
<style>
    .ec-pro-actions{
        opacity:1 !important;
        visibility: visible !important;
        top: -36px !important;
    }
</style>