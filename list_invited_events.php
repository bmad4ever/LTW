<?php
function invited_events() {
	$dbh = new PDO('sqlite:database.db');
  $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
  $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $stmt = $dbh->prepare("SELECT * from events 
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
	INNER JOIN 
	(SELECT * FROM invitations WHERE invitations.user_id = ?) as invitations
	ON invitations.event_id=events.id_event
   GROUP BY id_event
	");
  $stmt->execute(array($_SESSION["login_user"]));
  $events = $stmt->fetchAll();
 
 return $events;
} 
?>