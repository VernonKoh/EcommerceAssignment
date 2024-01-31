<?php
    session_start(); // detect current session

    // read data input from previous page
    $name = $_POST["name"];
    $birthdate = $_POST["birthdate"];
    $address = $_POST["address"];
    $country = $_POST["country"];
    $phone = $_POST["phone"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $security_question = $_POST["security_question"];
    $security_answer = $_POST["security_answer"];

    // include the php file that establishes database connection handle: $conn
    include_once("mysql_conn.php");

    // define the INSERT SQL statement
    $qry = "INSERT INTO Shopper (Name, BirthDate, Address, Country, Phone, Email, Password, PwdQuestion, PwdAnswer) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($qry);

    // ssssss is referring to 9 string characters
    $stmt->bind_param("sssssssss", $name, $birthdate, $address, $country, $phone, $email, $password, $security_question, $security_answer);

    if ($stmt->execute()) { // sql statement executed succesfully
        // retrieve the shooper ID assigned to the new shopper
        $qry = "SELECT LAST_INSERT_ID() AS ShopperID";
        $result = $conn->query($qry); // execute sql and get the returned result
        while ($row = $result->fetch_array()) {
            $_SESSION["ShopperID"] = $row["ShopperID"];
        }

        // successfuly message and shopper ID
        $message = "Registration successful<br />
        Your Shopper ID is $_SESSION[ShopperID]<br />";
        // save the shopper name in a session variable
        $_SESSION["ShopperName"] = $name;
    }
    else {
        // error message
        $message = "<h3 stype='color:red;'>Error inserting record!</h3>";
    }

    // release the resource allocated for prepared statement
    $stmt->close();
    // close database connection
    $conn->close();

    // display the page header layout with updated session state and links
    include("header.php");
    // display message
    echo $message;
    // display page footer layout
    include("footer.php");
?>