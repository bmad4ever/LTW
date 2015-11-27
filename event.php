<?php
include("header.php");
include("getInputSafe.php");

	$valid_user = validate_user();
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
						WHERE comments.event_id= ? ORDER BY date_comment DESC");
  $stmt->execute(array($id));
  $comments = $stmt->fetchAll();
  
  $last_comment_id = end($comments)['id'];
   
 /*  	function htmlencode($str) {
    $str = HTMLPurifier_Encoder::cleanUTF8($str);
    $str = htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
    return $str;
}*/
   
?>
<!DOCTYPE HTML>
<html>

  <head>
    <title><?=$event_info[0]['title']?></title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="css/myStyle.css"> 
	
	<script type="text/javascript" src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
	
	<script type="text/javascript">
	//safe against XSS!!! id already checked with preg_match at header
    <?php 
		echo ("var event_id=".json_encode($id).";"); 
		echo ("var last_comment_id=0;");//.json_encode($last_comment_id).";");
		//variables only created if user is logged!
		if($valid_user){
		echo ("var userid=".json_encode($_SESSION['login_user']).";");
		echo ("var username=".json_encode($_SESSION['login_username']).";");	
		}
	?>
	</script>
	<? if($valid_user) echo "<script type=\"text/javascript\" src=\"login_funcs.js\"></script>"; ?>
	<script type="text/javascript" src="event.js"></script>
	
  </head>
  <body>
  
    <header>
		<? login_header(); ?>
      <h1><?=$event_info[0]['title']?></title></h1>
    </header>
	
	
	
	<section id="pseudo_chat" >
				<? 
	if($valid_user)
	  echo '<br><form action="sendimage.php" method="post" enctype="multipart/form-data">
		<input type="file" name="image">
		<input type="hidden" name="event_id" value="'.$_GET['id'].'"])>
		<input type="submit" value="upload image">
      </form>';
	?>
	<h3 id="comments_header" >Comments</h3>
	</section>
	
	<div id="comments" > </div>
	
	
  	<aside id="event">
		<section>
			<p>Creator: <?=$event_info[0]['username']?></p>
			<p>Date: <?=$event_info[0]['event_date']?></p>
			<p>Tipo: <?=$event_info[0]['name']?></p>
			<p>Description:<br> <?=$event_info[0]['description']?></p>
		</section>

		</section id="registered_users">
			<p><strong>Registered Users</strong></p>
		</section>
		
	</aside>
	
	<footer id="image_slider"></footer>


 
  </body>

</html>
