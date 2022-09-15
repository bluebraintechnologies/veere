<template>
  <!-- Recent Purchase Popup  -->
    <div class="recent-purchase">
        <img src="assets/images/product-image/53_1.jpg" alt="payment image">
        <div class="detail">
            <p>Someone in new just bought</p>
            <h6>Mixed Nut Dry Fruits</h6>
            <p>10 Minutes ago</p>
        </div>
        <a href="javascript:void(0)" class="icon-btn recent-close">×</a>
    </div>
    <!-- Recent Purchase Popup end -->
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