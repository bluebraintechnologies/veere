const state = {
    currency: 'Rs. ',
    mediaPath : '',
    imagePath:'',
    paymentModes:[
        {
            label:'Cash On Delivery',
            image:'/images/cod.png',
            value:'cash_on_delivery'
        },
        {
            label:'Razorpay',
            image:'/images/rozarpay.png',
            value:'razorpay'
        }
    ]
}

const getters = {
    currency : state => state.currency,
    mediaPath : state => state.mediaPath,
    imagePath : state => state.imagePath,
    paymentModes: state => state.paymentModes,
}
const settings = {
    state,
    getters
}
export default settings;