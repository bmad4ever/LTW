<?php

//if (isset($_GET['event_id'])) {
  // Current time
  //$timestamp = time();
  
if (isset($_GET['event_id']) && isset($_GET['last_id']) )
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
						WHERE comments.event_id= ? AND comments.id > ? ORDER BY date_comment DESC LIMIT 10");
  $stmt->execute(array($_GET['event_id'],$_GET['last_id']));
  $comments = $stmt->fetchAll();
  
  //$messages = array_reverse($messages);

  // Add a time field to each message
  /*foreach ($messages as $index => $message) {
    $time = date('Y-m-d H:i:s', $message['date']);
    $messages[$index]['time'] = $time;
  }*/

  // JSON encode    
  echo json_encode($comments);
}
else echo json_encode("INVALID");

 ?>