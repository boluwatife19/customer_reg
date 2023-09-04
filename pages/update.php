<?php

include '../components/connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
   $user_id = $_SESSION['user_id'];
} else {
   $user_id = '';
   header('location:home.php'); // Redirect to the home page if the user is not logged in.
};

if (isset($_POST['submit'])) {

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);

   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);

   $nvalue = $_POST['nvalue'];
   $nvalue = filter_var($nvalue, FILTER_SANITIZE_STRING);

   $contact = $_POST['contact'];
   $contact = filter_var($contact, FILTER_SANITIZE_STRING);

   // Update user's name if it's not empty.
   if (!empty($name)) {
      $update_name = $conn->prepare("UPDATE `users` SET name = ? WHERE id = ?");
      $update_name->execute([$name, $user_id]);
   }

   // Update user's nvalue (number of goods purchased) if it's not empty.
   if (!empty($nvalue)) {
      $update_nvalue = $conn->prepare("UPDATE `users` SET nvalue = ? WHERE id = ?");
      $update_nvalue->execute([$nvalue, $user_id]);
   }
   
   // Update user's contact if it's not empty.
   if (!empty($contact)) {
      $update_contact = $conn->prepare("UPDATE `users` SET contact = ? WHERE id = ?");
      $update_contact->execute([$contact, $user_id]);
   }

   // Check if the provided email already exists in the database.
   if (!empty($email)) {
      $select_email = $conn->prepare("SELECT * FROM `users` WHERE email = ?");
      $select_email->execute([$email]);
      if ($select_email->rowCount() > 0) {
         $message[] = 'email already taken!';
      } else {
         // Update user's email if it's not empty and doesn't exist in the database.
         $update_email = $conn->prepare("UPDATE `users` SET email = ? WHERE id = ?");
         $update_email->execute([$email, $user_id]);
      }
   }

   $empty_pass = '$2y$10$WDWQNlkpazdg1cs3HfF2B.v4OXCQtu6u1sKFas7H8qX.Yw0mIDSPm';
   $select_prev_pass = $conn->prepare("SELECT password FROM `users` WHERE id = ?");
   $select_prev_pass->execute([$user_id]);
   $fetch_prev_pass = $select_prev_pass->fetch(PDO::FETCH_ASSOC);
   $prev_pass = $fetch_prev_pass['password'];
   $old_pass = sha1($_POST['old_pass']);
   $old_pass = filter_var($old_pass, FILTER_SANITIZE_STRING);
   $new_pass = sha1($_POST['new_pass']);
   $new_pass = filter_var($new_pass, FILTER_SANITIZE_STRING);
   $confirm_pass = sha1($_POST['confirm_pass']);
   $confirm_pass = filter_var($confirm_pass, FILTER_SANITIZE_STRING);

   // Check if the old password matches the stored password.
   if ($old_pass != $empty_pass) {
      if ($old_pass != $prev_pass) {
         $message[] = 'old password not matched!';
      } elseif ($new_pass != $confirm_pass) {
         $message[] = 'confirm password not matched!';
      } else {
         if ($new_pass != $empty_pass) {
            // Update the user's password if the old password is correct and the new passwords match.
            $update_pass = $conn->prepare("UPDATE `users` SET password = ? WHERE id = ?");
            $update_pass->execute([$confirm_pass, $user_id]);
            $message[] = 'password updated successfully!';
         } else {
            $message[] = 'please enter a new password!';
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
   <title>update profile</title>
   <script src="https://kit.fontawesome.com/d2314fe5d0.js" crossorigin="anonymous"></script>

   <link rel="stylesheet" href="../styles/bootstrap_VAPOUR.css">

</head>

<body>

   <?php include '../components/user_header.php'; ?>

   <section class="container p-5">

      <h1>REGISTER</h1>

      <form action="" method="post">

         <legend>Fill Out All Required Info</legend>

         <fieldset>

            <div class="form-group">
               <label for="code-text" class="form-label mt-4">Enter Your Full Name</label>
               <input type="text" name="name" required placeholder="<?= $fetch_profile['name']; ?>" class="form-control" maxlength="50">
            </div>

            <div class="form-group">
               <label for="code-text" class="form-label mt-4">Email Address</label>
               <input type="email" name="email" required placeholder="<?= $fetch_profile['email']; ?>" class="form-control" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
            </div>

            <div class="form-group">
               <label for="code-text" class="form-label mt-4">How many of our goods have you purchased</label>
               <input type="number" name="nvalue" required placeholder="<?= $fetch_profile['nvalue']; ?>" class="form-control" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
            </div>

            <div class="form-group">
               <label for="code-text" class="form-label mt-4">Enter a Phone Number You will love to display</label>
               <input type="number" name="contact" max-length="15" required placeholder="<?= $fetch_profile['contact']; ?>" class="form-control" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
            </div>

            <div class="form-group">
               <label for="code-text" class="form-label mt-4">Enter Your Old Password</label>
               <input type="password" name="old_pass" placeholder="enter your old password" required placeholder="Enter a password" class="form-control" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
            </div>

            <div class="form-group">
               <label for="code-text" class="form-label mt-4">Enter a new Your Password</label>
               <input type="password" name="new_pass" placeholder="enter your new password" required class="form-control" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
            </div>

            <div class="form-group">
               <label for="code-text" class="form-label mt-4">Comfirm Your New Password</label>
               <input type="password" name="confirm_pass" placeholder="enter your new password" required class="form-control" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
            </div>

            <input type="submit" value="Update Now" name="submit" class="btn btn-primary mt-3">
         </fieldset>
      </form>

      <p>already have an account? <a href="login.php">login now</a></p>

   </section>
   <?php include '../components/footer.php'; ?>
   <script src="script.js"></script>

</body>

</html>
