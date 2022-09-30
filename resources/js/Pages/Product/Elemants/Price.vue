<script>
export default {
    props:{
        price:[String, Number],
        currency:[String]
    },
    computed: {
        formatted_price() {
            let price = parseFloat(this.price)
           return price.toFixed(2);
        }
    }
}
</script>
<template>
    <span class="ec-price">
        <span class="new-price">{{ currency }} {{ formatted_price }}</span>
<!--         <span class="old-price">{{ currency }} {{ formatted_price }}</span>
 -->    </span>
</template>