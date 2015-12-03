<?php
include("header.php");
include("getInputSafe.php");

  if (isset($_SESSION['login_user']) && isset($_GET['comment']) &&  isset($_SESSION['display_event_id'])) {
  
  $com = cleanUserTextTags(trim($_GET['comment']));
  
	if($com!="")
	{
  
  // Current datetime
  $current_datetime = date("Y-m-d H:i:s");
  
  // Database connection
  $dbh = new PDO('sqlite:database.db');
  $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
  $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	  
    // Insert Comment
    $stmt = $dbh->prepare("INSERT INTO comments VALUES (null, ?, ?, ?,?)");
    $stmt->execute(array($_SESSION['login_user'], $_SESSION['display_event_id'], $current_datetime , $com) );
	
	echo json_encode("OK"); 
	}	
  }
  echo json_encode("INVALID");  
?>