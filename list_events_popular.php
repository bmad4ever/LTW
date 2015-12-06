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
	WHERE events.publico=1
   GROUP BY id_event
	HAVING count>0
   ORDER BY count DESC
   LIMIT 5 OFFSET 0");
  $stmt->execute();
  $events = $stmt->fetchAll();
 
 return $events;
} 
?>