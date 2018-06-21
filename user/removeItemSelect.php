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

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
  if ($_POST['removeItemMonthDay'] != ""){
    $removeItemMonthDay = $_POST['removeItemMonthDay'];

    $itemMonth = date("m", strtotime($removeItemMonthDay));
    $dateObj   = DateTime::createFromFormat('!m', $itemMonth);
    $itemMonth = $dateObj->format('F');
    $itemMonth = strtolower($itemMonth);
    
    $itemDay = date("d", strtotime($removeItemMonthDay));
  } else{
    echo "<script type='text/javascript'>window.location='index.php';</script>";
  }
}

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
        <li class="nav-item">
          <a class="nav-link" href="index.php">Home</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true"
            aria-expanded="false">
            More Financial Tools
          </a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdown">
            <a class="dropdown-item" href="../otherTools/capital">Carry Forward Capital Losses</a>
            <a class="dropdown-item" href="../otherTools/loans">Loans</a>
          </div>
        </li>
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
      <div class="col-sm-4"></div>
      <div class="col-sm-4 text-center">
        <form action="removeItemExecute.php" method="post">
          <label>Item Name:</label>
          <select id="itemName" class="form-control">
            <?php
            // Attempt select query execution
            $sql = "SELECT itemName FROM $itemMonth WHERE email = '$email' AND day = '$itemDay'";
            $result = mysqli_query($link, $sql);
            if (mysqli_num_rows($result) == 0 && $_POST['removeItemMonthDay'] != ""){
              echo "<script type='text/javascript'>alert('No items found for $itemMonth $itemDay.');window.location='index.php';</script>";
            } else if ($_POST['removeItemMonthDay'] != ""){
              while ($row = mysqli_fetch_assoc($result)){
                $itemName = $row['itemName'];
                echo "<option value='$itemName'>$itemName</option>";
              }
            } else{
              echo "<script type='text/javascript'>window.location='index.php';</script>";
            }

            // Free result set
            mysqli_free_result($result);

            // Close connection
            mysqli_close($link);
            ?>
          </select>
          <br>
          <div class="form-group">
            <input type="hidden" id="removeItemMonthDay" value="<?php echo $removeItemMonthDay ?>">
            <input type="submit" class="btn btn-primary" value="Submit">
            <input type="reset" class="btn btn-default" value="Reset">
          </div>
        </form>
      </div>
      <div class="col-sm-4"></div>
    </div>
  </div>
</body>

</html>