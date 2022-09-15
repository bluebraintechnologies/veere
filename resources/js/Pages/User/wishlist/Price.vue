<script>
export default {
    props:{
        price:[String, Number],
        currency:[String]
    },
    computed: {
        formatted_price() {
           return this.price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        }
    }
}
</script>
<template>
    <span>{{ currency }} {{ formatted_price }}</span>
</template>