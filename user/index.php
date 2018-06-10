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
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.js"></script>
</head>

<body>
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <span>Hello,
      <?php echo $email ?>
    </span>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
      aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true"
            aria-expanded="false">
            Settings
          </a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdown">
            <a class="dropdown-item" href="changeEmail.php">Change Email</a>
            <a class="dropdown-item" href="changePassword.php">Change Password</a>
            <a class="dropdown-item" href="deleteAccount.php" onclick="return confirm('Are you sure you want to delete your account?')">Delete Account</a>
          </div>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="logout.php">Logout</a>
        </li>
    </div>
  </nav>
  <div class="container">
    <div class="row">
      <div class="col-sm-12">
        <form>
          <div class="form-group">
            Select Month:
            <select id="itemMonth" class="form-control">
              <option value="january">January</option>
              <option value="february">February</option>
              <option value="march">March</option>
              <option value="april">April</option>
              <option value="may">May</option>
              <option value="june">June</option>
              <option value="july">July</option>
              <option value="august">August</option>
              <option value="september">September</option>
              <option value="october">October</option>
              <option value="november">November</option>
              <option value="december">December</option>
            </select>
          </div>
          <div class="form-group">
            <input type="submit" id="addItem" class="btn btn-primary" value="Add">
            <input type="reset" class="btn btn-default" value="Reset">
          </div>
        </form>
      </div>
    </div>
    <div class="row">
      <div class="col-sm-12">
        <div id="displayItems"></div>
      </div>
    </div>
  </div>
</body>

</html>