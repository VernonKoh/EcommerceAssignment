<?php
ob_start(); // Start output buffering
session_start();
if (isset($_POST['action'])) {
    switch ($_POST['action']) {
        case 'add':
            addItem();
            break;
        case 'update':
            updateItem();
            break;
        case 'remove':
            removeItem();
            break;
    }
}

function addItem()
{
    // Check if user is logged in 
    if (!isset($_SESSION["ShopperID"])) {
        // Redirect to login page if the session variable ShopperID is not set
        header("Location: login.php");
        exit;
    }

    include_once("mysql_conn.php"); // Establish database connection handle: $conn

    // Check if a shopping cart exists, if not, create a new shopping cart
    if (!isset($_SESSION["Cart"])) {
        // Create a shopping cart for the shopper
        $qry = "INSERT INTO Shopcart(ShopperID) VALUES(?)";
        $stmt = $conn->prepare($qry);
        $stmt->bind_param("i", $_SESSION["ShopperID"]); // "i" - integer
        $stmt->execute();
        $stmt->close();

        $qry = "SELECT LAST_INSERT_ID() AS ShopCartID";
        $result = $conn->query($qry);
        $row = $result->fetch_array();
        $_SESSION["Cart"] = $row["ShopCartID"];
    }

    // If the ProductID exists in the shopping cart, 
    // update the quantity, else add the item to the Shopping Cart.
    $pid = $_POST["product_id"];
    $quantity = $_POST["quantity"];
    $qry = "SELECT * FROM ShopCartItem WHERE ShopCartID=? AND ProductID=?";
    $stmt = $conn->prepare($qry);
    $stmt->bind_param("ii", $_SESSION["Cart"], $pid); // "i" - integer
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    $addNewItem = 0;

    if ($result->num_rows > 0) { // Selected product exists in shopping cart
        // Increase the quantity of purchase
        $qry = "UPDATE ShopCartItem SET Quantity=LEAST(Quantity+?, 10)
				WHERE ShopCartID=? AND ProductID=?";
        $stmt = $conn->prepare($qry);
        $stmt->bind_param("iii", $quantity, $_SESSION["Cart"], $pid);
        $stmt->execute();
        $stmt->close();
    } else { // Selected product has yet to be added to shopping cart, add item to Shopping Cart.
        $qry = "INSERT INTO ShopCartItem(ShopCartID, ProductID, Price, Name, Quantity)
		SELECT ?, ?, Price, ProductTitle, ? FROM Product WHERE ProductID=?";
        $stmt = $conn->prepare($qry);
        $stmt->bind_param("iiii", $_SESSION["Cart"], $pid, $quantity, $pid);
        $stmt->execute();
        $stmt->close();
        $addNewItem = 1;
    }

    $conn->close();

    // Update session variable used for counting the number of items in the shopping cart.
    if (isset($_SESSION["NumCartItem"])) {
        $_SESSION["NumCartItem"] = $_SESSION["NumCartItem"] + $addNewItem;
    } else {
        $_SESSION["NumCartItem"] = 1;
    }

    // Redirect shopper to the shopping cart page
    header("Location: shoppingCart.php");
    exit;
}

function updateItem()
{
    // Check if shopping cart exists 
    if (!isset($_SESSION["Cart"])) {
        // Redirect to login page if the session variable Cart is not set
        header("Location: login.php");
        exit;
    }

    // TO DO 2
    // Write code to implement: if a user clicks on "Update" button, update the database
    // and also the session variable for counting the number of items in the shopping cart.
    $cartid = $_SESSION["Cart"];
    $pid = $_POST["product_id"];
    $quantity = $_POST["quantity"];
    include_once("mysql_conn.php"); // Establish database connection handle: $conn
    $qry = "UPDATE ShopCartItem SET Quantity=? WHERE ProductID=? AND ShopCartID=?";
    $stmt = $conn->prepare($qry);
    $stmt->bind_param("iii", $quantity, $pid, $cartid); // "i" - integer
    $stmt->execute();
    $stmt->close();
    $conn->close();
    header("Location: shoppingCart.php");
    exit;
}

function removeItem()
{
    if (!isset($_SESSION["Cart"])) {
        // Redirect to login page if the session variable Cart is not set
        header("Location: login.php");
        exit;
    }

    // TO DO 3
    // Write code to implement: if a user clicks on "Remove" button, update the database
    // and also the session variable for counting the number of items in the shopping cart.
    include_once("mysql_conn.php"); // Establish database connection handle: $conn

    // Check if the product ID is provided in the POST request
    if (isset($_POST["product_id"])) {
        $pid = $_POST["product_id"];

        // Check if the product exists in the shopping cart
        $qry = "SELECT * FROM ShopCartItem WHERE ShopCartID=? AND ProductID=?";
        $stmt = $conn->prepare($qry);
        $stmt->bind_param("ii", $_SESSION["Cart"], $pid);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        if ($result->num_rows > 0) {
            // Product exists in the shopping cart, delete the item
            $qry = "DELETE FROM ShopCartItem WHERE ShopCartID=? AND ProductID=?";
            $stmt = $conn->prepare($qry);
            $stmt->bind_param("ii", $_SESSION["Cart"], $pid);
            $stmt->execute();
            $stmt->close();

            // Reduce the session variable NumCartItem by 1
            if (isset($_SESSION["NumCartItem"]) && $_SESSION["NumCartItem"] > 0) {
                $_SESSION["NumCartItem"] -= 1;
            }
        }
    }

    $conn->close();

    // Redirect shopper back to the shopping cart page
    header("Location: shoppingCart.php");
    exit;
}
ob_end_flush(); // Flush the output buffer
?>