<?php
// Check if there are any messages to display
if(isset($message)){
   // Loop through each message and display it
   foreach($message as $message){
      echo '
      <div class="message">
         <span>'.$message.'</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
   }
}
?>

<nav class="navbar navbar-expand-lg bg-primary" data-bs-theme="dark">
<div class="container-fluid">
      <a href="../pages/home.php" class="navbar-brand">ID CARD</a>
      
      <?php 
            // Check if the user is logged in and retrieve their profile information
            $select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
            $select_profile->execute([$user_id]);
            if($select_profile->rowCount() > 0){
               $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
         ?>
         <!-- Display a welcome message with the user's name -->
         <h2 class="text-black"> welcome <span><?= $fetch_profile['name']; ?></span></h2>
       <div>
         <!-- Buttons for updating profile and logging out -->
         <button class="btn bg-black"><a href="update.php">update profile</a></button>
         <button class="btn btn-danger"><a href="../components/user_logout.php">LOGOUT</a></button>
         <?php
            }else{
         ?>
            <!-- Display a message if the user is not logged in -->
            <p class="name">login or register!</p>
            <div class="flex-btn">
               <a href="login.php" class="option-btn">login</a>
               <a href="register.php" class="option-btn">register</a>
            </div> 
         <?php
          }
         ?>
      </div>
</nav>
