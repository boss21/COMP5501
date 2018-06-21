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
  if ($_POST['itemMonthDay'] != "" && $_POST['itemName'] != "" && $_POST['itemAmount'] != ""){
    $itemMonthDay = $_POST['itemMonthDay'];

    $itemMonth = date("m", strtotime($itemMonthDay));
    $dateObj   = DateTime::createFromFormat('!m', $itemMonth);
    $itemMonth = $dateObj->format('F');
    $itemMonth = strtolower($itemMonth);
    
    $itemDay = date("d", strtotime($itemMonthDay));
    $itemName = $_POST['itemName'];
    $itemAmount = $_POST['itemAmount'];

    // Attempt select query execution
    $sql = "SELECT * FROM $itemMonth WHERE email = '$email' AND day = '$itemDay' AND itemName = '$itemName'";
    $result = mysqli_query($link, $sql);
    if (mysqli_num_rows($result) != 0){
      $dup = true;
    }

    // Free result set
    mysqli_free_result($result);

    // Attempt select query execution
    $sql = "INSERT INTO $itemMonth (email, day, itemName, itemAmount, timestamp) VALUES ('$email', '$itemDay', '$itemName', '$itemAmount', '$itemMonthDay')";
    if ($dup == false){
      mysqli_query($link, $sql);
      mysqli_close($link);
      echo "<script type='text/javascript'>alert('Item Successfully Added.');</script>";
    } else if ($dup == true){
      mysqli_close($link);
      echo "<script type='text/javascript'>alert('Duplicate Item, Not Adding. If you need to edit an item, click Edit Item.');</script>";
    }
  } else if ($_POST['currentBal'] != ""){
    $currentBal = $_POST['currentBal'];
    // Attempt select query execution
    $sql = "UPDATE users SET currentBalance = '$currentBal' WHERE email = '$email'";
    if (mysqli_query($link, $sql)){
      mysqli_close($link);
      echo "<script type='text/javascript'>alert('Current Balance Updated.'); window.location = 'index.php';</script>";
    }
  } else if ($_POST['month'] != ""){
    $month = $_POST['month'];
    if ($month == "all"){
      // Attempt select query execution
      $sql1 = "DELETE FROM january WHERE email = '$email'";
      $sql2= "DELETE FROM february WHERE email = '$email'";
      $sql3= "DELETE FROM march WHERE email = '$email'";
      $sql4= "DELETE FROM april WHERE email = '$email'";
      $sql5= "DELETE FROM may WHERE email = '$email'";
      $sql6= "DELETE FROM june WHERE email = '$email'";
      $sql7= "DELETE FROM july WHERE email = '$email'";
      $sql8= "DELETE FROM august WHERE email = '$email'";
      $sql9= "DELETE FROM september WHERE email = '$email'";
      $sql10= "DELETE FROM october WHERE email = '$email'";
      $sql11= "DELETE FROM november WHERE email = '$email'";
      $sql12= "DELETE FROM december WHERE email = '$email'";
      if (mysqli_query($link, $sql1) && mysqli_query($link, $sql2) && mysqli_query($link, $sql3) && mysqli_query($link, $sql4) && mysqli_query($link, $sql5) && mysqli_query($link, $sql6) && mysqli_query($link, $sql7) && mysqli_query($link, $sql8) && mysqli_query($link, $sql9) && mysqli_query($link, $sql10) && mysqli_query($link, $sql11) && mysqli_query($link, $sql12)){
        mysqli_close($link);
        echo "<script type='text/javascript'>alert('$month cleared.'); window.location = 'index.php';</script>";
      }
    } else{
      // Attempt select query execution
      $sql = "DELETE FROM $month WHERE email = '$email'";
      if (mysqli_query($link, $sql)){
        mysqli_close($link);
        echo "<script type='text/javascript'>alert('$month cleared.'); window.location = 'index.php';</script>";
      }
    }
  }
}

// Attempt select query execution
$sql = "UPDATE users SET januaryBalance = NULL, februaryBalance = NULL, marchBalance = NULL, aprilBalance = NULL, mayBalance = NULL, juneBalance = NULL, julyBalance = NULL, augustBalance = NULL, septemberBalance = NULL, octoberBalance = NULL, novemberBalance = NULL, decemberBalance = NULL WHERE email = '$email'";
mysqli_query($link, $sql);

// Attempt select query execution
$sql = "SELECT * FROM users WHERE email = '$email'";
$result = mysqli_query($link, $sql);
$row = mysqli_fetch_array($result);

//grab data here
$currentBal = $row['currentBalance'];

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
  <style>
    .btn-grid {
      margin: -2px 0;
    }

    .btn-grid>.btn {
      margin: 2px 0;
    }

    .col-centered {
      float: none;
      margin: 0 auto;
    }
  </style>
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
      <div class="col-sm-12 text-center">
        <form action="<?php echo htmlspecialchars($_SERVER[" PHP_SELF "]); ?>" method="post">
          <div class="form-group col-sm-6 col-centered">
            <label>Current Balance:</label>
            <input name="currentBal" type="number" required="required" min="1" max="999999" step=".01" value="<?php echo $currentBal ?>"
              class="form-control">
          </div>
          <br>
          <div class="form-group">
            <input type="submit" class="btn btn-primary" value="Save">
            <input type="reset" class="btn btn-default" value="Reset">
          </div>
        </form>
      </div>
    </div>
  </div>
</body>

</html>