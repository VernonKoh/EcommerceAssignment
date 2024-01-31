<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" defer></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Add your custom styles here */
        .wobble-bin {
            transition: transform 0.3s ease-in-out;
        }

        .wobble-bin:hover {
            transform: rotate(8deg);
        }
    </style>
</head>

<body>
    <?php
    ob_start(); // Start output buffering
    // Include the code that contains shopping cart's functions.
    // Current session is detected in "cartFunctions.php, hence need not start session here.
    include_once("cartFunctions.php");
    include("header.php"); // Include the Page Layout header
    
    if (!isset($_SESSION["ShopperID"])) { // Check if the user is logged in
        // Redirect to the login page if the session variable shopperid is not set
        header("Location: login.php");
        exit;
    }

    echo "<div id='myShopCart' style='margin:auto'>"; // Start a container
    
    if (isset($_SESSION["Cart"])) {
        include_once("mysql_conn.php");

        // To Do 1 (Practical 4): 
        // Retrieve from the database and display the shopping cart in a table
        $qry = "SELECT sc.*, p.OfferedPrice, ((CASE WHEN p.OfferedPrice IS NOT NULL THEN p.OfferedPrice ELSE p.Price END) * sc.Quantity) AS Total
        FROM ShopCartItem sc
        JOIN Product p ON sc.ProductID = p.ProductID
        WHERE sc.ShopCartID=?";

        $stmt = $conn->prepare($qry);
        $stmt->bind_param("i", $_SESSION["Cart"]); // "i" - integer
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        if ($result->num_rows > 0) {
            // To Do 2 (Practical 4): Format and display 
            // the page header and header row of the shopping cart page
            echo "<p class='page-title' style='text-align:center; margin:10px;'>Shopping Cart</p>";
            echo "<div class='table-responsive' >"; // Bootstrap responsive table
            echo "<table class='table table-hover'>"; // Start of the table
            echo "<thead class='cart-header'>"; // Start of the table's header section
            echo "<tr>"; // Start of the header row
            echo "<th style='width:60px; text-align: center;'>Product ID</th>";
            echo "<th width ='250px'>Item Name</th>";
            echo "<th width='90px'>Price (S$)</th>";

            echo "<th width='60px'>Quantity</th>";
            echo "<th width='120px'>Total (S$)</th>";
            echo "<th>&nbsp;</th>";
            echo "</tr>"; // End of the header row
            echo "</thead>"; // End of the table's header section
    
            // To Do 5 (Practical 5):
            // Declare an array to store the shopping cart items in the session variable 
            $_SESSION["Items"] = array();

            // To Do 3 (Practical 4): 
            // Display the shopping cart content
            $subTotal = 0; // Declare a variable to compute the subtotal before tax
            $totalItems = 0; // Initialize a variable to store the total number of items
    
            echo "<tbody>"; // Start of the table's body section
    
            while ($row = $result->fetch_array()) {
                echo "<tr>";
                echo "<td style='width:10%; text-align:center;'>$row[ProductID]</td>";
                echo "<td style='width:50%'>$row[Name]<br />";

                // Check if there is an offered price and it is not null
                if (isset($row["OfferedPrice"]) && $row["OfferedPrice"] !== null) {
                    // Use the offered price if it exists
                    $formattedPrice = number_format($row["OfferedPrice"], 2);
                } else {
                    // Otherwise, use the regular price
                    $formattedPrice = number_format($row["Price"], 2);
                }

                echo "<td>$formattedPrice</td>";




                echo "<td>"; // Column for updating the quantity of purchase
                echo "<form action='cartFunctions.php' method='post' style='text-align:center;'>";
                echo "<select name='quantity' onChange='this.form.submit()'>";

                for ($i = 1; $i <= 10; $i++) { // To populate the drop-down list from 1 to 10
                    $selected = ($i == $row["Quantity"]) ? "selected" : "";
                    echo "<option value='$i' $selected>$i</option>";
                }

                echo "</select>";
                echo "<input type='hidden' name='action' value='update' />";
                echo "<input type ='hidden' name='product_id' value='$row[ProductID]' />";

                echo "</form>";
                echo "</td>";
                $formattedTotal = number_format($row["Total"], 2);
                echo "<td style='text-align:center;'>$formattedTotal</td>";
                echo "<td>"; // Column for removing an item from the shopping cart
                echo "<form action='cartFunctions.php' method='post'>";
                echo "<input type='hidden' name='action' value='remove' />";
                echo "<input type='hidden' name='product_id' value='$row[ProductID]' />";
                echo "<input class='wobble-bin' type='image' src='Images/Others/trash-can.png' title='Remove Item' />";
                echo "</form>";
                echo "</td>";
                echo "</tr>";

                // To Do 6 (Practical 5):
                // Store the shopping cart items in the session variable as an associate array
                $_SESSION["Items"][] = array(
                    "productId" => $row["ProductID"],
                    "name" => $row["Name"],
                    "price" => $row["Price"],
                    "quantity" => $row["Quantity"]
                );

                // Accumulate the running subtotal
                $subTotal += (isset($row["OfferedPrice"]) && $row["OfferedPrice"] !== null) ? ($row["OfferedPrice"] * $row["Quantity"]) : ($row["Price"] * $row["Quantity"]);



                // Accumulate the total quantity of items
                $totalItems += $row["Quantity"];
            }

            echo "</tbody>"; // End of the table's body section
            echo "</table>"; // End of the table
            echo "</div>"; // End of the Bootstrap responsive table
    


            // To Do 4 (Practical 4): 
// Display the subtotal at the end of the shopping cart
            echo "<p style='text-align:right; font-size:20px; font-weight:bold;'>
Subtotal = S$<span id='subtotalValue'>" . number_format($subTotal, 2) . "</span>";
            $_SESSION["SubTotal"] = round($subTotal, 2);

            echo "<form action='checkoutProcess.php' method='post' id='checkoutForm'>";
            echo "<b style='margin-bottom: 10px; display: block;'>Delivery Mode:</b>";
            echo "<div style='margin-bottom: 10px;'>";
            echo "<input type='radio' name='delivery_mode' value='Normal Delivery' id='normalDelivery' checked> Normal Delivery (S$ 5) - Delivered within 2 working days ";
            echo "</div>";
            echo "<div>";
            echo "<input type='radio' name='delivery_mode' value='Express Delivery' id='expressDelivery'> Express Delivery (S$ 10) - Delivered within 24 hours";
            echo "</div>";
            echo "<button type='button' class='btn btn-primary' onclick='updateCharges()' style='margin-top: 15px;'>Calculate Charges</button>";
            // Add spacing using div elements
            echo "<div style='height: 20px;'></div>";

            // To Do 7: Display the shipping charges and overall total
            $deliveryCharge = ($_SESSION["SubTotal"] > 200) ? 0 : 5.00;
            $message = ($_SESSION["SubTotal"] > 200) ? "Congratulations! Since your order exceeds $200, your delivery charge will be waived! <br> Express Delivery will be provided." : "";
            echo "<div id='shippingChargeValue'>Delivery Fee: S$" . number_format($deliveryCharge, 2) . "<br>" . $message . "</div>";

            // Display the total number of items 
            echo "<div class='row justify-content-end'>";
            echo "<div style='text-align:right;'>"; // align right
            echo "<p>Total Items in Cart: " . $totalItems . "</p>";
            echo "</div>";
            echo "</div>";

            echo "<div id='overallTotal' style='text-align:right; font-size:15px; margin-top: 10px; font-weight: bold;'>Total (Inclusive of Delivery Fees): S$";
            echo number_format($_SESSION["SubTotal"] + $deliveryCharge, 2);
            echo "</div>"; // Display total including delivery fee
            // Add PayPal Checkout button on the shopping cart page
            echo "<input type='image' style='float:right; margin-top: 20px;' src='https://www.paypal.com/en_US/i/btn/btn_xpressCheckout.gif' onclick='return validateForm()'>";
            echo "</form></p>";

        } else {
            echo "<h3 style='text-align:center; color:red;'>Empty shopping cart!</h3>";
        }
        $conn->close(); // Close the database connection
    } else {
        echo "<h3 style='text-align:center; color:red;'>Empty shopping cart!</h3>";
    }



    // JavaScript function to update charges based on the selected delivery mode
    echo "<script>
    function updateCharges() {
        var deliveryMode = document.querySelector('input[name=\"delivery_mode\"]:checked');
        if (!deliveryMode) {
            alert('Please select a delivery mode.');
            return;
        }
    
        // Default delivery charge
        var shipCharge = (deliveryMode.value === 'Normal Delivery') ? 5.00 : 10.00;
    
        // To Do 8: Update the delivery charge in the session variable
        var subtotalElement = document.getElementById('subtotalValue');
        if (subtotalElement) {
            var subtotal = parseFloat(subtotalElement.innerText);
            if (subtotal > 200) {
                shipCharge = 0;
            }
        } else {
            console.error('Subtotal element not found.');
            return;
        }
    
        // Display the shipping charges
        var shippingChargeElement = document.getElementById('shippingChargeValue');
        if (shippingChargeElement) {
            shippingChargeElement.innerHTML = 'Delivery Fee: S$' + shipCharge.toFixed(2);
        } else {
            console.error('Shipping charge element not found.');
            return;
        }
    
        // Calculate the overall total
        var total = subtotal + shipCharge;
    
        // Display the overall total
        var overallTotalElement = document.getElementById('overallTotal');
        if (overallTotalElement) {
            overallTotalElement.innerHTML = 'Total (Inclusive of Delivery Fees): S$' + total.toFixed(2);
        } else {
            console.error('Overall total element not found.');
        }
    
        
    }
    

function validateForm() {
    var deliveryMode = document.querySelector('input[name=\"delivery_mode\"]:checked');
    if (!deliveryMode) {
        alert('Please select a delivery mode before checking out');
        return false;
    }
    return true;
}
</script>";





    echo "</div>"; // End of the container
    include("footer.php"); // Include the Page Layout footer
    ob_end_flush(); // Flush the output buffer
    ?>

</body>

</html>