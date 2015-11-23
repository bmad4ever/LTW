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
				<?foreach($comments as $row1){?>
					User: <?=$row1['username']?>
					<?=$row1['comment_text']?>
				<?}?>
	</div>
	

  </body>

</html>
