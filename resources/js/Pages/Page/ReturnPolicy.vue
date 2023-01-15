<script>
import GuestLayout from "@/Layouts/Main.vue";
import { mapGetters } from "vuex";
import moment from 'moment';

export default {
    name: "Category",
    components: {
        GuestLayout,
    },
    data() {
        return {
            
        };
    },
    props: {
        
    },
    computed: {
        ...mapGetters(["navItems"]),
        
    },
    methods: {
        
    },
    created(){
        
    },
};
</script>

<template>
    <Head title="Orders and Returns Policy" />

    <GuestLayout>
        <div class="sticky-header-next-sec  ec-breadcrumb section-space-mb">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="row ec_breadcrumb_inner">
                            <div class="col-md-6 col-sm-12">
                                <h2 class="ec-breadcrumb-title">Orders and Returns Policy </h2>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <ul class="ec-breadcrumb-list">
                                    <li class="ec-breadcrumb-item">
                                        <Link :href="route('home')">Home</Link>
                                    </li>
                                     <li class="ec-breadcrumb-item">
                                        Orders and Returns Policy
                                    </li>
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
                    <!-- <div class="col-md-12 text-center">
                        <div class="section-title mb-0">
                            <h4 class="text-center">How Our Story Began</h4>
                            <h2 class="text-center">47 Years of Experience</h2>
                        </div>
                    </div> -->
                    <div class="ec-common-wrapper">
                        
                        <p>Veeere believes in &amp; we can helping its customers as far as possible, and has therefore a liberal cancellation policy.</p>
                        <h5>How do I cancel an order on Veeere?</h5>
                        <p>If unfortunately you have to cancel an order, please do so within 24 hours of placing the order.</p>
                        <p>For outright cancellations by you:</p>
                        <ul>
                            <li>If you cancel your order before your product has shipped, we will refund the entire amount.</li>
                        </ul>
                        <p>If the cancellation is after your product has shipped:</p>
                        <ul>
                            <li>If you received the product, it will be eligible for replacement, only in cases where there are defects found with the product.</li>
                        </ul>
                        <h5>Refund Policy</h5>
                        <p>Refunds will be made in the same form that the payment is received within 10 working days from the date of cancellation of order.</p>
                        <h5>Return Policy</h5>
                        <p>There is no Return in Groceries food Items.</p>
                        <p>If you think, you have received the product in a bad condition or if the packaging is tampered with or damaged before delivery, <b>please refuse to accept the package</b> and return the package to the delivery person or kindly inform us within 2-3 working days. Also, please call our customer care or email us at <b>support@veeere.in</b> mentioning your Order ID. We will personally ensure that a brand new replacement is issued to you with no additional cost. Please make sure that the original product tag and packing is intact when you send us the product back.</p>
                        <p>Apart from condition reserved herein above, the following products shall not be eligible for return or replacement, Viz:</p>
                        <ul>
                            <li>Any product that exhibits physical damage to the box or to the product;</li>
                            <li>Any product that is returned without all original packaging and accessories included with the product at the time of delivery;</li>
                        </ul>
                            
                    </div>                    
                </div>
            </div>
        </section>
    </GuestLayout>
</template>

