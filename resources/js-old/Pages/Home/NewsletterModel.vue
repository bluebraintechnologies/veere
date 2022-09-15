<template>
  <!-- Newsletter Modal Start -->
    <div id="ec-popnews-bg"></div>
    <div id="ec-popnews-box">
        <div id="ec-popnews-close"><i class="ecicon eci-close"></i></div>
        <div class="row">
            <div class="col-md-6 disp-no-767">
                <img src="assets/images/banner/newsletter-7.png" alt="newsletter">
            </div>
            <div class="col-md-6">
                <div id="ec-popnews-box-content">
                    <h2>Stay home, stay safe & get your daily needs from Ekka shop.</h2>
                    <p>Subscribe the ekka ecommerce to get in touch and get the future update. </p>
                    <form id="ec-popnews-form" action="#" method="post">
                        <input type="email" name="newsemail" placeholder="Email Address" required />
                        <button type="button" class="btn btn-primary" name="subscribe">Subscribe</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Newsletter Modal end -->
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