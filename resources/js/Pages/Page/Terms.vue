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
    <Head title="Terms & Conditions" />

    <GuestLayout>
        <div class="sticky-header-next-sec  ec-breadcrumb section-space-mb">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="row ec_breadcrumb_inner">
                            <div class="col-md-6 col-sm-12">
                                <h2 class="ec-breadcrumb-title">Terms & Conditions </h2>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <ul class="ec-breadcrumb-list">
                                    <li class="ec-breadcrumb-item">
                                        <Link :href="route('home')">Home</Link>
                                    </li>
                                     <li class="ec-breadcrumb-item">
                                        Terms & Conditions
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
                        
                            
                        <h5>Companies Information</h5>
                        <p>SS India Foods Pvt Ltd is the licensed owner of the brand “Deepak” and the website Veeere.com (”The Site”) from SS India Foods Pvt Ltd. As a visitor to the Site/ Customer you are advised to please read the Term &amp; Conditions carefully. By accessing the services provided by the Site you agree to the terms provided in this Terms &amp; Conditions document.</p>
                        <h5>Personal Information</h5>
                        <p>As part of the registration process on the Site, SSIF may collect the following personally identifiable information about you: Name including first and last name, alternate email address, mobile phone number and contact details, Postal code, Demographic profile (like your age, gender, occupation, education, address etc.) and information about the pages on the site you visit/access, the links you click on the site, the number of times you access the page and any such browsing information.</p>
                        <h5>Eligibility</h5>
                        <p>Services of the Site would be available to only select geographies in India. Persons who are "incompetent to contract" within the meaning of the Indian Contract Act, 1872 including un-discharged insolvents etc. are not eligible to use the Site. If you are a minor i.e. under the age of 18 years but at least 13 years of age you may use the Site only under the supervision of a parent or legal guardian who agrees to be bound by these Terms of Use. If your age is below 18 years your parents or legal guardians can transact on behalf of you if they are registered users. You are prohibited from purchasing any material which is for adult consumption and the sale of which to minors is prohibited.</p>
                        <h5>License &amp; Site access</h5>
                        <p>SSIF grants you a limited sub-license to access and make personal use of this site and not to download (other than page caching) or modify it, or any portion of it, except with express written consent of SSIF. This license does not include any resale or commercial use of this site or its contents; any collection and use of any product listings, descriptions, or prices; any derivative use of this site or its contents; any downloading or copying of account information for the benefit of another merchant; or any use of data mining, robots, or similar data gathering and extraction tools. This site or any portion of this site may not be reproduced, duplicated, copied, sold, resold, visited, or otherwise exploited for any commercial purpose without express written consent of SSIF. You may not frame or utilize framing techniques to enclose any trademark, logo, or other proprietary information (including images, text, page layout, or form) of the Site or of SSIF and its affiliates without express written consent. You may not use any Meta tags or any other "hidden text" utilizing the Site’s or SSIF name or Veeere name or trademarks without the express written consent of SSIF. Any unauthorized use terminates the permission or license granted by SSIF.</p>
                        <h5>You Agree and Confirm</h5>
                        <ul>
                            <li>That in the event that a non-delivery occurs on account of a mistake by you (i.e. wrong name or address or any other wrong information) any extra cost incurred by Veeere for redelivery shall be claimed from you.</li>
                            <li>That you will use the services provided by the Site, its affiliates, consultants and contracted companies, for lawful purposes only and comply with all applicable laws and regulations while using and transacting on the Site.</li>
                            <li>You will provide authentic and true information in all instances where such information is requested of you. Veeere reserves the right to confirm and validate the information and other details provided by you at any point of time. If upon confirmation your details are found not to be true (wholly or partly), it has the right in its sole discretion to reject the registration and debar you from using the Services and / or other affiliated websites without prior intimation whatsoever.</li>
                            <li>You authorise Veeere to contact you for any transactional purposes related to your order/account.</li>
                            <li>That you are accessing the services available on this Site and transacting at your sole risk and are using your best and prudent judgment before entering into any transaction through this Site.</li>
                            <li>That the address at which delivery of the product ordered by you is to be made will be correct and proper in all respects.</li>
                            <li>That before placing an order you will check the product description carefully. By placing an order for a product you agree to be bound by the conditions of sale included in the item's description.</li>
                        </ul>
                    
                            
                    </div>                    
                </div>
            </div>
        </section>
    </GuestLayout>
</template>

