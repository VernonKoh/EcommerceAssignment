<?php
session_start(); // Detect the current session
include("header.php"); // Include the Page Layout header
?>

<!-- Create a container, 60% width of viewport -->
<div style='width:60%; margin:auto;'>
    <!-- Display Page Header - Category's name is read 
     from the query string passed from the previous page -->
    <div class="row" style="padding:5px">
        <div class="col-sm-9 offset-sm-3">
            <span class="page-title">Reviews</span>
        </div>
        <p><a href="giveReview.php">Write a Review</a></p>
    </div>

    <?php
    // Include the PHP file that establishes the database connection handle: $conn
    include_once("mysql_conn.php");

    $sql = "SELECT * FROM Review";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        // There is an error with the statement
        echo "Prepare statement failed: (" . $conn->errno . ")" . $conn->error;
        die();
    }

    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();

    // Display each review in a row
    while ($row = $result->fetch_assoc()) {
        echo "<div class='row' style='padding:5px; margin-bottom: 10px; border: 2px solid red;'>"; // Start a new row with red border

        // Display review details
        echo "<div class='col-8'>";
        echo "<p>Topic: {$row['ProductName']}</p>";
        echo "<p>Star Rating: {$row['StarRating']}</p>";
        echo "<p>Feedback: {$row['Feedback']}</p>";
        echo "<p>Date: {$row['Date']}</p>";
        echo "</div>"; // end of col-8

        echo "</div>"; // end of row with red border
    }
    ?>

    <?php
    // Include the Page Layout footer
    include("footer.php");
    ?>
