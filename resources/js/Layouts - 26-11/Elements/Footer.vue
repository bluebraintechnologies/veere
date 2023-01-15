<template>
  <!-- Footer Start -->
    <footer class="ec-footer">
        <div class="footer-container">
            <div class="footer-top section-space-footer-p">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-12 col-lg-3 ec-footer-cont-social">
                            <div class="ec-footer-contact">
                                <div class="ec-footer-widget">
                                    <h4 class="ec-footer-heading">Address</h4>
                                    <div class="ec-footer-links">
                                        <ul class="align-items-center">
                                            <li class="ec-footer-link ec-foo-location"><span><img
                                                        src="/images/icons/foo-location.svg"
                                                        class="svg_img foo_svg" alt="" /></span>
                                                <p v-html="business.business_add"></p>
                                            </li>
                                            <li class="ec-footer-link ec-foo-call"><span><img
                                                        src="/images/icons/foo-wp.svg" class="svg_img foo_svg"
                                                        alt="" /></span><a :href="'tel:' + business.business_contact">{{ business.business_contact }}</a>
                                            </li>
                                            <li class="ec-footer-link ec-foo-mail"><span><img
                                                        src="/images/icons/foo-mail.svg" class="svg_img foo_svg"
                                                        alt="" /></span><a
                                                    :href="'mailto:' + business.business_email"> {{ business.business_email }}</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="ec-footer-social">
                                <div class="ec-footer-widget">
                                    <h4 class="ec-footer-heading marg-b-0 s-head">Follow Us</h4>
                                    <div class="ec-footer-links">
                                        <ul class="align-items-center">
                                            <li class="ec-footer-link">
                                                <a v-if="business.business_instagram" target="blank" :href="business.business_instagram"><i class="ecicon eci-instagram" aria-hidden="true"></i></a>
                                                <a v-else href="javascript:void(0)"><i class="ecicon eci-instagram" aria-hidden="true"></i></a>
                                            </li>
                                            <li class="ec-footer-link">
                                                <a v-if="business.business_twitter" target="blank" :href="business.business_twitter"><i class="ecicon eci-twitter-square" aria-hidden="true"></i></a>
                                                <a v-else href="javascript:void(0)"><i class="ecicon eci-twitter-square" aria-hidden="true"></i></a>
                                            </li>
                                            <li class="ec-footer-link">
                                                <a v-if="business.business_facebook" target="blank" :href="business.business_facebook"><i class="ecicon eci-facebook-square" aria-hidden="true"></i></a>
                                                <a v-else href="javascript:void(0)"><i class="ecicon eci-facebook-square" aria-hidden="true"></i></a>
                                            </li>
                                            <li class="ec-footer-link">
                                                <a v-if="business.business_linkedin" target="blank" :href="business.business_linkedin"><i class="ecicon eci-linkedin-square" aria-hidden="true"></i></a>
                                                <a v-else href="javascript:void(0)"><i class="ecicon eci-linkedin-square" aria-hidden="true"></i></a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-lg-3 ec-footer-cat">
                            <div class="ec-footer-widget">
                                <h4 class="ec-footer-heading">Information</h4>
                                <div class="ec-footer-links">
                                    <ul class="align-items-center">
                                        <!-- <li class="ec-footer-link"><a href="#">About us</a></li>
                                        <li class="ec-footer-link"><a href="#">FAQ</a></li> -->
                                        <li class="ec-footer-link"><a href="#">Delivery Information</a></li>
                                        <li class="ec-footer-link"><a target="_blank" href="https://agent.veeere.com/">Agent Login</a></li>
                                        <!-- <li class="ec-footer-link"><a href="#">Contact us</a></li> -->
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-lg-3 ec-footer-info">
                            <div class="ec-footer-widget">
                                <h4 class="ec-footer-heading">Account</h4>
                                <div class="ec-footer-links">
                                    <ul class="align-items-center">
                                        <li class="ec-footer-link"><Link :href="route('dashboard')">My Account</Link></li>
                                        <li class="ec-footer-link"><Link :href="route('orders')">Order History</Link></li>
                                        <li class="ec-footer-link"><Link :href="route('wishlist')">Wishlist</Link></li>
                                        <li class="ec-footer-link"><Link :href="route('addresses')">Addresses</Link></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-lg-3 ec-footer-account">
                            <div class="ec-footer-widget">
                                <h4 class="ec-footer-heading">Useful Links</h4>
                                <div class="ec-footer-links">
                                    <ul class="align-items-center">
                                        <li class="ec-footer-link"><Link :href="route('about')">About us</Link></li>
                                        <li class="ec-footer-link"><Link :href="route('terms-and-condistions')">Terms & conditions</Link></li>
                                        <li class="ec-footer-link"><Link :href="route('privacy-policy')">Privacy Policy</Link></li>
                                        <li class="ec-footer-link"><Link :href="route('return-policy')">Return Policy</Link></li>
                                        <li class="ec-footer-link"><Link :href="route('faqs')">FAQs</Link></li>
                                        <li class="ec-footer-link"><Link :href="route('contact-us')">Contact us</Link></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-lg-3 ec-footer-service">
                            <div class="ec-footer-widget">
                                <h4 class="ec-footer-heading">Newsletter</h4>
                                <div class="ec-footer-links">
                                    <ul class="align-items-center">
                                        <li class="ec-footer-link">Get instant updates about our new products and
                                            special promos!</li>
                                    </ul>
                                    <div class="ec-subscribe-form">
                                        <form id="ec-newsletter-form" name="ec-newsletter-form" method="post"
                                            action="#">
                                            <div id="ec_news_signup" class="ec-form">
                                                <input class="ec-email" type="email" required=""
                                                    placeholder="Enter your email here..." name="ec-email" value="" />
                                                <button id="ec-news-btn" class="button btn-primary" type="submit"
                                                    name="subscribe" value=""><i class="ecicon eci-paper-plane-o"
                                                        aria-hidden="true"></i></button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <div class="container">
                    <div class="row">
                        <!-- Footer payment -->
                        <div class="footer-bottom-right">
                            <div class="footer-bottom-payment d-flex justify-content-center">
                                <div class="payment-link">
                                    <img src="/images/icons/payment.png" alt="">
                                </div>

                            </div>
                        </div>
                        <!-- Footer payment -->
                        <!-- Footer Copyright Start -->
                        <div class="footer-copy">
                            <div class="footer-bottom-copy ">
                                <div class="ec-copy">Copyright © 
                                    <Link class="site-name" :href="route('home')">Veeere</Link> 
                                    all rights reserved. Maintained by 
                                    <a href="https://bluebraintech.com" target="_blank">Blue Brain Technologies</a>
                                </div>
                            </div>
                        </div>
                        <!-- Footer Copyright End -->

                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!-- Footer Area End -->
</template>

<script>
import { mapActions, mapGetters } from 'vuex';

export default {
    // emits: ["closeSidebar"],
    components:{
    },
    props: {
        // showCart: [Boolean],
    },
    computed: {
        ...mapGetters(['business'])
    },
    methods: {
         ...mapActions(['getBusinessDetails']),
    },
    mounted(){
        if(this.business.business_address !== 'undefined'){
            this.getBusinessDetails()
        }
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