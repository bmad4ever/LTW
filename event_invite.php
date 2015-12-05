<?php

include('header.php');
include("getInputSafe.php");

if(!checkLogged())
{
	header("Location: main.php");//?errorMsg=".urlencode("Illegal Access to Upload Event page!"));
	return '';
}

	$valid_user = validate_user();
	$id = $_GET['id'];
	
  if(!validateInput($number_match,$id))
  {
	  header("Location: main.php?errorMsg=".urlencode("Illegal DATA in GET to Access an Event!"));
		return '';
  }
  
  //get userlist
  $stmt = $dbh->prepare("SELECT * from users");
  $stmt->execute(array());
  $users = $stmt->fetchAll();
  
  //get event
  $stmt = $dbh->prepare("SELECT * from events 
   INNER JOIN users
   ON users.id=events.owner
   INNER JOIN eventTypes
   ON eventTypes.id=events.eventtype
   WHERE events.id_event= ?");
  $stmt->execute(array($id));
  $event_info = $stmt->fetchAll();
  
  if($_SESSION['login_user']!=$event_info[0]['owner']) {
	  header("Location: main.php?");
		return '';
  }
  
  
  

?>
<!DOCTYPE HTML>
<html>
  <head>
    <title>Invite to <?=$event_info[0]['title']?></title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="css/myStyle.css"> 
  </head>
  <body>
    <header>
	<?php login_header(); ?>
      <h1>Invite to '<?=$event_info[0]['title']?>'</h1>
    </header>
	
    <div id="user_list">
      
	  <form action="send_invites.php" method="post" enctype="multipart/form-data">
		<?php foreach($users as $row){?>
			<input value="<?=$row['id']?>" name="invite[]" type="checkbox"/> <?=$row['username']?> <br>
		<?php } ?>
		
		<input type="hidden" name="id" value="<?=$event_info[0]['id_event']?>" />
		<br> <input type="submit" value="Upload">
      </form>
	  
    </div>
	
  </body>
</html>
