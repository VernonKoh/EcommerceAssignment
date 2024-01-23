<?php
session_start();
include("header.php"); // Include the Page Layout header
include_once("myPayPal.php"); // Include the file that contains PayPal settings
include_once("mysql_conn.php");
include_once("mysql_conn.php");

if ($_POST) // Post Data received from Shopping cart page.
{
    // To Do 6 (DIY): Check to ensure each product item saved in the associative
    // array is not out of stock
    foreach ($_SESSION['Items'] as $item) {
        $productId = $item["productId"]; // Make sure "productId" matches the case in your array
        $quantityPurchased = $item["quantity"];

        // Execute SQL statement to get the quantity in stock for the respective product
        $getStockQuery = "SELECT Quantity FROM product WHERE ProductID = ?";
        $stmt = $conn->prepare($getStockQuery);
        $stmt->bind_param("i", $productId);
        $stmt->execute();
        $stmt->bind_result($stockQuantity);
        $stmt->fetch();
        $stmt->close();

        // Check if stock quantity is sufficient
        if ($stockQuantity < $quantityPurchased) {
            // Display an “out of stock” message and stop the checkout process
            echo "<div style='color:red'><b>Product ID $productId : $item[name] is out of stock.</b></div>";
            echo "<div>Please return to the shopping cart to amend your purchase.</div>";
            include("footer.php"); // Include the Page Layout footer
            exit; // Stop the checkout process
        }
    }
    // End of To Do 6

    $paypal_data = '';
    // Get all items from the shopping cart, concatenate to the variable $paypal_data
    // $_SESSION['Items'] is an associative array
    foreach ($_SESSION['Items'] as $key => $item) {
        $paypal_data .= '&L_PAYMENTREQUEST_0_QTY' . $key . '=' . urlencode($item["quantity"]);
        $paypal_data .= '&L_PAYMENTREQUEST_0_AMT' . $key . '=' . urlencode($item["price"]);
        $paypal_data .= '&L_PAYMENTREQUEST_0_NAME' . $key . '=' . urlencode($item["name"]);
        $paypal_data .= '&L_PAYMENTREQUEST_0_NUMBER' . $key . '=' . urlencode($item["productId"]);
    }

    // To Do 1A: Compute Shipping charge - Retrieve it dynamically from the form
    if (isset($_POST['delivery_mode'])) {
        $selectedDeliveryMode = $_POST['delivery_mode'];
        $_SESSION["ShipCharge"] = ($selectedDeliveryMode === 'Normal Delivery') ? 5.00 : 10.00;
    } else {
        // Default to 0.00 if no option is selected
        $_SESSION["ShipCharge"] = 0.00;
    }

    // Data to be sent to PayPal
    $padata = '&CURRENCYCODE=' . urlencode($PayPalCurrencyCode) .
        '&PAYMENTACTION=Sale' .
        '&ALLOWNOTE=1' .
        '&PAYMENTREQUEST_0_CURRENCYCODE=' . urlencode($PayPalCurrencyCode) .
        '&PAYMENTREQUEST_0_AMT=' . urlencode($_SESSION["SubTotal"] +
            $_SESSION["ShipCharge"]) . // Updated to include shipping charge
        '&PAYMENTREQUEST_0_ITEMAMT=' . urlencode($_SESSION["SubTotal"]) .
        '&PAYMENTREQUEST_0_SHIPPINGAMT=' . urlencode($_SESSION["ShipCharge"]) .
        '&BRANDNAME=' . urlencode("Mamaya e-BookStore") .
        $paypal_data .
        '&RETURNURL=' . urlencode($PayPalReturnURL) .
        '&CANCELURL=' . urlencode($PayPalCancelURL);

    // We need to execute the "SetExpressCheckOut" method to obtain PayPal token
    $httpParsedResponseAr = PPHttpPost('SetExpressCheckout', $padata, $PayPalApiUsername,
        $PayPalApiPassword, $PayPalApiSignature, $PayPalMode);

    // Respond according to the message we receive from Paypal
    if ("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) ||
        "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) {
        if ($PayPalMode == 'sandbox')
            $paypalmode = '.sandbox';
        else
            $paypalmode = '';

        // Redirect user to PayPal store with Token received.
        $paypalurl = 'https://www' . $paypalmode .
            '.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token=' .
            $httpParsedResponseAr["TOKEN"] . '';
        header('Location: ' . $paypalurl);
    } else {
        // Show error message
        echo "<div style='color:red'><b>SetExpressCheckOut failed : </b>" .
            urldecode($httpParsedResponseAr["L_LONGMESSAGE0"]) . "</div>";
        echo "<pre>" . print_r($httpParsedResponseAr) . "</pre>";
    }
}

// Paypal redirects back to this page using ReturnURL, We should receive TOKEN and Payer ID
if (isset($_GET["token"]) && isset($_GET["PayerID"])) {
    // we will be using these two variables to execute the "DoExpressCheckoutPayment"
    // Note: we haven't received any payment yet.
    $token = $_GET["token"];
    $playerid = $_GET["PayerID"];
    $paypal_data = '';

    // Get all items from the shopping cart, concatenate to the variable $paypal_data
    // $_SESSION['Items'] is an associative array
    foreach ($_SESSION['Items'] as $key => $item) {
        $paypal_data .= '&L_PAYMENTREQUEST_0_QTY' . $key . '=' . urlencode($item["quantity"]);
        $paypal_data .= '&L_PAYMENTREQUEST_0_AMT' . $key . '=' . urlencode($item["price"]);
        $paypal_data .= '&L_PAYMENTREQUEST_0_NAME' . $key . '=' . urlencode($item["name"]);
        $paypal_data .= '&L_PAYMENTREQUEST_0_NUMBER' . $key . '=' . urlencode($item["productId"]);
    }

    // Data to be sent to PayPal
$padata = '&TOKEN=' . urlencode($token) .
'&PAYERID=' . urlencode($playerid) .
'&PAYMENTREQUEST_0_PAYMENTACTION=' . urlencode("SALE") .
$paypal_data .
'&PAYMENTREQUEST_0_ITEMAMT=' . urlencode($_SESSION["SubTotal"]) .
'&PAYMENTREQUEST_0_SHIPPINGAMT=' . urlencode($_SESSION["ShipCharge"]) . // Include shipping charge
'&PAYMENTREQUEST_0_AMT=' . urlencode($_SESSION["SubTotal"] +
    $_SESSION["ShipCharge"]) . // Updated to include shipping charge
'&PAYMENTREQUEST_0_CURRENCYCODE=' . urlencode($PayPalCurrencyCode) .
'&L_PAYMENTREQUEST_0_NAME0=Shipping' . // Add a line item for shipping
'&L_PAYMENTREQUEST_0_AMT0=' . urlencode($_SESSION["ShipCharge"]) .
'&L_PAYMENTREQUEST_0_QTY0=1'; // Quantity is 1 for shipping item

    // We need to execute the "DoExpressCheckoutPayment" at this point 
    // to receive payment from the user.
    $httpParsedResponseAr = PPHttpPost('DoExpressCheckoutPayment', $padata,
        $PayPalApiUsername, $PayPalApiPassword,
        $PayPalApiSignature, $PayPalMode);

    // Check if everything went ok...
    if ("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) ||
        "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) {
        // To Do 5 (DIY): Update stock inventory in product table 
        // after successful checkout
        foreach ($_SESSION['Items'] as $item) {
            $productId = $item["productId"];
            $quantityPurchased = $item["quantity"];

            // Execute SQL statement to reduce the stock quantity in the respective record in the product table
            $updateStockQuery = "UPDATE product SET Quantity = Quantity - ? WHERE ProductID = ?";
            $stmt = $conn->prepare($updateStockQuery);
            $stmt->bind_param("ii", $quantityPurchased, $productId);
            $stmt->execute();
            $stmt->close();
        }
        // End of To Do 5

        // To Do 2: Update shopcart table, close the shopping cart (OrderPlaced=1)
        $total = $_SESSION["SubTotal"] + $_SESSION["ShipCharge"];
        $qry = "UPDATE shopcart SET OrderPlaced=1, Quantity=?,
                SubTotal=?, ShipCharge=?, Total=?
                WHERE ShopCartID=?";
        $stmt = $conn->prepare($qry);

        $stmt->bind_param("idddi", $_SESSION["NumCartItem"],
            $_SESSION["SubTotal"], $_SESSION["ShipCharge"],
            $total,
            $_SESSION["Cart"]);
        $stmt->execute();
        $stmt->close();
        // End of To Do 2

        // We need to execute the "GetTransactionDetails" API Call at this point 
        // to get customer details
        $transactionID = urlencode(
            $httpParsedResponseAr["PAYMENTINFO_0_TRANSACTIONID"]);
        $nvpStr = "&TRANSACTIONID=" . $transactionID;
        $httpParsedResponseAr = PPHttpPost('GetTransactionDetails', $nvpStr,
            $PayPalApiUsername, $PayPalApiPassword,
            $PayPalApiSignature, $PayPalMode);

        if ("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) ||
            "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) {
            // To Do 3: Insert an Order record with shipping information
            // Get the Order ID and save it in session variable.
            $qry = "INSERT INTO orderdata (ShopCartID)
                    VALUES(?)";
            $stmt = $conn->prepare($qry);

            $stmt->bind_param("i", $_SESSION["Cart"]);
            $stmt->execute();
            $stmt->close();
            $qry = "SELECT LAST_INSERT_ID() AS OrderID";
            $result = $conn->query($qry);
            $row = $result->fetch_array();
            $_SESSION["OrderID"] = $row["OrderID"];
            // End of To Do 3

            $conn->close();

            // To Do 4A: Reset the "Number of Items in Cart" session variable to zero.
            $_SESSION["NumCartItem"] = 0;

            // To Do 4B: Clear the session variable that contains Shopping Cart ID.
            unset($_SESSION["Cart"]);

            // To Do 4C: Redirect shopper to the order confirmed page.
            header("Location: orderConfirmed.php");
            exit;
        } else {
            echo "<div style='color:red'><b>GetTransactionDetails failed:</b>" .
                urldecode($httpParsedResponseAr["L_LONGMESSAGE0"]) . '</div>';
            echo "<pre>" . print_r($httpParsedResponseAr) . "</pre>";
            $conn->close();
        }
    } else {
        echo "<div style='color:red'><b>DoExpressCheckoutPayment failed : </b>" .
            urldecode($httpParsedResponseAr["L_LONGMESSAGE0"]) . '</div>';
        echo "<pre>" . print_r($httpParsedResponseAr) . "</pre>";
    }
}

include("footer.php"); // Include the Page Layout footer
?>