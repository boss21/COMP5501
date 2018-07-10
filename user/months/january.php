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

sleep(5);

//If date is passed delete data
$currDay = date("d");
$currTimestamp = date("Y-m-d");
$sql = "DELETE FROM january WHERE email = '$email' AND timestamp < '$currTimestamp'";
$result = mysqli_query($link, $sql);

$days = array();
$itemNames = array();
$itemAmounts = array();

for ($i = 0; $i < 31; $i++){
    $days[$i] = $i;
    $itemNames[$i] = array();
    $itemAmounts[$i] = array();
}

// Attempt select query execution
$sql = "SELECT currentBalance, decemberBalance FROM users WHERE email = '$email'";
$result = mysqli_query($link, $sql);
$row = mysqli_fetch_array($result);

//grab data here
if (date("m") == 1){
    $januaryBal = $row['currentBalance'];
}else{
    $januaryBal = $row['decemberBalance'];
}

// Free result set
mysqli_free_result($result);

// Attempt select query execution
$sql = "UPDATE users SET januaryBalance = '$januaryBal' WHERE email = '$email'";
mysqli_query($link, $sql);

$sql = "SELECT day, itemName, itemAmount FROM january WHERE email = '$email' ORDER BY day ASC, itemAmount DESC";
$result = mysqli_query($link, $sql);

if (mysqli_num_rows($result) > 0){
    
    $sameDay = 69;
    $count = 0;
    while ($row = mysqli_fetch_array($result)){
        if ($sameDay == $row['day']){
            $count++;
            $itemNames[$row['day']-1][$count] = $row['itemName'];
            $itemAmounts[$row['day']-1][$count] = $row['itemAmount'];
        }else{
            $count = 0;
            $itemNames[$row['day']-1][$count] = $row['itemName'];
            $itemAmounts[$row['day']-1][$count] = $row['itemAmount'];
        }
        $sameDay = $row['day'];
    }
    mysqli_free_result($result);

    echo "<hr>";
    if ($currDay >= 1 && $currDay <= 7 || date("m") != 1){
        //WEEK1
        echo "<b><u>WEEK 1</u></b>";
        echo "<br>";
        $week1 = $januaryBal;
        for ($i = 0; $i < 7; $i++){
            for ($j = 0; $j < sizeof($itemAmounts[$i]); $j++){
                if ($itemAmounts[$i][$j] != ""){
                    if ($itemAmounts[$i][$j] < 0){
                        echo $itemAmounts[$i][$j]." ".$itemNames[$i][$j]." 1/".($i+1);
                    }else{
                        echo "+".$itemAmounts[$i][$j]." ".$itemNames[$i][$j]." 1/".($i+1);
                    }
                    echo "<br>";
                    $week1 = $week1 + $itemAmounts[$i][$j];
                }
            }
        }
        echo "<br>Balance = ".$week1;
        echo "<br><br>";
    }
    if ($currDay >= 1 && $currDay <= 14 || date("m") != 1){
        //WEEK2
        echo "<b><u>WEEK 2</u></b>";
        echo "<br>";
        if ($week1 != ""){
            $week2 = $week1;
        }else{
            $week2 = $januaryBal;
        }
        for ($i = 7; $i < 14; $i++){
            for ($j = 0; $j < sizeof($itemAmounts[$i]); $j++){
                if ($itemAmounts[$i][$j] != ""){
                    if ($itemAmounts[$i][$j] < 0){
                        echo $itemAmounts[$i][$j]." ".$itemNames[$i][$j]." 1/".($i+1);
                    }else{
                        echo "+".$itemAmounts[$i][$j]." ".$itemNames[$i][$j]." 1/".($i+1);
                    }
                    echo "<br>";
                    $week2 = $week2 + $itemAmounts[$i][$j];
                }
            }
        }
        echo "<br>Balance = ".$week2;
        echo "<br><br>";
    }
    if ($currDay >= 1 && $currDay <= 21 || date("m") != 1){
        //WEEK3
        echo "<b><u>WEEK 3</u></b>";
        echo "<br>";
        if ($week2 != ""){
            $week3 = $week2;
        }else{
            $week3 = $januaryBal;
        }
        for ($i = 14; $i < 21; $i++){
            for ($j = 0; $j < sizeof($itemAmounts[$i]); $j++){
                if ($itemAmounts[$i][$j] != ""){
                    if ($itemAmounts[$i][$j] < 0){
                        echo $itemAmounts[$i][$j]." ".$itemNames[$i][$j]." 1/".($i+1);
                    }else{
                        echo "+".$itemAmounts[$i][$j]." ".$itemNames[$i][$j]." 1/".($i+1);
                    }
                    echo "<br>";
                    $week3 = $week3 + $itemAmounts[$i][$j];
                }
            }
        }
        echo "<br>Balance = ".$week3;
        echo "<br><br>";
    }
    if ($currDay >= 1 && $currDay <= 28 || date("m") != 1){
        //WEEK4
        echo "<b><u>WEEK 4</u></b>";
        echo "<br>";
        if ($week3 != ""){
            $week4 = $week3;
        }else{
            $week4 = $januaryBal;
        }
        for ($i = 21; $i < 28; $i++){
            for ($j = 0; $j < sizeof($itemAmounts[$i]); $j++){
                if ($itemAmounts[$i][$j] != ""){
                    if ($itemAmounts[$i][$j] < 0){
                        echo $itemAmounts[$i][$j]." ".$itemNames[$i][$j]." 1/".($i+1);
                    }else{
                        echo "+".$itemAmounts[$i][$j]." ".$itemNames[$i][$j]." 1/".($i+1);
                    }
                    echo "<br>";
                    $week4 = $week4 + $itemAmounts[$i][$j];
                }
            }
        }
        echo "<br>Balance = ".$week4;
        echo "<br><br>";
    }
    if ($currDay >= 1 && $currDay <= 31 || date("m") != 1){
        //WEEK5
        echo "<b><u>WEEK 5</u></b>";
        echo "<br>";
        if ($week4 != ""){
            $week5 = $week4;
        }else{
            $week5 = $januaryBal;
        }
        for ($i = 28; $i < 31; $i++){
            for ($j = 0; $j < sizeof($itemAmounts[$i]); $j++){
                if ($itemAmounts[$i][$j] != ""){
                    if ($itemAmounts[$i][$j] < 0){
                        echo $itemAmounts[$i][$j]." ".$itemNames[$i][$j]." 1/".($i+1);
                    }else{
                        echo "+".$itemAmounts[$i][$j]." ".$itemNames[$i][$j]." 1/".($i+1);
                    }
                    echo "<br>";
                    $week5 = $week5 + $itemAmounts[$i][$j];
                }
            }
        }
        echo "<br>Balance = ".$week5;
        echo "<br>";
    }
    echo "<hr>";

    // Attempt select query execution
    $sql = "UPDATE users SET januaryBalance = '$week5' WHERE email = '$email'";
    mysqli_query($link, $sql);
}

// Close connection
mysqli_close($link);
?>