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
    <Head title="Privacy Policy" />

    <GuestLayout>
        <div class="sticky-header-next-sec  ec-breadcrumb section-space-mb">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="row ec_breadcrumb_inner">
                            <div class="col-md-6 col-sm-12">
                                <h2 class="ec-breadcrumb-title">Privacy Policy </h2>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <ul class="ec-breadcrumb-list">
                                    <li class="ec-breadcrumb-item">
                                        <Link :href="route('home')">Home</Link>
                                    </li>
                                     <li class="ec-breadcrumb-item">
                                        Privacy Policy
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
                            
                        <p>At Veeere, we are committed to protecting your privacy. We developed this Privacy Policy to inform you about how we treat the information that we collect about you when you use the Services. By using the Services, you consent to the practices described in this Privacy Policy.</p>
                        <h5>Updates to Privacy Policy</h5>
                        <p>Veeere reserves the right, at any time, to modify this Privacy Policy. By continuing to use the Services following such modifications, you agree to be bound by such modifications. We will post our most current Privacy Policy on this page, and you should periodically visit this page to review it.</p>
                        <h5>Information that We Collect from You</h5>
                        <p>You may be able to use some of our Services without providing any personal information to us. However, in order to take advantage of certain features, offerings, or other functions of our Services, you may be asked or required to provide personal information. For example, depending on the feature or service you wish to access, we may ask you to register an account with us or otherwise collect information from you such as your name, address, e-mail address, telephone number, credit card information, household information, demographic information, and/or information about your interests. You can always choose to not provide us with the requested information; however, if you choose to not provide such information, you may not be able to access or use certain features or functions of the Services.</p>
                        <p>To the extent that you provide us with a mobile telephone number you hereby:</p>
                        <ul>
                            <li>represent that you are the accountholder and subscriber for the mobile telephone number that you provide, or that you have the accountholder and subscriber’s permission to receive text messages and/or phone calls at that telephone number.</li>
                            <li>agree to notify us promptly if the mobile telephone number you have provided changes</li>
                            <li>agree to indemnify us in full for all claims, expenses, costs, and damages related to, arising under, or caused by, in whole or in part, your failure to notify us promptly if the phone number you have provided changes, including but not limited to, all claims, expenses, costs, and damages related to or arising under the Telephone Consumer Protection Act</li>
                            <li>understand and agree that based on your plan with your mobile carrier, normal usage rates and fees, including data fees and text message rates, may apply to text messages and/or phone calls delivered by  Veeere behalf.</li>
                        </ul>
                        <h5>Information We Collect from Other Sources</h5>
                        <p>We may also receive information about you from other sources, including third parties that help us: update, expand, and analyse our records; identify new customers; and prevent or detect fraud. We may also receive information about you from social media platforms including but not limited to when you interact with us on those platforms, access our social media content, or use your social media login credentials to access our Services. The information we will be able to receive will be determined by the privacy settings, policies, and/or procedures of the applicable social media platform. </p>
                        <h5>Information We Collect Automatically</h5>
                        <p>When you use the Services, we may collect information about how you access, use, or otherwise interact with the Services, our emails, and online advertising. We do so for various reasons, including to help ensure that the Services continue to work correctly, to improve the Services, to support marketing campaigns, to understand our customers and how they use the Services, and to offer you a more personalized experience. The information we collect may include technical information such as your IP address, the type of device that you use to access the Services, the operating system on your device, the website or other online service that directed you to our Services, and the content that you view on the Services. For more information about how we collect this information, please see our Cookies and Other Tracking Technologies section below.</p>
                        <h5>Information Collected from Mobile Devices</h5>
                        <p>When you access our Services via a mobile device, we may collect various information, including the type of mobile device you use, the temporary or persistent device identifier associated with your device, the IP address of your mobile device, the device’s operating system, the browser you use to access the Services, information about how you use the Services, and your device’s location as described below.</p>
                        <p>You may be able to use our mobile applications to scan barcodes or take images of products or prescription bottles in order to create shopping lists or conduct transactions. To do so, our mobile applications may request permission to access your camera and photo files. Please note that some features of our mobile applications and/or Services may not be usable or available to you if you do not grant us such permission. </p>
                        <h5>Location Information</h5>
                        <p>Our mobile applications and websites may also collect precise information about the location of your device after you permit us to obtain such information from technologies such as Bluetooth beacons, GPS, Wi-Fi signals, or cell tower signals. We may also use technologies to determine if you are shopping in one of our stores by relying on signals transmitted by your mobile devices. We collect this information to provide you with services, content and offers based on your location. For example, we may use our mobile applications to provide you with the location of nearby stores and deals available in those stores. You may be able to enable our Services to notify us when you enter one of our stores so that we can prepare any orders you may have or to let you know about the events and deals available in the store. To opt out of the collection of precise location or presence information, you can adjust the permissions on your mobile device or uninstall our mobile applications.</p>
                        <h5>How Do We Use Your Information?</h5>
                        <ul>
                            <li>We may use the information we collect to deliver promotional and marketing materials to you via physical mail and electronically regarding Food Bazaar and its products and services.
                            </li>
                            <li>We may use the information we collect: to fulfil your requests for our products, programs, or services; to respond to your inquiries; to offer you other products, programs, or services that we believe may be of interest to you; to survey you; and otherwise for the purposes for which you provided the information. 
                            </li>
                            <li>We may combine the information we collect from your use of the Services with information that we collect from other sources.
                            </li>
                            <li>We may use any of the information we collect for research, market studies, or to enhance the products and services we provide to you. 
                            </li>
                            <li>We may use the information we automatically collect from you to deliver marketing communications, special offers, or advertisements that may be of interest to you, including the delivery of targeted advertisements that are based on your previous online activity on the Services and other third-party services (for example, if you view a page on our website about a particular product, you may receive an online advertisement for that product or a related product on a different area of our Services or on other websites on the Internet you visit).
                            </li>
                            <li>We may share information with our partners and advertisers for our legitimate business purposes, but we do not sell or rent your personal information to outside parties.
                            </li>
                            <li>We may share anonymised information with our partners and advertisers. Anonymised information is information that is altered, combined, or otherwise processed so that it can’t reasonably be identified with an individual person.
                            </li>
                            <li>We may use information we collect to create personalized content, offers, services, and advertising. This includes personalized content based on the precise location information we collect from mobile devices or the location that we infer from your IP address or other information. 
                            </li>
                            <li>We may disclose the information to third parties when we are required to do so by any law, regulation, court order, subpoena, law enforcement, or other regulatory requirement. 
                            </li>
                            <li>We may disclose the information if we believe that such disclosure is necessary or appropriate: to comply with applicable law; to operate the Services; to protect the rights, property or safety of Veeere, other users of the Services, partners, affiliates, sponsors, providers, licensors, merchants, or others; to prevent harm or loss; or in connection with an investigation of suspected or actual unlawful activity.
                            </li>
                        </ul>
                        <h5>Public Information</h5>
                        <p>If you post comments, pictures, or other content via public forums that may be available on the Services, that information may be publicly available. The information may be searchable by search engines and copied or republished by third parties. We have no control over those third parties. Please think carefully before you post any information via the Services.</p>
                        <p><b>Your Options Regarding the Information that We Collect</b> You may choose to: </p>
                        <ul>
                            <li>Stop receiving marketing or promotional e-mails or direct mailing.</li>
                            <li>Update and correct the information that you provide us</li>
                            <li>Request that we cancel your account or that we no longer use the information you have given us to provide you services.</li>
                        </ul>                   
                            
                    </div>                    
                </div>
            </div>
        </section>
    </GuestLayout>
</template>

