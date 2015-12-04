<?php
include("header.php");
//if (isset($_GET['event_id'])) {
  // Current time
  //$timestamp = time();
  
if (isset($_SESSION['display_event_id']) && isset($_GET['last_id']) &&  isset($_GET['limit']))
{
    // Current datetime
  //$current_datetime = date("Y-m-d H:i:s");
  
  // Database connection
  $dbh = new PDO('sqlite:database.db');
  $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
  $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  // Retrieve new comments
  $stmt = $dbh->prepare("SELECT comment_text,date_comment,comments.id,username from comments
						INNER JOIN users ON users.id=comments.user_id
						WHERE comments.event_id= ? AND comments.id > ? ORDER BY date_comment ASC LIMIT ?");
  $stmt->execute(array($_SESSION['display_event_id'],$_GET['last_id'],$_GET['limit']));
  $comments = $stmt->fetchAll();
 
  echo json_encode($comments);
}
else echo json_encode("INVALID");

 ?>