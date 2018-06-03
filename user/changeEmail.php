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
 
// Define variables and initialize with empty values
$email = $genderpref = $agepreflow = $ageprefhigh = $hash = "";
$email_err = $genderpref_err = $agepreflow_err = $ageprefhigh_err = $param_hash = "";

$active = 1;

$username = $_SESSION['username'];

// Attempt select query execution
$sql = "SELECT * FROM users WHERE username = '$username'";
$result = mysqli_query($link, $sql);
$row = mysqli_fetch_array($result);

$emailvalid = $row['email'];
$genderpref = $row['genderpref'];
$agepreflow = $row['agepreflow'];
$ageprefhigh = $row['ageprefhigh'];

// Free result set
mysqli_free_result($result);
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

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
	
    // Validate email
    if(empty(trim($_POST["email"]))){
        $email_err = "Please enter your .edu email address.";
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
                
                if(mysqli_stmt_num_rows($stmt) == 1 && $emailvalid != $param_email){
                    $email_err = "This email is already taken.";
                } else if(substr($_POST["email"], -4) != ".edu"){
                    $email_err = "Must be a .edu email address.";
                } else{
                    $email = trim($_POST["email"]);
                    if($emailvalid != $email){
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

    // Validate Gender Preference
    if(empty(trim($_POST['genderpref']))){
        $genderpref_err = "<br />Please select your Gender Preference.";     
    } else{
        $genderpref = trim($_POST['genderpref']);
    }

    // Validate Age Preference Low
    if(empty(trim($_POST['agepreflow']))){
        $agepreflow_err = "<br />Please select your Age Preference Min.<br /><br />";     
    } else{
        $agepreflow = trim($_POST['agepreflow']);
    }

    // Validate Age Preference High
    if(empty(trim($_POST['ageprefhigh']))){
        $ageprefhigh_err = "<br />Please select your Age Preference Max.<br /><br />";     
    } else{
        $ageprefhigh = trim($_POST['ageprefhigh']);
    }

    // Validate Low to High
    if(trim($_POST['agepreflow']) > trim($_POST['ageprefhigh'])){
        $agepreflow_err = "<br />Min must be less than or equal to Max.<br /><br />"; 
    }
    
    // Check input errors before updating in database
    if(!empty($password_err) && !empty($confirm_password_err) && empty($email_err) && empty($genderpref_err) && empty($agepreflow_err) && empty($ageprefhigh_err)){

        $hash = rand(0,1000000000000);

        // Prepare an update statement
        $sql = "UPDATE users SET email = ?, genderpref = ?, agepreflow = ?, ageprefhigh = ?, hash = ?, active = '$active' WHERE username = '$username'";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssss", $param_email, $param_genderpref, $param_agepreflow, $param_ageprefhigh, $param_hash);
            
            // Set parameters
            $param_email = $email;
			$param_genderpref = $genderpref;
            $param_agepreflow = $agepreflow;
            $param_ageprefhigh = $ageprefhigh;
            $param_hash = password_hash($hash, PASSWORD_DEFAULT);
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                if ($emailvalid != $email){
                // Send Email
				$to      = $email; // Send email to our user
				$subject = "FaceMeetFace - Verify Email Address"; // Give the email a subject 
				$message = 
"Hello ".$username."!

Your email was changed.

Please click the link below to re-activate your account:
https://facemeetface.com/user/verify.php?email=".$param_email."&hash=".$param_hash.""; // Our message above including the link
                $headers = "From:fmfnoreply@gmail.com" . "\r\n"; // Set from headers
				mail($to, $subject, $message, $headers); // Send our email
                // Notice
                $message = "Your changes have been saved! Please click the verification link sent to your email address to re-activate your account.";
                echo "<script type='text/javascript'>alert('$message');window.location='logout.php';</script>";
                } else {
                // Notice
                $message = "Your changes have been saved!";
                echo "<script type='text/javascript'>alert('$message');window.location='../user/index.php';</script>";
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
         
        // Close statement
        mysqli_stmt_close($stmt);
    }
    else if(empty($password_err) && empty($confirm_password_err) && empty($email_err) && empty($genderpref_err) && empty($agepreflow_err) && empty($ageprefhigh_err)){

        $hash = rand(0,1000000000000);

        // Prepare an update statement
        $sql = "UPDATE users SET password = ?, email = ?, genderpref = ?, agepreflow = ?, ageprefhigh = ?, hash = ?, active = '$active' WHERE username = '$username'";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssssss", $param_password, $param_email, $param_genderpref, $param_agepreflow, $param_ageprefhigh, $param_hash);
            
            // Set parameters
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            $param_email = $email;
			$param_genderpref = $genderpref;
            $param_agepreflow = $agepreflow;
            $param_ageprefhigh = $ageprefhigh;
            $param_hash = password_hash($hash, PASSWORD_DEFAULT);
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                if ($emailvalid != $email){
                // Send Email
				$to      = $email; // Send email to our user
				$subject = "FaceMeetFace - Verify Email Address"; // Give the email a subject 
				$message = 
"Hello ".$username."!

Your email was changed.

Please click the link below to re-activate your account:
https://facemeetface.com/user/verify.php?email=".$param_email."&hash=".$param_hash.""; // Our message above including the link
                $headers = "From:fmfnoreply@gmail.com" . "\r\n"; // Set from headers
				mail($to, $subject, $message, $headers); // Send our email
                // Notice
                $message = "Your changes have been saved! Please click the verification link sent to your email address to re-activate your account.";
                echo "<script type='text/javascript'>alert('$message');window.location='logout.php';</script>";
                } else {
                // Notice
                $message = "Your changes have been saved!";
                echo "<script type='text/javascript'>alert('$message');window.location='../user/index.php';</script>";
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
    <title>FaceMeetFace</title>
    <link rel="stylesheet" href="https://facemeetface.com/css/bootstrap.css">
    <link rel="stylesheet" href="https://facemeetface.com/css/font-awesome-4.7.0/css/font-awesome.min.css">
</head>
<body>
<div>
    <div class="icon-bar-edit">
        <a style="color:white"><i class="fa fa-cog"><strong style="font-family:sans-serif"> Edit Settings</strong></i></a>
    </div>
    <div>
        <br />
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Change Password:</label>
                <input type="password" name="password" class="form-control" value="<?php echo $password; ?>">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                <label>Confirm Password:</label>
                <input type="password" name="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>">
                <span class="help-block"><?php echo $confirm_password_err; ?></span>
            </div>
			<div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
                <label>Email Address (.edu only):</label>
				<input type="email" name="email" class="form-control" value="<?php echo $emailvalid; ?>" pattern=".+@.+.edu">
                <span class="help-block"><?php echo $email_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($genderpref_err)) ? 'has-error' : ''; ?>">
                <label>Gender Preference:</label>
                <br />
                <input type="radio" name="genderpref" <?php if (isset($genderpref) && $genderpref=="M") echo "checked";?> value="M">Male
                <input type="radio" name="genderpref" <?php if (isset($genderpref) && $genderpref=="F") echo "checked";?> value="F">Female
                <input type="radio" name="genderpref" <?php if (isset($genderpref) && $genderpref=="B") echo "checked";?> value="B">Bisexual
                <span class="help-block"><?php echo $genderpref_err; ?></span>
            </div>
            <div>
                <label>Age Preference:</label>
                <br />
                <div class="btn-group">
                    <div class="form-group <?php echo (!empty($agepreflow_err)) ? 'has-error' : ''; ?>">
                        <input type="number" name="agepreflow" min="18" max="30" value="<?php echo $agepreflow; ?>">
                    </div>
                    <span>&nbsp;-&nbsp;</span>
                    <div class="form-group <?php echo (!empty($ageprefhigh_err)) ? 'has-error' : ''; ?>">
                        <input type="number" name="ageprefhigh" min="18" max="30" value="<?php echo $ageprefhigh; ?>">
                    </div>
                </div>
                <span class="help-block"><?php echo $agepreflow_err; ?></span>
                <span class="help-block"><?php echo $ageprefhigh_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Save">
                <input type="reset" class="btn btn-default" value="Reset">
                <input type="button" class="btn btn-primary" onclick="location.href='../user/index.php';" value="Cancel">
            </div>
        </form>
    </div>
</div>
</body>
</html>