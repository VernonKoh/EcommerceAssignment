<?php
// Detect the current session
session_start();

// Include the php file that establishes database connection handle: $conn
include_once("mysql_conn.php");

// Include the Page Layout header
include("header.php");

// Reading inputs entered on the previous page
$email = $_POST["email"];

$sql = "SELECT * FROM Shopper WHERE Email=?";
$stmt = $conn->prepare($sql);
if (!$stmt) { // There is an error with the statement
    echo "Prepare statement failed: (" . $conn->errno . ")" . $conn->error;
    die();
}

$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$_user = $result->fetch_assoc(); // Fetch data
$stmt->close();

if ($_user && $email == $_user['Email']) {
    // Save user's info in session variables
    $_SESSION["SecurityQn"] = $_user["PwdQuestion"];
} else {
    echo  "<h3 style='color:red'>Invalid Login Credentials</h3>";
}

// Display Security Question
$content1 = "Welcome Guest<br />";
$content2 = "<li class='nav-item'><a class='nav-link' href='register.php'>Sign Up</a></li><li class='nav-item'><a class='nav-link' href='login.php'>Login</a></li>";

if (isset($_SESSION["ShopperName"])) {
    $content1 = "Welcome <b>" . htmlspecialchars($_SESSION["SecurityQn"]) . "</b>";
}

?>

<!-- Create a centrally located container-->
<div style="width:80%; margin:auto;">
    <!-- Create an HTML Form within the container-->
    <form action="getPassword.php" method="post">
        <!-- 1st row Header Row-->
        <div class="mb-3 row">
            <div class="col-sm-9 offset-sm-3">
                <span class="page-title">Security Question</span>
            </div>
        </div>
        <!-- Display Security Question -->
        <div class="mb-3 row">
            <label class="col-sm-3 col-form-label">Security Question:</label>
            <div class="col-sm-9">
                <p><?php echo htmlspecialchars($_SESSION["SecurityQn"]); ?></p>
            </div>
        </div>
        <!-- Display Input for Answer -->
        <div class="mb-3 row">
            <label class="col-sm-3 col-form-label" for="answer">Answer:</label>
            <div class="col-sm-9">
                <input class="form-control" type="text" name="answer" id="answer" required />
            </div>
        </div>
        <!-- Submit Buttin to get password -->
        <div class="mb-3 row">
            <div class="col-sm-9 offset-sm-3">
                <button type="submit" class="btn btn-primary">Get Password</button>
            </div>
        </div>
    </form>
</div>
<?php
// Include the Page Layout footer
include("footer.php");
?>
