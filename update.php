<?php 
// Detect the current session
session_start(); 

// Include the Page Layout header
include("header.php"); 

// include the php file that establishes database connection handle: $conn
include_once("mysql_conn.php");

// retrieve the details of this shopper
$current_shopper_id = $_SESSION["ShopperID"];
$table_name = "Shopper";

$qry= "SELECT * FROM $table_name where shopperID='$current_shopper_id'";
$result = $conn->query($qry); 

// Display each details in a row
while ($row = $result->fetch_array()) {    
    $name = $row["Name"];
    // echo $name;
    // echo "<br />";
    $birthdate = $row["BirthDate"];
    // echo $birthdate;
    // echo "<br />";
    $address = $row["Address"];
    // echo $address;
    // echo "<br />";
    $country = $row["Country"];
    // echo $country;
    // echo "<br />";
    $phone = $row["Phone"];
    // echo $phone;
    // echo "<br />";
    $email = $row["Email"];
    // echo $email;
    // echo "<br />";
    $password = $row["Password"];
    // echo $password;
    // echo "<br />";
    $security_question = $row["PwdQuestion"];
    // echo $security_question;
    // echo "<br />";
    $security_answer = $row["PwdAnswer"];
    // echo $security_answer;
    // echo "<br />";
}
?>
<script type="text/javascript">
function validateForm()
{
    // To Do 1 - Check if password matched
    if (document.update.new_password.value != document.update.new_password2.value) {
        alert ("Passwords not matched!");
        return false; // cancel submission
    }
	       start with 6, 8 or 9

    if (document.update.phone.value != "") {
        var str = document.update.phone.value;
        if (str.length != 8) {
            alert("Please enter an 8-digit phone number.")
            return false; // cancel submission
        }
        else if (str.substr(0,1) != "6" && str.substr(0,1) != "8" && str.substr(0,1) != "9") {
            alert("Phone number must start with 6, 8 or 9.")
            return false; // cancel submission
        }
    }
    
    return true;  // No error found
}
</script>

<div style="width:80%; margin:auto;">
<form name="update" action="editMember.php" method="post" 
      onsubmit="return validateForm()">
    <div class="mb-3 row">
        <div class="col-sm-9 offset-sm-3">
            <span class="page-title">Update Profile Details</span>
        </div>
    </div>
    <div class="mb-3 row">
        <label class="col-sm-3 col-form-label" for="name">Name:</label>
        <div class="col-sm-9">
            
            <input class="form-control" name="name" id="name" 
                   type="text" value="<?php echo $name; ?>" required /> (required)
        </div>
    </div>
    <div class="mb-3 row">
        <label class="col-sm-3 col-form-label" for="name">Birthdate:</label>
        <div class="col-sm-9">
            <input class="form-control" name="birthdate" id="birthdate" 
                   type="date" value="<?php echo $birthdate; ?>" min="1924-01-01" max="2005-01-01" required /> (required)
        </div>
    </div>
    <div class="mb-3 row">
        <label class="col-sm-3 col-form-label" for="address">Address:</label>
        <div class="col-sm-9">
            <textarea class="form-control" name="address" id="address"
                      cols="25" rows="4"><?php echo $address; ?></textarea>
        </div>
    </div>
    <div class="mb-3 row">
        <label class="col-sm-3 col-form-label" for="country">Country:</label>
        <div class="col-sm-9">
            <input class="form-control" name="country" id="country" type="text" value="<?php echo $country; ?>" />
        </div>
    </div>
    <div class="mb-3 row">
        <label class="col-sm-3 col-form-label" for="phone">Phone:</label>
        <div class="col-sm-9">
            <input class="form-control" name="phone" id="phone" type="text" value="<?php echo $phone; ?>" />
        </div>
    </div>
    <div class="mb-3 row">
        <label class="col-sm-3 col-form-label" for="email">
            Email Address:</label>
        <div class="col-sm-9">
            <input class="form-control" name="email" id="email" 
                   type="email" value="<?php echo $email; ?>" required /> (required)
        </div>
    </div>
    <div class="mb-3 row">
        <label class="col-sm-3 col-form-label" for="old_password">
            Old Password:</label>
        <div class="col-sm-9">
            <input class="form-control" name="old_password" id="old_password" 
                   type="password" value="<?php echo $password; ?>" required /> (required)
        </div>
    </div>
    <div class="mb-3 row">
        <label class="col-sm-3 col-form-label" for="new_password">
            New Password:</label>
        <div class="col-sm-9">
            <input class="form-control" name="new_password" id="new_password" 
                   type="password" required /> (required)
        </div>
    </div>
    <div class="mb-3 row">
        <label class="col-sm-3 col-form-label" for="new_password2">
            Retype Password:</label>
        <div class="col-sm-9">
            <input class="form-control" name="new_password2" id="new_password2" 
                   type="password" required /> (required)
        </div>
    </div>
    <div class="mb-3 row">
        <label class="col-sm-3 col-form-label" for="security_question">Security question:</label>
        <div class="col-sm-9">
            <input class="form-control" name="security_question" id="security_question" 
                   type="text" value="<?php echo $security_question; ?>" required /> (required)
        </div>
    </div>
    <div class="mb-3 row">
        <label class="col-sm-3 col-form-label" for="security_answer">Security answer:</label>
        <div class="col-sm-9">
            <input class="form-control" name="security_answer" id="security_answer" 
                   type="text" value="<?php echo $security_answer; ?>" required /> (required)
        </div>
    </div>
    <div class="mb-3 row">       
        <div class="col-sm-9 offset-sm-3">
            <button type="submit">Update</button>
        </div>
    </div>
</form>
</div>
<?php 
// Include the Page Layout footer
include("footer.php"); 
?>