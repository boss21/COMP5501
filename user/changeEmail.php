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

$oldemail = $_SESSION['email'];
 
// Define variables and initialize with empty values
$email = $hash = "";
$email_err = $param_hash = "";

$active = 1;
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
	
    // Validate email
    if(empty(trim($_POST["email"]))){
        $email_err = "Please enter your email.";
    } else{
        // Prepare a select statement
        $sql = "SELECT email FROM users WHERE email = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_email);
            
            // Set parameters
            $param_email = trim($_POST["email"]);
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1 && $oldemail != $param_email){
                    $email_err = "This email is already taken.";
                } else{
                    $email = trim($_POST["email"]);
                    if($oldemail != $email){
                    $active = 0;
                    }
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
         
            // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Check input errors before updating in database
    if(empty($email_err)){

        $hash = rand(0,1000000000000);

        // Prepare an update statement
        $sql = "UPDATE users SET email = ?, hash = ?, active = '$active' WHERE email = '$oldemail'";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ss", $param_email, $param_hash);
            
            // Set parameters
            $param_email = $email;
            $param_hash = password_hash($hash, PASSWORD_DEFAULT);
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                if ($oldemail != $email){
                // Send Email
				$to      = $email; // Send email to our user
				$subject = "The Financial Wizard - Verify Email"; // Give the email a subject 
				$message = 
"Hello ".$email."!

Your email was changed.

Please click the link below to re-activate your account:
http://35.196.62.65/user/verify.php?email=".$param_email."&hash=".$param_hash.""; // Our message above including the link
                $headers = "From:tfwnoreply@gmail.com" . "\r\n"; // Set from headers
				mail($to, $subject, $message, $headers); // Send our email
                // Notice
                $message = "Your changes have been saved! Please click the verification link sent to your email to re-activate your account.";
                echo "<script type='text/javascript'>alert('$message');window.location='logout.php';</script>";
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
         
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Close connection
    mysqli_close($link);
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
                    <div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
                        <label>New Email:</label>
                        <input type="email" name="email" class="form-control" value="<?php echo $oldemail; ?>">
                        <span class="help-block" style="color:red">
                            <?php echo $email_err; ?>
                        </span>
                    </div>
                    <div class="form-group">
                        <input type="submit" class="btn btn-primary" value="Save">
                        <input type="reset" class="btn btn-default" value="Reset">
                        <input type="button" class="btn btn-primary" onclick="location.href='index.php';" value="Cancel">
                    </div>
                </form>
            </div>
            <div class="col-sm-4"></div>
        </div>
    </div>
</body>

</html>