<?php

// Include config file
require_once '../dbconfig.php';

// Define variables and initialize with empty values
$email = "";
$email_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Check if email is empty
    if(empty(trim($_POST["email"]))){
        $email_err = 'Please enter your email.';
    } else{
        $email = trim($_POST["email"]);
    }
    
    // Validate credentials
    if(empty($email_err)){
        // Prepare a select statement
        $sql = "SELECT email, hash, active FROM users WHERE email = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_email);
            
            // Set parameters
            $param_email = $email;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Store result
                mysqli_stmt_store_result($stmt);
                
                // Check if email exists
                if(mysqli_stmt_num_rows($stmt) == 1){     
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $email, $old_hash, $active);
                    if(mysqli_stmt_fetch($stmt)){
                        if($active == 1){

                            //Update hash
                            $hash = rand(0,1000000000000);
                            $hash = password_hash($hash, PASSWORD_DEFAULT);
                            mysqli_query($link, "UPDATE users SET hash='".$hash."' WHERE email='".$email."' AND hash='".$old_hash."'");


                            //Send Reset Password Email
                            $to      = $email; // Send email to our user
                            $subject = "The Financial Wizard - Reset Password"; // Give the email a subject 
                            $message = 
"Hello ".$email."!

Please click the link below to reset your password:
http://35.196.62.65/user/passwordReset.php?email=".$email."&hash=".$hash.""; // Our message above including the link
                            $headers = "From:tfwnoreply@gmail.com" . "\r\n"; // Set from headers
                            mail($to, $subject, $message, $headers); // Send our email
                            // Email Reset Password Notice
                            $emailmessage = "An email has been sent to you with instructions on how to reset your password.";
                            echo "<script type='text/javascript'>alert('$emailmessage');window.location='../index.php';</script>";
                        } else{
                            $email_err = 'Please verify your email before resetting your password.';
                        }
                    }
                } else{
                    // Display an error message if email doesn't exist
                    $email_err = 'No account found with that email.';
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
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-sm-4"></div>
        <div class="col-sm-4 text-center">
            <br>
            <a href="../index.php"><img class="img-fluid" src="../images/logo.png" alt="TFW"></a>
            <br>
            <br>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
                    <label>Email:</label>
                    <input type="email" name="email" class="form-control" value="<?php echo $email; ?>">
                    <span class="help-block"><?php echo $email_err; ?></span>
                </div>
                <div class="form-group">
                    <input type="submit" class="btn btn-primary" value="Submit">
                    <input type="reset" class="btn btn-default" value="Reset">
                    <a href="../user" class="btn btn-primary">Login</a>
                </div>
            </form>
        </div>
        <div class="col-sm-4"></div>
    </div>
</div>
</body>
</html>