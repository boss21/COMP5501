<?php

// Initialize the session
session_start();
 
// If session variable is not set it will redirect to login page
if(!isset($_SESSION['email']) || empty($_SESSION['email'])){
  header("location: ../../index.php");
  exit;
}

// Include config file
require_once '../../dbconfig.php';

$email = $_SESSION['email'];

$sql = "SELECT day, itemName, itemAmount FROM june WHERE email = '$email' ORDER BY day ASC";
$result = mysqli_query($link, $sql);
while ($row = mysqli_fetch_array($result)){
    echo "Day: ".$row['day']." Item Name:".$row['itemName']." Item Amount: $".$row['itemAmount']."<br>";
}
    
mysqli_free_result($result);
mysqli_close($link);
?>