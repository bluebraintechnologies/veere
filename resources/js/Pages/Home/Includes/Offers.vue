<template>
     <section class="section ec-ser-spe-section section-space-p">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <img :src="this.$banner_token+pdetails.image" alt="" />
                </div>
            </div>
        </div>
    </section>
</template>
<script>
export default {
    props:{
        pdetails: Object
    }
}
</script>