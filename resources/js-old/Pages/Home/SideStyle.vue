<template>
  <!-- Click To Call -->
    <div class="ec-cc-style cc-right-bottom">
        <!-- Start Floating Panel Container -->
        <div class="cc-panel">			
            <!-- Panel Content -->
            <div class="cc-header">
                <img src="assets/images/whatsapp/profile_01.jpg" alt="profile image"/>
                <h2>John Mark</h2>
                <p>Tachnical Manager</p>
            </div>
            <div class="cc-body">
                <p><b>Hey there &#128515;</b></p>
                <p>Need help ? just give me a call.</p>
            </div>
            <div class="cc-footer">
                <a href="tel:+919099153528" class="cc-call-button">
                    <span>Call me</span>
                    <svg width="13px" height="10px" viewBox="0 0 13 10">
                    <path d="M1,5 L11,5"></path>
                    <polyline points="8 1 12 5 8 9"></polyline>
                    </svg>
                </a>
            </div>
        </div>
        <!--/ End Floating Panel Container -->

        <!-- Start Right Floating Button-->
        <div class="cc-button cc-right-bottom">
            <img src="assets/images/icons/call.svg" class="svg_img cc-call-svg" alt="call image" />
        </div>
        <!--/ End Right Floating Button-->

    </div>
    <!-- Click To Call end -->
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