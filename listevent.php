<?php
include("header.php");
	$id = $_GET['id'];
  
  $stmt = $dbh->prepare("SELECT * from events
						INNER JOIN users ON users.id=events.owner
						INNER JOIN eventTypes ON eventTypes.id=events.eventtype;
						WHERE id=".$id);
  $stmt->execute();
  $event_info = $stmt->fetchAll();
  
  $stmt = $dbh->prepare("SELECT * from comments
						INNER JOIN users ON users.id=comments.user_id
						WHERE event_id=".$id);
  $stmt->execute();
  $comments = $stmt->fetchAll();
  
  
?>
<!DOCTYPE HTML>
<html>
<?foreach($event_info as $row){?>
  <head>
    <title><?=$row['title']?></title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="css/myStyle.css"> 
  </head>
  <body>
    <header>
      <h1>Event Information</h1>
    </header>
	
	<div id="list_event">
		
			<h3>Title: <?=$row['title']?></h3>
			<p>Creator: <?=$row['username']?></p>
			<p>Date: <?=$row['event_date']?></p>
			<p>Tipo: <?=$row['name']?></p>
			<p>Description: <?=$row['description']?></p>
			
			<h3>Comments</h3>
				<?foreach($event_info as $row1){?>
					User: <?=$row1['username']?>
					<?=$row1['comment_text']?>
				<?}?>
	</div>
	
	
  </body>
 <?}?>
</html>
