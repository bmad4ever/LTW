<?php
include("header.php");
include("getInputSafe.php");

	$valid_user = validate_user();

	
if (!validate_user()) {
	header("Location: main.php?errorMsg=".urlencode("Register Event User Not Logged In!"));
	
} else {
	
	$event_id=$_GET['id'];
	
	$dbh = new PDO('sqlite:database.db');
	$dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
	$stmt = $dbh->prepare("SELECT owner from events
							WHERE id_event=?");
	$stmt->execute(array($event_id));
	$owner = $stmt->fetch();
	
	echo $_SESSION['login_user'];
	echo $owner['owner'];
	if($_SESSION['login_user']==$owner['owner']) {
		//delete event
		$stmt = $dbh->prepare("DELETE FROM events
							WHERE id_event=?");
		$stmt->execute(array($event_id));
		echo "<script type='text/javascript'>alert('Event deleted.');window.location.href = 'main.php';</script>";
	}
	else {
		header('Location: main.php');
	}
}


?>