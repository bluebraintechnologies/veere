<script>
export default {
    props:{
        price:[String, Number],
        currency:[String]
    },
    computed: {
        formatted_price() {
           return parseInt(this.price).toFixed(2);
        }
    }
}
</script>
<template>
    <span class="new-price">{{ currency }} {{ formatted_price }}</span>
</template>