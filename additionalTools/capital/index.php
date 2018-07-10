<?php

// Initialize the session
session_start();
 
// If session variable is not set it will redirect to login page
if(!isset($_SESSION['email']) || empty($_SESSION['email'])){
  header("location: ../../index.php");
  exit;
}

// Include config file
require_once '../../dbconfig.php';

$email = $_SESSION['email'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>The Financial Wizard</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.css">
    <style>
        .col-centered {
            float: none;
            margin: 0 auto;
        }
    </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.js"></script>
    <script>
        function calculate(){
            document.getElementById("output").innerHTML = "";
            var mtdacg = document.getElementById("mtdacg").value;
            var cl = document.getElementById("cl").value;
            var cg = document.getElementById("cg").value;
            if (mtdacg != "" && cl != "" && cg != ""){
                var offset = cg-cl;
                if (offset > 0){
                    document.getElementById("output").innerHTML = "<br>Your capital losses are not great enough to offset your capital gains for the current year.";
                } else if (offset == 0){
                    document.getElementById("output").innerHTML = "<br>You can carry forward your capital losses for the current year only.";
                } else {
                    var additionalYears = ((offset*-1)/mtdacg)-1;
                    document.getElementById("output").innerHTML = "<br>You can carry forward your capital losses for the current year plus "+additionalYears.toFixed(2)+" additional years.";
                    document.getElementById("output").innerHTML += "<br>Note that capital losses carry forward only applies if you have capital gains for the future year(s).";
                }
            } else {
                document.getElementById("output").innerHTML = "<br><span style='color:red'>Please fill out all fields.</span>";
            }
        }
        function clear(){
            document.getElementById("mtdacg").value = "";
            document.getElementById("cl").value = "";
            document.getElementById("cg").value = "";
            document.getElementById("output").innerHTML = "";
        }
        window.onload = function () {
            document.getElementById("calculate").onclick = calculate;
            document.getElementById("clear").onclick = clear;
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
                    <a class="nav-link" href="../../user/index.php">Home</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false">
                        Additional Financial Calculators
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="../../additionalTools/capital">Carry Forward Capital Losses</a>
                        <a class="dropdown-item" href="../../additionalTools/loans">Pay Back Loans</a>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false">
                        Settings
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="../../user/changeEmail.php">Change Email</a>
                        <a class="dropdown-item" href="../../user/changePassword.php">Change Password</a>
                        <a class="dropdown-item" href="../../user/deleteAccount.php" onclick="return confirm('Are you sure you want to delete your account?')">Delete Account</a>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../../user/logout.php">Logout</a>
                </li>
        </div>
    </nav>
    <br>
    <div class="container">
        <div class="row">
            <div class="form-group col-sm-12 text-center">
                <h1>Capital Losses Carry Forward Calculator</h1>
                The Capital Losses Carry Forward Calculator tells you how many years you can carry forward your current capital losses. It
                starts by asking you to enter the maximum tax deduction amount you are allowed use to offset your capital
                gains for the current year. It then asks you to enter your total capital losses for the current year and
                then your total capital gains for the current year. It then calculates how many years you can carry forward
                your current capital losses if any.
                <br>
                <br>
                <label>Enter in the Maximum Tax Deduction Amount for Capital Gains:</label>
                <input id="mtdacg" type="number" required="required" min="0" max="999999999" step=".01" class="form-control col-sm-6 col-centered">
                <br>
                <label>Enter in the Total Capital Losses for the Current Year:</label>
                <input id="cl" type="number" required="required" min="0" max="999999999" step=".01" class="form-control col-sm-6 col-centered">
                <br>
                <label>Enter in the Total Capital Gains for the Current Year:</label>
                <input id="cg" type="number" required="required" min="0" max="999999999" step=".01" class="form-control col-sm-6 col-centered">
                <br>
                <button id="calculate" class="btn btn-primary">Calculate</button>
                <button id="clear" class="btn btn-default">Clear</button>
                <div id="output"></div>
            </div>
        </div>
    </div>
</body>

</html>