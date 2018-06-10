
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
                            echo "Data Matched";
                        } else if($active == 0){
							$active_err = '<br><br>Please verify your email.';
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
