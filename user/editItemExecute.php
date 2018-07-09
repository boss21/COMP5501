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
  if ($_POST['editItemMonthDay'] != "" && $_POST['itemName'] != ""){
    $editItemMonthDay = $_POST['editItemMonthDay'];
    $itemName = $_POST['itemName'];

    $itemMonth = date("m", strtotime($editItemMonthDay));
    $dateObj   = DateTime::createFromFormat('!m', $itemMonth);
    $itemMonth = $dateObj->format('F');
    $itemMonth = strtolower($itemMonth);
    
    $itemDay = date("d", strtotime($editItemMonthDay));

    // Attempt select query execution
    $sql = "SELECT itemAmount FROM $itemMonth WHERE email = '$email' AND day = '$itemDay' AND itemName = '$itemName'";
    $result = mysqli_query($link, $sql);
    $row = mysqli_fetch_assoc($result);
    $itemAmount = $row['itemAmount'];

    // Free result set
    mysqli_free_result($result);

    // Close connection
    mysqli_close($link);
  } else if ($_POST['editItemMonthDay'] != "" && $_POST['itemNameOld'] != "" && $_POST['itemNameUpdated'] != "" && $_POST['itemAmountUpdated'] != ""){
    $editItemMonthDay = $_POST['editItemMonthDay'];
    $itemNameOld = $_POST['itemNameOld'];
    $itemNameUpdated = $_POST['itemNameUpdated'];
    $itemAmountUpdated = $_POST['itemAmountUpdated'];

    $itemMonth = date("m", strtotime($editItemMonthDay));
    $dateObj   = DateTime::createFromFormat('!m', $itemMonth);
    $itemMonth = $dateObj->format('F');
    $itemMonth = strtolower($itemMonth);
    
    $itemDay = date("d", strtotime($editItemMonthDay));

    // Attempt select query execution
    $sql = "UPDATE $itemMonth SET itemName = '$itemNameUpdated', itemAmount = '$itemAmountUpdated' WHERE email = '$email' AND day = '$itemDay' AND itemName = '$itemNameOld'";
    if (mysqli_query($link, $sql)){
      mysqli_close($link);
      echo "<script type='text/javascript'>alert('Item Updated.');window.location='index.php';</script>";
    } else{
      mysqli_close($link);
      echo "<script type='text/javascript'>alert('Oops, Something Went Wrong. Please try again later.');window.location='index.php';</script>";
    }
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
            <a class="dropdown-item" href="../otherTools/capital">Best Way To Carry Forward Capital Losses</a>
            <a class="dropdown-item" href="../otherTools/loans">Best Way To Pay Back Loans</a>
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
        <form action="<?php echo htmlspecialchars($_SERVER[" PHP_SELF "]); ?>" method="post">
          <label>Name:</label>
          <input name="itemNameUpdated" type="text" required="required" maxlength="20" value="<?php echo $itemName ?>" class="form-control">
          <label>Amount (negative for an expense):</label>
          <input name="itemAmountUpdated" type="number" required="required" max="999999" step=".01" value="<?php echo $itemAmount ?>" class="form-control">
          <br>
          <div class="form-group">
            <input type="hidden" name="editItemMonthDay" value="<?php echo $editItemMonthDay ?>">
            <input type="hidden" name="itemNameOld" value="<?php echo $itemName ?>">
            <input type="submit" class="btn btn-primary" value="Update">
            <input type="reset" class="btn btn-default" value="Reset">
          </div>
        </form>
      </div>
      <div class="col-sm-4"></div>
    </div>
  </div>
</body>

</html>