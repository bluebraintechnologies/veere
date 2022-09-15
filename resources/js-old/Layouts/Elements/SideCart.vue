<template>
    <!-- Ekka Cart Start -->
    <div class="ec-side-cart-overlay" style="display:block" v-if="cartStatus"></div>
    <div id="ec-side-cart" class="ec-side-cart" :class="{'ec-open':cartStatus}">
        <div class="ec-cart-inner">
            <div class="ec-cart-top">
                <div class="ec-cart-title">
                    <span class="cart_title">My Cart</span>
                    <button class="ec-close" type="button" @click="closeSidebar">×</button>
                </div>
                <ul class="eccart-pro-items">
                    <li>
                        <a href="product-left-sidebar.html" class="sidecart_pro_img"><img
                                src="assets/images/product-image/55_1.jpg" alt="product"></a>
                        <div class="ec-pro-content">
                            <a href="single-product-left-sidebar.html" class="cart_pro_title">Roasted Almonds, Pumpkin Snacks</a>
                            <span class="cart-price"><span>$49.00</span> x 1</span>
                            <div class="qty-plus-minus">
                                <input class="qty-input" type="text" name="ec_qtybtn" value="1" />
                            </div>
                            <a href="#" class="remove">×</a>
                        </div>
                    </li>
                    <li>
                        <a href="product-left-sidebar.html" class="sidecart_pro_img"><img
                                src="assets/images/product-image/64_1.jpg" alt="product"></a>
                        <div class="ec-pro-content">
                            <a href="product-left-sidebar.html" class="cart_pro_title">Lemon - Seedless</a>
                            <span class="cart-price"><span>$8.00</span> x 1</span>
                            <div class="qty-plus-minus">
                                <input class="qty-input" type="text" name="ec_qtybtn" value="1" />
                            </div>
                            <a href="#" class="remove">×</a>
                        </div>
                    </li>
                    <li>
                        <a href="product-left-sidebar.html" class="sidecart_pro_img"><img
                                src="assets/images/product-image/71_1.jpg" alt="product"></a>
                        <div class="ec-pro-content">
                            <a href="product-left-sidebar.html" class="cart_pro_title">lichi - organic</a>
                            <span class="cart-price"><span>$25.00</span> x 1</span>
                            <div class="qty-plus-minus">
                                <input class="qty-input" type="text" name="ec_qtybtn" value="1" />
                            </div>
                            <a href="#" class="remove">×</a>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="ec-cart-bottom">
                <div class="cart-sub-total">
                    <table class="table cart-table">
                        <tbody>
                            <tr>
                                <td class="text-left">Sub-Total :</td>
                                <td class="text-right">$300.00</td>
                            </tr>
                            <tr>
                                <td class="text-left">VAT (20%) :</td>
                                <td class="text-right">$60.00</td>
                            </tr>
                            <tr>
                                <td class="text-left">Total :</td>
                                <td class="text-right primary-color">$360.00</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="cart_btn">
                    <a href="cart.html" class="btn btn-primary">View Cart</a>
                    <a href="checkout.html" class="btn btn-secondary">Checkout</a>
                </div>
            </div>
        </div>
    </div>
    <!-- Ekka Cart End -->
</template>
<script>

export default {
    emits: ["closeSidebar"],
    // emits: ["closeSidebar"],
    components:{
      
    },
    props: {
        showCart: [Boolean],
    },
    computed: {
        cartStatus() {
            return this.showCart;
        },
    },
    setup(props, { emit }) {
        const closeSidebar = () => {
            emit("closeSidebar");
        };
        return {
            closeSidebar,
        };
    },
};
</script>