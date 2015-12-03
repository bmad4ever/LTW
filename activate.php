<?php
include('header.php');
include('getInputSafe.php');

sleep(1);//avoid spam

$code = $_GET['code'];

function verifyCode() {
	global $code;
	$db = new PDO('sqlite:database.db');
	$stmt = $db->prepare('SELECT * FROM users WHERE code=?');
	$stmt->execute(array($code)); 
    $result=$stmt->fetchAll();
	
	if (count($result) == 0) {
		return 0;
	}
	else {
		return $result[0]['id'];
	}
}

function activateUser($id) {
	global $code;
	$db = new PDO('sqlite:database.db');
	
	$stmt = $db->prepare('SELECT active FROM users WHERE id=?');
	$stmt->execute(array($id)); 
    $result=$stmt->fetchAll();
	
	if($result[0]['active']==1) {
		return 1;
	}
	else {
		$stmt = $db->prepare("UPDATE users
						SET active=1
						WHERE id=?");
		$stmt->execute(array($id)); 
		$result=$stmt->fetchAll();
		return 0;
	}
	
	
}

$id=verifyCode();

if($id==0) {
	echo "<script type='text/javascript'>alert('Code is not valid. Returning to main page.');window.location.href = 'main.php';</script>";
}
else if(activateUser($id)==1){
		echo "<script type='text/javascript'>alert('User already activated. You can log in.');window.location.href = 'main.php';</script>";
	}
	else {
		echo "<script type='text/javascript'>alert('User succesfully activated. You can log in.');window.location.href = 'main.php';</script>";
	}


?>