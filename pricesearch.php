<!doctype html>
<html>
<head>
    <meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Price Search</title>
<link rel="stylesheet" href="css/bootstrap.min.css">
<script src="js/bootstrap.bundle.min.js"></script>
<link rel="stylesheet" href ="css/site.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.1.0/css/bootstrap.min.css">
<!-- <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css"> -->


</head>

<body>
<div class="row">
            <div class="col-sm-12">
                
                <?php
                session_start(); 
                include("header.php");?>

            </div>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card mt-4">
                    <div class="card-header">
                        <h4>Search Product By Price</h4>
                    </div>
                    <div class="card-body">

                        <form action="" method="GET">
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="">Start Price</label>
                                    <input type="text" name="start_price" value="<?php if(isset($_GET['start_price'])){echo $_GET['start_price']; }else{echo "0";} ?>" class="form-control" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="">End Price</label>
                                    <input type="text" name="end_price" value="<?php if(isset($_GET['end_price'])){echo $_GET['end_price']; }else{echo "1000";} ?>" class="form-control" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="">Click Me</label> <br/>
                                    <button type="submit" class="btn btn-primary px-4">Filter</button>
                                </div>
                                
                            </div>
                        </form>

                    </div>
                </div>
            </div>

            <div class="col-md-12 mt-3">
                <div class="card">
                    <div class="card-header">
                        <h5>Product Details</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">

                        <?php  
                        include_once("mysql_conn.php");

                        if(isset($_GET['start_price']) && isset($_GET['end_price']))
                        {
                            $startprice = $_GET['start_price'];
                            $endprice = $_GET['end_price'];
                            if (!is_numeric($startprice) || !is_numeric($endprice)) {
                                // Handle validation failure (e.g., display an error message)
                                die("Please enter valid numeric values for prices.");
                                
                            }
                            else{
                            //$query = "SELECT * FROM product WHERE Price BETWEEN $startprice AND $endprice ";
                            $query="SELECT DISTINCT p.ProductID, p.ProductTitle, p.ProductImage,p.ProductDesc ,p.Price, p.Quantity ,p.Offered,p.OfferedPrice
                            FROM Product p INNER JOIN ProductSpec ps ON p.ProductID=ps.ProductID
                            WHERE 
                            CASE 
                            WHEN p.Offered = 1 THEN 
                            p.OfferedPrice 
                            BETWEEN $startprice AND $endprice 
                            OR (p.OfferedPrice = $startprice AND p.OfferedPrice <= $endprice)
                            OR (p.OfferedPrice >= $startprice AND p.OfferedPrice = $endprice)
                            ELSE 
                            p.Price 
                            BETWEEN $startprice AND $endprice 
                            OR (p.Price = $startprice AND p.Price <= $endprice)
                            OR (p.Price >= $startprice AND p.Price = $endprice)
                            END
                            ORDER BY ProductTitle";
                            }
                        }

                        else
                        {
                            $query = "SELECT DISTINCT p.ProductID, p.ProductTitle, p.ProductImage,p.ProductDesc ,p.Price, p.Quantity ,p.Offered,p.OfferedPrice
                            FROM Product p INNER JOIN ProductSpec ps ON p.ProductID=ps.ProductID ORDER BY ProductTitle";
                            
                        }
                        
                        
                        
                        $result = $conn->query($query); // Execute the SQL and get the result
                        if ($result->num_rows > 0) { // If found, display records


                            while ($row = $result->fetch_array()) {
                    
                                echo "<div class='row' style='padding:5px; margin-bottom: 10px; border: 1px solid #ddd; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.15), 0 1px 3px rgba(0, 0, 0, 0.08), inset 0 1px 2px rgba(255, 255, 255, 0.1);'>"; // Start a new row for each product
                    
                    
                                // Product link and price (left column)
                                $product = "productDetails.php?pid={$row['ProductID']}";
                                $formattedPrice = number_format($row["Price"], 2);
                                echo "<div class='col-8'>"; // 67% of row width
                                echo "<p class='category-link'><a href='{$product}'>{$row['ProductTitle']}</a></p>";
                                echo "<p>{$row['ProductDesc']}</p>";
                                if ($row["OfferedPrice"] == null) {
                                    // Display the price before offer and strike it off
                                    $formattedPrice = number_format($row["Price"], 2);
                                    echo "<p style='font-weight:bold; color:red; font-size:20px;'>Price: S$ $formattedPrice</p>";
                                    }
                                else{
                                        // Display the price before offer and strike it off
                                      $formattedPrice = number_format($row["Price"], 2);
                                      echo "<p style='text-decoration: line-through;'>Price: S$ $formattedPrice</p>";
                                      // Right column - display the product's price
                                      $formattedPriceBeforeOffer = number_format($row["OfferedPrice"], 2);
                                      echo "<p style='font-weight:bold; color:red; font-size:20px;'><span style='color:green'>On Offer!:</span> S$ $formattedPriceBeforeOffer</p>";
                                      }
                                echo "</div>";
                    
                                // Product image (right column)
                                $img = "./Images/products/{$row['ProductImage']}";
                                echo "<div class='col-4'>"; // 33% of row width
                                echo "<p class='flower'><img src='{$img}' style='max-width:100%; height:auto;'></p>";
                                echo "</div>";
                    
                                echo "</div>"; // End of the row
                            }
                        } 
                        else {
                            echo " No records found!";
                        }

                        
                        
                        
                        include("footer.php"); // Include the Page Layout footer
                        ?>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

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