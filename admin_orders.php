<?php
include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
   header('location:login.php');
}

if (isset($_POST['update_order'])) {

   $order_update_id = $_POST['order_id'];
   $update_payment = $_POST['update_payment'];
   mysqli_query($conn, "UPDATE `orders` SET payment_status = '$update_payment' WHERE id = '$order_update_id'") or die('query failed');
   $message[] = 'payment status has been updated!';
}

if (isset($_GET['delete'])) {
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

      <h1 class="title">Placed Orders</h1>
      <?php
         if (isset($_GET['status'])) {
            $status = $_GET['status'];}else{
               $status = "";
            }
         ?>

               <!-- Filter Box -->
         <section class="search-form">
         <form class="example mb-10" action="" method="GET" style="margin:auto;max-width:300px; margin-right:0;">            
            <select name="status" class="status">
               <option disabled hidden value='' <?php echo (($status != 'Pending' || $status != 'Completed') ? "selected":"") ?> >Filter by Payment Status</option>
               <option <?php echo ($status == 'Pending' ? "selected":"") ?>  value="Pending">Pending</option>
               <option <?php echo ($status == 'Completed' ? "selected":"") ?> value="Completed">Completed</option>
            </select>
            <button type="submit" value="status"><i class="fa fa-search"></i></button>
         </form>

         <?php
         if (isset($_GET['status'])) {
            $search_item = $_GET['status'];
         ?>
            <div class="container">
               <table class="table">
                  <thead>
                     <tr>
                        <th scope="col">Name</th>
                        <th scope="col">Product</th>
                        <th scope="col">Date</th>
                        <th scope="col">Payment Status</th>
                        <th scope="col">View Details</th>
                     </tr>
                  </thead>
                  <tbody>
                     <?php
                     $sql = "SELECT * FROM `orders` WHERE `payment_status` LIKE '%$search_item%'";
                     $result = mysqli_query($conn, $sql);
                     if ($result) {
                        while ($row = mysqli_fetch_assoc($result)) {
                           $name = $row['name'];
                           $total_products = $row['total_products'];
                           $placed_on = $row['placed_on'];
                           $payment_status = $row['payment_status'];
                           $order_id = $row['id'];                           
                           echo '<tr>
                              <th scope="row">'.$name.'</th>
                              <td>'.$total_products.'</td>
                              <td>'.$placed_on.'</td>
                              <td>'.$payment_status.'</td>
                              <td>
                                 <a class="btn btn-primary" href="admin_view.php?order_id=' . $order_id . '">View Details</a>
                              </td>
                           </tr>';
                        }
                     }
                     ?>
                  </tbody>
               </table>
            </div>
         </section>
         <?php
            }else{
         ?>

      <!-- Search Bar -->
      <section class="search-form">
         <form class="example mb-10" action="" method="GET" style="margin:auto;max-width:300px; margin-right:0;">
            <input type="text" placeholder="Search.." name="search">
            <button type="submit" value="search"><i class="fa fa-search"></i></button>
         </form>

         <?php
         if (isset($_GET['search'])) {
            $search_item = $_GET['search'];
         ?>
            <div class="container">
               <table class="table">
                  <thead>
                     <tr>
                        <th scope="col">Name</th>
                        <th scope="col">Product</th>
                        <th scope="col">Date</th>
                        <th scope="col">Payment Status</th>
                        <th scope="col">View Details</th>
                     </tr>
                  </thead>
                  <tbody>
                     <?php
                     $sql = "SELECT * FROM `orders` WHERE `total_products` LIKE '%$search_item%'";
                     $result = mysqli_query($conn, $sql);
                     if ($result) {
                        while ($row = mysqli_fetch_assoc($result)) {
                           $name = $row['name'];
                           $total_products = $row['total_products'];
                           $placed_on = $row['placed_on'];
                           $payment_status = $row['payment_status'];
                           $order_id = $row['id'];                           
                           echo '<tr>
                              <th scope="row">'.$name.'</th>
                              <td>'.$total_products.'</td>
                              <td>'.$placed_on.'</td>
                              <td>'.$payment_status.'</td>
                              <td>
                                 <a class="btn btn-primary" href="admin_view.php?order_id=' . $order_id . '">View Details</a>
                              </td>
                           </tr>';
                        }
                     }
                     ?>
                  </tbody>
               </table>
            </div>
         </section>
         <?php
         } else{
         ?>

   <!-- Show data into table -->
   <section class="search-form">
   <div class="container">
      <table class="table">
      <thead>
         <tr>
         <th scope="col">Name</th>
         <th scope="col">Product</th>
         <th scope="col">Date</th>
         <th scope="col">Payment Status</th>
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
                  $payment_status = $row['payment_status'];
                  $order_id = $row['id'];                  
                  echo '<tr>
                  <th scope="row">'.$name.'</th>
                  <td>'.$total_products.'</td>
                  <td>'.$placed_on.'</td>
                  <td>'.$payment_status.'</td>
                  <td>
                     <a class="btn btn-primary" href="admin_view.php?order_id='.$order_id.'">View Details</a>
                  </td>
                  </tr>';
               }
            }
         ?>
      </tbody>
      </table>
   </div>
   </section>
   <?php 
   }
}
   ?>
   </section>

   <!-- custom admin js file link  -->
<script src="js/admin_script.js"></script>

</body>
</html>