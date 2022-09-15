<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Veeere </title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="index, follow">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i&display=swap" rel="stylesheet">
</head>
<body>

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<form name='razorpayform' action="{!!route('payment.rozer')!!}" method="POST">
 <input type="hidden" name="_token" value="{{ csrf_token() }}">
 <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id">
 <input type="hidden" name="razorpay_signature" id="razorpay_signature">
 <input type="hidden" name="razorpay_order_id" id="razorpay_order_id">
</form>
<script>
 var options = {
   "key": "rzp_test_ZCPqL2xGjnRTIc",
   "amount": "<?php echo round($combined_order->final_total) * 100 ?>",
   "currency": "INR",
   "name": "<?= env('APP_NAME') ?>",
   "description": "Cart Payment",
   "image": "<?= asset('images/logo.png') ?>",
   "prefill": {
        "name":"<?= Auth::user()->name ?>",
        "email":"<?= Auth::user()->email ?>"
   },
   "handler": function(response) {
     alert(response.razorpay_payment_id);
     alert(response.razorpay_order_id);
     alert(response.razorpay_signature)
   },
   "theme": {
     "color": "#F37254"
   }
 };
 options.handler = function(response) {
   document.getElementById('razorpay_payment_id').value = response.razorpay_payment_id;
   document.getElementById('razorpay_signature').value = response.razorpay_signature;
   document.razorpayform.submit();
 };
 options.theme.image_padding = false;
 options.modal = {
   ondismiss: function() {
     window.location.href = "/checkout/payment?payment=false"
   },
   escape: true,
   backdropclose: false
 };
 var rzp1 = new Razorpay(options);
 rzp1.on('payment.failed', function(response) {
   alert(response.error);
 });
 rzp1.open();
</script>

</body>
</html>