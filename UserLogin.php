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
  
 $con = mysqli_connect($HostName,$HostUser,$HostPass,$DatabaseName);
 
 $email = $_POST['email'];
 $password = $_POST['password'];
 
 $Sql_Query = "SELECT email, password, active FROM users WHERE email = ?";
 
 $check = mysqli_fetch_array(mysqli_query($con,$Sql_Query));
 
 if(isset($check)){
 
 echo "Data Matched";
 }
 else{
 echo "Invalid Username or Password Please Try Again";
 }
 
 }else{
 echo "Check Again";
 }
mysqli_close($con);

?>
