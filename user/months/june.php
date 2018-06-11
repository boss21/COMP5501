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

$days = new array();
$itemNames = new array();
$itemAmounts = new array();

for ($i = 0; $i < 30; $i++){
    $days[$i] = $i;
    $itemNames[$i] = "";
    $itemAmounts[$i] = "";
}

$sql = "SELECT day, itemName, itemAmount FROM june WHERE email = '$email' ORDER BY day ASC";
$result = mysqli_query($link, $sql);
while ($row = mysqli_fetch_array($result)){
    $itemNames[$row['day']] = $row['itemName'];
    $itemNames[$row['day']] = $row['itemAmount'];
}
mysqli_free_result($result);
mysqli_close($link);

echo "<h4>WEEK 1</h4>";
for ($i = 0; $i < 7; $i++){

}
echo "<h4>WEEK 2</h4>";
for ($i = 7; $i < 14; $i++){

}
echo "<h4>WEEK 3</h4>";
for ($i = 14; $i < 21; $i++){

}
echo "<h4>WEEK 4</h4>";
for ($i = 21; $i < 28; $i++){
    
}
echo "<h4>WEEK 5</h4>";
for ($i = 28; $i < 30; $i++){

}
?>