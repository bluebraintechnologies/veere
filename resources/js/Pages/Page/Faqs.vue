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
    <Head title="Frequently Asked Questions" />

    <GuestLayout>
        <div class="sticky-header-next-sec  ec-breadcrumb section-space-mb">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="row ec_breadcrumb_inner">
                            <div class="col-md-6 col-sm-12">
                                <h2 class="ec-breadcrumb-title">Frequently Asked Questions </h2>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <ul class="ec-breadcrumb-list">
                                    <li class="ec-breadcrumb-item">
                                        <Link :href="route('home')">Home</Link>
                                    </li>
                                     <li class="ec-breadcrumb-item">
                                        FAQs
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
                        
                            <h5>Where do you deliver?</h5>
                            <p>At the present time, Veeere delivers only in Delhi City.</p>
                            <p>&nbsp;</p>
                            <h5>What about the home delivery services in COVID-19 lockdown time?</h5>
                            <p>We are giving our best to deliver the groceries on time, but because of Covid-19 rules, the delivery time may delay by a day.</p>
                            <p>&nbsp;</p>
                            <h5>How do I order at Veeere?</h5>
                            <p>Placing an order is very simple. Just register on the veeere website/mobile application, pick your choice of products with a wide range of product selection in the online store and proceed to checkout.</p>
                            <p>&nbsp;</p>
                            <h5>Do I have to necessarily register with Veeere to place an order?</h5>
                            <p>No, you can order online on <a href="https://veeere.com/">veeere.com</a>&nbsp;the website as a guest user. To use the app you need to be registered.</p>
                            <p>&nbsp;</p>
                            <h5>How do I cancel an order?</h5>
                            <p>You can cancel an order online within &amp; days of the order placed.</p>
                            <p>&nbsp;</p>
                            <h5>Is there any additional charge if I cancel an order?</h5>
                            <p>NO, we don't charge anything extra.</p>
                            <p>&nbsp;</p>
                            <h5>How do I know what time I will receive my delivery?</h5>
                            <p>Our representative will call you before delivery. We aim to deliver your order between 6 am to 9 pm every day of the previous day's order.</p>
                            <p>&nbsp;</p>
                            <h5>Are you open every day?</h5>
                            <p>We are open 365 days and deliver in allotted time every day; exceptional days would be Strikes &amp; Natural calamity.</p>
                            <p>&nbsp;</p>
                            <h5>What do I do if I forget my password?</h5>
                            <p>Click the Veeere log-in page, click on "forgot password?" You will then be given a chance to enter your e-mail address and have mail sent to you for the new password.</p>
                            <p>&nbsp;</p>
                            <h5>Can I change my email address?</h5>
                            <p>Veeere allows you to update your account information from time to time with your email address, ship-to address by clicking on "My Account" and then update your details. Please remember this is only the place you can change your billing address if you have recently changed your locality.</p>
                            <p>&nbsp;</p>
                            <h5>What are the modes of payment?</h5>
                            <p>Cash on Delivery, Swipe your card on delivery, or Online Payment.</p>
                            <p>&nbsp;</p>
                            <h5>How I will get my money back if I cancelled the order?</h5>
                            <p>Once you cancel the order, you will get the confirmation mail from Veeere and within 6 - 8 working days your amount will refund in your source account through you did the payment.</p>
                            <p>&nbsp;</p>
                            <h5>Are there any delivery charges or minimum order quantity?</h5>
                            <p>Shipping Charges is 70/- Rs Per Order.</p>
                            <p>&nbsp;</p>
                            <h5>Can I return the products that are ordered?</h5>
                            <p>Yes, you can! You will have 7 days from the time of order placed to return the products.</p>
                            <p>&nbsp;</p>
                            <h5>What About COVID-19 related precautions?</h5>
                            <p>We take special precautions while packaging and delivery of products and it's on our high priority to take all precautions first. We maintain the special distancing while packaging and delivering to users' doorsteps.</p>
                            <p>&nbsp;</p>
                            <h5>Is it necessary for me to register an account in order to buy an item?</h5>
                            <p>Yes and this is made with your own convenience in mind. By creating (or using) an account during the purchasing process you will have 3 things made so much easier for yourself:</p>
                            <ul>
                            <li>Keeping track of your order's delivery status.</li>
                            <li>Changing or cancelling the order.</li>
                            <li>Using your previous purchase history to become eligible for a Loyal Customer's discount.</li>
                            </ul>
                            
                    </div>                    
                </div>
            </div>
        </section>
    </GuestLayout>
</template>

