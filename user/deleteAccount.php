<?php

// Initialize the session
session_start();
 
// If session variable is not set it will redirect to login page
if(!isset($_SESSION['username']) || empty($_SESSION['username'])){
  header("location: login.php");
  exit;
}

// Include config file
require_once '../dbconfig.php';

$username = $_SESSION['username'];

// Attempt select query execution
$sql = "DELETE FROM users WHERE username = '$username'";
$delete = mysqli_query($link, $sql);

// Free result set
mysqli_free_result($delete);

for ($t = 1; $t <= 5; $t++){
  // Attempt select query execution
  $sql = "DELETE FROM user_likes_".$t." WHERE username = '$username'";
  $result = mysqli_query($link, $sql);
  
  // Free result set
  mysqli_free_result($result);
}

for ($t = 1; $t <= 10; $t++){
  // Attempt select query execution
  $sql = "DELETE FROM user_dislikes_".$t." WHERE username = '$username'";
  $result = mysqli_query($link, $sql);
  
  // Free result set
  mysqli_free_result($result);
}

for ($t = 1; $t <= 1; $t++){
  // Attempt select query execution
  $sql = "DELETE FROM user_matches_".$t." WHERE username = '$username'";
  $result = mysqli_query($link, $sql);
  
  // Free result set
  mysqli_free_result($result);
}

//PURGE ENTIRE DB OF THEIR USERNAME
for ($b = 1; $b <= 1000; $b++){
  for ($t = 1; $t <= 5; $t++){
    // Attempt select query execution
    $sql = "UPDATE user_likes_".$t." SET like_".$b." = NULL WHERE like_".$b." = '$username'";
    $result = mysqli_query($link, $sql);
    
    // Free result set
    mysqli_free_result($result);
  }
  
  for ($t = 1; $t <= 10; $t++){
    // Attempt select query execution
    $sql = "UPDATE user_dislikes_".$t." SET dislike_".$b." = NULL WHERE dislike_".$b." = '$username'";
    $result = mysqli_query($link, $sql);
    
    // Free result set
    mysqli_free_result($result);
  }
  
  for ($t = 1; $t <= 1; $t++){
    // Attempt select query execution
    $sql = "UPDATE user_matches_".$t." SET match_".$b." = NULL WHERE match_".$b." = '$username'";
    $result = mysqli_query($link, $sql);
    
    // Free result set
    mysqli_free_result($result);
  }
}

// Attempt select query execution
$sql = "DELETE FROM chat WHERE sender = '$username' OR receiver = '$username'";
$delete = mysqli_query($link, $sql);

// Free result set
mysqli_free_result($delete);

// Unset all of the session variables
$_SESSION = array();

// Destroy the session.
session_destroy();

mysqli_close($link);

// Account Deleted
echo '<center><div style="width:320px;font-size:14px;font-family:sans-serif;text-align:center">Your account has been deleted.<br /><br />Redirecting you to the home page.</div></center>';
// Redirect to home page
echo "<script>setTimeout(\"location.href = '../';\",5000);</script>";
?>