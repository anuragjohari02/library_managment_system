<?php

// admin_view.php

include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:login.php');
   exit();
}

if (!isset($_GET['order_id'])) {
   // If the order ID is not provided in the URL, redirect back to the admin_orders.php page or show an error message.
   header('location:admin_orders.php');
   exit();
}

if(isset($_POST['update_order'])){

   $order_update_id = $_POST['order_id'];
   $update_payment = $_POST['update_payment'];
   mysqli_query($conn, "UPDATE `orders` SET payment_status = '$update_payment' WHERE id = '$order_update_id'") or die('query failed');
   $message[] = 'Payment status has been updated!';

}

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   mysqli_query($conn, "DELETE FROM `orders` WHERE id = '$delete_id'") or die('query failed');
   header('location:admin_orders.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Orders</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">

</head>
<body>
   
<?php include 'admin_header.php'; ?>

<section class="orders">

   <h1 class="title">Order Detail</h1>

   <div class="box-container">

   <?php
      $order_id = $_GET['order_id'];
      $select_orders = mysqli_query($conn, "SELECT * FROM `orders` WHERE id = '$order_id'") or die('query failed');
      if(mysqli_num_rows($select_orders) > 0){
         while($order_data = mysqli_fetch_assoc($select_orders)){
   ?>
   <div class="box">
      <p> User Id : <span><?php echo $order_data['user_id']; ?></span> </p>
      <p> Placed On : <span><?php echo $order_data['placed_on']; ?></span> </p>
      <p> Name : <span><?php echo $order_data['name']; ?></span> </p>
      <p> Number : <span><?php echo $order_data['number']; ?></span> </p>
      <p> Email : <span><?php echo $order_data['email']; ?></span> </p>
      <p> Address : <span><?php echo $order_data['address']; ?></span> </p>
      <p> Total Products : <span><?php echo $order_data['total_products']; ?></span> </p>
      <p> Total Price : <span>$<?php echo $order_data['total_price']; ?>/-</span> </p>
      <p> Payment Method : <span><?php echo $order_data['method']; ?></span> </p>
      <form action="" method="post">
         <input type="hidden" name="order_id" value="<?php echo $order_data['id']; ?>">
         <select name="update_payment">
            <option value="" selected disabled><?php echo $order_data['payment_status']; ?></option>
            <option value="Pending">Pending</option>
            <option value="Completed">Completed</option>
         </select>
         <input type="submit" value="update" name="update_order" class="option-btn">
         <a href="admin_orders.php?delete=<?php echo $order_data['id']; ?>" onclick="return confirm('delete this order?');" class="delete-btn">delete</a>
      </form>
   </div>
   <?php
         }
      }else{
         echo '<p class="empty">no orders placed yet!</p>';
      }
   ?>

</div>

<!-- custom admin js file link  -->
<script src="js/admin_script.js"></script>

</body>
</html>

