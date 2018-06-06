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
 <nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>                        
      </button>
      <a class="navbar-brand">Hi <?php echo $email; ?></a>
    </div>
    <div class="collapse navbar-collapse" id="myNavbar">
      <ul class="nav navbar-nav">
        
     
      </ul>
      <ul class="nav navbar-nav navbar-right">
      <li class="dropdown">
          <a class="dropdown-toggle" data-toggle="dropdown" href="#">Settings <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="changeEmail.php">Change Email</a></li>
            <li><a href="changePassword.php">Change Password</a></li>
			<li><a href="deleteAccount.php">Delete your Account</a></li>
          </ul>
        
        <li><a href="logout.php"><span class="glyphicon glyphicon-log-in"></span> Logout</a></li>
      </ul>
    </div>
  </div>
</nav>
  WOO!
</body>

</html>
