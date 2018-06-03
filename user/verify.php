<?php

// Include config file
require_once '../dbconfig.php';

if(isset($_GET['email']) && !empty($_GET['email']) AND isset($_GET['hash']) && !empty($_GET['hash'])){
    // Verify data
	
    $email = mysqli_escape_string($link, $_GET['email']); // Set email variable
    $hash = mysqli_escape_string($link, $_GET['hash']); // Set hash variable
    
    $search = mysqli_query($link, "SELECT email, hash, active FROM users WHERE email='".$email."' AND hash='".$hash."' AND active='0'"); 
    $match  = mysqli_num_rows($search);
	
    if($match > 0){
        // We have a match, activate the account
        mysqli_query($link, "UPDATE users SET active='1' WHERE email='".$email."' AND hash='".$hash."' AND active='0'");
		mysqli_close($link);
        echo "<script type='text/javascript'>alert('Your account has been activated.');window.location='../index.php';</script>";
    }else{
		mysqli_close($link);
		echo "<script type='text/javascript'>alert('Either this url is invalid or you have already activated your account.');window.location='../index.php';</script>";
    }
         
}else{
	mysqli_close($link);
	echo "<script type='text/javascript'>alert('Invalid approach, please use the link that was sent to your email address.');window.location='../index.php';</script>";
}
?>