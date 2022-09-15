<template>
    <Head title="Cart" />

    <GuestLayout>
        <div class="sticky-header-next-sec  ec-breadcrumb section-space-mb">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="row ec_breadcrumb_inner">
                            <div class="col-md-6 col-sm-12">
                                <h2 class="ec-breadcrumb-title">Checkout</h2>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <ul class="ec-breadcrumb-list">
                                   <li class="ec-breadcrumb-item">
                                        <a :href="route('home')">Home</a>
                                    </li>
                                    <li class="ec-breadcrumb-item active">Checkout</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <section class="ec-page-content section-space-p">
            <div class="container">
                <div class="row">
                    <div class="ec-cart-leftside col-lg-12 col-md-12">
                       <div class="text-center py-4 mb-4">
                            <img src="/images/check.png" alt="success" />
                            <h1 class="mt-4 h3 mb-3 fw-600">Thank You for Your Order!</h1>
                            <p class="opacity-70 font-italic mb-3">
                                A copy of your order summary has been sent to your registered email.
                            </p>
                            <p class="opacity-70 mb-4">
                                <Link :href="route('dashboard')">Click Here to view your orders</Link>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </GuestLayout>
</template>
<script>
import GuestLayout from "@/Layouts/Main.vue";

export default {
    emits: ["closeSidebar"],
    data() {
        return {
            loading:false,
        }
    },
    components: { GuestLayout },
    props: {
        combined_order:[Array, Object]
    },
    methods: {
        
    },
   
};
</script>