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
        <div class="er-price">
            <div class="old-price"><small>{{ currency }} {{ parseFloat(oldPrice).toFixed(2) }}</small></div>
            <div class="new-price">{{ currency }} {{ parseFloat(newPrice).toFixed(2) }}</div>
        </div>
        <!-- <span class="ec-price">
            <span class="new-price">{{ currency }} {{ formatted_price }}</span>
        </span> -->
    </template>
    <style>
        .er-price{
            display:block !important;
            padding-left: 24px !important;
            padding-bottom: 5px !important;
        }
        .ec-product-inner .ec-pro-content .er-price div.new-price {
            color: #555;
            font-weight: 700;
            font-size: 15px;
            font-family: "Montserrat";
        }
        .ec-pro-content .er-price div.old-price {
            font-size: 15px;
            margin-right: 15px;
            text-decoration: line-through;
            color: #777777;
        }
    </style>