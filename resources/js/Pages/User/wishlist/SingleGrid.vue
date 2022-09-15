<script>
import { mapActions, mapGetters } from "vuex";
import CartButton from '@/Pages/User/Common/CartButton.vue';
import WishlistButton from '@/Pages/User/Common/WishlistButton.vue';
import Price from './Price.vue';
import ProductImage from "./ProductImage.vue";

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
        currency() {
            return (this.currency.active_currency)?this.currency.active_currency.symbol+' ':'Rs. ';
        },
        category() {
            let ct = this.categoryItems.filter(ele => {
                return ele.id == this.product.category_id
            })
            return ct[0]
        }
    },
    methods: {
        ...mapActions(["addCartItem"]),
    },
};
</script>
<template>
    <div class="ec-product-inner">
        <div class="ec-pro-image-outer">
            <div class="ec-pro-image">
                <Link :href="route('product', product.slug)" class="image">
                    <product-image class-name="main-image" :imgid="mainImage" :alt="product.name" />
                    <product-image class-name="hover-image" :imgid="hoverImage" :alt="product.name" />
                </Link>
            </div>
        </div>
        <div class="ec-pro-content">

            <h5 class="ec-pro-title">
                <Link :href="route('product', product.slug)">{{ product.name }}</Link>
            </h5>
            <h6 class="ec-pro-stitle" v-if="category">
                <Link :href="route('products.category', category.slug)">{{ category.name }}</Link>
            </h6>
            <div class="ec-pro-rat-price">
                <div class="ec-pro-rat-pri-inner">
                    <span class="ec-price">
                        <span class="new-price">
                            <price :price="final_price" :currency="currency" />
                        </span>
                        <span class="old-price" v-if="final_price != product.unit_price">
                            <price :price="product.unit_price" :currency="currency" />
                        </span>
                    </span>
                    <!-- <span class="ec-pro-rating">
                        <i class="ecicon eci-star fill"></i>
                        <i class="ecicon eci-star fill"></i>
                        <i class="ecicon eci-star fill"></i>
                        <i class="ecicon eci-star-o"></i>
                        <i class="ecicon eci-star-o"></i>
                    </span> -->
                </div>
            </div>
            <div class="pro-hidden-block">
                <div class="ec-pro-actions">
                    <wishlist-button :product="product"></wishlist-button>
                    <cart-button :product="product"></cart-button>
                </div>
            </div>
        </div>
    </div>
</template>