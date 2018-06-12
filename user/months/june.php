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
    $itemNames[$i] = array();
    $itemAmounts[$i] = array();
}

$sql = "SELECT day, itemName, itemAmount FROM june WHERE email = '$email' ORDER BY day ASC";
$result = mysqli_query($link, $sql);
$sameDay = 100;
$count = 0;
while ($row = mysqli_fetch_array($result)){
    if ($sameDay == $row['day']){
        $itemNames[$row['day']][$count] = $row['itemName'];
        $itemAmounts[$row['day']][$count] = $row['itemAmount'];
        $count++;
    }else{
        $itemNames[$row['day']][0] = $row['itemName'];
        $itemAmounts[$row['day']][0] = $row['itemAmount'];
    }
    $sameDay = $row['day'];
}
mysqli_free_result($result);

// Attempt select query execution
$sql = "SELECT currentBalance, juneBalance FROM users WHERE email = '$email'";
$result = mysqli_query($link, $sql);
$row = mysqli_fetch_array($result);

//grab data here
if ($row['juneBalance'] == ""){
    $juneBal = $row['currentBalance'];
}else{
    $juneBal = $row['juneBalance'];
}

// Free result set
mysqli_free_result($result);
 
// Close connection
mysqli_close($link);

echo "<hr>";
//WEEK1
echo "<b><u>WEEK 1</u></b>";
echo "<br>";
$week1 = $juneBal;
for ($i = 0; $i < 7; $i++){
    for ($j = 0; $j < sizeof($itemAmounts[$i]); $j++){
        if ($itemAmounts[$i][$j] != ""){
            if ($itemAmounts[$i][$j] < 0){
                echo $itemAmounts[$i][$j]." ".$itemNames[$i][$j]." 6/".$i;
            }else{
                echo "+".$itemAmounts[$i][$j]." ".$itemNames[$i][$j]." 6/".$i;
            }
            echo "<br>";
            $week1 = $week1 + $itemAmounts[$i][$j];
        }
    }
}
echo "Balance = ".$week1;
echo "<br><br>";

//WEEK2
echo "<b><u>WEEK 2</u></b>";
echo "<br>";
$week2 = $week1;
for ($i = 7; $i < 14; $i++){
    for ($j = 0; $j < sizeof($itemAmounts[$i]); $j++){
        if ($itemAmounts[$i][$j] != ""){
            if ($itemAmounts[$i][$j] < 0){
                echo $itemAmounts[$i][$j]." ".$itemNames[$i][$j]." 6/".$i;
            }else{
                echo "+".$itemAmounts[$i][$j]." ".$itemNames[$i][$j]." 6/".$i;
            }
            echo "<br>";
            $week2 = $week2 + $itemAmounts[$i][$j];
        }
    }
}
echo "Balance = ".$week2;
echo "<br><br>";

//WEEK3
echo "<b><u>WEEK 3</u></b>";
echo "<br>";
$week3 = $week2;
for ($i = 14; $i < 21; $i++){
    for ($j = 0; $j < sizeof($itemAmounts[$i]); $j++){
        if ($itemAmounts[$i][$j] != ""){
            if ($itemAmounts[$i][$j] < 0){
                echo $itemAmounts[$i][$j]." ".$itemNames[$i][$j]." 6/".$i;
            }else{
                echo "+".$itemAmounts[$i][$j]." ".$itemNames[$i][$j]." 6/".$i;
            }
            echo "<br>";
            $week3 = $week3 + $itemAmounts[$i][$j];
        }
    }
}
echo "Balance = ".$week3;
echo "<br><br>";

//WEEK4
echo "<b><u>WEEK 4</u></b>";
echo "<br>";
$week4 = $week3;
for ($i = 21; $i < 28; $i++){
    for ($j = 0; $j < sizeof($itemAmounts[$i]); $j++){
        if ($itemAmounts[$i][$j] != ""){
            if ($itemAmounts[$i][$j] < 0){
                echo $itemAmounts[$i][$j]." ".$itemNames[$i][$j]." 6/".$i;
            }else{
                echo "+".$itemAmounts[$i][$j]." ".$itemNames[$i][$j]." 6/".$i;
            }
            echo "<br>";
            $week4 = $week4 + $itemAmounts[$i][$j];
        }
    }
}
echo "Balance = ".$week4;
echo "<br><br>";

//WEEK5
echo "<b><u>WEEK 5</u></b>";
echo "<br>";
$week5 = $week4;
for ($i = 28; $i < 30; $i++){
    for ($j = 0; $j < sizeof($itemAmounts[$i]); $j++){
        if ($itemAmounts[$i][$j] != ""){
            if ($itemAmounts[$i][$j] < 0){
                echo $itemAmounts[$i][$j]." ".$itemNames[$i][$j]." 6/".$i;
            }else{
                echo "+".$itemAmounts[$i][$j]." ".$itemNames[$i][$j]." 6/".$i;
            }
            echo "<br>";
            $week5 = $week5 + $itemAmounts[$i][$j];
        }
    }
}
echo "Balance = ".$week5;
echo "<br>";
echo "<hr>";
?>