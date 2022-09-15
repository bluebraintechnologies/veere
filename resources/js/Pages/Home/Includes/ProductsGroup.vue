<template>
    <div class="row space-t-50">
        <div class="col-lg-3 col-md-6 col-sm-12 col-xs-6 ec-all-product-content ec-new-product-content margin-b-30">
            <div class="col-md-12">
                <div class="section-title">
                    <h2 class="ec-title text-uppercase text-muted">Best Sellers</h2>
                </div>
            </div>
            <div class="ec-new-slider">
                <div class="col-sm-12 ec-all-product-block" v-for="(pp, pk) in bestSellers" :key="'bs'+pk">
                    <single-thumb :product="pp"></single-thumb>
                </div>
            </div>
        </div>
                        <!-- ec New Arrivals -->
        <div class="col-lg-3 col-md-6 col-sm-12 col-xs-6 ec-all-product-content ec-new-product-content margin-b-30">
            <div class="col-md-12">
                <div class="section-title">
                    <h2 class="ec-title text-uppercase text-muted">New Arrivals</h2>
                </div>
            </div>
            <div class="ec-new-slider">
                <div class="col-sm-12 ec-all-product-block" v-for="(pp, pk) in newArrivals" :key="'na'+pk">
                    <single-thumb :product="pp"></single-thumb>
                </div>
            </div>
        </div>
        <!-- ec Top Selling -->
        <div class="col-lg-3 col-md-6 col-sm-12 col-xs-6 ec-all-product-content ec-new-product-content margin-b-30">
            <div class="col-md-12">
                <div class="section-title">
                    <h2 class="ec-title text-uppercase text-muted">Top Selling</h2>
                </div>
            </div>
            <div class="ec-new-slider">
                <div class="col-sm-12 ec-all-product-block" v-for="(pp, pk) in topSelling" :key="'ts'+pk">
                    <single-thumb :product="pp"></single-thumb>
                </div>
            </div>
        </div>
        <!-- ec Top Rated -->
        <div class="col-lg-3 col-md-6 col-sm-12 col-xs-6 ec-all-product-content ec-new-product-content m-auto">
            <div class="col-md-12">
                <div class="section-title">
                    <h2 class="ec-title text-uppercase text-muted">Top Rated</h2>
                </div>
            </div>
            <div class="ec-new-slider">
                <div class="col-sm-12 ec-all-product-block" v-for="(pp, pk) in topRated" :key="'ybbs'+pk">
                    <single-thumb :product="pp"></single-thumb>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import SingleThumb from '@/Pages/Product/SingleThumb.vue'
export default {
    components: { SingleThumb },
    props: {
        topSelling:[Array, Object],
        topRated:[Array, Object],
        bestSellers:[Array, Object],
        newArrivals:[Array, Object],
    },
}
</script>