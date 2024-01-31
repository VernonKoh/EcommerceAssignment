<?php
// Detect the current session
session_start();

// Include the php file that establishes the database connection handle: $conn
include_once("mysql_conn.php");

// Include the Page Layout header
include("header.php");

// Reading inputs entered on the previous page
$answer = $_POST["answer"];

$sql = "SELECT * FROM Shopper WHERE PwdAnswer=?";
$stmt = $conn->prepare($sql);
if (!$stmt) { // There is an error with the statement
    echo "Prepare statement failed: (" . $conn->errno . ")" . $conn->error;
    die();
}

$stmt->bind_param("s", $answer);
$stmt->execute();
$result = $stmt->get_result();
$_user = $result->fetch_assoc(); // Fetch data  
$stmt->close();

if ($_user && $answer == $_user['PwdAnswer']) {
    // Save user's info in session variables
    $_SESSION["Password"] = $_user["Password"];

} else {
    echo "<h3 style='color:red'>Invalid Login Credentials</h3>";
}

// Display Security Question
$content1 = "Welcome Guest<br />";
$content2 = "<li class='nav-item'><a class='nav-link' href='register.php'>Sign Up</a></li><li class='nav-item'><a class='nav-link' href='login.php'>Login</a></li>";

if (isset($_SESSION["Password"])) {
    $content1 = "Welcome <b>" . htmlspecialchars($_SESSION["Password"]) . "</b>";
    $content2 = "<li class='nav-item'><a class='nav-link' href='update.php'>Update Profile</a></li>
                <li class='nav-item'><a class='nav-link' href='logout.php'>Logout</a></li>";

}
?>

<!-- Create a centrally located container-->
<div style="width:80%; margin:auto;">
    <!-- 1st row Header Row-->
    <div class="mb-3 row">
        <div class="col-sm-9 offset-sm-3">
            <span class="page-title">Password</span>
        </div>
    </div>

    <!-- Display Password -->
    <?php if (isset($_SESSION["Password"])) : ?>
        <div class="mb-3 row">
            <label class="col-sm-3 col-form-label">Your Password:</label>
            <div class="col-sm-9">
                <p><?php echo htmlspecialchars($_SESSION["Password"]); ?></p>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php
// Include the Page Layout footer
include("footer.php");
?>
