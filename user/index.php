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
  if ($_POST['itemMonthDay'] != "" && $_POST['itemName'] != "" && $_POST['itemAmount'] != "" && $_POST['frequency'] != ""){
    $itemMonthDay = $_POST['itemMonthDay'];

    $itemMonth = date("m", strtotime($itemMonthDay));
    $dateObj   = DateTime::createFromFormat('!m', $itemMonth);
    $itemMonth = $dateObj->format('F');
    $itemMonth = strtolower($itemMonth);
    
    $itemDay = date("d", strtotime($itemMonthDay));
    $itemName = $_POST['itemName'];
    $itemAmount = $_POST['itemAmount'];

    if ($_POST['frequency'] == "once"){
      // Attempt select query execution
      $sql = "SELECT * FROM $itemMonth WHERE email = '$email' AND day = '$itemDay' AND itemName = '$itemName'";
      $result = mysqli_query($link, $sql);
      if (mysqli_num_rows($result) != 0){
        $dup = true;
      }

      // Free result set
      mysqli_free_result($result);

      // Attempt select query execution
      $sql = "INSERT INTO $itemMonth (email, day, itemName, itemAmount) VALUES ('$email', '$itemDay', '$itemName', '$itemAmount')";
      if ($dup == false){
        mysqli_query($link, $sql);
        mysqli_close($link);
        echo "<script type='text/javascript'>alert('Item Successfully Added.');</script>";
      } else if ($dup == true){
        mysqli_close($link);
        echo "<script type='text/javascript'>alert('Duplicate Item, Not Adding. If you need to edit an item, click Edit Item.');</script>";
      }
    }else if ($_POST['frequency'] == "weekly"){
      
    }else if ($_POST['frequency'] == "monthly"){
      if (date("m") == 1){
        // Attempt select query execution
        $sql = "SELECT * FROM $itemMonth WHERE email = '$email' AND day = '$itemDay' AND itemName = '$itemName'";
        $result = mysqli_query($link, $sql);
        if (mysqli_num_rows($result) != 0){
          $dup = true;
        }

        // Free result set
        mysqli_free_result($result);

        // Attempt select query execution
        $sql = "INSERT INTO january (email, day, itemName, itemAmount) VALUES ('$email', '$itemDay', '$itemName', '$itemAmount')";
        $sql .= "INSERT INTO february (email, day, itemName, itemAmount) VALUES ('$email', '$itemDay', '$itemName', '$itemAmount')";
        if ($dup == false){
          mysqli_query($link, $sql);
          mysqli_close($link);
          echo "<script type='text/javascript'>alert('Item Successfully Added.');</script>";
        } else if ($dup == true){
          mysqli_close($link);
          echo "<script type='text/javascript'>alert('Duplicate Item, Not Adding. If you need to edit an item, click Edit Item.');</script>";
        }
      }else if(date("m") == 2){

      }else if(date("m") == 3){
        
      }else if(date("m") == 4){
        
      }else if(date("m") == 5){
        
      }else if(date("m") == 6){
        
      }else if(date("m") == 7){
        
      }else if(date("m") == 8){
        
      }else if(date("m") == 9){
        
      }else if(date("m") == 10){
        
      }else if(date("m") == 11){
        
      }else if(date("m") == 12){
        
      }
    }
  }else if ($_POST['currentBal'] != ""){
    $currentBal = $_POST['currentBal'];
    // Attempt select query execution
    $sql = "UPDATE users SET currentBalance = '$currentBal' WHERE email = '$email'";
    if (mysqli_query($link, $sql)){
      mysqli_close($link);
      echo "<script type='text/javascript'>alert('Current Balance Updated.'); window.location = 'index.php';</script>";
    }
  }else if ($_POST['month'] != ""){
    $month = $_POST['month'];
    // Attempt select query execution
    $sql = "DELETE FROM $month WHERE email = '$email'";
    if (mysqli_query($link, $sql)){
      mysqli_close($link);
      echo "<script type='text/javascript'>alert('$month cleared.'); window.location = 'index.php';</script>";
    }
  }
}

// Attempt select query execution
$sql = "SELECT * FROM users WHERE email = '$email'";
$result = mysqli_query($link, $sql);
$row = mysqli_fetch_array($result);

//grab data here
$currentBal = $row['currentBalance'];

// Free result set
mysqli_free_result($result);
 
// Close connection
mysqli_close($link);

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
  <title>The Financial Wizard</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.css">
  <style>
    .btn-grid {
      margin: -2px 0;
    }

    .btn-grid>.btn {
      margin: 2px 0;
    }

    .col-centered {
      float: none;
      margin: 0 auto;
    }
  </style>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.js"></script>
  <script>
    window.onload = function () {
      $("#currentBalance").hide();
      $("#addItem").hide();
      $("#editItem").hide();
      $("#removeItem").hide();
      $("#clearMonth").hide();
      document.getElementById("currentBalanceButton").addEventListener("click", currentBalanceShow);
      document.getElementById("addItemButton").addEventListener("click", addItemShow);
      document.getElementById("editItemButton").addEventListener("click", editItemShow);
      document.getElementById("removeItemButton").addEventListener("click", removeItemShow);
      document.getElementById("clearMonthButton").addEventListener("click", clearMonthShow);
      document.getElementById("frequency").addEventListener("change", disableDate);

      //Show Current Month First
      var TodayDate = new Date();
      var m = TodayDate.getMonth() + 1;
      if (m == 1) {
        document.getElementById("months").innerHTML = "<h2><a href='#' id='januaryA'>January</a></h2><div id='january'></div><h2><a href='#' id='februaryA'>February</a></h2><div id='february'></div><h2><a href='#' id='marchA'>March</a></h2><div id='march'></div><h2><a href='#' id='aprilA'>April</a></h2><div id='april'></div><h2><a href='#' id='mayA'>May</a></h2><div id='may'></div><h2><a href='#' id='juneA'>June</a></h2><div id='june'></div><h2><a href='#' id='julyA'>July</a></h2><div id='july'></div><h2><a href='#' id='augustA'>August</a></h2><div id='august'></div><h2><a href='#' id='septemberA'>September</a></h2><div id='september'></div><h2><a href='#' id='octoberA'>October</a></h2><div id='october'></div><h2><a href='#' id='novemberA'>November</a></h2><div id='november'></div><h2><a href='#' id='decemberA'>December</a></h2><div id='december'></div>";
      } else if (m == 2) {
        document.getElementById("months").innerHTML = "<h2><a href='#' id='februaryA'>February</a></h2><div id='february'></div><h2><a href='#' id='marchA'>March</a></h2><div id='march'></div><h2><a href='#' id='aprilA'>April</a></h2><div id='april'></div><h2><a href='#' id='mayA'>May</a></h2><div id='may'></div><h2><a href='#' id='juneA'>June</a></h2><div id='june'></div><h2><a href='#' id='julyA'>July</a></h2><div id='july'></div><h2><a href='#' id='augustA'>August</a></h2><div id='august'></div><h2><a href='#' id='septemberA'>September</a></h2><div id='september'></div><h2><a href='#' id='octoberA'>October</a></h2><div id='october'></div><h2><a href='#' id='novemberA'>November</a></h2><div id='november'></div><h2><a href='#' id='decemberA'>December</a></h2><div id='december'></div><h2><a href='#' id='januaryA'>January</a></h2><div id='january'></div>";
      } else if (m == 3) {
        document.getElementById("months").innerHTML = "<h2><a href='#' id='marchA'>March</a></h2><div id='march'></div><h2><a href='#' id='aprilA'>April</a></h2><div id='april'></div><h2><a href='#' id='mayA'>May</a></h2><div id='may'></div><h2><a href='#' id='juneA'>June</a></h2><div id='june'></div><h2><a href='#' id='julyA'>July</a></h2><div id='july'></div><h2><a href='#' id='augustA'>August</a></h2><div id='august'></div><h2><a href='#' id='septemberA'>September</a></h2><div id='september'></div><h2><a href='#' id='octoberA'>October</a></h2><div id='october'></div><h2><a href='#' id='novemberA'>November</a></h2><div id='november'></div><h2><a href='#' id='decemberA'>December</a></h2><div id='december'></div><h2><a href='#' id='januaryA'>January</a></h2><div id='january'></div><h2><a href='#' id='februaryA'>February</a></h2><div id='february'></div>";
      } else if (m == 4) {
        document.getElementById("months").innerHTML = "<h2><a href='#' id='aprilA'>April</a></h2><div id='april'></div><h2><a href='#' id='mayA'>May</a></h2><div id='may'></div><h2><a href='#' id='juneA'>June</a></h2><div id='june'></div><h2><a href='#' id='julyA'>July</a></h2><div id='july'></div><h2><a href='#' id='augustA'>August</a></h2><div id='august'></div><h2><a href='#' id='septemberA'>September</a></h2><div id='september'></div><h2><a href='#' id='octoberA'>October</a></h2><div id='october'></div><h2><a href='#' id='novemberA'>November</a></h2><div id='november'></div><h2><a href='#' id='decemberA'>December</a></h2><div id='december'></div><h2><a href='#' id='januaryA'>January</a></h2><div id='january'></div><h2><a href='#' id='februaryA'>February</a></h2><div id='february'></div><h2><a href='#' id='marchA'>March</a></h2><div id='march'></div>";
      } else if (m == 5) {
        document.getElementById("months").innerHTML = "<h2><a href='#' id='mayA'>May</a></h2><div id='may'></div><h2><a href='#' id='juneA'>June</a></h2><div id='june'></div><h2><a href='#' id='julyA'>July</a></h2><div id='july'></div><h2><a href='#' id='augustA'>August</a></h2><div id='august'></div><h2><a href='#' id='septemberA'>September</a></h2><div id='september'></div><h2><a href='#' id='octoberA'>October</a></h2><div id='october'></div><h2><a href='#' id='novemberA'>November</a></h2><div id='november'></div><h2><a href='#' id='decemberA'>December</a></h2><div id='december'></div><h2><a href='#' id='januaryA'>January</a></h2><div id='january'></div><h2><a href='#' id='februaryA'>February</a></h2><div id='february'></div><h2><a href='#' id='marchA'>March</a></h2><div id='march'></div><h2><a href='#' id='aprilA'>April</a></h2><div id='april'></div>";
      } else if (m == 6) {
        document.getElementById("months").innerHTML = "<h2><a href='#' id='juneA'>June</a></h2><div id='june'></div><h2><a href='#' id='julyA'>July</a></h2><div id='july'></div><h2><a href='#' id='augustA'>August</a></h2><div id='august'></div><h2><a href='#' id='septemberA'>September</a></h2><div id='september'></div><h2><a href='#' id='octoberA'>October</a></h2><div id='october'></div><h2><a href='#' id='novemberA'>November</a></h2><div id='november'></div><h2><a href='#' id='decemberA'>December</a></h2><div id='december'></div><h2><a href='#' id='januaryA'>January</a></h2><div id='january'></div><h2><a href='#' id='februaryA'>February</a></h2><div id='february'></div><h2><a href='#' id='marchA'>March</a></h2><div id='march'></div><h2><a href='#' id='aprilA'>April</a></h2><div id='april'></div><h2><a href='#' id='mayA'>May</a></h2><div id='may'></div>";
      } else if (m == 7) {
        document.getElementById("months").innerHTML = "<h2><a href='#' id='julyA'>July</a></h2><div id='july'></div><h2><a href='#' id='augustA'>August</a></h2><div id='august'></div><h2><a href='#' id='septemberA'>September</a></h2><div id='september'></div><h2><a href='#' id='octoberA'>October</a></h2><div id='october'></div><h2><a href='#' id='novemberA'>November</a></h2><div id='november'></div><h2><a href='#' id='decemberA'>December</a></h2><div id='december'></div><h2><a href='#' id='januaryA'>January</a></h2><div id='january'></div><h2><a href='#' id='februaryA'>February</a></h2><div id='february'></div><h2><a href='#' id='marchA'>March</a></h2><div id='march'></div><h2><a href='#' id='aprilA'>April</a></h2><div id='april'></div><h2><a href='#' id='mayA'>May</a></h2><div id='may'></div><h2><a href='#' id='juneA'>June</a></h2><div id='june'></div>";
      } else if (m == 8) {
        document.getElementById("months").innerHTML = "<h2><a href='#' id='augustA'>August</a></h2><div id='august'></div><h2><a href='#' id='septemberA'>September</a></h2><div id='september'></div><h2><a href='#' id='octoberA'>October</a></h2><div id='october'></div><h2><a href='#' id='novemberA'>November</a></h2><div id='november'></div><h2><a href='#' id='decemberA'>December</a></h2><div id='december'></div><h2><a href='#' id='januaryA'>January</a></h2><div id='january'></div><h2><a href='#' id='februaryA'>February</a></h2><div id='february'></div><h2><a href='#' id='marchA'>March</a></h2><div id='march'></div><h2><a href='#' id='aprilA'>April</a></h2><div id='april'></div><h2><a href='#' id='mayA'>May</a></h2><div id='may'></div><h2><a href='#' id='juneA'>June</a></h2><div id='june'></div><h2><a href='#' id='julyA'>July</a></h2><div id='july'></div>";
      } else if (m == 9) {
        document.getElementById("months").innerHTML = "<h2><a href='#' id='septemberA'>September</a></h2><div id='september'></div><h2><a href='#' id='octoberA'>October</a></h2><div id='october'></div><h2><a href='#' id='novemberA'>November</a></h2><div id='november'></div><h2><a href='#' id='decemberA'>December</a></h2><div id='december'></div><h2><a href='#' id='januaryA'>January</a></h2><div id='january'></div><h2><a href='#' id='februaryA'>February</a></h2><div id='february'></div><h2><a href='#' id='marchA'>March</a></h2><div id='march'></div><h2><a href='#' id='aprilA'>April</a></h2><div id='april'></div><h2><a href='#' id='mayA'>May</a></h2><div id='may'></div><h2><a href='#' id='juneA'>June</a></h2><div id='june'></div><h2><a href='#' id='julyA'>July</a></h2><div id='july'></div><h2><a href='#' id='augustA'>August</a></h2><div id='august'></div>";
      } else if (m == 10) {
        document.getElementById("months").innerHTML = "<h2><a href='#' id='octoberA'>October</a></h2><div id='october'></div><h2><a href='#' id='novemberA'>November</a></h2><div id='november'></div><h2><a href='#' id='decemberA'>December</a></h2><div id='december'></div><h2><a href='#' id='januaryA'>January</a></h2><div id='january'></div><h2><a href='#' id='februaryA'>February</a></h2><div id='february'></div><h2><a href='#' id='marchA'>March</a></h2><div id='march'></div><h2><a href='#' id='aprilA'>April</a></h2><div id='april'></div><h2><a href='#' id='mayA'>May</a></h2><div id='may'></div><h2><a href='#' id='juneA'>June</a></h2><div id='june'></div><h2><a href='#' id='julyA'>July</a></h2><div id='july'></div><h2><a href='#' id='augustA'>August</a></h2><div id='august'></div><h2><a href='#' id='septemberA'>September</a></h2><div id='september'></div>";
      } else if (m == 11) {
        document.getElementById("months").innerHTML = "<h2><a href='#' id='novemberA'>November</a></h2><div id='november'></div><h2><a href='#' id='decemberA'>December</a></h2><div id='december'></div><h2><a href='#' id='januaryA'>January</a></h2><div id='january'></div><h2><a href='#' id='februaryA'>February</a></h2><div id='february'></div><h2><a href='#' id='marchA'>March</a></h2><div id='march'></div><h2><a href='#' id='aprilA'>April</a></h2><div id='april'></div><h2><a href='#' id='mayA'>May</a></h2><div id='may'></div><h2><a href='#' id='juneA'>June</a></h2><div id='june'></div><h2><a href='#' id='julyA'>July</a></h2><div id='july'></div><h2><a href='#' id='augustA'>August</a></h2><div id='august'></div><h2><a href='#' id='septemberA'>September</a></h2><div id='september'></div><h2><a href='#' id='octoberA'>October</a></h2><div id='october'></div>";
      } else if (m == 12) {
        document.getElementById("months").innerHTML = "<h2><a href='#' id='decemberA'>December</a></h2><div id='december'></div><h2><a href='#' id='januaryA'>January</a></h2><div id='january'></div><h2><a href='#' id='februaryA'>February</a></h2><div id='february'></div><h2><a href='#' id='marchA'>March</a></h2><div id='march'></div><h2><a href='#' id='aprilA'>April</a></h2><div id='april'></div><h2><a href='#' id='mayA'>May</a></h2><div id='may'></div><h2><a href='#' id='juneA'>June</a></h2><div id='june'></div><h2><a href='#' id='julyA'>July</a></h2><div id='july'></div><h2><a href='#' id='augustA'>August</a></h2><div id='august'></div><h2><a href='#' id='septemberA'>September</a></h2><div id='september'></div><h2><a href='#' id='octoberA'>October</a></h2><div id='october'></div><h2><a href='#' id='novemberA'>November</a></h2><div id='november'></div>";
      }

      $("#january").hide();
      $("#february").hide();
      $("#march").hide();
      $("#april").hide();
      $("#may").hide();
      $("#june").hide();
      $("#july").hide();
      $("#august").hide();
      $("#september").hide();
      $("#october").hide();
      $("#november").hide();
      $("#december").hide();

      document.getElementById("januaryA").addEventListener("click", januaryView);
      document.getElementById("februaryA").addEventListener("click", februaryView);
      document.getElementById("marchA").addEventListener("click", marchView);
      document.getElementById("aprilA").addEventListener("click", aprilView);
      document.getElementById("mayA").addEventListener("click", mayView);
      document.getElementById("juneA").addEventListener("click", juneView);
      document.getElementById("julyA").addEventListener("click", julyView);
      document.getElementById("augustA").addEventListener("click", augustView);
      document.getElementById("septemberA").addEventListener("click", septemberView);
      document.getElementById("octoberA").addEventListener("click", octoberView);
      document.getElementById("novemberA").addEventListener("click", novemberView);
      document.getElementById("decemberA").addEventListener("click", decemberView);

      updateJanuary();
      updateFebruary();
      updateMarch();
      updateApril();
      updateMay();
      updateJune();
      updateJuly();
      updateAugust();
      updateSeptember();
      updateOctober();
      updateNovember();
      updateDecember();
    }
    function currentBalanceShow() {
      $("#currentBalance").show();
      $("#addItem").hide();
      $("#editItem").hide();
      $("#removeItem").hide();
      $("#clearMonth").hide();
    }
    function addItemShow() {
      $("#addItem").show();
      $("#currentBalance").hide();
      $("#editItem").hide();
      $("#removeItem").hide();
      $("#clearMonth").hide();
    }
    function editItemShow() {
      $("#editItem").show();
      $("#currentBalance").hide();
      $("#addItem").hide();
      $("#removeItem").hide();
      $("#clearMonth").hide();
    }
    function removeItemShow() {
      $("#removeItem").show();
      $("#currentBalance").hide();
      $("#addItem").hide();
      $("#editItem").hide();
      $("#clearMonth").hide();
    }
    function clearMonthShow() {
      $("#clearMonth").show();
      $("#currentBalance").hide();
      $("#addItem").hide();
      $("#editItem").hide();
      $("#removeItem").hide();
    }
    function disableDate() {
      if (document.getElementById("frequency").value == "weekly" || document.getElementById("frequency").value == "monthly") {
        document.getElementById("itemMonthDay").disabled = true;
      } else {
        document.getElementById("itemMonthDay").disabled = false;
      }
    }
    function januaryView() {
      if ($("#january").is(":hidden")) {
        $("#january").show();
      } else {
        $("#january").hide();
      }
    }
    function februaryView() {
      if ($("#february").is(":hidden")) {
        $("#february").show();
      } else {
        $("#february").hide();
      }
    }
    function marchView() {
      if ($("#march").is(":hidden")) {
        $("#march").show();
      } else {
        $("#march").hide();
      }
    }
    function aprilView() {
      if ($("#april").is(":hidden")) {
        $("#april").show();
      } else {
        $("#april").hide();
      }
    }
    function mayView() {
      if ($("#may").is(":hidden")) {
        $("#may").show();
      } else {
        $("#may").hide();
      }
    }
    function juneView() {
      if ($("#june").is(":hidden")) {
        $("#june").show();
      } else {
        $("#june").hide();
      }
    }
    function julyView() {
      if ($("#july").is(":hidden")) {
        $("#july").show();
      } else {
        $("#july").hide();
      }
    }
    function augustView() {
      if ($("#august").is(":hidden")) {
        $("#august").show();
      } else {
        $("#august").hide();
      }
    }
    function septemberView() {
      if ($("#september").is(":hidden")) {
        $("#september").show();
      } else {
        $("#september").hide();
      }
    }
    function octoberView() {
      if ($("#october").is(":hidden")) {
        $("#october").show();
      } else {
        $("#october").hide();
      }
    }
    function novemberView() {
      if ($("#november").is(":hidden")) {
        $("#november").show();
      } else {
        $("#november").hide();
      }
    }
    function decemberView() {
      if ($("#december").is(":hidden")) {
        $("#december").show();
      } else {
        $("#december").hide();
      }
    }
    function updateJanuary() {
      var url = "months/january.php";
      $('#january').load(url);
    }
    function updateFebruary() {
      var url = "months/february.php";
      $('#february').load(url);
    }
    function updateMarch() {
      var url = "months/march.php";
      $('#march').load(url);
    }
    function updateApril() {
      var url = "months/april.php";
      $('#april').load(url);
    }
    function updateMay() {
      var url = "months/may.php";
      $('#may').load(url);
    }
    function updateJune() {
      var url = "months/june.php";
      $('#june').load(url);
    }
    function updateJuly() {
      var url = "months/july.php";
      $('#july').load(url);
    }
    function updateAugust() {
      var url = "months/august.php";
      $('#august').load(url);
    }
    function updateSeptember() {
      var url = "months/september.php";
      $('#september').load(url);
    }
    function updateOctober() {
      var url = "months/october.php";
      $('#october').load(url);
    }
    function updateNovember() {
      var url = "months/november.php";
      $('#november').load(url);
    }
    function updateDecember() {
      var url = "months/december.php";
      $('#december').load(url);
    }
  </script>

</head>

<body>
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <span>Hello,
      <?php echo $email ?>
    </span>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
      aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav ml-auto">
        <li class="nav-item">
          <a class="nav-link" href="index.php">Home</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true"
            aria-expanded="false">
            More Financial Tools
          </a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdown">
            <a class="dropdown-item" href="../otherTools/capital">Capital Gains/Losses</a>
            <a class="dropdown-item" href="../otherTools/loans">Loans</a>
          </div>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true"
            aria-expanded="false">
            Settings
          </a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdown">
            <a class="dropdown-item" href="changeEmail.php">Change Email</a>
            <a class="dropdown-item" href="changePassword.php">Change Password</a>
            <a class="dropdown-item" href="deleteAccount.php" onclick="return confirm('Are you sure you want to delete your account?')">Delete Account</a>
          </div>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="logout.php">Logout</a>
        </li>
    </div>
  </nav>
  <div class="container">
    <div class="row">
      <div class="col-sm-2 text-center"></div>
      <div class="col-sm-8 text-center">
        <br>
        <div class="btn-grid">
          <button type="button" id="currentBalanceButton" class="btn btn-default">Current Balance</button>
          <button type="button" id="addItemButton" class="btn btn-success">Add Item</button>
          <button type="button" id="editItemButton" class="btn btn-primary">Edit Item</button>
          <button type="button" id="removeItemButton" class="btn btn-danger">Remove Item</button>
          <button type="button" id="clearMonthButton" class="btn btn-default">Clear Month</button>
        </div>
        <br>
        <div id="currentBalance">
          <form action="<?php echo htmlspecialchars($_SERVER[" PHP_SELF "]); ?>" method="post">
            <div class="form-group col-sm-6 col-centered">
              <label>Current Balance:</label>
              <input name="currentBal" type="number" required="required" min="1" max="999999" step=".01" value="<?php echo $currentBal ?>"
                class="form-control">
            </div>
            <br>
            <div class="form-group">
              <input type="submit" class="btn btn-primary" value="Save">
              <input type="reset" class="btn btn-default" value="Reset">
            </div>
          </form>
        </div>
        <div id="addItem">
          <form action="<?php echo htmlspecialchars($_SERVER[" PHP_SELF "]); ?>" method="post">
            <div class="form-group col-sm-6 col-centered">
              <label>Date:</label>
              <input id="itemMonthDay" name="itemMonthDay" type="date" required="required" min="<?php echo date('Y-m-d'); ?>" max="<?php echo date('Y-m-d', strtotime('+364 days')); ?>"
                class="form-control">
              <label>Name:</label>
              <input name="itemName" type="text" required="required" maxlength="20" class="form-control">
              <label>Amount (negative for an expense):</label>
              <input name="itemAmount" type="number" required="required" min="1" max="999999" step=".01" class="form-control">
              <label>Frequency</label>
              <select id="frequency" name="frequency" required="required" class="form-control">
                <option value="once">Once</option>
                <option value="weekly">Weekly</option>
                <option value="monthly">Monthly</option>
              </select>
            </div>
            <br>
            <div class="form-group">
              <input type="submit" class="btn btn-primary" value="Add">
              <input type="reset" class="btn btn-default" value="Reset">
            </div>
          </form>
        </div>
        <div id="editItem">
        </div>
        <div id="removeItem">
        </div>
        <div id="clearMonth">
          <form action="<?php echo htmlspecialchars($_SERVER[" PHP_SELF "]); ?>" method="post">
            <div class="form-group col-sm-6 col-centered">
              <label>Month:</label>
              <select name="month" class="form-control">
                <option value="january">January</option>
                <option value="february">February</option>
                <option value="march">March</option>
                <option value="april">April</option>
                <option value="may">May</option>
                <option value="june">June</option>
                <option value="july">July</option>
                <option value="august">August</option>
                <option value="september">September</option>
                <option value="october">October</option>
                <option value="november">November</option>
                <option value="december">December</option>
              </select>
            </div>
            <br>
            <div class="form-group">
              <input type="submit" class="btn btn-primary" value="Clear">
              <input type="reset" class="btn btn-default" value="Reset">
            </div>
          </form>
        </div>
      </div>
      <div class="col-sm-2 text-center"></div>
    </div>
    <div class="row">
      <div id="months" class="col-sm-12 text-center"></div>
    </div>
  </div>
</body>

</html>