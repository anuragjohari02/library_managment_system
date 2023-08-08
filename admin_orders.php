<?php

include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:login.php');
}

if(isset($_POST['update_order'])){

   $order_update_id = $_POST['order_id'];
   $update_payment = $_POST['update_payment'];
   mysqli_query($conn, "UPDATE `orders` SET payment_status = '$update_payment' WHERE id = '$order_update_id'") or die('query failed');
   $message[] = 'payment status has been updated!';

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
   <title>orders</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">

   <!-- Bootstrap CSS -->
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css">

</head>
<body>
   
<?php include 'admin_header.php'; ?>

<section class="orders">

   <h1 class="title">placed orders</h1>

   <!-- Show data into table -->

   <div class="container">
      <table class="table">
      <thead>
         <tr>        
         <th scope="col">Name</th>
         <th scope="col">Product</th>
         <th scope="col">Date</th>
         <th scope="col">View Details</th>
         </tr>
      </thead>
         <tbody>
            <?php
               $sql = "SELECT * FROM `orders`";
               $result = mysqli_query($conn,$sql);
               if($result){
                  while($row = mysqli_fetch_assoc($result)){
                     $name = $row['name'];
                     $total_products = $row['total_products'];
                     $placed_on = $row['placed_on'];
                     $order_id = $row['id']; // Get the order ID

                     echo '<tr>
                     <th scope="row">'.$name.'</th>
                     <td>'.$total_products.'</td>
                     <td>'.$placed_on.'</td>
                     <td>
                     <button class="btn btn-primary viewDetailsButton" data-order-id="'.$order_id.'">View Details</button>                
                     </td>
                     </tr>';
                  }
               }
            ?>
         </tbody>
      </table>
   </div>

<div class="box-container">
   <?php
   $select_orders = mysqli_query($conn, "SELECT * FROM `orders`") or die('query failed');
   if(mysqli_num_rows($select_orders) > 0){
      while($fetch_orders = mysqli_fetch_assoc($select_orders)){
   ?>
   <div class="box" id="viewDetails_<?php echo $fetch_orders['id']; ?>" style="display:none">
   <p> user id : <span><?php echo $fetch_orders['user_id']; ?></span> </p>
         <p> placed on : <span><?php echo $fetch_orders['placed_on']; ?></span> </p>
         <p> name : <span><?php echo $fetch_orders['name']; ?></span> </p>
         <p> number : <span><?php echo $fetch_orders['number']; ?></span> </p>
         <p> email : <span><?php echo $fetch_orders['email']; ?></span> </p>
         <p> address : <span><?php echo $fetch_orders['address']; ?></span> </p>
         <p> total products : <span><?php echo $fetch_orders['total_products']; ?></span> </p>
         <p> total price : <span>$<?php echo $fetch_orders['total_price']; ?>/-</span> </p>
         <p> payment method : <span><?php echo $fetch_orders['method']; ?></span> </p>
         <form action="" method="post">
            <input type="hidden" name="order_id" value="<?php echo $fetch_orders['id']; ?>">
            <select name="update_payment">
               <option value="" selected disabled><?php echo $fetch_orders['payment_status']; ?></option>
               <option value="pending">pending</option>
               <option value="completed">completed</option>
            </select>
            <input type="submit" value="update" name="update_order" class="option-btn">
            <a href="admin_orders.php?delete=<?php echo $fetch_orders['id']; ?>" onclick="return confirm('delete this order?');" class="delete-btn">delete</a>
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

<!-- Updated JavaScript to handle the button click and show the form -->
<script>
   const viewDetailsButtons = document.querySelectorAll(".viewDetailsButton");
   viewDetailsButtons.forEach(button => {
      button.addEventListener("click", function () {
         const orderId = button.getAttribute("data-order-id");
         const formContainer = document.getElementById("viewDetails_" + orderId);
         if (formContainer.style.display === "none") {
            formContainer.style.display = "block";
         } else {
            formContainer.style.display = "none";
         }
      });
   });
</script>
