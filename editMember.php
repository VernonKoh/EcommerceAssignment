<?php
    session_start(); // detect current session

    // include the php file that establishes database connection handle: $conn
    include_once("mysql_conn.php");

    // display the page header layout with updated session state and links
    include("header.php");

    // Retrieve the details of the current shopper from the session
    $current_shopper_id = $_SESSION["ShopperID"];
    echo "current shopper: " . $current_shopper_id;
    echo "<br />";

    // Retrieve user input from the form submission
    $name = $_POST["name"];
    $birthdate = $_POST["birthdate"];
    $address = $_POST["address"];
    $country = $_POST["country"];
    $phone = $_POST["phone"];
    $email = $_POST["email"];
    $old_password = $_POST["old_password"];
    $new_password = $_POST["new_password"];
    $new_password2 = $_POST["new_password2"];
    $security_question = $_POST["security_question"];
    $security_answer = $_POST["security_answer"];

    // Check if email is not empty
    if(!empty($email)) {
        $email = trim($email);
        $sql = "SELECT * FROM Shopper WHERE email='".$email."' AND ShopperID !='".$current_shopper_id."'";
        $results = $conn->query($sql);

        // Display SQL query for debugging purposes
        /*
        echo "<pre>";
        var_dump($sql);
        echo "</pre>";
        */

        // Fetch associative array to determine if the email already exists
        $rowcount = $results->fetch_assoc();

        // Display fetched row count for debugging purposes
        /*
        echo "<pre>";
        var_dump($rowcount);
        echo "</pre>";
        */

        // If email does not exist for another user, update shopper profile
        if($rowcount<=0) 
        {
            $stmt = "UPDATE Shopper SET Name='$name', BirthDate='$birthdate', Address='$address', Country='$country', Phone='$phone', Email='$email', Password='$new_password', PwdQuestion='$security_question', PwdAnswer='$security_answer' WHERE ShopperID='$current_shopper_id'";
            $sql = mysqli_query($conn, $stmt);
            echo "Your profile has been successfully updated!";
        }
        else {
           echo "Email address already exists to another user!";
           echo "<br />";
           echo "Back to <a href='update.php'>Update Profile</a> page.";
        }
    }

    // display page footer layout
    include("footer.php");
?>