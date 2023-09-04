<?php
// Include the database connection file
include '../components/connect.php';

// Start a session
session_start();

// Check if a user is logged in and retrieve their user_id if available
if (isset($_SESSION['user_id'])) {
   $user_id = $_SESSION['user_id'];
} else {
   $user_id = '';
}

// Initialize a message variable
$messagee = 'FILL REQUIRED DETAILS';

// Check if the login form has been submitted
if (isset($_POST['submit'])) {

   // Retrieve and sanitize user input for email and password
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $pass = sha1($_POST['pass']); // Hash the password (this is not recommended, consider using bcrypt or a more secure method)
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);

   // Check if there is a matching user in the database
   $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ? AND password = ?");
   $select_user->execute([$email, $pass]);
   $row = $select_user->fetch(PDO::FETCH_ASSOC);

   if ($select_user->rowCount() > 0) {
      // Set the user's session and redirect to the home page
      $_SESSION['user_id'] = $row['id'];
      header('location:home.php');
   } else {
      // Display an error message if the login credentials are incorrect
      $message[] = 'incorrect username or password!';
      $messagee = 'incorrect username or password!';
   }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Login</title>
   <!-- Include FontAwesome for icons -->
   <script src="https://kit.fontawesome.com/d2314fe5d0.js" crossorigin="anonymous"></script>
   <!-- Include your custom CSS file -->
   <link rel="stylesheet" href="../styles/bootstrap_VAPOUR.css">
</head>

<body>
<nav class="navbar navbar-expand-lg bg-black">
  <div class="container-fluid">
    <?php echo $messagee ?>
  </div>
</nav>
   <section class="form-container p-5">
      <h3>Login Now</h3>
      <form action="" method="post">
         <legend>Fill Out All Required Info</legend>
         <fieldset>
            <div class="form-group">
               <label for="code-text" class="form-label mt-4">Enter Your Email</label>
               <input type="email" name="email" required placeholder="Enter your email" class="box form-control" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
            </div>
            <div class="form-group">
               <label for="code-text" class="form-label mt-4">Enter Your Password</label>
               <input type="password" name="pass" required placeholder="Enter your password" class="box form-control" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
            </div>
            <input type="submit" value="Login Now" name="submit" class="btn btn-primary mt-3">
         </fieldset>
      </form>
      <div class="text-center">Don't have an account? <a href="register.php">Register Now</a></div>
   </section>
   <?php include '../components/footer.php'; ?>
   <script src="script.js"></script>
</body>

</html>
