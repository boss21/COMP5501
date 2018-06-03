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
 
// Define variables and initialize with empty values
$email = $password = $confirm_password = "";
$email_err = $password_err = $confirm_password_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    
    // Validate password
    if(empty(trim($_POST['password']))){
        $password_err = "Please enter a password.";     
    } elseif(strlen(trim($_POST['password'])) < 6){
        $password_err = "Password must have at least 6 characters.";
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
    if(empty($password_err) && empty($confirm_password_err)){
		
        // Prepare an insert statement
        $sql = "UPDATE users SET password = ? WHERE email = '$email'";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_password);
            
            // Set parameters
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Password Change Notice
                $emailmessage = "Your password has been successfully changed.";
                echo "<script type='text/javascript'>alert('$emailmessage');window.location='index.php';</script>";
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
                <form action="<?php echo htmlspecialchars($_SERVER[" PHP_SELF "]); ?>" method="post">
                    <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                        <label>New Password:</label>
                        <input type="password" name="password" class="form-control" value="<?php echo $password; ?>">
                        <span class="help-block" style="color:red">
                            <?php echo $password_err; ?>
                        </span>
                    </div>
                    <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                        <label>Confirm Password:</label>
                        <input type="password" name="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>">
                        <span class="help-block" style="color:red">
                            <?php echo $confirm_password_err; ?>
                        </span>
                    </div>
                    <div class="form-group">
                        <input type="submit" class="btn btn-primary" value="Save">
                        <input type="reset" class="btn btn-default" value="Reset">
                    </div>
                </form>
            </div>
            <div class="col-sm-4"></div>
        </div>
    </div>
</body>

</html>