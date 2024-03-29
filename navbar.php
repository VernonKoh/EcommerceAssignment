<?php
// Display guest welcome message, Login and Registration links
// when shopper has yet to login,
$content1 = "Welcome Guest<br />";
$content2 = "<li class='nav-item'>
		     <a class='nav-link' href='register.php'>Sign Up</a></li>
			 <li class='nav-item'>
		     <a class='nav-link' href='login.php'>Login</a></li>";

if (isset($_SESSION["ShopperName"])) {
  // To Do 1 (Practical 2) - 
  // Display a greeting message, Change Password and logout links 
  // after the shopper has logged in.

  $content1 = "Welcome <b>$_SESSION[ShopperName]</b>";
  $content2 = "<li class='nav-item'>
        <a class='nav-link' href='update.php'>Update Profile</a></li>
        <li class='nav-item'>
        <a class='nav-link' href='logout.php'>Logout</a></li>";

  // To Do 2 (Practical 4) - 
  // Display the number of items in the cart
  if (isset($_SESSION["NumCartItem"])) {
    $content1 .= "! Explore our products and enjoy your shopping experience.";
  }
}
?>

<!-- To Do 3 (Practical 1) - 
     Display a navbar which is visible before or after collapsing -->
<nav class="navbar navbar-expand-md navbar-dark bg-dark">
  <div class="container-fluid">
    <!-- Dynamic Text Display -->
    <span class="navbar-text ms-md-2" style="color:#F7BE81; max-width: 80%;">
      <?php echo $content1; ?>
    </span>

    <form name="frmSearch" method="get" action="search.php">
      <div class="search-container">
        <input type="search" class="search-bar" id="search" name="keywords" placeholder="Search..." required>
        <button type="submit" class="submit-button"><img src="Images/Others/searchicon.png" alt="Search"></button>
      </div>
    </form>

    <!-- Toggler/Collapsible Button -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#collapsibleNavbar">
      <span class="navbar-toggler-icon"></span>
    </button>
  </div>
</nav>

<!-- To Do 4 (Practical 1) - 
     Define a collapsible navbar -->
<nav class="navbar navbar-expand-md navbar-dark bg-dark">
  <div class="container-fluid">
    <!-- Collapsible part of the navbar -->
    <div class="collapse navbar-collapse" id="collapsibleNavbar">
      <!-- Left-justified menu items -->
      <ul class="navbar-nav me-auto">
        <li class="nav-item">
          <a class="nav-link" href="index.php">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="category.php">Product Categories</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="pricesearch.php">Price Search</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="shoppingCart.php">Shopping Cart
            <?php
            if (isset($_SESSION["NumCartItem"]) && $_SESSION["NumCartItem"] > 0) {
              echo "(" . $_SESSION["NumCartItem"] . ")";
            }
            ?>
          </a>
        </li>
        <!--Review-->
        <li class="nav-item">
          <a class="nav-link" href="review.php">Review</a>
        </li>
      </ul>
      <!-- Right-justified menu items -->
      <ul class="navbar-nav ms-auto">
        <?php echo $content2; ?>
      </ul>
    </div>
  </div>
</nav>

<style>
  .search-container {
    position: relative;
    width: 100%;
    max-width: 400px;
    margin: 0 auto;
  }

  .search-bar {
    width: 100%;
    padding: 8px 40px 8px 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 16px;
    box-sizing: border-box;
  }

  .submit-button {
    position: absolute;
    top: 50%;
    right: 0;
    transform: translateY(-50%);
    width: 30px;
    height: 30px;
    padding: 0;
    border: none;
    background: none;
    cursor: pointer;
    transition: transform 0.3s ease;
  }

  /* button[type="submit"]:hover {
  transform: translateY(-50%) scale(1.2);
} */

  .submit-button img {
    width: 100%;
    height: auto;
    vertical-align: middle;
  }

  @keyframes pulse {
    0% {
      transform: scale(1);
    }

    50% {
      transform: scale(1.2);
    }

    100% {
      transform: scale(1);
    }
  }

  .submit-button img {
    animation: pulse 2s infinite;
  }
</style>