<?php
ob_start();
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
    // Check if the user is logged in 
    if (!isset($_SESSION["ShopperID"])) {
        header("Location: login.php");
        exit;
    }

    include_once("mysql_conn.php");

    if (!isset($_SESSION["Cart"])) {
        createNewCart();
    }

    $pid = $_POST["product_id"];
    $quantity = $_POST["quantity"];

    $addNewItem = 0;

    if (productExistsInCart($pid)) {
        updateQuantityInCart($pid, $quantity);
    } else {
        addProductToCart($pid, $quantity);
        $addNewItem = 1;
    }

    $conn->close();

    updateNumCartItem($addNewItem);

    header("Location: shoppingCart.php");
    exit;
}

function createNewCart()
{
    global $conn;

    $qry = "INSERT INTO Shopcart(ShopperID) VALUES(?)";
    $stmt = $conn->prepare($qry);
    $stmt->bind_param("i", $_SESSION["ShopperID"]);
    $stmt->execute();
    $stmt->close();

    $qry = "SELECT LAST_INSERT_ID() AS ShopCartID";
    $result = $conn->query($qry);
    $row = $result->fetch_array();
    $_SESSION["Cart"] = $row["ShopCartID"];
}

function productExistsInCart($pid)
{
    global $conn;

    $qry = "SELECT * FROM ShopCartItem WHERE ShopCartID=? AND ProductID=?";
    $stmt = $conn->prepare($qry);
    $stmt->bind_param("ii", $_SESSION["Cart"], $pid);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();

    return ($result->num_rows > 0);
}

function updateQuantityInCart($pid, $quantity)
{
    global $conn;

    $qry = "UPDATE ShopCartItem SET Quantity=LEAST(Quantity+?, 10) WHERE ShopCartID=? AND ProductID=?";
    $stmt = $conn->prepare($qry);
    $stmt->bind_param("iii", $quantity, $_SESSION["Cart"], $pid);
    $stmt->execute();
    $stmt->close();
}

function addProductToCart($pid, $quantity)
{
    global $conn;

    $qry = "INSERT INTO ShopCartItem(ShopCartID, ProductID, Price, Name, Quantity)
            SELECT ?, ?, Price, ProductTitle, ? FROM Product WHERE ProductID=?";
    $stmt = $conn->prepare($qry);
    $stmt->bind_param("iiii", $_SESSION["Cart"], $pid, $quantity, $pid);
    $stmt->execute();
    $stmt->close();
}

function updateNumCartItem($addNewItem)
{
    if (isset($_SESSION["NumCartItem"])) {
        $_SESSION["NumCartItem"] += $addNewItem;
    } else {
        $_SESSION["NumCartItem"] = 1;
    }
}

// Other functions (updateItem, removeItem) remain the same...

ob_end_flush();
?>