<?php
// Include the database connection file
include '../components/connect.php';

// Start a session
session_start();

// Check if a user is logged in and retrieve their user_id if available
if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Home Page</title>
   <!-- Include FontAwesome for icons -->
   <script src="https://kit.fontawesome.com/d2314fe5d0.js" crossorigin="anonymous"></script>

   <!-- Include your custom CSS file -->
   <link rel="stylesheet" href="../styles/bootstrap_VAPOUR.css">

</head>
<body>
   
<?php include '../components/user_header.php'; ?>
<?php
// Fetch the user profile from the database based on the user_id
$select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
$select_profile->execute([$user_id]);
if($select_profile->rowCount() > 0){
   $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
?>
<div class="card">
      <div class="card-header">
        <h3 class="mb-0">Employee ID Card</h3>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-4">
            <!-- Display the employee photo (you can replace 'catwoman.jpg' with the actual photo) -->
            <img src="../catwoman.jpg" alt="Employee Photo" class="img-fluid">
          </div>
          <div class="col-md-8">
            <!-- Display employee details from the database -->
            <h4 class="card-title"><?= $fetch_profile['name']; ?></h4>
            <p class="card-text"><strong>Employee ID:</strong> EMP12345</p>
            <p class="card-text"><strong>Value:</strong> <?= $fetch_profile['nvalue']; ?></p>
            <p class="card-text"><strong>Position:</strong>
            <?php 
            // Determine the employee position based on the 'nvalue' field
            if($fetch_profile['nvalue'] < 10){
               echo "New Customer";
            }elseif($fetch_profile['nvalue'] <= 50){
               echo "Intermediate Customer";
            }else{
               echo "VIP CLIENT";
            }
            ?></p>
            <p class="card-text"><strong>Contact:</strong><?= $fetch_profile['contact']; ?></p>
          </div>
        </div>
      </div>
    </div>
    <?php
}else{
?>
   <!-- Display a message if the user is not logged in -->
   <p class="name">Please ensure to login first!</p>
   <a href="login.php" class="option-btn">Login</a>
<?php
}
?>


<?php include '../components/footer.php'; ?>
<script src="script.js"></script>

</body>
</html>
