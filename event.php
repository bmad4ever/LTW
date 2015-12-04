<?php
include("header.php");
include("getInputSafe.php");
include("social_media.php");

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
   WHERE events.id_event= ?");
  $stmt->execute(array($id));
  $event_info = $stmt->fetchAll();
  
  //get event comments
  $stmt = $dbh->prepare("SELECT * from comments
						INNER JOIN users ON users.id=comments.user_id
						WHERE comments.event_id= ? ORDER BY date_comment DESC");
  $stmt->execute(array($id));
  $comments = $stmt->fetchAll();
  
  //get event users
  $stmt = $dbh->prepare("SELECT * from registers
						INNER JOIN users ON users.id=registers.user_id
						WHERE registers.event_id= ? ORDER BY username ASC");
  $stmt->execute(array($id));
  $registredUsers = $stmt->fetchAll();
  
  $last_comment_id = end($comments)['id'];
   
 /*  	function htmlencode($str) {
    $str = HTMLPurifier_Encoder::cleanUTF8($str);
    $str = htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
    return $str;
}*/

	/*function checkRegister($userid) {
		$stmt = $dbh->prepare("SELECT COUNT(*) from registers
						WHERE event_id=? AND user_id=?");
		$stmt->execute(array($id,$userid));
		return $stmt->fetch();
	}*/
	
   
?>
<!DOCTYPE HTML>
<html>

  <head>
    <title><?=$event_info[0]['title']?></title>
	
	<meta property="og:url"           content="<?php echo 'http://'.$_SERVER['HTTP_HOST'].'/event.php?id='.$event_info[0]['id_event'];?>" />
    <meta property="og:type"          content="website" />
    <meta property="og:title"         content="EventBook - <?=$event_info[0]['title']?>" />
    <meta property="og:description"   content="<?=$event_info[0]['description']?>" />
	
    <meta charset="utf-8">
    <link rel="stylesheet" href="css/myStyle.css"> 
	
    <?php 
	meta_includes();
		$_SESSION['display_event_id']=$id;//echo ("var event_id=".json_encode($id).";"); 
		//echo ("var last_comment_id=0;");//.json_encode($last_comment_id).";");
	?>
	
	<?php if($valid_user) echo "<script type=\"text/javascript\" src=\"login_funcs.js\"></script>"; ?>
	<script type="text/javascript" src="event.js"></script>
	
  </head>
  <body>
  
  <!--input type="hidden" id="event_id" data-value=<!--php? echo $id; ?>-->
  
    <header>
		<?php login_header(); ?>
      <h1><?=$event_info[0]['title']?></h1>
    </header>
	
	
	
	<section id="pseudo_chat" >
				<?php 
	if($valid_user)
	  echo '<br><form action="sendimage.php" method="post" enctype="multipart/form-data">
		<input type="file" name="image">
		<input type="hidden" name="event_id" value="'.$_GET['id'].'")>
		<input type="submit" value="upload image">
      </form>';
	?>
	<h3 id="show_regs_or_coms_button" >Click me to see Registers</h3>
	</section>
	
	<div id="comments" > </div>
	
	<div id="registered_users">
		<!--p><strong>Registered Users</strong></p-->
		<?php foreach($registredUsers as $row) { ?>
		<?php echo $row['username']; ?><br>
			
		<?php } ?>
	</div>
	
	
  	<aside id="event">
		<section id="social_media">
			<?php facebook_share($event_info[0]['id_event']); twitter_share();?>
		</section>
		<section>
			<p>Creator: <?=$event_info[0]['username']?></p>
			<p>Date: <?=$event_info[0]['event_date']?></p>
			<p>Tipo: <?=$event_info[0]['name']?></p>
			<p>Description:<br> <?=$event_info[0]['description']?></p>
			
			<?php
			if($valid_user) {
				$stmt = $dbh->prepare("SELECT COUNT(*) as count from registers
						WHERE event_id=? AND user_id=?");
				$stmt->execute(array($id,$_SESSION['login_user']));
				$registered = $stmt->fetch();

				if($registered['count']==0) {
					echo '<form method="post" action="register.php">
						<input type="hidden" name="event_id" value="'.$event_info[0]['id_event'].'">
						<input type="hidden" name="user_id" value="'.$_SESSION['login_user'].'">
						<input type="submit" value="Going">
						</form>';
				}
				else {
					echo '<form method="post" action="register_delete.php">
						<input type="hidden" name="event_id" value="'.$event_info[0]['id_event'].'">
						<input type="hidden" name="user_id" value="'.$_SESSION['login_user'].'">
						<input type="submit" value="Not going" >
						</form>';
				}
			}
			else {
				echo '<b>Create an account (or login) to register yourself!</b>';
			}
			
			
			
			if($valid_user && $_SESSION['login_user']==$event_info[0]['owner']) {
				echo '<br>
						<a href="edit_event.php?id='.$event_info[0]['id_event'].'" class="button">Edit</a>
						<a href="delete_event.php?id='.$event_info[0]['id_event'].'" class="button">Delete</a>
						<a href="event_invite.php?id='.$event_info[0]['id_event'].'" class="button">Invite</a>
						';
			}
		?>
			
			</section>
		
		
		
	</aside>
	
	
	<footer id="image_slider"></footer>
 
  </body>

</html>
