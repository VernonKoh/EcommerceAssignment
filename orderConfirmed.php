<?php
session_start(); // Detect the current session
include("header.php"); // Include the Page Layout header
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout Success</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .container {
            max-width: 1300px;
            margin: 30px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #007bff;
        }

        p {
            margin-bottom: 15px;
        }

        ul {
            list-style-type: none;
            padding: 0;
        }

        li {
            margin-bottom: 10px;
        }

        a {
            color: #007bff;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Checkout Successful</h1>

        <?php
        if (isset($_SESSION["OrderID"])) {
            echo "<p>Thank you for your purchase!</p>";
            echo "<p>Your order number is: <strong>$_SESSION[OrderID]</strong></p>";

            // Display purchased items and quantity
            if (isset($_SESSION["Items"])) {
                echo "<p>Items Purchased:</p>";
                echo "<ul>";
                foreach ($_SESSION["Items"] as $item) {
                    echo "<li>";
                    echo "Product: $item[name]<br>";
                    echo "Quantity: $item[quantity]<br>";
                    echo "Subtotal: S$ " . number_format($item['price'] * $item['quantity'], 2) . "<br>";
                    echo "</li>";
                }
                echo "</ul>";
            }

            // Display delivery mode
            if (isset($_SESSION["ShipCharge"])) {
                echo "<p>Delivery Fee: <strong>S$ $_SESSION[ShipCharge]</strong></p>";
            }

            // Calculate and display total amount
            if (isset($_SESSION["SubTotal"])) {
                $totalAmount = $_SESSION["SubTotal"] + $_SESSION["ShipCharge"] + $_SESSION["Tax"];
                echo "<p>Total Amount: <strong>S$ " . number_format($totalAmount, 2) . "</strong></p>";
            }

            echo '<p>Thank you for your purchase! <a href="index.php">Continue shopping</a></p>';
        } else {
            echo "<p>No order information found.</p>";
        }
        ?>
    </div>
</body>

</html>

<?php include("footer.php"); // Include the Page Layout footer ?>