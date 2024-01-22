<?php
session_start(); // Detect the current session
include("header.php"); // Include the Page Layout header
?>
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
    $qry = "SELECT DISTINCT p.ProductID, p.ProductTitle, p.ProductImage,p.ProductDesc ,p.Price, p.Quantity 
    FROM Product p INNER JOIN ProductSpec ps ON p.ProductID=ps.ProductID
    WHERE (ProductTitle LIKE ?) or (ProductDesc like ?)
    OR (SpecVal LIKE ?) OR (Price LIKE ?)
    ORDER BY ProductTitle";

    $stmt = $conn->prepare($qry);
    // $SearchText = "%".$SearchText."%";
    $stmt->bind_param("sssd", $SearchText, $SearchText, $SearchText, $SearchText); // "i" integer
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    // Display each product in a row
    if ($result->num_rows > 0) { // If found, display records


        while ($row = $result->fetch_array()) {

            echo "<div class='row' style='padding:5px; margin-bottom: 10px; border: 1px solid #ddd; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.15), 0 1px 3px rgba(0, 0, 0, 0.08), inset 0 1px 2px rgba(255, 255, 255, 0.1);'>"; // Start a new row for each product


            // Product link and price (left column)
            $product = "productDetails.php?pid={$row['ProductID']}";
            $formattedPrice = number_format($row["Price"], 2);
            echo "<div class='col-8'>"; // 67% of row width
            echo "<p class='category-link'><a href='{$product}'>{$row['ProductTitle']}</a></p>";
            echo "<p>{$row['ProductDesc']}</p>";
            echo "<span style='font-weight: bold; color: red;'>Price: S$ {$formattedPrice}</span>";
            echo "</div>";

            // Product image (right column)
            $img = "./Images/products/{$row['ProductImage']}";
            echo "<div class='col-4'>"; // 33% of row width
            echo "<p class='flower'><img src='{$img}' style='max-width:100%; height:auto;'></p>";
            echo "</div>";

            echo "</div>"; // End of the row
        }
    } else {
        echo " No records found!";
    }


    // To Do (DIY): End of Code
}

echo "</div>"; // End of container
include("footer.php"); // Include the Page Layout footer
?>

<style>
    .category-link {
        font-size: 18px;
        font-weight: bold;
        color: #333;
        margin-bottom: 10px;
    }

    .category-link a {
        color: #0066cc;
        text-decoration: none;

    }

    .category-link a:hover {
        text-decoration: underline;

    }

    .flower {
        text-align: center;
    }

    .flower img {
        width: 200px;
        height: 200px;
        vertical-align: middle;
    }

    .flower p {
        display: inline-block;
        vertical-align: middle;
        font-size: 18px;
        font-weight: bold;
        margin-left: 20px;
    }
</style>