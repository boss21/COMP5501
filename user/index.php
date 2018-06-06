<?php

// Initialize the session
session_start();
 
// If session variable is not set it will redirect to login page
if(!isset($_SESSION['email']) || empty($_SESSION['email'])){
  header("location: ../index.php");
  exit;
}

// Include config file
require_once '../dbconfig.php';

$email = $_SESSION['email'];

// Attempt select query execution
$sql = "SELECT * FROM users WHERE email = '$email'";
$result = mysqli_query($link, $sql);
$row = mysqli_fetch_array($result);

//grab data here

// Free result set
mysqli_free_result($result);
 
// Close connection
mysqli_close($link);

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
  <title>The Financial Wizard</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.css">
</head>

<body>
 
<div class="navbar">
   <a href="#home">Welcome <?php echo $email; ?> to The Financial Wizard</a>
  
   <div class="dropdown">
     <button class="dropbtn">Settings 
       <i class="fa fa-caret-down"></i>
     </button>
     <div class="dropdown-content">
       <a href="changeEmail.php">Change Email</a>
       <a href="changePassword.php">Change Password</a>
       <a href="deleteAccount.php">Delete Account</a>
     </div>
   </div>
   <b href="logout.php">Logout</a>
 </div>
	
	
	
  WOO!
</body>

</html>
