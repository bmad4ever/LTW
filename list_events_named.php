<?php

	//get number of pages
	$stmt = $dbh->prepare("SELECT COUNT(*) as count from events");
	$stmt->execute();
	$pages = $stmt->fetch();
	$npages= $pages['count']/5;
	$npages = ceil($npages); //ceil rounds float up
	
  //get event
  $stmt = $dbh->prepare("SELECT * from 
  (SELECT * FROM events WHERE title LIKE ?%) as events 
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
   ORDER BY event_date
   LIMIT 5 OFFSET ?");
  $stmt->execute(array(5 * ($page - 1)));
  $events = $stmt->fetchAll();


?>