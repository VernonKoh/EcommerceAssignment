<?php
session_start(); // Detect current session

// Read data input from the previous page
$name = $_POST["productName"];
$ranking = $_POST["ranking"];
$feedback = $_POST["feedback"];

// Include the PHP file that establishes the database connection handle: $conn
include_once("mysql_conn.php");

// Define the INSERT SQL statement for the "Review" table
$qry = "INSERT INTO Review (ProductName, StarRating, Feedback) VALUES (?, ?, ?)";

$stmt = $conn->prepare($qry);

// "sis" is referring to a string, integer, and string
$stmt->bind_param("sis", $name, $ranking, $feedback);

if ($stmt->execute()) { // SQL statement executed successfully
    $message = "Review submitted successfully!";
} else {
    // Error message
    $message = "<h3 style='color:red;'>Error inserting record!</h3>";
}

// Release the resource allocated for the prepared statement
$stmt->close();
// Close the database connection
$conn->close();

// Display the page header layout with the updated session state and links
include("header.php");
// Display the message
echo $message;
// Display the page footer layout
include("footer.php");
?>
