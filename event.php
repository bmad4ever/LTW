<?php
include("header.php");
include("getInputSafe.php");

	$id = $_GET['id'];
	
  if(!validateInput($number_match,$id))
  {
	  header("Location: main.php?errorMsg=".urlencode("Illegal DATA in GET to Access an Event!"));
		return '';
  }
  
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
   
   	function htmlencode($str) {
    $str = HTMLPurifier_Encoder::cleanUTF8($str);
    $str = htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
    return $str;
}
   
?>
<!DOCTYPE HTML>
<html>

  <head>
    <title><?=$event_info[0]['title']?></title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="css/myStyle.css"> 
	
	<script type="text/javascript" src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
	
	<script type="text/javascript"> 
	//safe against XSS, id already checked with preg_match at header
	var event_id; 
	$event_id = <?php echo (json_encode($id)); ?>; 
	</script>
	
	<script type="text/javascript" src="event.js"></script>
	
  </head>
  <body>

    <header>
      <h1><?=$event_info[0]['title']?></title></h1>
    </header>
	
	<div id="event">
		
			<p>Creator: <?=$event_info[0]['username']?></p>
			<p>Date: <?=$event_info[0]['event_date']?></p>
			<p>Tipo: <?=$event_info[0]['name']?></p>
			<p>Description: <?=$event_info[0]['description']?></p>
			
			<h3>Comments</h3>
				<?foreach($comments as $row){?>
				<div id="comment">	User: <?=$row['username']?>
					<br>
					<?=$row['comment_text']?>
				</div>
				<?}?>
	</div>
 
  </body>

</html>
