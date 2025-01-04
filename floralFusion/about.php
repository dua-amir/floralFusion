<?php

@include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>about</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php @include 'header.php'; ?>

<section class="heading">
    <h3>about us</h3>
    <p> <a href="home.php">home</a> / about </p>
</section>

<section class="about">

    <div class="flex">

        <div class="image">
            <img src="images/about1.jpg" alt="">
        </div>

        <div class="content">
            <h3>why choose us?</h3>
            <p>At <b>FloralFusion</b>, we combine artistry and quality to bring you creations that stand out. Each product is handmade with care, making every piece truly one of a kind.</p>
            <a href="shop.php" class="btn">shop now</a>
        </div>

    </div>

    <div class="flex">

        <div class="content">
            <h3>what we provide?</h3>
            <p>We offer a range of handmade items, including paper and ribbon flower bouquets, personalized cards, scrapbooks, and more. Perfect for gifts or preserving cherished memories.</p>
            <a href="contact.php" class="btn">contact us</a>
        </div>

        <div class="image">
            <img src="images/about2.jpg" alt="">
        </div>

    </div>

    <div class="flex">

        <div class="image">
            <img src="images/about3.jpg" alt="">
        </div>

        <div class="content">
            <h3>who we are?</h3>
            <p>We are passionate crafters dedicated to creating unique, handmade products. At <b>FloralFusion</b>, our mission is to spread happiness and add beauty to your special occasions.</p>
            <a href="#reviews" class="btn">clients reviews</a>
        </div>

    </div>

</section>

<section class="reviews" id="reviews">

    <h1 class="title">client's reviews</h1>

    <div class="box-container">

        <div class="box">
            <img src="images/pic1.jpeg" alt="">
            <p>The floral bouquet was breathtaking! So much effort and love went into it. Highly recommended!</p>
            <div class="stars">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star-half-alt"></i>
            </div>
            <h3>Sana M.</h3>
        </div>

        <div class="box">
            <img src="images/pic2.jpeg" alt="">
            <p>I ordered a scrapbook, and it was just perfect. Every detail was beautifully done!</p>
            <div class="stars">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
            </div>
            <h3>Ali K.</h3>
        </div>

        <div class="box">
            <img src="images/pic3.jpeg" alt="">
            <p>FloralFusion’s work is amazing. The ribbon bouquet I got was a showstopper at my event.</p>
            <div class="stars">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star-half-alt"></i>
            </div>
            <h3>Zoya A.</h3>
        </div>

        <div class="box">
            <img src="images/pic4.jpeg" alt="">
            <p>Such unique and beautiful handmade products! I'll definitely be coming back for more.</p>
            <div class="stars">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star-half-alt"></i>
            </div>
            <h3>Ahsan H.</h3>
        </div>

        <div class="box">
            <img src="images/pic5.jpeg" alt="">
            <p>The gift box I ordered was elegant and so well-crafted. Perfect for gifting!</p>
            <div class="stars">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
            </div>
            <h3>Mehak R.</h3>
        </div>

        <div class="box">
            <img src="images/pic6.jpeg" alt="">
            <p>Exceptional quality and creativity. FloralFusion products made my event even more special.</p>
            <div class="stars">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star-half-alt"></i>
            </div>
            <h3>Hiba F.</h3>
        </div>

    </div>

</section>











<?php @include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>