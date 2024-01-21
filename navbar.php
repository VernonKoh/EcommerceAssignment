<?php 
//Display guest welcome message, Login and Registration links
//when shopper has yet to login,
$content1 = "Welcome Guest<br />";
$content2 = "<li class='nav-item'>
		     <a class='nav-link' href='register.php'>Sign Up</a></li>
			 <li class='nav-item'>
		     <a class='nav-link' href='login.php'>Login</a></li>";

if(isset($_SESSION["ShopperName"])) { 
	//To Do 1 (Practical 2) - 
    //Display a greeting message, Change Password and logout links 
    //after shopper has logged in.
	
    $content1 = "Welcome <b>$_SESSION[ShopperName]</b>";
    $content2 = "<li class='nav-item'>
    <a class='nav-link' href='changepassword.php'>Change Password</a></li>
    <li class='nav-item'>
    <a class='nav-link' href='logout.php'>Logout</a></li>";

	//To Do 2 (Practical 4) - 
    //Display number of item in cart
	if (isset($_SESSION["NumCartItem"])) {
        $content1.= ", $_SESSION[NumCartItem] item(s) in shopping cart.";
        
    }
}
?>
<!-- To Do 3 (Practical 1) - 
     Display a navbar which is visible before or after collapsing -->
     

<nav class="navbar navbar-expand-md navbar-dark bg-dark" >
<div class="container-fluid">
<!-- Dynamic Text Display --> 
<span class="navbar-text ms-md-2"
style="color:#F7BE81; max-width: 80%;"> 
<?php echo $content1; ?>
</span>

<form name="frmSearch" method="get" action="search.php">
    <div class="search-container">
  <input type="search" id="search" name="keywords" placeholder="Search..." required>
  <button type="submit"><img src="Images/Others/searchicon.png" alt="Search"></button>
</div>
</form>

<!-- Toggler/Collapsibe Button -->
<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#collapsibleNavbar">
<span class="navbar-toggler-icon"></span>
</button>
</div>
</nav>
<!-- To Do 4 (Practical 1) - 
     Define a collapsible navbar -->
     

<nav class="navbar navbar-expand-md navbar-dark bg-dark">
<div class="container-fluid">
<!-- Collapsible part of navbar -->
<div class="collapse navbar-collapse" id="collapsibleNavbar"> <!-- Left-justified menu items -->
<ul class="navbar-nav me-auto">
<li class="nav-item">
<a class="nav-link" href="category.php">Product Categories</a> </li>
<li class="nav-item" >
<a class="nav-link" href="search.php">Product Search</a> </li>
<li class="nav-item" >
<a class="nav-link" href="shoppingCart.php">Shopping Cart</a> </li>
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

input[type="search"] {
  width: 100%;
  padding: 8px 40px 8px 10px;
  border: 1px solid #ccc;
  border-radius: 5px;
  font-size: 16px;
  box-sizing: border-box;
}

button[type="submit"] {
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

button[type="submit"]:hover {
  transform: translateY(-50%) scale(1.2);
}

button[type="submit"] img {
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

button[type="submit"] img {
  animation: pulse 2s infinite;
}
    </style>