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
    <Head title="About Us" />

    <GuestLayout>
        <div class="sticky-header-next-sec  ec-breadcrumb section-space-mb">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="row ec_breadcrumb_inner">
                            <div class="col-md-6 col-sm-12">
                                <h2 class="ec-breadcrumb-title">About us </h2>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <ul class="ec-breadcrumb-list">
                                    <li class="ec-breadcrumb-item">
                                        <Link :href="route('home')">Home</Link>
                                    </li>
                                     <li class="ec-breadcrumb-item">
                                        About Us
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
                    <div class="col-md-12 text-center">
                        <div class="section-title mb-0">
                            <h4 class="text-center">How Our Story Began</h4>
                            <h2 class="text-center">47 Years of Experience</h2>
                        </div>
                    </div>
                    <div class="ec-common-wrapper">
                        <p>S.S India Foods Pvt. Ltd. is a supplier of high grade pulses and flours (Besan, Whole wheat chakki atta, Sattu, etc). The company deals in these products under the brand name "DEEPAK" which has been registered since 1971. The company is a sister concern of “S.S Food Industries” which is registered with Dall and Besan Millers Association, Delhi and has a monthly production capacity exceeding 6000 MT/Month. Our sister concern firm has all the resources and facilities to handle both medium and large scale requirements on an immediate basis. The main aim of S.S India Foods Pvt. Ltd. is to provide the best products to its customers all over India as per their requirements.</p>
                        <p>The food items are manufactured in our sister concern firm (cleaned, de-stoned and packaged) using the most modern methods so that they can meet the highest standards. To ensure the right quality for its customers, the company enforces stringent quality control processes leading to consistency in weight, colour and taste. The entire product range is manufactured and processed in hygienic and dust free environment, cleaned through the best colour sorting and gravity separator machines. We also offer packaging and marking to be done as per our clients’ requirements or even in a neutral way.</p>
                        <p>Our focus is always on maintaining quality. We work relentlessly in ensuring that our customers are delighted with our products and services. We emphasize on high quality products, attractive prices, prompt response and timely delivery. Such high standards of quality have enabled the company to enjoy the trust of customers in the Indian market.</p>
                        <p>Various quality checks are performed at multiple stages, before final packaging; to make sure that the customers receive the best products at economical prices. Strict quality controls are maintained in all the departments so that only the best products reach our customers. The company has its own quality testing facilities that examine different aspects of colour, size, cleaning, etc before dispatching them to the customers.</p>
                        <p>The company is registering a growing client base and is planning to expand its business offshore. The company is guided by able promoters who have more than twenty years of practical experience. "Our success is based on our people - our customers and our team of employees. Our customer relation wing is there to serve you, providing all possible assistance in choosing the products as per your requirements. We are known all over India for our business values and commitments to customers. By consistently supplying quality products, we have earned the loyalty and trust of our customers over the years.” said Mr. Bimal Goenka, Director S.S India Foods Pvt. Ltd.</p>
                        <h3>Vision</h3>
                        <p>The main aim of S.S India Foods Pvt. Ltd. is to provide the best products to its customers all over India as per their requirements.</p>
                        <h3>Services</h3>
                        <p>We are known all over India for our superior services, business values and commitments to customers. Our Customer Relation Wing serves the customers and provides them with all the possible assistance in choosing the products as per their requirements.</p>
                        <h3>Infrastructure</h3>
                        <p>Our success lies in our advanced infrastructure. We are equipped with the best technologies and facilities to offer our customers with premium quality standards across all our product range. Our infrastructure includes:</p>
                        <ul>
                            <li>Modern Plants</li>
                            <li>High Production</li>
                            <li>Advanced Technology</li>
                            <li>Multiple Processing &amp; Cleaning Stages</li>
                            <li>Scientific Storage</li>
                            <li>Quality Control Department</li>
                            <li>Quality Packaging</li>
                            <li>Transportation</li>
                        </ul>
                        <h3>Quality</h3>
                        <p>Our focus is always on maintaining quality. We work relentlessly in ensuring that our customers are delighted with our products and services. We emphasize on high quality products, attractive prices, prompt response and timely delivery. Such high standards of quality have enabled the company to enjoy the trust of customers in the Indian market. Various quality checks are performed at multiple stages, before final packaging; to make sure that the customers receive the best products at economical prices. Strict quality controls are maintained in all the departments so that only the best products reach our customers. The company has its own quality testing facilities that examine different aspects of colour, size, cleaning, etc before dispatching them to the customers.</p>
                    </div>                    
                </div>
            </div>
        </section>
    </GuestLayout>
</template>

