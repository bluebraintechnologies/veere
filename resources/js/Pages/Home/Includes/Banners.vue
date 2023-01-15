<template>
    <section class="ec-banner section section-space-p">
        <div class="container">
            <div class="row">
                <div class="ec-banner-inner">
                    <div class="ec-banner-block" v-for="(item, index) in banners" :key="'home-banner-' + index">
                        <div class="banner-block">
                            <a href="#">
                                <img :src="(item)?this.$offer_token+item:this.$media_url+'product-image/50_2.jpg'" alt="">
                            </a>
                        </div>
                    </div>

                    <!-- <div class="ec-banner-block">
                        <div class="banner-block">
                            <a href="#">
                                <img src="/images/b1.jpg" alt="">
                            </a>
                        </div>
                    </div>

                    <div class="ec-banner-block">
                        <div class="banner-block">
                            <a href="#">
                                <img src="/images/b2.jpg" alt="">
                            </a>
                        </div>
                    </div> -->
                </div>
            </div>
        </div>
    </section>
</template>
<script>    
    export default {
        components: {
        },
        props: {
            banners:Object
        },
        computed: {
        },
        created(){
            
        }
    }
</script>