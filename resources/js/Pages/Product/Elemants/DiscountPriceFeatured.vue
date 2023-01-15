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
        <div class="single-grid-price" style="padding-bottom: 45px !important;">
            <div class="old-price" style="text-decoration: line-through;"><small>{{ currency }} {{ parseFloat(oldPrice).toFixed(2) }}</small></div>
            <div class="new-price">{{ currency }} {{ parseFloat(newPrice).toFixed(2) }}</div>
        </div>
        <!-- <span class="ec-price">
            <span class="new-price">{{ currency }} {{ formatted_price }}</span>
        </span> -->
    </template>