<?php
echo "fuck";

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

echo "fuck";

for ($i = 0; $i < 30; $i++){
    $days[$i] = $i;
    $itemNames[$i] = "";
    $itemAmounts[$i] = "";
}

echo "fuck";

$sql = "SELECT day, itemName, itemAmount FROM june WHERE email = '$email' ORDER BY day ASC";
$result = mysqli_query($link, $sql);
while ($row = mysqli_fetch_array($result)){
    $itemNames[$row['day']] = $row['itemName'];
    $itemNames[$row['day']] = $row['itemAmount'];
}
mysqli_free_result($result);
mysqli_close($link);
/*
echo "WEEK 1";
for ($i = 0; $i < 7; $i++){

}
echo "WEEK 2";
for ($i = 7; $i < 14; $i++){

}
echo "WEEK 3";
for ($i = 14; $i < 21; $i++){

}
echo "WEEK 4";
for ($i = 21; $i < 28; $i++){
    
}
echo "WEEK 5";
for ($i = 28; $i < 30; $i++){

}
*/
?>