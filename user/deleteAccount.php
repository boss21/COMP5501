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

// Attempt select query execution
$sql = "DELETE FROM users WHERE email = '$email'";
$delete = mysqli_query($link, $sql);

// Free result set
mysqli_free_result($delete);

// Attempt select query execution
$sql = "DELETE FROM january WHERE email = '$email'";
$delete = mysqli_query($link, $sql);

// Free result set
mysqli_free_result($delete);

// Attempt select query execution
$sql = "DELETE FROM february WHERE email = '$email'";
$delete = mysqli_query($link, $sql);

// Free result set
mysqli_free_result($delete);

// Attempt select query execution
$sql = "DELETE FROM march WHERE email = '$email'";
$delete = mysqli_query($link, $sql);

// Free result set
mysqli_free_result($delete);

// Attempt select query execution
$sql = "DELETE FROM april WHERE email = '$email'";
$delete = mysqli_query($link, $sql);

// Free result set
mysqli_free_result($delete);

// Attempt select query execution
$sql = "DELETE FROM may WHERE email = '$email'";
$delete = mysqli_query($link, $sql);

// Free result set
mysqli_free_result($delete);

// Attempt select query execution
$sql = "DELETE FROM june WHERE email = '$email'";
$delete = mysqli_query($link, $sql);

// Free result set
mysqli_free_result($delete);

// Attempt select query execution
$sql = "DELETE FROM july WHERE email = '$email'";
$delete = mysqli_query($link, $sql);

// Free result set
mysqli_free_result($delete);

// Attempt select query execution
$sql = "DELETE FROM august WHERE email = '$email'";
$delete = mysqli_query($link, $sql);

// Free result set
mysqli_free_result($delete);

// Attempt select query execution
$sql = "DELETE FROM september WHERE email = '$email'";
$delete = mysqli_query($link, $sql);

// Free result set
mysqli_free_result($delete);

// Attempt select query execution
$sql = "DELETE FROM october WHERE email = '$email'";
$delete = mysqli_query($link, $sql);

// Free result set
mysqli_free_result($delete);

// Attempt select query execution
$sql = "DELETE FROM november WHERE email = '$email'";
$delete = mysqli_query($link, $sql);

// Free result set
mysqli_free_result($delete);

// Attempt select query execution
$sql = "DELETE FROM december WHERE email = '$email'";
$delete = mysqli_query($link, $sql);

// Free result set
mysqli_free_result($delete);

// Unset all of the session variables
$_SESSION = array();

// Destroy the session.
session_destroy();

mysqli_close($link);

// Account Deleted
echo '<center><div style="width:320px;font-size:14px;font-family:sans-serif;text-align:center">Your account has been deleted.<br><br>Redirecting you to the login page.</div></center>';
// Redirect to home page
echo "<script>setTimeout(\"location.href = '../';\",5000);</script>";
?>