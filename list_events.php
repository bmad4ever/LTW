<?php
	include("header.php");
	
	$page=$_GET['page'];

	//get number of pages
	$stmt = $dbh->prepare("SELECT COUNT(*) as count from events");
	$stmt->execute();
	$pages = $stmt->fetch();
	$npages= $pages['count']/5;
	$npages = ceil($npages); //ceil rounds float up
	
  //get event
  $stmt = $dbh->prepare("SELECT * from events 
   INNER JOIN users
   ON users.id=events.owner
   INNER JOIN eventTypes
   ON eventTypes.id=events.eventtype
   ORDER BY event_date
   LIMIT 5 OFFSET ?");
  $stmt->execute(array(5 * ($page - 1)));
  $events = $stmt->fetchAll();
  
?>
<!DOCTYPE HTML>
<html>

  <head>
    <title>Public events</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="css/myStyle.css"> 
  </head>
  <body>

  
    <header>
		<? login_header(); ?>
      <h1>Public events</title></h1>
    </header>
	
	<div id="list_events">
		<table>
		<?foreach($events as $row){?>
			<tr>
				<td>IMAGE</td>
				<td>
					<p><?=$row['title']?> @ <?=$row['event_date']?></p>
					<p><?=$row['name']?> by <?=$row['username']?></p>
				</td>
			</tr>
		<?}?>
		</table>
	</div>
	<div id="number_pages">
		<?php 
			for ($x = 1; $x <= $npages; $x++) {
				echo '<a href="list_events.php?page='.$x.'">'.$x.'</a> ';
			} 
		?>
	</div>
	
  </body>

</html>
