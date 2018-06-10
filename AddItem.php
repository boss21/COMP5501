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
	 
$sql = "INSERT INTO $itemMonth (email, day, itemName, itemAmount) VALUES ('$email', '$itemDay', '$itemName', '$itemAmount')";
  if ($dup == false){
    mysqli_query($link, $sql);
    mysqli_close($link);
		echo "<script type='text/javascript'>alert('Item Successfully Added.');</script>";
  } else if ($dup == true){
    mysqli_close($link);
    echo "<script type='text/javascript'>alert('Duplicate Item, Not Adding.');</script>";
  } else{
    mysqli_close($link);
		echo "<script type='text/javascript'>alert('Oops, Something Went Wrong. Please Try Again Later.');</script>";
  }
}
	 
 }else{
 echo "Check Again";
 }
mysqli_close($con);
?>
