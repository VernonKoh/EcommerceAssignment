<?php
ob_start(); // Start output bufferings
session_start();
include("header.php"); // Include the Page Layout header
include_once("myPayPal.php"); // Include the file that contains PayPal settings
include_once("mysql_conn.php"); 

if($_POST) //Post Data received from Shopping cart page.
{
	// To Do 6 (DIY): Check to ensure each product item saved in the associative
	//                array is not out of stock
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
			echo "<div style='color:red; background-color: #ffe6e6; padding: 10px; border-radius: 5px; margin-bottom: 10px;'>";
			echo "<b>Product ID $productId: $item[name] is out of stock.</b><br>";
			echo "Please <a href='shoppingCart.php'>return to the shopping cart</a> to amend your purchase.";
			echo "</div>";
			include("footer.php"); // Include the Page Layout footer
			exit; // Stop the checkout process
		}
	}
	// End of To Do 6
	
	$paypal_data = '';
	// Get all items from the shopping cart, concatenate to the variable $paypal_data
	// $_SESSION['Items'] is an associative array

	// Calculate discount and update shopcart table with discount
	// Calculate discount and update shopcart table with discount
	
	$totalDiscount = 0; // Initialize total discount

	foreach ($_SESSION['Items'] as $index => $item) {
		// Retrieve price and offered price from the product table
		$productId = $item["productId"];
		$getProductPriceQuery = "SELECT Price, OfferedPrice FROM product WHERE ProductID = ?";
		$stmt = $conn->prepare($getProductPriceQuery);
		$stmt->bind_param("i", $productId);
		$stmt->execute();
		$stmt->bind_result($price, $offeredPrice);
		$stmt->fetch();
		$stmt->close();

		// Calculate discount for each item
		if (isset($offeredPrice) && $offeredPrice !== null) {
			// Calculate discount only if OfferedPrice is set and not null
			$discount = $price - $offeredPrice;
		} else {
			// If OfferedPrice is not set or null, set discount to 0
			$discount = 0;
		}
		$totalDiscount += $discount; // Add discount to total

		// Update shopcart table with the calculated discount
		$updateShopcartQuery = "UPDATE shopcart SET Discount = ? WHERE ShopCartID = ?";
		$stmt = $conn->prepare($updateShopcartQuery);
		$stmt->bind_param("di", $discount, $item["ShopCartID"]); // Use ShopCartID as the identifier
		$stmt->execute();
		$stmt->close();
	}


	foreach($_SESSION['Items'] as $key => $item) {
		// Determine the price to use (OfferedPrice if available, otherwise Price)
		$price = isset($item["OfferedPrice"]) ? $item["OfferedPrice"] : $item["Price"]; 
		// Check if there is an offered price and it is not null
		if (isset($row["OfferedPrice"]) && $row["OfferedPrice"] !== null) {
			// Use the offered price if it exists
			$formattedPrice = number_format($row["OfferedPrice"], 2);

		} else {
			// Otherwise, use the regular price
			$formattedPrice = number_format($row["Price"], 2);

		}

		// Update PayPal data with the correct price
		$paypal_data .= '&L_PAYMENTREQUEST_0_QTY' . $key . '=' . urlencode($item["quantity"]);
		$paypal_data .= '&L_PAYMENTREQUEST_0_AMT' . $key . '=' . urlencode($formattedPrice);
		$paypal_data .= '&L_PAYMENTREQUEST_0_NAME' . $key . '=' . urlencode($item["name"]);
		$paypal_data .= '&L_PAYMENTREQUEST_0_NUMBER' . $key . '=' . urlencode($item["productId"]);
		$paypal_data .= '&L_PAYMENTREQUEST_0_ITEMAMT=' . $key . '=' . urlencode($item["price"]);
	}
	// Fetch current GST rate from database
	$currentDate = date("Y-m-d");
	$getGSTRateQuery = "SELECT TaxRate FROM gst WHERE EffectiveDate <= ? ORDER BY EffectiveDate DESC LIMIT 1";
	$stmt = $conn->prepare($getGSTRateQuery);
	$stmt->bind_param("s", $currentDate);
	$stmt->execute();
	$stmt->bind_result($currentGSTRate);
	$stmt->fetch();
	$stmt->close();

	// Check if a valid GST rate is retrieved
	if ($currentGSTRate !== null) {
		// Update tax session variable with the retrieved tax rate
		$_SESSION["Tax"] = $currentGSTRate;
	} else {
		// Handle case when no valid GST rate is found
		$_SESSION["Tax"] = 0;
	}	
	
	// To Do 1A: Compute Shipping charge - Retrieve it dynamically from the form
	if ($_SESSION["SubTotal"] > 200) {
		// If subtotal is greater than $200, waive the delivery fee
		$_SESSION["ShipCharge"] = 0.00;
	} else {
		// Otherwise, set the delivery fee based on the selected delivery mode
		if (isset($_POST['delivery_mode'])) {
			$selectedDeliveryMode = $_POST['delivery_mode'];
			$_SESSION["ShipCharge"] = ($selectedDeliveryMode === 'Normal Delivery') ? 5.00 : 10.00;
		} else {
			// Default to $5.00 if no option is selected
			$_SESSION["ShipCharge"] = 5.00;
		}
	}
	//Data to be sent to PayPal
	$padata = '&CURRENCYCODE='.urlencode($PayPalCurrencyCode).
			  '&PAYMENTACTION=Sale'.
			  '&ALLOWNOTE=1'.
			  '&PAYMENTREQUEST_0_CURRENCYCODE='.urlencode($PayPalCurrencyCode).
			  '&PAYMENTREQUEST_0_AMT='.urlencode($_SESSION["SubTotal"] +
				                                 $_SESSION["Tax"] + 
												 $_SESSION["ShipCharge"]).
			  '&PAYMENTREQUEST_0_ITEMAMT='.urlencode($_SESSION["SubTotal"]). 
			  '&PAYMENTREQUEST_0_SHIPPINGAMT='.urlencode($_SESSION["ShipCharge"]). 
			  '&PAYMENTREQUEST_0_TAXAMT='.urlencode($_SESSION["Tax"]). 	
			  '&BRANDNAME='.urlencode("Gifting").
			  $paypal_data.				
			  '&RETURNURL='.urlencode($PayPalReturnURL ).
			  '&CANCELURL='.urlencode($PayPalCancelURL);	
		
	//We need to execute the "SetExpressCheckOut" method to obtain paypal token
	$httpParsedResponseAr = PPHttpPost('SetExpressCheckout', $padata, $PayPalApiUsername, 
	                                   $PayPalApiPassword, $PayPalApiSignature, $PayPalMode);
		
	//Respond according to message we receive from Paypal
	if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || 
	   "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) {					
		if($PayPalMode=='sandbox')
			$paypalmode = '.sandbox';
		else
			$paypalmode = '';
				
		//Redirect user to PayPal store with Token received.
		$paypalurl ='https://www'.$paypalmode. 
		            '.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token='.
					$httpParsedResponseAr["TOKEN"].'';
		header('Location: '.$paypalurl);
	}
	else {
		//Show error message
		echo "<div style='color:red'><b>SetExpressCheckOut failed : </b>".
		      urldecode($httpParsedResponseAr["L_LONGMESSAGE0"])."</div>";
		echo "<pre>".print_r($httpParsedResponseAr)."</pre>";
	}
}

//Paypal redirects back to this page using ReturnURL, We should receive TOKEN and Payer ID
if(isset($_GET["token"]) && isset($_GET["PayerID"])) 
{	
	//we will be using these two variables to execute the "DoExpressCheckoutPayment"
	//Note: we haven't received any payment yet.
	$token = $_GET["token"];
	$playerid = $_GET["PayerID"];
	$paypal_data = '';
	
	// Get all items from the shopping cart, concatenate to the variable $paypal_data
	// $_SESSION['Items'] is an associative array

	//Data to be sent to PayPal
	$padata = '&TOKEN='.urlencode($token).
			  '&PAYERID='.urlencode($playerid).
			  '&PAYMENTREQUEST_0_PAYMENTACTION='.urlencode("SALE").
			  $paypal_data.	
			  '&PAYMENTREQUEST_0_ITEMAMT='.urlencode($_SESSION["SubTotal"]).
              '&PAYMENTREQUEST_0_TAXAMT='.urlencode($_SESSION["Tax"]).
              '&PAYMENTREQUEST_0_SHIPPINGAMT='.urlencode($_SESSION["ShipCharge"]).
			  '&PAYMENTREQUEST_0_AMT='.urlencode($_SESSION["SubTotal"] + 
			                                     $_SESSION["Tax"] + 
								                 $_SESSION["ShipCharge"]).
			  '&PAYMENTREQUEST_0_CURRENCYCODE='.urlencode($PayPalCurrencyCode);
	
	//We need to execute the "DoExpressCheckoutPayment" at this point 
	//to receive payment from user.
	$httpParsedResponseAr = PPHttpPost('DoExpressCheckoutPayment', $padata, 
	                                   $PayPalApiUsername, $PayPalApiPassword, 
									   $PayPalApiSignature, $PayPalMode);
	
	//Check if everything went ok..
	if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || 
	   "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) 
	{
		// To Do 5 (DIY): Update stock inventory in product table 
		//                after successful checkout
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
		$total = $_SESSION["SubTotal"] + $_SESSION["Tax"] + $_SESSION["ShipCharge"];
		foreach ($_SESSION['Items'] as $item) {
			// Calculate discount for each item
			$productId = $item["productId"];
			$getProductPriceQuery = "SELECT Price, OfferedPrice FROM product WHERE ProductID = ?";
			$stmt = $conn->prepare($getProductPriceQuery);
			$stmt->bind_param("i", $productId);
			$stmt->execute();
			$stmt->bind_result($price, $offeredPrice);
			$stmt->fetch();
			$stmt->close();
			// Calculate discount for each item
			if (isset($offeredPrice) && $offeredPrice !== null) {
				// Calculate discount only if OfferedPrice is set and not null
				$discount = ($price - $offeredPrice) *$item["quantity"] ;
			} else {
				// If OfferedPrice is not set or null, set discount to 0
				$discount = 0;
			}
			$totalDiscount += $discount; // Add discount to total
			
			// Store the total discount amount in the session
			$_SESSION["Discount"] = $totalDiscount;

			// Update shopcart table with discount and other details
			$qry = "UPDATE shopcart SET OrderPlaced = 1, Quantity = ?, SubTotal = ?, ShipCharge = ?, Tax = ?, Total = ?, Discount = ?
					WHERE ShopCartID = ?";
			$stmt = $conn->prepare($qry);
			$stmt->bind_param("idddidi", $_SESSION["NumCartItem"], $_SESSION["SubTotal"], $_SESSION["ShipCharge"], $_SESSION["Tax"], $total, $totalDiscount, $_SESSION["Cart"]);
			$stmt->execute();
			$stmt->close();
		}

		// End of To Do 2
		
		//We need to execute the "GetTransactionDetails" API Call at this point 
		//to get customer details
		$transactionID = urlencode(
		                 $httpParsedResponseAr["PAYMENTINFO_0_TRANSACTIONID"]);
		$nvpStr = "&TRANSACTIONID=".$transactionID;
		$httpParsedResponseAr = PPHttpPost('GetTransactionDetails', $nvpStr, 
		                                   $PayPalApiUsername, $PayPalApiPassword, 
										   $PayPalApiSignature, $PayPalMode);

		if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || 
		   "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) 
		   {
			//gennerate order entry and feed back orderID information
			//You may have more information for the generated order entry 
			//if you set those information in the PayPal test accounts.
			
			$ShipName = addslashes(urldecode($httpParsedResponseAr["SHIPTONAME"]));
			
			$ShipAddress = urldecode($httpParsedResponseAr["SHIPTOSTREET"]);
			if (isset($httpParsedResponseAr["SHIPTOSTREET2"]))
				$ShipAddress .= ' '.urldecode($httpParsedResponseAr["SHIPTOSTREET2"]);
			if (isset($httpParsedResponseAr["SHIPTOCITY"]))
			    $ShipAddress .= ' '.urldecode($httpParsedResponseAr["SHIPTOCITY"]);
			if (isset($httpParsedResponseAr["SHIPTOSTATE"]))
			    $ShipAddress .= ' '.urldecode($httpParsedResponseAr["SHIPTOSTATE"]);
			$ShipAddress .= ' '.urldecode($httpParsedResponseAr["SHIPTOCOUNTRYNAME"]). 
			                ' '.urldecode($httpParsedResponseAr["SHIPTOZIP"]);
				
			$ShipCountry = urldecode(
			               $httpParsedResponseAr["SHIPTOCOUNTRYNAME"]);
			
			$ShipEmail = urldecode($httpParsedResponseAr["EMAIL"]);			
			
			// To Do 3: Insert an Order record with shipping information
			//          Get the Order ID and save it in session variable.
			$qry = "INSERT INTO orderdata (ShipName, ShipAddress, ShipCountry,
											ShipEmail, ShopCartID)
					VALUES(?, ?, ?, ?, ?)";
			$stmt = $conn->prepare($qry);

			$stmt -> bind_param("ssssi", $ShipName, $ShipAddress, $ShipCountry,
								$ShipEmail, $_SESSION["Cart"]);
			$stmt-> execute();
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
		} 
		else 
		{
		    echo "<div style='color:red'><b>GetTransactionDetails failed:</b>".
			                urldecode($httpParsedResponseAr["L_LONGMESSAGE0"]).'</div>';
			echo "<pre>".print_r($httpParsedResponseAr)."</pre>";
			$conn->close();
		}
	}
	else {
		echo "<div style='color:red'><b>DoExpressCheckoutPayment failed : </b>".
		                urldecode($httpParsedResponseAr["L_LONGMESSAGE0"]).'</div>';
		echo "<pre>".print_r($httpParsedResponseAr)."</pre>";
	}
}

include("footer.php"); // Include the Page Layout footer
ob_end_flush(); // Flush the output buffer
?>