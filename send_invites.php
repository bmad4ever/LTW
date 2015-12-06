<?php
include("header.php");
$event_id=$_POST['id'];
	$dbh = new PDO('sqlite:database.db');
	$dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
	$stmt = $dbh->prepare("SELECT owner from events
							WHERE id_event=?");
	$stmt->execute(array($event_id));
	$owner = $stmt->fetch();

if(!checkLogged() || $_SESSION['login_user']!=$owner['owner']){
	session_destroy();
    header("Location: main.php?errorMsg=".urlencode("Illegal event invitation!"));
	return '';
}


if(isset($_POST['invite'])){
  if (is_array($_POST['invite'])) {
    foreach($_POST['invite'] as $user_id){
		$dbh = new PDO('sqlite:database.db');
		$dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$stmt = $dbh->prepare("INSERT OR IGNORE INTO invitations (user_id,event_id) VALUES (?,?)");
		$stmt->execute(array($user_id,$event_id) );
		
		
		
    }
  }
  else {
		$user_id = $_POST['invite'];
		$dbh = new PDO('sqlite:database.db');
		$dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$stmt = $dbh->prepare("INSERT INTO invitations VALUES (?,?)");
		$stmt->execute(array($user_id,$event_id) );
  }
  
  echo "<script type='text/javascript'>alert('Invites sent!');window.location.href = 'event.php?id=".urlencode($event_id)."';</script>";
  
}
else {
	echo "<script type='text/javascript'>alert('Select someone to invite.');window.location.href = 'event_invite.php?id=".urlencode($event_id)."';</script>";
}


	


?>