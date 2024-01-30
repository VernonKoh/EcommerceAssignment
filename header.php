<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Group 2 Online Gift Shop </title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script src="js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="css/site.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.1.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">


</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-sm-12" style="margin-right: 20px;">

                <div class="w3-content w3-section" style="max-width:96% ">
                    <img class="mySlides" src="Images/Others/Banner7.jpg" style="width:100%; height:320px;">
                    <img class="mySlides" src="Images/Others/Banner5.jpg" style="width:100%; height:320px;">
                    <img class="mySlides" src="Images/Others/Banner6.jpg" style="width:100%; height:320px;">
                </div>
            </div>


            <div class="row">
                <div class="col-sm-12">
                    <?php include("navbar.php"); ?>

                </div>


            </div>
            <div class="row">
                <div class="col-sm-12" style="padding:15;">



                    <script>
                        var myIndex = 0;
                        carousel();

                        function carousel() {
                            var i;
                            var x = document.getElementsByClassName("mySlides");
                            for (i = 0; i < x.length; i++) {
                                x[i].style.display = "none";
                            }
                            myIndex++;
                            if (myIndex > x.length) { myIndex = 1 }
                            x[myIndex - 1].style.display = "block";
                            setTimeout(carousel, 2000); // Change image every 2 seconds
                        }
                    </script>