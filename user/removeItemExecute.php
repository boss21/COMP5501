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

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
  if ($_POST['removeItemMonthDay'] != "" && $_POST['itemName'] != ""){
    $removeItemMonthDay = $_POST['removeItemMonthDay'];
    $itemName = $_POST['itemName'];

    $itemMonth = date("m", strtotime($removeItemMonthDay));
    $dateObj   = DateTime::createFromFormat('!m', $itemMonth);
    $itemMonth = $dateObj->format('F');
    $itemMonth = strtolower($itemMonth);
    
    $itemDay = date("d", strtotime($removeItemMonthDay));

    // Attempt select query execution
    $sql = "DELETE FROM $itemMonth WHERE email = '$email' AND day = '$itemDay' AND itemName = '$itemName'";
    if (mysqli_query($link, $sql)){
        mysqli_close($link);
        echo "<script type='text/javascript'>alert('Item Removed.');window.location='index.php';</script>";
    } else{
        mysqli_close($link);
        echo "<script type='text/javascript'>alert('Oops, Something Went Wrong. Please try again later.');window.location='index.php';</script>";
    }
  } else{
    echo "<script type='text/javascript'>window.location='index.php';</script>";
  }
}

?>