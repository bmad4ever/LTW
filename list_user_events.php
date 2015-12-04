<?php
function user_events() {
	$dbh = new PDO('sqlite:database.db');
  $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
  $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  
    $stmt = $dbh->prepare("SELECT * FROM 
	(SELECT * FROM events
	WHERE owner = ?) as events
	INNER JOIN eventTypes
    ON eventTypes.id=events.eventtype
    LEFT JOIN (
      Select images.event_id as image_event_id, images.extension,min(images.id) as image_id 
        from images 
        group by images.event_id
     )
	ON image_event_id = events.id_event ");
  $stmt->execute(array($_SESSION["login_user"]));
  $user_created_events = $stmt->fetchAll();
  
  return $user_created_events;
}
  ?>
  
