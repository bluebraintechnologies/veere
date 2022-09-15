<template>
  <!-- Footer navigation panel for responsive display -->
    <div class="ec-nav-toolbar">
        <div class="container">
            <div class="ec-nav-panel">
                <div class="ec-nav-panel-icons">
                    <a href="#ec-mobile-menu" class="navbar-toggler-btn ec-header-btn ec-side-toggle"><img
                            src="assets/images/icons/menu.svg" class="svg_img header_svg" alt="icon" /></a>
                </div>
                <div class="ec-nav-panel-icons">
                    <a href="#ec-side-cart" class="toggle-cart ec-header-btn ec-side-toggle"><img
                            src="assets/images/icons/cart.svg" class="svg_img header_svg" alt="icon" /><span
                            class="ec-cart-noti ec-header-count ec-cart-count cart-count-lable">3</span></a>
                </div>
                <div class="ec-nav-panel-icons">
                    <a href="index.html" class="ec-header-btn"><img src="assets/images/icons/home.svg"
                            class="svg_img header_svg" alt="icon" /></a>
                </div>
                <div class="ec-nav-panel-icons">
                    <a href="wishlist.html" class="ec-header-btn"><img src="assets/images/icons/wishlist.svg"
                            class="svg_img header_svg" alt="icon" /><span class="ec-cart-noti">4</span></a>
                </div>
                <div class="ec-nav-panel-icons">
                    <a href="#ec-mobile-sidebar" class="ec-header-btn ec-sidebar-toggle d-lg-none">
                        <img src="assets/images/icons/category-icon.svg" class="svg_img header_svg" alt="icon" />
                    </a>
                </div>

            </div>
        </div>
    </div>
    <!-- Footer navigation panel for responsive display end -->
</template>

<script>

export default {
    // emits: ["closeSidebar"],
    components:{
    },
    props: {
        // showCart: [Boolean],
    },
    computed: {
        // cartStatus() {
        //     return this.showCart;
        // },
    },
    setup(props, { emit }) {
        // const closeSidebar = () => {
        //     emit("closeSidebar");
        // };
        // return {
        //     closeSidebar,
        // };
    },
};
</script>