<script>
    export default {
        props:{
            oldPrice:[String, Number],
            newPrice:[String, Number],
            currency:[String]
        },
        computed: {
            formatted_price() {

               return parseFloat(this.price).toFixed(2);
            }
        }
    }
    </script>
    <template>
        <span class="ec-price">
            <span class="old-price">{{ currency }} {{ parseFloat(oldPrice).toFixed(2) }}</span>
            <span class="new-price">{{ currency }} {{ parseFloat(newPrice).toFixed(2) }}</span>
        </span>
    </template>