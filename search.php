<?php
// The non-empty search keyword is sent to server
if (isset($_GET["keywords"]) && trim($_GET['keywords']) != "") {
    // To Do (DIY): Retrieve list of product records with "ProductTitle" 
    // contains the keyword entered by shopper, and display them in a table.

    // Include the PHP file that establishes database connection handle: $conn
    include_once("mysql_conn.php");
    // To Do (DIY): Starting ....
    $SearchText = "%" . $_GET["keywords"] . "%"; // Read Category ID from query string
    echo "<p> Search Results for <b> $_GET[keywords] </b>: </p>";

    // Form SQL to retrieve list of products associated to the Search Text
    $qry = "SELECT DISTINCT p.ProductID, p.ProductTitle, p.ProductImage, p.Price, p.Quantity 
    FROM Product p INNER JOIN ProductSpec ps ON p.ProductID=ps.ProductID
    WHERE (ProductTitle LIKE ?) or (ProductDesc like ?)
    OR (SpecVal LIKE ?)
    ORDER BY ProductTitle";

    $stmt = $conn->prepare($qry);
    // $SearchText = "%".$SearchText."%";
    $stmt->bind_param("sss", $SearchText, $SearchText, $SearchText); // "i" integer
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    // Display each product in a row
    if ($result->num_rows > 0) { // If found, display records


        while ($row = $result->fetch_array()) {

            echo "<div class='row' style='padding:5px'>"; // Start a new row
            // Left column - display a text link showing the product's name,
            // display the selling price in red in a new paragraph
            $product = "productDetails.php?pid=$row[ProductID]";
            $formattedPrice = number_format($row["Price"], 2);
            echo "<div class='col-8'>"; //67% of row width
            echo "<p><a href=$product>$row[ProductTitle]</a></p>";
            echo "Price:<span style='font-weight: bold; color: red;'>
            S$ $formattedPrice</span>";
            echo "</div>";

            // Right column display the product's image
            $img = "./Images/products/$row[ProductImage]";
            echo "<div class='col-4'>"; //33% of row width
            echo "<img src='$img' />";
            echo "</div>";

            echo "</div>"; // End of a row

        }
    } else {
        echo " No records found!";
    }


    // To Do (DIY): End of Code
}

echo "</div>"; // End of container
include("footer.php"); // Include the Page Layout footer
?>