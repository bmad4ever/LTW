<?php

function popular_events() {
	$dbh = new PDO('sqlite:database.db');
  $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
  $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//get popular events
  $stmt = $dbh->prepare("SELECT *,count(registers.event_id) as count from events 
   INNER JOIN users
   ON users.id=events.owner
   INNER JOIN eventTypes
   ON eventTypes.id=events.eventtype
   LEFT JOIN (
      Select images.event_id as image_event_id, images.extension,min(images.id) as image_id 
        from images 
        group by images.event_id
     )
	ON image_event_id = events.id_event 
	INNER JOIN registers
	ON registers.event_id=events.id_event
   GROUP BY id_event
	HAVING count>0
   ORDER BY count DESC
   LIMIT 5 OFFSET 0");
  $stmt->execute();
  $events = $stmt->fetchAll();
 
  
  
?>
	
	<div id="list_events">
		<table>
		<?php foreach($events as $row){?>
			<tr>
				<td><a href="event.php?id=<?=$row['id_event']?>"><?php echo "<img class='list_img_thumbs' src='images/thumbs_small/".md5($row['image_id']).".".$row['extension']."'>"?></a></td>
				<td>
					<p><a href="event.php?id=<?=$row['id_event']?>"><?=$row['title']?> @ <?=$row['event_date']?></a></p>
					<p><?=$row['name']?> by <?=$row['username']?></p>
				</td>
			</tr>
		<?php } ?>
		</table>
	</div>
<?
}
?>
