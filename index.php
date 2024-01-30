<?php
// Detect the current session
session_start();
// Include the Page Layout header
include("header.php");
?>
<!-- <img src="Images/Category/Flowers.jpg" class="img-fluid" 
     style="display:block; margin:auto;"/> -->
<style>
    * {
        font-family: 'Poppins';
        margin: 0;
        padding: 0;
        scroll-padding-top: 1rem;
        scroll-behavior: smooth;
        list-style: none;
        text-decoration: none;
        box-sizing: border-box;
    }

    /* -------------------- Global variable for the colors -------------------- */
    :root {
        --main-color: red;
        --text-color: black;
        --bg-color: #fff;
        --yellow: #f9d806;
        --light-yellow: #ffee80;
        --black: #130f40;
        --light-color: #666;
        --box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .1);
        --border: .1rem solid rgba(0, 0, 0, .1);
        --color-primary: #009579;
        --color-primary-dark: #007f67;
        --color-secondary: #252c6a;
        --color-error: #cc3333;
        --color-success: #4bb544;
        --pink: #e84393;
        --border-radius: 4px;
        --green: #27ae60;
        --dark-color: #219150;
        --black: #444;
        --light-color: #666;
        --border-hover: .1rem solid var(--black);
    }


    html {
        font-size: 86.5%;
        scroll-behavior: smooth;
        scroll-padding-top: 6rem;
        overflow-x: hidden;
    }

    section {
        padding: 2rem 9%;
    }

    img {
        width: 100%;
    }

    body {
        color: var(--text-color);
    }

    .container {
        margin-left: auto;
        margin-right: auto;
    }

    .heading {
        text-align: center;
        font-size: 4rem;
        color: #333;
        padding: 1rem;
        margin: 2rem 0;
        background: rgba(255, 51, 153, .05);
    }

    .heading span {
        color: var(--pink);
    }

    header {
        display: block;
        width: 100%;
        position: fixed;
        top: 0;
        left: 0;
        z-index: 100;
    }

    /* -------------------- Homepage Banner -------------------- */
    .home {
        margin: auto;
        width: 100%;
        min-height: 640px;
        display: flex;
        align-items: center;
        background: url('images/pexels-tima-miroshnichenko-6169668.jpg');
        background-repeat: no-repeat;
        background-size: cover;
        background-position: center left;
    }

    .home-text {
        padding-left: 30px;
        color: var(--bg-color);
    }

    .hometext h1 {
        font-size: 3.4rem;
    }

    .home-text p {
        font-size: 1.3rem;
        font-weight: 800;
        margin: 0.5rem 0 1.2rem;
        color: white;
    }

    .btn {
        display: inline-block;
        margin-top: 0px;
        padding: .8rem 3rem;
        background: white;
        color: black;
        cursor: pointer;
        font-size: 15px;
        border-radius: .5rem;
        font-weight: 500;
        text-align: center;
    }

    #menu-btn {
        font-size: 2.5rem;
        color: var(--light-color);
        display: none;
    }

    .btnss {
        display: inline-block;
        margin-top: 0px;
        padding: .8rem 3rem;
        background: var(--green);
        color: black;
        cursor: pointer;
        font-size: 15px;
        border-radius: .5rem;
        font-weight: 500;
        text-align: center;
    }

    .btnss:hover {
        background: var(--color-primary-dark);
    }

    .btn:hover {
        background: yellow;
    }

    .btns {
        display: inline-block;
        margin-top: 1rem;
        border-radius: 5rem;
        background: #333;
        color: #fff;
        padding: .6rem 2.5rem;
        cursor: pointer;
        font-size: 1.3rem;
    }

    .btns:hover {
        background: var(--pink);
    }

    /* -------------------- END -------------------- */
    .about .row {
        display: flex;
        align-items: center;
        gap: 2rem;
        flex-wrap: wrap;
        padding: 2rem 0;
        padding-bottom: 3rem;
    }

    .about .row .video-container {
        flex: 1 1 40rem;
        position: relative;
    }

    .about .row .video-container video {
        width: 100%;
        border: 1.5rem solid var(--black);
        border-radius: 1rem;
        box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .1);
        height: 100%;
        object-fit: cover;
    }

    .about .row .video-container h3 {
        position: absolute;
        top: 50%;
        transform: translateY(200%);
        font-size: 2rem;
        background: #fff;
        width: 100%;
        padding: 0.4rem 0.8rem;
        text-align: center;
        mix-blend-mode: screen;
    }

    .about .row .content {
        flex: 1 1 40rem;
    }

    .about .row .content h3 {
        font-size: 2.5rem;
        color: #333;
    }

    .about .row .content p {
        font-size: 1.2rem;
        color: #999;
        padding: .5rem 0;
        padding-top: 1rem;
        line-height: 1.5;
    }

    /* -------------------- END -------------------- */

    /* -------------------- Subscribe page -------------------- */
    .newsletter {
        background: url(https://media-cldnry.s-nbcnews.com/image/upload/t_nbcnews-fp-1200-630,f_auto,q_auto:best/newscms/2018_30/2505991/180723-open-ofice-space-dk-928.jpg) no-repeat;
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
    }

    .newsletter form {
        max-width: 35rem;
        margin-left: auto;
        text-align: center;
        padding: 3rem 0;
    }

    .newsletter form h3 {
        font-size: 1.8rem;
        color: #fff;
        padding-bottom: .4rem;
        font-weight: normal;
    }

    .newsletter form .box {
        width: 100%;
        margin: .7rem 0;
        padding: 1rem 1.2rem;
        font-size: 1.2rem;
        color: var(--black);
        border-radius: .9rem;
        text-transform: none;
    }

    /* -------------------- END -------------------- */

    /* -------------------- Contact us page -------------------- */
    .contact .row {
        display: flex;
        flex-wrap: wrap;
        gap: 1.5rem;
    }

    .contact .row .map {
        flex: 1 1 42rem;
        width: 100%;
        padding: 2rem;
        box-shadow: var(--box-shadow);
        border: var(--border);
        border-radius: .5rem;
        height: 50rem;
    }

    .contact .row form {
        padding: 2rem;
        flex: 1 1 42rem;
        box-shadow: var(--box-shadow);
        border: var(--border);
        text-align: center;
        border-radius: .5rem;

    }

    .contact .row form h3 {
        font-size: 3rem;
        color: var(--black);
        padding-bottom: 1rem;
    }

    .contact .row form .box {
        width: 100%;
        border-radius: .5rem;
        padding: 1rem 1.2rem;
        font-size: 1.6rem;
        text-transform: none;
        border: var(--border);
        margin: .7rem 0;
    }

    .contact .row form textarea {
        height: 15rem;
        resize: none;
    }

    .contactheadings {
        padding-bottom: 2rem;
        text-align: center;
        font-size: 4.5rem;
        color: var(--black);
    }

    /* -------------------- END -------------------- */
    /* -------------------- Footer -------------------- */
    .footer .box-container {
        display: flex;
        flex-wrap: wrap;
        gap: 1.5rem;
    }

    .footer .box-container .box {
        flex: 1 1 25rem;
    }

    .footer .box-container .box h3 {
        color: #333;
        font-size: 2.5rem;
        padding: 1rem 0;
    }

    .footer .box-container .box a {
        display: block;
        color: #666;
        font-size: 1.5rem;
        padding: 1rem 0;
    }

    .footer .box-container .box a:hover {
        color: var(--pink);
        text-decoration: underline;
    }

    .footer .box-container .box img {
        margin-top: 1rem;
        width: 60%;
    }

    .inner-footer {
        margin: 0;
        padding: 0;
        background-color: black;
    }

    .social-links {
        display: flex;
    }

    .social-links ul {
        padding: 10px;
        display: flex;
        width: 400px;
        height: 80px;
        margin: auto;
    }

    .social-items {
        list-style: none;
    }

    .social-items a {
        padding: 10px 30px;
        font-size: 40px;
        color: white;
    }

    .social-items a:hover {
        color: red;
    }

    .outer-footer {
        padding: 15px;
        text-align: center;
        color: white;
        font-size: 18px;
        background-color: #191919;
    }

    /* -------------------- END -------------------- */
    .category .slide {
        margin-bottom: 5rem;
        box-shadow: var(--box-shadow);
        border: var(--border);
        text-align: center;
        padding: 2rem;
        background: var(--white);
        border-radius: .5rem;
    }

    .category .slide:hover {
        background-color: var(--black);
    }

    .category .slide:hover img {
        filter: invert();
    }

    .category .slide:hover h3 {
        color: var(--white);
    }

    .category .slide img {
        height: 7rem;
        width: 100%;
        object-fit: contain;
        margin-bottom: 1rem;
        user-select: none;
    }

    .category .slide h3 {
        font-size: 2rem;
        color: var(--black);
        user-select: none;
    }
</style>


<head>
    <section class="about" id="about">
        <h1 class="heading"> <span> About </span> Us </h1>

        <div class="row">
            <div class="video-container">
                <video src="Images/Others/pexels-rodnae-productions-5700371 (2160p).mp4" loop autoplay muted></video>
                <h3>Online Gift Shop</h3>
            </div>

            <div class="content">
                <div id="headerWithLottie">
                    <div id="lottieAnimation"></div>
                    <h3>Online Gift Shop</h3>
                    <lottie-player src="https://assets3.lottiefiles.com/temp/lf20_9gY9Yf.json" background="transparent"
                        speed="0.75" style="width: 50px; height: 50px;" loop autoplay>
                    </lottie-player>
                </div>
                <p>Where every gift tells a story and every moment is celebrated with a touch of thoughtfulness. Our
                    online gift shop is more than just a place to browse and buy; it's a curated collection of unique
                    treasures designed to bring joy, surprise, and delight to your special occasions.</p>
                <p>We believe that the best gifts are the ones that convey thoughtfulness and sincerity. That's why
                    we've handpicked a diverse range of products that go beyond the ordinary, offering you a selection
                    that is as thoughtful as it is unique.</p>
                <a href="#" class="btns">Learn More</a>
            </div>

        </div>
        </div>

        <section class="newsletter">

            <form onsubmit="sendNews(); reset(); return false;">
                <h3>Subscribe For Latest News</h3>
                <input type="email" id="newsEmail" placeholder="Enter Your Email" class="box">
                <input type="submit" value="Subscribe" class="btns">
            </form>

        </section>


        <!--- Contact us --->
        <section class="contact" id="contact">

            <h1 class="contactheadings"> Contact Us </h1>

            <div class="row">
                <iframe class="map"
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3988.7400248395365!2d103.76947568419236!3d1.3321089581828935!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31da107d8eb4e359%3A0x75d2e7ffdeeb0c43!2sNgee%20Ann%20Polytechnic!5e0!3m2!1sen!2ssg!4v1690967720206!5m2!1sen!2ssg"
                    width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"></iframe>
                <form onsubmit="sendEmail(); reset(); return false;">
                    <h3>Message Us</h3>
                    <input type="text" id="name" placeholder="Enter Name" class="box" required>
                    <input type="email" id="email" placeholder="Enter Email" class="box" required>
                    <input type="text" id="subject" placeholder="Enter Subject" class="box" required>
                    <textarea id="message" placeholder="Enter Message" class="box" cols="30" rows="10"
                        required></textarea>
                    <input type="submit" value="Send Message" class="btn">
                </form>
            </div>
        </section>

        <!-- Footer section starts  -->
        <section class="footer">

            <div class="box-container">

                <div class="box">
                    <h3>Quick Links</h3>
                    <a href="#home">Home</a>
                    <a href="#about">About Us</a>
                    <a href="#contact">Contact Us</a>
                </div>

                <div class="box">
                    <h3>Extra Links</h3>
                </div>

                <div class="box">
                    <h3>Locations</h3>
                    <a href="#">Singapore</a>
                </div>

            </div>
        </section>

        <?php
        // Include the Page Layout footer
        include("footer.php");
        ?>
        <!-- Footer section ends -->
    </section>
</head>