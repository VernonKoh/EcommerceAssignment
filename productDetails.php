<?php
session_start(); // Detect the current session
include("header.php"); // Include the Page Layout header
?>
<!-- Create a container, 90% width of viewport -->
<div style='width:90%; margin:auto;'>

  <?php
  $pid = $_GET["pid"]; // Read Product ID from query string
  
  // Include the PHP file that establishes database connection handle: $conn
  include_once("mysql_conn.php");
  $qry = "SELECT * from product where ProductID=?";
  $stmt = $conn->prepare($qry);
  $stmt->bind_param("i", $pid); 	// "i" - integer 
  $stmt->execute();
  $result = $stmt->get_result();
  $stmt->close();

  // To Do 1:  Display Product information. Starting ....
  
  while ($row = $result->fetch_array()) {
    // Display Page Header -
    // Product's name is read from the "ProductTitle" column of "product" table.
    echo "<div class='row' >";
    echo "<div class='col-sm-12' style='padding:5px'>";
    echo "<span class='page-title'>$row[ProductTitle]</span>";
    echo "</div>";
    echo "</div>";

    echo "<div class='row'>"; // Start a new row
    // Left column - display the product's description, 
    echo "<div class='col-sm-9' style='padding:5px'>";
    echo "<p>$row[ProductDesc]</p>";
    // Left column - display the product's Specification, 
    $qry = "SELECT s.SpecName, ps.SpecVal from productspec ps INNER JOIN specification s ON ps.SpecID=s.SpecID WHERE ps.ProductID=?
        ORDER BY ps.priority";
    $stmt = $conn->prepare($qry);
    $stmt->bind_param("i", $pid);
    // "i" integer
    $stmt->execute();
    $result2 = $stmt->get_result();
    $stmt->close();
    while ($row2 = $result2->fetch_array()) {
      echo $row2["SpecName"] . ": " . $row2["SpecVal"] . "<br />";
    }
    echo "</div>"; // End of left column
    // Right column - display the product's image
    $img = "./Images/Products/$row[ProductImage]";
    echo "<div class='col-sm-3' style='vertical-align:top; padding:5px'>";
    echo "<p class='flower'><img src=$img /></p>";

    if ($row["OfferedPrice"] == null) {
      // Display the price before offer and strike it off
      $formattedPrice = number_format($row["Price"], 2);
      echo "<p style='font-weight:bold; color:red; font-size:20px;'>Price: S$ $formattedPrice</p>";
    } else {
      // Display the price before offer and strike it off
      $formattedPrice = number_format($row["Price"], 2);
      echo "<p style='text-decoration: line-through;'>Price: S$ $formattedPrice</p>";
      // Right column - display the product's price
      $formattedPriceBeforeOffer = number_format($row["OfferedPrice"], 2);
      echo "<p style='font-weight:bold; color:red; font-size:20px;'>On Offer: S$ $formattedPriceBeforeOffer</p>";
    }
    if ($row["Quantity"] <= 0) {
      echo "<p style='color:red; font-size:30px;'>Out of Stock</p>";
      echo "<button type='submit' class='buttondisable' disabled>Add to Cart</button>";
    } else {
      echo "<form action='cartFunctions.php' method='post'>";
      echo "<input type='hidden' name='action' value='add' />";
      echo "<input type='hidden' name='product_id' value='$pid' />";
      echo "Quantity: <input type='number' name='quantity' value='1' min='1' max='10' style='width:40px' required />";
      echo "<button type='submit' class='cartbutton'>Add to Cart</button>";

      echo "</form>";
    }
    echo "</div>"; //end of right column
    echo "</div>"; //end of row
  }


  // To Do 1:  Ending ....
  
  // To Do 2:  Create a Form for adding the product to shopping cart. Starting ....
  
  // To Do 2:  Ending ....
  
  $conn->close(); // Close database connnection
  echo "</div>"; // End of container
  include("footer.php"); // Include the Page Layout footer
  


  ?>

  <style>
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

    .cartbutton {
      padding: 10px 20px;
      margin: 10px;
      font-family: Arial, sans-serif;
      background-color: #007BFF;
      /* Blue */
      color: #FFFFFF;
      /* White */
      border: none;
    }

    .buttondisable {
      padding: 10px 20px;
      margin: 10px;
      font-family: Arial, sans-serif;
      background-color: grey;
      /* Blue */
      color: black;
      /* White */
      border: none;
      opacity: 0.5;
    }

    @keyframes wobble {
      0% {
        transform: rotate(0deg);
      }

      10% {
        transform: rotate(10deg);
      }

      20% {
        transform: rotate(-10deg);
      }

      30% {
        transform: rotate(10deg);
      }

      40% {
        transform: rotate(-10deg);
      }

      50% {
        transform: rotate(0deg);
      }
    }

    .cartbutton:hover {
      animation: wobble 0.5s ease-in-out;
    }
  </style>