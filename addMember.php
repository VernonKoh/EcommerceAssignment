<?php
session_start(); // detect current session

// read data input from previous page
$name = $_POST["name"];
$birthdate = $_POST["birthdate"];
$address = $_POST["address"];
$country = $_POST["country"];
$phone = $_POST["phone"];
$email = $_POST["email"];
$password = $_POST["password"];
$security_question = $_POST["security_question"];
$security_answer = $_POST["security_answer"];

// include the php file that establishes database connection handle: $conn
include_once("mysql_conn.php");

// Check if the email already exists in the database
$qry_check_email = "SELECT COUNT(*) AS email_count FROM Shopper WHERE Email = ?";
$stmt_check_email = $conn->prepare($qry_check_email);
$stmt_check_email->bind_param("s", $email);
$stmt_check_email->execute();
$result_check_email = $stmt_check_email->get_result();
$row_check_email = $result_check_email->fetch_assoc();
$email_count = $row_check_email["email_count"];

if ($email_count > 0) {
    // If the email already exists, show an error message
    $message = "<h3 style='color:red;'>Error: Email already exists!</h3>";
} else {
    // define the INSERT SQL statement
    $qry = "INSERT INTO Shopper (Name, BirthDate, Address, Country, Phone, Email, Password, PwdQuestion, PwdAnswer) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($qry);

    // ssssss is referring to 9 string characters
    $stmt->bind_param("sssssssss", $name, $birthdate, $address, $country, $phone, $email, $password, $security_question, $security_answer);

    if ($stmt->execute()) { // sql statement executed succesfully
        // retrieve the shopper ID assigned to the new shopper
        $qry = "SELECT LAST_INSERT_ID() AS ShopperID";
        $result = $conn->query($qry); // execute sql and get the returned result
        while ($row = $result->fetch_array()) {
            $_SESSION["ShopperID"] = $row["ShopperID"];
        }

        // successful message and shopper ID
        $message = "Registration successful<br />
        Your Shopper ID is $_SESSION[ShopperID]<br />";
        // save the shopper name in a session variable
        $_SESSION["ShopperName"] = $name;
    } else {
        // error message
        $message = "<h3 style='color:red;'>Error inserting record!</h3>";
    }

    // release the resource allocated for prepared statement
    $stmt->close();
}

// close database connection
$conn->close();

// display the page header layout with updated session state and links
include("header.php");
// display message
echo $message;
// display page footer layout
include("footer.php");
?>
