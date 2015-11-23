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
    <title><?=$event_info['title']?></title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="css/myStyle.css"> 
  </head>
  <body>

  
    <header>
      <h1><?=$event_info['title']?></title></h1>
    </header>
	
	<div id="list_event">
		
			<p>Creator: <?=$event_info['username']?></p>
			<p>Date: <?=$event_info['event_date']?></p>
			<p>Tipo: <?=$event_info['name']?></p>
			<p>Description: <?=$event_info['description']?></p>
			
			<h3>Comments</h3>
				<?foreach($comments as $row){?>
					User: <?=$row['username']?>
					<?=row['comment_text']?>
				<?}?>
	</div>
	
  </body>

</html>
