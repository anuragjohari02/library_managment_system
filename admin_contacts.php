<?php

include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:login.php');
};

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>messages</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">

</head>
<body>
   
<?php include 'admin_header.php'; ?>

<section class="messages">

   <h1 class="title"> messages </h1>

   <!-- Show data into table -->
   <section class="search-form">
   <div class="container">
      <table class="table">
      <thead>
         <tr>
         <th scope="col">Name</th>
         <th scope="col">Number</th>
         <th scope="col">Email</th>
         <th scope="col">View Details</th>
         </tr>
      </thead>
      <tbody>
         <?php
            $sql = "SELECT * FROM `message`";
            $result = mysqli_query($conn,$sql);
            if($result){
               while($row = mysqli_fetch_assoc($result)){
                  $name = $row['name'];
                  $number = $row['number'];
                  $email = $row['email'];
                  $user_id = $row['id'];                  
                  echo '<tr>
                  <th scope="row">'.$name.'</th>
                  <td>'.$number.'</td>
                  <td>'.$email.'</td>
                  <td>
                     <a class="btn btn-primary" href="admin_view_message.php?user_id='.$user_id.'">View Details</a>
                  </td>
                  </tr>';
               }
            }
         ?>
      </tbody>
      </table>
   </div>
   </section>

</section>

<!-- custom admin js file link  -->
<script src="js/admin_script.js"></script>

</body>
</html>