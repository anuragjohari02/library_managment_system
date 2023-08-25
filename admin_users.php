<?php

include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:login.php');
}

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   mysqli_query($conn, "DELETE FROM `users` WHERE id = '$delete_id'") or die('query failed');
   header('location:admin_users.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Users</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">

</head>
<body>
   
<?php include 'admin_header.php'; ?>

<section class="users">

   <h1 class="title"> User Accounts </h1>

   <!-- multiple dropdown for email selection -->
    <div class="multiselect">
        <div class="selectBox" onclick="showCheckboxes()">
            <select>
              <option selected disabled hidden>Select Emails</option>
            </select>
            <div class="overSelect"></div>
        </div>
        <div id="checkboxes">
            <?php
               $select_users = mysqli_query($conn, "SELECT * FROM `users`") or die('query failed');
               while($fetch_users = mysqli_fetch_assoc($select_users)){
            ?>
            <form method="POST">
            <?php
               $select_users = mysqli_query($conn, "SELECT * FROM `users`") or die('query failed');
               while($fetch_users = mysqli_fetch_assoc($select_users)){
                  ?>
                  <label for="option_<?php echo $fetch_users['id']; ?>">
                        <input type="checkbox" name="selected_emails[]" value="<?php echo $fetch_users['email']; ?>" id="option_<?php echo $fetch_users['id']; ?>"/>
                        <?php echo $fetch_users['email']; ?>
                  </label>
                  <?php
               }
               ?>
               <input type="submit" value="Choose Selected Emails">
            </form>
            <?php
               }
            ?>
        </div>
    </div>


<!-- Users -->
<?php
if(isset($_POST['selected_emails'])){
    $selectedEmails = $_POST['selected_emails'];

   if(!empty($selectedEmails)){
        ?>
      <div class="box-container">
            <?php
            foreach ($selectedEmails as $selectedEmail) {
                $select_user = mysqli_query($conn, "SELECT * FROM `users` WHERE email='$selectedEmail'") or die('query failed');
                $fetch_user = mysqli_fetch_assoc($select_user);

                if ($fetch_user) {
                    ?>
                    <div class="box">
                        <p> User Id : <span><?php echo $fetch_user['id']; ?></span> </p>
                        <p> Username : <span><?php echo $fetch_user['name']; ?></span> </p>
                        <p> Email : <span><?php echo $fetch_user['email']; ?></span> </p>
                        <p> User Type : <span style="color:<?php if($fetch_user['user_type'] == 'admin'){ echo 'var(--orange)'; } ?>"><?php echo $fetch_user['user_type']; ?></span> </p>
                        <a href="admin_users.php?delete=<?php echo $fetch_user['id']; ?>" onclick="return confirm('delete this user?');" class="delete-btn">delete user</a>
                    </div>
         <?php
                }
            }
         ?>
      </div>
        <?php
   }
}
?>

</section>

<!-- custom admin js file link  -->
<script src="js/admin_script.js"></script>

</body>
</html>

~