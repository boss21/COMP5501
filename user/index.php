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
    $sql = "INSERT INTO $itemMonth (email, day, itemName, itemAmount) VALUES ('$email', '$itemDay', '$itemName', '$itemAmount')";
    if ($dup == false){
      mysqli_query($link, $sql);
      mysqli_close($link);
      echo "<script type='text/javascript'>alert('Item Successfully Added.');</script>";
    } else if ($dup == true){
      mysqli_close($link);
      echo "<script type='text/javascript'>alert('Duplicate Item, Not Adding.');</script>";
    }
  } else if ($_POST['currentBal'] != ""){
    $currentBal = $_POST['currentBal'];
    // Attempt select query execution
    $sql = "INSERT INTO users (currentBalance) VALUES ('$currentBal')";
    if (mysqli_query($link, $sql)){
      mysqli_close($link);
      echo "<script type='text/javascript'>alert('Current Balance Updated.');</script>";
    }
  }
}

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
    .btn-grid
    {
        margin: -2px 0;
    }

    .btn-grid > .btn
    {
        margin: 2px 0;
    }
  </style>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.js"></script>
  <script>
    window.onload = function () {
      $("#currentBalance").hide();
      $("#addItem").hide();
      $("#editItem").hide();
      $("#removeItem").hide();
      document.getElementById("currentBalanceButton").addEventListener("click", currentBalanceShow);
      document.getElementById("addItemButton").addEventListener("click", addItemShow);
      document.getElementById("editItemButton").addEventListener("click", editItemShow);
      document.getElementById("removeItemButton").addEventListener("click", removeItemShow);

      //June
      updateJune();
      //setInterval( "updateJune()", 1000 );
    }
    function currentBalanceShow() {
      $("#currentBalance").show();
      $("#addItem").hide();
      $("#editItem").hide();
      $("#removeItem").hide();
    }
    function addItemShow() {
      $("#addItem").show();
      $("#currentBalance").hide();
      $("#editItem").hide();
      $("#removeItem").hide();
    }
    function editItemShow() {
      $("#editItem").show();
      $("#currentBalance").hide();
      $("#addItem").hide();
      $("#removeItem").hide();
    }
    function removeItemShow() {
      $("#removeItem").show();
      $("#currentBalance").hide();
      $("#addItem").hide();
      $("#editItem").hide();
    }
    function updateJune() {
      var url = "months/june.php";
      $('#january').load(url);
    }
  </script>

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
            <a class="dropdown-item" href="../otherTools/capital">Capital Gains/Losses</a>
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
      <div class="col-sm-4 text-center"></div>
      <div class="col-sm-4 text-center">
        <br>
        <div class="btn-grid">
          <button type="button" id="currentBalanceButton" class="btn btn-default">Current Balance</button>
          <button type="button" id="addItemButton" class="btn btn-success">Add Item</button>
          <button type="button" id="editItemButton" class="btn btn-primary">Edit Item</button>
          <button type="button" id="removeItemButton" class="btn btn-danger">Remove Item</button>
        </div>
        <br>
        <br>
        <div id="currentBalance">
          <form action="<?php echo htmlspecialchars($_SERVER[" PHP_SELF "]); ?>" method="post">
            <div class="form-group">
              <label>Enter Current Balance:</label>
              <input name="currentBal" type="number" required="required" max="999999" step=".01" value="<?php echo $currentBal ?>" class="form-control">
            </div>
            <div class="form-group">
              <input type="submit" class="btn btn-primary" value="Save">
              <input type="reset" class="btn btn-default" value="Reset">
            </div>
          </form>
        </div>
        <div id="addItem">
          <form action="<?php echo htmlspecialchars($_SERVER[" PHP_SELF "]); ?>" method="post">
            <div class="form-group">
              <label>Enter Date:</label>
              <input name="itemMonthDay" type="date" required="required" min="<?php echo date('Y-m-d'); ?>" max="<?php echo date('Y-m-d', strtotime('+364 days')); ?>"
                class="form-control">
              <label>Enter Item Name:</label>
              <input name="itemName" type="text" required="required" maxlength="20" class="form-control">
              <label>Enter Item Amount (negative for an expense):</label>
              <input name="itemAmount" type="number" required="required" max="999999" step=".01" class="form-control">
            </div>
            <div class="form-group">
              <input type="submit" class="btn btn-primary" value="Add">
              <input type="reset" class="btn btn-default" value="Reset">
            </div>
          </form>
        </div>
        <div id="editItem">
        </div>
        <div id="removeItem">
        </div>
      </div>
      <div class="col-sm-4 text-center"></div>
    </div>
    <div class="row">
      <div class="col-sm-12 text-center">
        <h3>January</h3>
        <div id="january"></div>
        <h3>February</h3>
        <div id="february"></div>
        <h3>March</h3>
        <div id="march"></div>
        <h3>April</h3>
        <div id="april"></div>
        <h3>May</h3>
        <div id="may"></div>
        <h3>June</h3>
        <div id="june"></div>
        <h3>July</h3>
        <div id="july"></div>
        <h3>August</h3>
        <div id="august"></div>
        <h3>September</h3>
        <div id="september"></div>
        <h3>October</h3>
        <div id="october"></div>
        <h3>November</h3>
        <div id="november"></div>
        <h3>December</h3>
        <div id="december"></div>
      </div>
    </div>
  </div>
</body>

</html>