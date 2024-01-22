<?php 
include("header.php"); // Include the Page Layout header




?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <script>
        function calculateTotal() {
            var subtotal = parseFloat(document.getElementById('subtotal').innerText);
            var deliveryCharge = parseFloat(document.querySelector('input[name="delivery"]:checked').value);
            
            var totalAmount = subtotal + deliveryCharge 

            document.getElementById('deliveryAmount').innerText = deliveryCharge.toFixed(2);
            document.getElementById('totalAmount').innerText = totalAmount.toFixed(2);
        }
    </script>
</head>
<body>

    <h2>Shopping Cart</h2>

    <p>Subtotal: $<span id="subtotal">100.00</span></p>

    <form>
        <input type="radio" name="delivery" value="5" onclick="calculateTotal()"> Normal Delivery ($5 per trip)<br>
        <input type="radio" name="delivery" value="10" onclick="calculateTotal()"> Express Delivery ($10 per trip)<br>
    </form>

    <p>Delivery Amount: $<span id="deliveryAmount">0.00</span></p>
    <p>Total Amount (inclusive of delivery and GST): $<span id="totalAmount">0.00</span></p>

</body>
</html>



<?php 

include("footer.php"); // Include the Page Layout footer

?>