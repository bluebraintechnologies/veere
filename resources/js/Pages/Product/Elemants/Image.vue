<template>
    <img class="main-image" :src="imagename" :alt="alt" :class="className" />
</template>
<script>
export default {
    props: {
        imagename: {
            type:String,
        },
        alt: {
            type:String,
            default:'Veeere Product'
        },
        className:String
    }
}
</script>