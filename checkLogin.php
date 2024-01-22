<?php
ob_start(); // Start output buffering
// Detect the current session
session_start();
// Include the Page Layout header
include("header.php");

// Reading inputs entered in the previous page
$email = isset($_POST["email"]) ? trim($_POST["email"]) : "";
$password = isset($_POST["password"]) ? trim($_POST["password"]) : "";

// Validate email and password length
if (strlen($email) == 0 || strlen($password) == 0) {
    echo "<h3 style='color:red'>Invalid Login Credentials</h3>";
    echo '<a href="login.php">Please try logging in again</a>';
    include("footer.php");
    exit;
}

// To Do 1 (Practical 2): Validate login credentials with the database
include_once("mysql_conn.php");

// Validate login credentials with the database
$qry = "SELECT * FROM Shopper WHERE Email=?";
$stmt = $conn->prepare($qry);
$stmt->bind_param("s", $email);

if ($stmt->execute()) {
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row && $password === $row["Password"]) {
        // Valid login
        $_SESSION["ShopperName"] = $row["Name"];
        $_SESSION["ShopperID"] = $row["ShopperID"];

        // To Do 2 (Practical 4): Get active shopping cart
        $qry = "SELECT ShopCartID FROM ShopCart WHERE ShopperID=? AND OrderPlaced=0";
        $stmt = $conn->prepare($qry);
        $stmt->bind_param("i", $_SESSION["ShopperID"]);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        if ($result->num_rows > 0) {
            // Active shopping cart found, retrieve ShopCartID
            $row = $result->fetch_assoc();
            $_SESSION["Cart"] = $row["ShopCartID"];

            // Count the number of uncheckout items
            $qry = "SELECT COUNT(*) AS NumItems FROM ShopCartItem WHERE ShopCartID=?";
            $stmt = $conn->prepare($qry);
            $stmt->bind_param("i", $_SESSION["Cart"]);
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();

            $row = $result->fetch_assoc();
            $_SESSION["NumCartItem"] = $row["NumItems"];
        } else {
            // No active shopping cart found
            $_SESSION["Cart"] = null;
            $_SESSION["NumCartItem"] = 0;
        }

        $conn->close();
        // Redirect to the home page
        header("Location: index.php");
        exit;
    } else {
        // Invalid login credentials
        echo "<h3 style='color:red'>Invalid Login Credentials</h3>";
        echo '<a href="login.php">Please try logging in again</a>';
    }
} else {
    // Handle database query execution error
    echo "<h3 style='color:red'>Error executing database query</h3>";
}

// Include the Page Layout footer
include("footer.php");
ob_end_flush(); // Flush the output buffer
?>