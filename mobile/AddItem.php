<?php
 if($_SERVER['REQUEST_METHOD']=='POST'){
  //Define your host here.
$HostName = "localhost";
//Define your database username here.
$HostUser = "root";
//Define your database password here.
$HostPass = "p4ssw0rd";
//Define your database name here.
$DatabaseName = "all_users";
  
 $link = mysqli_connect($HostName,$HostUser,$HostPass,$DatabaseName);
 
 $itemMonth = trim($_POST['month']);
 $email = trim($_POST['email']);
 $itemDay = trim($_POST['day']);
 $itemName = trim($_POST['itemname']);
 $itemAmount = trim($_POST['itemvalue']);
	 
  // Attempt select query execution
  $sql = "SELECT * FROM $itemMonth WHERE email = '$email' AND day = '$itemDay' AND itemName = '$itemName'";
  $result = mysqli_query($link, $sql);
  if (mysqli_num_rows($result) != 0){
    $dup = true;
  }

	 
$sql = "INSERT INTO $itemMonth (email, day, itemName, itemAmount) VALUES ('$email', '$itemDay', '$itemName', '$itemAmount')";
  if ($dup == false){
    mysqli_query($link, $sql);
    mysqli_close($link);
		echo "Item Added.";
  } else if ($dup == true){
    mysqli_close($link);
    echo "Duplicate Item, Not Adding.";
  } else{
    mysqli_close($link);
		echo "Oops, Something Went Wrong. Please Try Again Later.";
  }

	 
 }else{
 echo "Check Again";
 }
mysqli_close($con);
?>
