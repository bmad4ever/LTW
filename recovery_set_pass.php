<?php
include('header.php');
include('getInputSafe.php');

sleep(1);//avoid spam

if ($_SERVER["REQUEST_METHOD"] != "POST") 	{
	 header("location: main.php");
	 return '';	
}
	
	$id = htmlentities($_POST['id']);
	$pass = htmlentities($_POST['log_password']);
	$passconf = htmlentities($_POST['log_password_conf']);
	$code = htmlentities($_POST['code']);
	
	if( !isset($id)
	||!isset($pass)
	||!isset($passconf)
	||$id===null
	||$id===""
	||$pass===null
	||$pass===""
	||$passconf===null
	||$passconf==="")
	{
	 header("location: main.php?errorMsg=".urlencode("Field is Empty!"));
	 return '';	
	}


function changePass() {
	global $id;
	global $pass;
	
	$db = new PDO('sqlite:database.db');
	
	$stmt = $db->prepare("UPDATE users
						SET password=?
						WHERE id=?");
	$stmt->execute(array(md5($pass),$id)); 
	
	$stmt = $db->prepare("DELETE FROM recovery
						WHERE user_id=?");
	$stmt->execute(array($id)); 
}

if ($pass==$passconf) {
	changePass();
	echo "<script type='text/javascript'>alert('Succesfully changed password. You can now login.');window.location.href = 'main.php';</script>";
}
else {
	echo "<script type='text/javascript'>alert('Passwords do not match.');window.location.href = 'recovery_change_pass.php?=$code';</script>";
}



	
?>