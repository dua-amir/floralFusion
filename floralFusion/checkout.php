<?php

@include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:login.php');
    exit;
}

if (isset($_POST['order'])) {

    // Form input values
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $number = mysqli_real_escape_string($conn, $_POST['number']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $method = mysqli_real_escape_string($conn, $_POST['method']);
    $address = mysqli_real_escape_string($conn, 'Flat no. ' . $_POST['flat'] . ', ' . $_POST['street'] . ', ' . $_POST['city'] . ', ' . $_POST['state'] . ' - ' . $_POST['pin_code']);
    $placed_on = date('d-M-Y');

    $errors = [];

    // Validate form fields
    if (empty($name)) $errors[] = "Please enter your name.";
    if (empty($number)) $errors[] = "Please enter your number.";
    if (empty($email)) $errors[] = "Please enter your email.";
    if (empty($_POST['flat'])) $errors[] = "Please enter flat/house details.";
    if (empty($_POST['street'])) $errors[] = "Please enter street/area details.";
    if (empty($_POST['city'])) $errors[] = "Please enter your city.";
    if (empty($_POST['state'])) $errors[] = "Please select your state.";
    if (empty($_POST['pin_code'])) $errors[] = "Please enter your pin/zip code.";
    if (empty($method)) $errors[] = "Please select a payment method.";

    // File validation
    $receipt = $_FILES['image']['name'];
    $receipt_tmp_name = $_FILES['image']['tmp_name'];
    $receipt_size = $_FILES['image']['size'];
    $receipt_folder = 'uploaded_receipts/' . $receipt;

    if (empty($receipt)) {
        $errors[] = "Please upload the receipt of payment.";
    } elseif (!in_array(pathinfo($receipt, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png'])) {
        $errors[] = "Only JPG, JPEG, and PNG files are allowed.";
    } elseif ($receipt_size > 5000000) { // 5MB file size limit
        $errors[] = "The receipt file size must be less than 2MB.";
    }

    // Check cart items
    $cart_total = 0;
    $cart_products = [];

    $cart_query = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('Query failed');
    if (mysqli_num_rows($cart_query) > 0) {
        while ($cart_item = mysqli_fetch_assoc($cart_query)) {
            $cart_products[] = $cart_item['name'] . ' (' . $cart_item['quantity'] . ') ';
            $sub_total = ($cart_item['price'] * $cart_item['quantity']);
            $cart_total += $sub_total;
        }
    } else {
        $errors[] = "Your cart is empty!";
    }

    if (empty($errors)) {
        $total_products = implode(', ', $cart_products);

        $order_query = mysqli_query($conn, "SELECT * FROM `orders` WHERE name = '$name' AND number = '$number' AND email = '$email' AND method = '$method' AND address = '$address' AND total_products = '$total_products' AND total_price = '$cart_total'") or die('Query failed');

        if (mysqli_num_rows($order_query) > 0) {
            $message[] = 'Order already placed!';
        } else {
            move_uploaded_file($receipt_tmp_name, $receipt_folder);

            mysqli_query($conn, "INSERT INTO `orders`(`user_id`, `name`, `number`, `email`, `method`, `address`, `total_products`, `total_price`, `placed_on`, `receipt_path`, `payment_status`) 
            VALUES ('$user_id', '$name', '$number', '$email', '$method', '$address', '$total_products', '$cart_total', '$placed_on', '$receipt_folder', 'pending')") or die('Query failed');

            mysqli_query($conn, "DELETE FROM `cart` WHERE user_id = '$user_id'") or die('Query failed');
            $message[] = 'Order placed successfully!';
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- custom admin css file link  -->
    <link rel="stylesheet" href="css/style.css">

    <style>
        .error {
            color: #e84393;
            font-size: 14px;
            font-weight: bold;
            margin: 5px 0;
        }
    </style>
</head>
<body>
   
<?php @include 'header.php'; ?>

<section class="heading">
    <h3>Checkout Order</h3>
    <p> <a href="home.php">Home</a> / Checkout </p>
</section>

<section class="display-order">
    <?php
    $grand_total = 0;
    $delivery_charges = 200;
    $select_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('Query failed');
    if (mysqli_num_rows($select_cart) > 0) {
        while ($fetch_cart = mysqli_fetch_assoc($select_cart)) {
            $total_price = ($fetch_cart['price'] * $fetch_cart['quantity']);
            $grand_total += $total_price;
    ?>    
    <p> <?php echo $fetch_cart['name'] ?> <span>(<?php echo 'PKR ' . $fetch_cart['price'] . '/- * ' . $fetch_cart['quantity']  ?>)</span> </p>
    <?php
        }
    } else {
        echo '<p class="empty">Your cart is empty</p>';
    }
    ?>
    <div class="grand-total">Total : <span>PKR <?php echo $grand_total; ?>/-</span></div>
    <div class="grand-total">Delivery Charges : <span>PKR <?php echo $delivery_charges; ?>/-</span></div>
    <?php
    $grand_total += $delivery_charges; // Add delivery charges to the grand total
    ?>
    <div class="grand-total">Grand Total : <span>PKR <?php echo $grand_total; ?>/-</span></div>
</section>

<section class="checkout">

    <form action="" method="POST" enctype="multipart/form-data">
        <h3>Place Your Order</h3>

        <?php
        if (!empty($errors)) {
            foreach ($errors as $error) {
                echo '<p class="error">' . $error . '</p>';
            }
        }
        ?>

        <div class="flex">
            <!-- Input fields -->
            <div class="inputBox">
                <span>Your Name :</span>
                <input type="text" name="name" placeholder="Enter your name">
            </div>
            <div class="inputBox">
                <span>Your Number :</span>
                <input type="number" name="number" min="0" placeholder="Enter your number">
            </div>
            <div class="inputBox">
                <span>Your Email :</span>
                <input type="email" name="email" placeholder="Enter your email">
            </div>
            <div class="inputBox">
                <span>Address Line 01 :</span>
                <input type="text" name="flat" placeholder="e.g. House no., street no.">
            </div>
            <div class="inputBox">
                <span>Address Line 02 :</span>
                <input type="text" name="street" placeholder="Area(e.g. Bahria Town)">
            </div>
            <div class="inputBox">
                <span>City :</span>
                <input type="text" name="city" placeholder="e.g. Rawalpindi">
            </div>
            <div class="inputBox">
                <span>State :</span>
                <select name="state">
                    <option value="">Select State</option>
                    <option value="Islamabad">Islamabad</option>
                    <option value="Punjab">Punjab</option>
                    <option value="Sindh">Sindh</option>
                    <option value="Balochistan">Balochistan</option>
                    <option value="KPK">KPK</option>
                    <option value="Gilgit">Gilgit</option>
                    <option value="Azad Kashmir">Azad Kashmir</option>
                </select>
            </div>
            <div class="inputBox">
                <span>Pin/Zip Code :</span>
                <input type="number" min="0" name="pin_code" placeholder="e.g. 123456">
            </div>
            <div class="inputBox">
                <span>Payment Method :</span>
                <select name="method">
                    <option value="">Select Payment Method</option>
                    <option value="Easypaisa">Easypaisa</option>
                    <option value="JazzCash">JazzCash</option>
                    <option value="SadaPay">SadaPay</option>
                </select>
            </div>
            <div class="payment-info" style="font-size: 15px;">
    <p style="font-size: 17px; font-weight: bold;">
        Please send your payment to the following account details:
    </p>
        <li style="font-size: 15px;">
            <strong>Easypaisa:</strong> <br>
            Account Number: <span style="font-weight: bold; color: #e84393; font-size: 15px;">0306-7509177</span>
            Account Name: <span style="font-weight: bold; color: #e84393; font-size: 15px;">Dua Amir</span>
        </li>
        <li style="font-size: 15px;">
            <strong>JazzCash:</strong> <br>
            Account Number: <span style="font-weight: bold; color: #e84393; font-size: 15px;">0333-1133652</span>
            Account Name: <span style="font-weight: bold; color: #e84393; font-size: 15px;">Dua Amir</span>
        </li>
        <li style="font-size: 15px;">
            <strong>SadaPay:</strong> <br>
            Account Number: <span style="font-weight: bold; color: #e84393; font-size: 15px;">0333-1133652</span>
            Account Name: <span style="font-weight: bold; color: #e84393; font-size: 15px;">Dua Amir</span>
        </li>
</div>


            <div class="inputBox">
                <span>Upload Receipt (jpg, jpeg or png): After sending the payment, please upload the receipt below for verification</span>
                <input type="file" accept="image/jpg, image/jpeg, image/png" name="image" class="box">
            </div>
        </div>

        <input type="submit" name="order" value="Order Now" class="btn">
    </form>
</section>

<?php @include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>
