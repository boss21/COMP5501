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
    <script src="https://unpkg.com/mathjs@5.0.2/dist/math.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.js"></script>
 <script>
        function calculate(){
         
            document.getElementById("output").innerHTML = "";
            document.getElementById("output2").innerHTML = "";
            var LA = document.getElementById("LA").value;
            var LIR = document.getElementById("LIR").value;
            var MP = document.getElementById("MP").value;
            var numer = 1-[(LA * (LIR/12))/MP];
            var denom = 1+LIR/12;
            var Months = (-math.log(numer)/math.log(denom));
            var IAmount = LA*(1+ LIR*Months/12)-LA;
            document.getElementById("output").innerHTML = "Number of Monthly Payments: " + Months;
            document.getElementById("output2").innerHTML = "Interest On Top of Loan To Be Paid: "+ IAmount;
        }
                           
        
                           
        function clear(){
            document.getElementById("LA").value = "";
            document.getElementById("LIR").value = "";
            document.getElementById("MP").value = "";
            document.getElementById("output").innerHTML = "";
            document.getElementById("output2").innerHTML = "";
        }
        window.onload = function () {
            document.getElementById("calculate").onclick=calculate;
            document.getElementById("clear").onclick=clear;
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
                <h1>Loans Calculator</h1>
                Here the user will enter in the Loan Amount, the Interest Rate, and the estimated Monthly Payment. The Loan Calculator will return the number of months needed to payoff the Loan as well as the amount of Interest paid on top of the intial loan.
                <br>
                <br>
                <label>Enter the Loan Amount:</label>
                <input id="LA" type="number" required="required" max="999999999" step=".01" class="form-control col-sm-6 col-centered">
                <br>
                <label>Enter Loan Interest Rate:</label>
                <input id="LIR" type="number" required="required" max="999999999" step=".01" class="form-control col-sm-6 col-centered">
                <br>
                <label>Enter Monthly Payment:</label>
                <input id="MP" type="number" required="required" max="999999999" step=".01" class="form-control col-sm-6 col-centered">
                <br>
                <button id="calculate" class="btn btn-primary">Calculate The Number of Monthly Payments</button>
                <button id="clear" class="btn btn-default">Clear</button>
                <div id="output"></div>
                <div id="output2"></div>
         </div>
        </div>
    </div>
</body>

</html>
