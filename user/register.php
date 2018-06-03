<?php

// Include config file
require_once '../dbconfig.php';
 
// Define variables and initialize with empty values
$email = $password = $confirm_password = "";
$email_err = $password_err = $confirm_password_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Validate email
    if(empty(trim($_POST["email"]))){
        $email_err = "Please enter an email.";
    } else{
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE email = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_email);
            
            // Set parameters
            $param_email = trim($_POST["email"]);
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $email_err = "This email is already taken.";
                } else{
                    $email = trim($_POST["email"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
         
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Validate password
    if(empty(trim($_POST['password']))){
        $password_err = "Please enter a password.";     
    } elseif(strlen(trim($_POST['password'])) < 6){
        $password_err = "Password must have atleast 6 characters.";
    } else{
        $password = trim($_POST['password']);
    }
    
    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = 'Please confirm password.';     
    } else{
        $confirm_password = trim($_POST['confirm_password']);
        if($password != $confirm_password){
            $confirm_password_err = 'Password did not match.';
        }
    }
    
    // Check input errors before inserting in database
    if(empty($email_err) && empty($password_err) && empty($confirm_password_err)){
        
		$hash = rand(0,1000000000000);
        $active = 0;
		
        // Prepare an insert statement
        $sql = "INSERT INTO users (email, password, hash, active) VALUES (?, ?, ?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssss", $param_email, $param_password, $param_hash, $param_active);
            
            // Set parameters
            $param_email = $email;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
			$param_hash = password_hash($hash, PASSWORD_DEFAULT);
            $param_active = $active;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){

                for ($t = 1; $t <= 5; $t++){
                    // Attempt select query execution
                    $sql_like = "INSERT INTO user_likes_".$t." (username) VALUES ('$username')";
                    $result_like = mysqli_query($link, $sql_like);
            
                    // Free result set
                    mysqli_free_result($result_like);
                }
                
				// Send Email
				$to      = $email; // Send email to our user
				$subject = "The Financial Wizard - Verify Email Address"; // Give the email a subject 
				$message = 
"Hello ".$email."!

Your account has been created.

Please click the link below to activate your account:
http://http://35.196.62.65/user/verify.php?email=".$param_email."&hash=".$param_hash.""; // Our message above including the link
                $headers = "From:tfwnoreply@gmail.com" . "\r\n"; // Set from headers
				mail($to, $subject, $message, $headers); // Send our email
                // Email Verification Notice
                $emailmessage = "Your account has been created! Please click the verification link sent to your email address to activate your account.";
                echo "<script type='text/javascript'>alert('$emailmessage');window.location='../index.php';</script>";
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
<a href="../index.php"><img class="img-responsive" src="../images/logo.png" alt="TFW"></a>
<div>
    <div>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <label>Email:</label>
                <input type="email" name="email" class="form-control" value="<?php echo $email; ?>">
                <span class="help-block"><?php echo $email_err; ?></span>
            </div>    
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Password:</label>
                <input type="password" name="password" class="form-control" value="<?php echo $password; ?>">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                <label>Confirm Password:</label>
                <input type="password" name="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>">
                <span class="help-block"><?php echo $confirm_password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Sign Up">
                <input type="reset" class="btn btn-default" value="Reset">
            </div>
            <div>Already have an account? <a href="../index.php" style="color:#ff0000">Log In</a></div>
        </form>
    </div>
</div>
</body>
</html>