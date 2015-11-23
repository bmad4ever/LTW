<?php
include("header.php");
	$id = $_GET['id'];
  
  //get event
  $stmt = $dbh->prepare("SELECT * from events 
   INNER JOIN users
   ON users.id=events.owner
   INNER JOIN eventTypes
   ON eventTypes.id=events.eventtype
   WHERE events.id= ?");
  $stmt->execute(array($id));
  $event_info = $stmt->fetchAll();
  
  //get event comments
  $stmt = $dbh->prepare("SELECT * from comments
						INNER JOIN users ON users.id=comments.user_id
						WHERE comments.event_id= ?");
  $stmt->execute(array($id));
  $comments = $stmt->fetchAll();
  
  
?>
<!DOCTYPE HTML>
<html>

  <head>
    <title>Event</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="css/myStyle.css"> 
  </head>
  <body>
  
  <?foreach($event_info as $row){?>
  
    <header>
      <h1><?=$row['title']?></title></h1>
    </header>
	
	<div id="list_event">
		
			<p>Creator: <?=$row['username']?></p>
			<p>Date: <?=$row['event_date']?></p>
			<p>Tipo: <?=$row['name']?></p>
			<p>Description: <?=$row['description']?></p>
			
			<h3>Comments</h3>
				<?foreach($comments as $row1){?>
					User: <?=$row1['username']?>
					<?=$row1['comment_text']?>
				<?}?>
	</div>
	
	 <?}?>
	
  </body>

</html>
