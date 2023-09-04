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

// Check if the registration form has been submitted
if (isset($_POST['submit'])) {

   // Retrieve and sanitize user input for registration
   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $nValue = $_POST['nValue'];
   $nValue = filter_var($nValue, FILTER_SANITIZE_STRING);
   $contact = $_POST['contact'];
   $contact = filter_var($contact, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $pass = sha1($_POST['pass']); // Hash the password (this is not recommended, consider using bcrypt or a more secure method)
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);
   $cpass = sha1($_POST['cpass']);
   $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

   // Check if the email already exists in the database
   $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ?");
   $select_user->execute([$email]);
   $row = $select_user->fetch(PDO::FETCH_ASSOC);

   if ($select_user->rowCount() > 0) {
      $message[] = 'Email already exists!';
   } else {
      // Check if the password and confirm password match
      if ($pass != $cpass) {
         $message[] = 'Confirm password not matched!';
      } else {
         // Insert the user data into the database
         $insert_user = $conn->prepare("INSERT INTO `users`(name, email, password, nvalue, contact) VALUES(?,?,?,?,?)");
         $insert_user->execute([$name, $email, $cpass, $nValue, $contact]);
         // Retrieve the user data from the database
         $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ? AND password = ?");
         $select_user->execute([$email, $pass]);
         $row = $select_user->fetch(PDO::FETCH_ASSOC);
         if ($select_user->rowCount() > 0) {
            // Set the user's session and redirect to the home page
            $_SESSION['user_id'] = $row['id'];
            header('location:home.php');
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
   <title>Register</title>
   <!-- Include FontAwesome for icons -->
   <script src="https://kit.fontawesome.com/d2314fe5d0.js" crossorigin="anonymous"></script>
   <!-- Include your custom CSS file -->
   <link rel="stylesheet" href="../styles/bootstrap_VAPOUR.css">
</head>

<body>
   <?php include '../components/user_header.php'; ?>

   <section class="container p-5">
      <h1>Register</h1>
      <form action="" method="post">
         <legend>Fill Out All Required Info</legend>
         <fieldset>
            <div class="form-group">
               <label for="code-text" class="form-label mt-4">Enter Your Full Name</label>
               <input type="text" name="name" required placeholder="Enter your name" class="form-control" maxlength="50">
            </div>
            <div class="form-group">
               <label for="code-text" class="form-label mt-4">Email Address</label>
               <input type="email" name="email" required placeholder="Enter your email" class="form-control" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
            </div>
            <div class="form-group">
               <label for="code-text" class="form-label mt-4">How many of our goods have you purchased</label>
               <input type="number" name="nValue" required placeholder="Numbers of our goods you have purchased" class="form-control" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
            </div>
            <div class="form-group">
               <label for="code-text" class="form-label mt-4">Enter a Phone Number You Will Love to Display</label>
               <input type="number" name="contact" maxlength="15" required placeholder="Enter a phone number" class="form-control" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
            </div>
            <div class="form-group">
               <label for="code-text" class="form-label mt-4">Enter A Password</label>
               <input type="password" name="pass" required placeholder="Enter a password" class="form-control" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
            </div>
            <div class="form-group">
               <label for="code-text" class="form-label mt-4">Confirm Your Password</label>
               <input type="password" name="cpass" required placeholder="Confirm your password" class="form-control" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
            </div>
            <input type="submit" value="Register Now" name="submit" class="btn btn-primary mt-3">
         </fieldset>
      </form>
      <p>Already have an account? <a href="login.php">Login Now</a></p>
   </section>
   <?php include '../components/footer.php'; ?>
   <script src="script.js"></script>
</body>

</html>
