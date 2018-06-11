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

$days = array();
$itemNames = array();
$itemAmounts = array();

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

// Attempt select query execution
$sql = "SELECT juneBalance FROM users WHERE email = '$email'";
$result = mysqli_query($link, $sql);
$row = mysqli_fetch_array($result);

//grab data here
if ($row['juneBalance'] == ""){
    $juneBal = 0;
}else{
    $juneBal = $row['juneBalance'];
}

// Free result set
mysqli_free_result($result);
 
// Close connection
mysqli_close($link);

//WEEK1
echo "<h6>WEEK 1</h6>";
$week1 = $juneBal;
for ($i = 0; $i < 7; $i++){
    if ($itemAmounts[$i] != ""){
        if (strpos($itemAmounts[$i], '-') != false){
            echo "- $".$itemAmounts[$i]." ".$itemNames[$i]." ".$i;
        }else{
            echo "+ $".$itemAmounts[$i]." ".$itemNames[$i]." ".$i;
        }
        $week1 = $week1 + $itemAmounts[$i];
    }
}
echo "Week 1 Balance = $".$week1;

//WEEK2
echo "<h6>WEEK 2</h6>";
$week2 = $week1;
for ($i = 7; $i < 14; $i++){
    if ($itemAmounts[$i] != ""){
        if (strpos($itemAmounts[$i], '-') != false){
            echo "- $".$itemAmounts[$i]." ".$itemNames[$i]." ".$i;
        }else{
            echo "+ $".$itemAmounts[$i]." ".$itemNames[$i]." ".$i;
        }
        $week2 = $week2 + $itemAmounts[$i];
    }
}
echo "Week 2 Balance = $".$week2;

//WEEK3
echo "<h6>WEEK 3</h6>";
$week3 = $week2;
for ($i = 14; $i < 21; $i++){
    if ($itemAmounts[$i] != ""){
        if (strpos($itemAmounts[$i], '-') != false){
            echo "- $".$itemAmounts[$i]." ".$itemNames[$i]." ".$i;
        }else{
            echo "+ $".$itemAmounts[$i]." ".$itemNames[$i]." ".$i;
        }
        $week3 = $week3 + $itemAmounts[$i];
    }
}
echo "Week 3 Balance = $".$week3;

//WEEK4
echo "<h6>WEEK 4</h6>";
$week4 = $week3;
for ($i = 21; $i < 28; $i++){
    if ($itemAmounts[$i] != ""){
        if (strpos($itemAmounts[$i], '-') != false){
            echo "- $".$itemAmounts[$i]." ".$itemNames[$i]." ".$i;
        }else{
            echo "+ $".$itemAmounts[$i]." ".$itemNames[$i]." ".$i;
        }
        $week4 = $week4 + $itemAmounts[$i];
    }
}
echo "Week 4 Balance = $".$week4;

//WEEK5
echo "<h6>WEEK 5</h6>";
$week5 = $week4;
for ($i = 28; $i < 30; $i++){
    if ($itemAmounts[$i] != ""){
        if (strpos($itemAmounts[$i], '-') != false){
            echo "- $".$itemAmounts[$i]." ".$itemNames[$i]." ".$i;
        }else{
            echo "+ $".$itemAmounts[$i]." ".$itemNames[$i]." ".$i;
        }
        $week5 = $week5 + $itemAmounts[$i];
    }
}
echo "Week 5 Balance = $".$week5;
?>