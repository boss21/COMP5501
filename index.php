<?php

// Include config file
require_once 'dbconfig.php';
 
// Define variables and initialize with empty values
$email = $password = $active = "";
$email_err = $password_err = $active_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Check if email is empty
    if(empty(trim($_POST["email"]))){
        $email_err = 'Please enter your email.';
    } else{
        $email = trim($_POST["email"]);
    }
    
    // Check if password is empty
    if(empty(trim($_POST['password']))){
        $password_err = 'Please enter your password.';
    } else{
        $password = trim($_POST['password']);
    }
    
    // Validate credentials
    if(empty($email_err) && empty($password_err)){
        // Prepare a select statement
        $sql = "SELECT email, password, active FROM users WHERE email = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_email);
            
            // Set parameters
            $param_email = $email;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Store result
                mysqli_stmt_store_result($stmt);
                
                // Check if email exists, if yes then verify password
                if(mysqli_stmt_num_rows($stmt) == 1){                    
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $email, $hashed_password, $active);
                    if(mysqli_stmt_fetch($stmt)){
                        if(password_verify($password, $hashed_password) && $active == 1){
                            /* Password is correct, so start a new session and
                            save the email to the session */
                            session_start();
                            $_SESSION['email'] = $email;      
                            header("location: /user");
                        } else if($active == 0){
							$active_err = '<br /><br />Please verify your email address.';
						} else{
                            // Display an error message if password is not valid
                            $password_err = 'The password you entered was not valid.';
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
                <a href="/">
                    <img class="img-fluid" src="../images/logo.png" alt="TFW">
                </a>
                <br>
                <br>
                <form action="<?php echo htmlspecialchars($_SERVER[" PHP_SELF "]); ?>" method="post">
                    <div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
                        <label>Email:</label>
                        <input type="text" name="email" class="form-control" value="<?php echo $email; ?>">
                        <span class="help-block">
                            <?php echo $email_err; ?>
                        </span>
                    </div>
                    <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                        <label>Password:</label>
                        <input type="password" name="password" class="form-control">
                        <span class="help-block">
                            <?php echo $password_err; ?>
                        </span>
                    </div>
                    <div>Forgot your password?
                        <a href="resetPassword.php" style="color:#007bff">Reset Password</a>
                    </div>
                    <br />
                    <div class="form-group <?php echo (!empty($active_err)) ? 'has-error' : ''; ?>">
                        <input type="submit" class="btn btn-primary" value="Log In">
                        <span class="help-block">
                            <?php echo $active_err; ?>
                        </span>
                    </div>
                    <div>Don't have an account?
                        <a href="/user/register.php" style="color:#007bff">Sign Up</a>
                    </div>
                </form>
            </div>
            <div class="col-sm-4"></div>
        </div>
    </div>
</body>

</html>