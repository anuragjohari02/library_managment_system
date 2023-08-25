<?php
include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:login.php');
}

$valid = true;
// $message = [];
// Initialize an associative array for validation errors
$errors = array();


if (isset($_POST['order_btn'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $number = $_POST['number'];
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $method = mysqli_real_escape_string($conn, $_POST['method']);
    $address = mysqli_real_escape_string($conn, 'flat no. ' . $_POST['flat'] . ', ' . $_POST['street'] . ', ' . $_POST['city'] . ', ' . $_POST['country'] . ' - ' . $_POST['pin_code']);
    $placed_on = date('d-M-Y');
    $flat = $_POST['flat'];
    $street = $_POST['street'];
    $city = $_POST['city'];
    $pin_code = $_POST['pin_code'];

    $cart_total = 0;
    $cart_products = []; // Initialize an array to store cart products

    $cart_query = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
    if (mysqli_num_rows($cart_query) > 0) {
        while ($cart_item = mysqli_fetch_assoc($cart_query)) {
            $cart_products[] = $cart_item['name'] . ' (' . $cart_item['quantity'] . ') ';
            $sub_total = ($cart_item['price'] * $cart_item['quantity']);
            $cart_total += $sub_total;
        }
    }

    $total_products = implode(', ', $cart_products);

    // Form validation with regular expressions

   if (!preg_match("/^[a-zA-Z ]*$/", $name)) {
      $valid = false;
      $errors['name'] = "Name must contain only letters not any special characters.";
   }
   

    if (!preg_match("/^[0-9]{10}$/", $number)) {
        $valid = false;
        $errors['number'] = "Invalid phone number.";
    }

    $pattern = "/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/";
    if (!preg_match ($pattern, $email) ) {
        $valid = false;
        $errors['email'] = "Invalid email format.";
    }

    if($method != 'cash on delivery'){
      $errors['method'] = "We don't accept '$method' for now. Please choose 'Cash on Delivery'.";
   }

    if (!preg_match("/^[a-zA-Z ]{2,}$/", $city)) {
        $valid = false;
        $errors['city'] = "City name must contain only letters.";
    }

    if (!preg_match("/^[0-9]+$/", $flat)) {
        $valid = false;
        $errors['flat'] = "Invalid flat number.";
    }

    if (!preg_match("/^[a-zA-Z0-9 ]+$/", $street)) {
        $valid = false;
        $errors['street'] = "Invalid street name.";
    }

    if (!preg_match("/^[0-9]{6}$/", $pin_code)) {
        $valid = false;
        $errors['pin_code'] = "Invalid pin code.";
    }

    if ($valid) {
      $order_query = mysqli_query($conn, "SELECT * FROM `orders` WHERE name = '$name' AND number = '$number' AND email = '$email' AND method = '$method' AND address = '$address' AND total_products = '$total_products' AND total_price = '$cart_total'") or die('query failed');

      if($cart_total == 0){
         $message[] = 'Your cart is empty';
      }else{
         if(mysqli_num_rows($order_query) > 0){
            $message[] = 'Order already placed!'; 
         }else{
            mysqli_query($conn, "INSERT INTO `orders`(user_id, name, number, email, method, address, total_products, total_price, placed_on) VALUES('$user_id', '$name', '$number', '$email', '$method', '$address', '$total_products', '$cart_total', '$placed_on')") or die('query failed');
            $message[] = 'Order placed successfully!';
            mysqli_query($conn, "DELETE FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
         }
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

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'header.php'; ?>

<div class="heading">
   <h3>Checkout</h3>
   <p> <a href="index.php">Home</a> / Checkout </p>
</div>

<section class="display-order">

   <?php  
      $grand_total = 0;
      $select_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
      if(mysqli_num_rows($select_cart) > 0){
         while($fetch_cart = mysqli_fetch_assoc($select_cart)){
            $total_price = ($fetch_cart['price'] * $fetch_cart['quantity']);
            $grand_total += $total_price;
   ?>
   <p> <?php echo $fetch_cart['name']; ?> <span>(<?php echo '$'.$fetch_cart['price'].'/-'.' x '. $fetch_cart['quantity']; ?>)</span> </p>
   <?php
      }
   }else{
      echo '<p class="empty">your cart is empty</p>';
   }
   ?>
   <div class="grand-total"> Grand Total : <span>$<?php echo $grand_total; ?>/-</span> </div>

</section>

<section class="checkout">

   <form action="" method="post">
      <h3>Place Your Order</h3>
      <div class="flex">

            <div class="inputBox">
               <span class="label">Your Name :</span>
               <input type="text" name="name" placeholder="Enter your name">
               <span class="validation-message"><?php echo isset($errors['name']) ? $errors['name'] : ''; ?></span>
            </div>    

            <div class="inputBox">
               <span class="label">Your Number :</span>
               <input type="number" name="number" placeholder="Enter your number" min="0">
               <span class="validation-message"><?php echo isset($errors['number']) ? $errors['number'] : ''; ?></span>
            </div>

            <div class="inputBox">
               <span class="label">Your Email :</span>
               <input type="email" name="email" placeholder="Enter your email">
               <span class="validation-message"><?php echo isset($errors['email']) ? $errors['email'] : ''; ?></span>
            </div>

            <div class="inputBox">
               <span class="label">Payment Method :</span>
               <select name="method">
                  <option value="cash on delivery">Cash on Delivery</option>
                  <option value="credit card">Credit Card</option>
                  <option value="paypal">Paypal</option>
                  <option value="paytm">Paytm</option>
               </select>
               <span class="validation-message"><?php echo isset($errors['method']) ? $errors['method'] : ''; ?></span>
            </div>

            <div class="inputBox">
               <span class="label">Country :</span>
               <select name="country">
                  <option value='India'>India</option>
               </select>
               <span class="validation-message"><?php echo isset($errors['country']) ? $errors['country'] : ''; ?></span>
            </div>

            <div class="inputBox">
               <span class="label">State :</span>
               <select name="state">
                  <option selected disabled value=''>Select State</option>
                  <option value="AN">Andaman and Nicobar Islands</option>
                  <option value="AP">Andhra Pradesh</option>
                  <option value="AR">Arunachal Pradesh</option>
                  <option value="AS">Assam</option>
                  <option value="BR">Bihar</option>
                  <option value="CH">Chandigarh</option>
                  <option value="CT">Chhattisgarh</option>
                  <option value="DN">Dadra and Nagar Haveli</option>
                  <option value="DD">Daman and Diu</option>
                  <option value="DL">Delhi</option>
                  <option value="GA">Goa</option>
                  <option value="GA">Gujarat</option>
                  <option value="HR">Haryana</option>
                  <option value="HP">Himachal Pradesh</option>
                  <option value="JK">Jammu and Kashmir</option>
                  <option value="JH">Jharkhand</option>
                  <option value="KA">Karnataka</option>
                  <option value="KL">Kerala</option>
                  <option value="LD">Lakshadweep</option>
                  <option value="MP">Madhya Pradesh</option>
                  <option value="ML">Meghalaya</option>
                  <option value="MH">Maharashtra</option>
                  <option value="MN">Manipur</option>
                  <option value="MZ">Mizoram</option>
                  <option value="NL">Nagaland</option>
                  <option value="OR">Odisha</option>
                  <option value="PY">Puducherry</option>
                  <option value="PB">Punjab</option>
                  <option value="RJ">Rajasthan</option>
                  <option value="SK">Sikkim</option>
                  <option value="TN">Tamil Nadu</option>
                  <option value="TG">Telangana</option>
                  <option value="TR">Tripura</option>
                  <option value="UT">Uttarakhand</option>
                  <option value="UP">Uttar Pradesh</option>
                  <option value="WB">West Bengal</option>
               </select>
               <span class="validation-message"><?php echo isset($errors['state']) ? $errors['state'] : ''; ?></span>
            </div>

            <div class="inputBox">
               <span class="label">City :</span>
               <input type="text" name="city" placeholder="e.g. Mumbai">
               <span class="validation-message"><?php echo isset($errors['city']) ? $errors['city'] : ''; ?></span>
            </div>

            <div class="inputBox">
               <span class="label">Street Name :</span>
               <input type="text" name="street" placeholder="e.g. Street Name">
               <span class="validation-message"><?php echo isset($errors['street']) ? $errors['street'] : ''; ?></span>
            </div>

            <div class="inputBox">
               <span class="label">Flat No :</span>
               <input type="number" min="0" name="flat" placeholder="e.g. Flat No.">
               <span class="validation-message"><?php echo isset($errors['flat']) ? $errors['flat'] : ''; ?></span>
            </div>

            <div class="inputBox">
               <span class="label">Pin Code :</span>
               <input type="number" min="0" name="pin_code" placeholder="e.g. 123456">
               <span class="validation-message"><?php echo isset($errors['pin_code']) ? $errors['pin_code'] : ''; ?></span>
            </div>
         </div>
      </div>
      
      <input type="submit" value="order now" class="btn" name="order_btn">
   </form>

</section>

<?php include 'footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>



</body>
</html>