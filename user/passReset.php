<?php

// Include config file
require_once '../dbconfig.php';

if($_SERVER["REQUEST_METHOD"] == "POST"){
  $email = $_POST['email'];
  $hash = $_POST['hash'];
  $pass = $_POST['password'];
  $hashed_pass = password_hash($pass, PASSWORD_DEFAULT);

  mysqli_query($link, "UPDATE users SET password='".$hashed_pass."' WHERE email='".$email."' AND hash='".$hash."'");
  mysqli_close($link);
  echo "<script type='text/javascript'>alert('Your password has been reset.');window.location='../index.php';</script>";
} else {
  echo "<script type='text/javascript'>alert('Invalid approach, please use the link that was sent to your email address.');window.location='../index.php';</script>";
}
?>