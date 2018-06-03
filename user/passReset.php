<?php

// Include config file
require_once '../dbconfig.php';

if($_SERVER["REQUEST_METHOD"] == "POST"){

  // Define variables and initialize with empty values
  $password = $confirm_password = "";
  $password_err = $confirm_password_err = "";

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

  if(empty($password_err) && empty($confirm_password_err)){

    $email = $_POST['email'];
    $hash = $_POST['hash'];
    $pass = $_POST['password'];
    $hashed_pass = password_hash($pass, PASSWORD_DEFAULT);

    mysqli_query($link, "UPDATE users SET password='".$hashed_pass."' WHERE email='".$email."' AND hash='".$hash."'");
    mysqli_close($link);
    echo "<script type='text/javascript'>alert('Your password has been reset.');window.location='../index.php';</script>";
  }
} else {
  echo "<script type='text/javascript'>alert('Invalid approach, please use the link that was sent to your email address.');window.location='../index.php';</script>";
}
?>