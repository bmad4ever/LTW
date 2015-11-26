<?php

  if (isset($_GET['userid']) && isset($_GET['comment']) && isset($_GET['event_id'])) {
  // Current datetime
  $current_datetime = date("Y-m-d H:i:s");
  
  // Database connection
  $dbh = new PDO('sqlite:database.db');
  $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
  $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	  
    // Insert Comment
    $stmt = $dbh->prepare("INSERT INTO comments VALUES (null, ?, ?, ?,?)");
    $stmt->execute(array($_GET['userid'], $_GET['event_id'], $current_datetime ,$_GET['comment']));
	
	echo json_encode("OK");  
  }
else  echo json_encode("INVALID");  
?>