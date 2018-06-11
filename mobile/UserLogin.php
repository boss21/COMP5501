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
 
 $email = trim($_POST['email']);
 $password = trim($_POST['password']);
	 
 $sql = "select password, active from users where email = '$email'";
 
 $result = mysqli_query($link,$sql);
 $row = mysqli_fetch_array($result);

 $hashed_password = $row['password'];
 $active = $row['active'];
  
 if(password_verify($password, $hashed_password) && $active == 1){
 echo "Login Successful";
 }else if($active == 0){
 echo "Please verify your email";
 }else{
 echo "Oops, Something Went Wrong. Please Try Again Later.";
 }
	 
 }else{
 echo "Check Again";
 }
mysqli_close($con);

?>
