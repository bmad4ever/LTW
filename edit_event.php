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
    <title>Edit event</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="css/myStyle.css"> 
  </head>
  <body>
    <header>
	<?php login_header(); ?>
      <h1>Edit event</h1>
    </header>
	
    <div id="edit_event_form">
      
	  <form action="upload_edit.php" method="post" enctype="multipart/form-data">
        <label>Title:
          <input type="text" name="title" value="<?=$event_info[0]['title']?>" placeholder="<?php echo_get('title'); ?>" >
        </label>
		<label>Type:
			<select name="types">
				<?php foreach( $event_type_names as $row){
					
						if ($row['id']==$event_info[0]['eventtype']) {
							echo '<option value="'.$row['id'].'" selected>'.$row['name'].'</option>';
						}
						else {
							echo '<option value="'.$row['id'].'">'.$row['name'].'</option>';
						}
					
				} ?>
			</select>
        </label>

		<label>Date:
          <input type="date" name="event_date" value="<?=$event_info[0]['event_date']?>"  placeholder="<?php echo_get('date'); ?>">
        </label>
		
		<label>Description:<br>
          <textarea name="description"><?=$event_info[0]['description']?></textarea>
        </label>
		
		<label>Public:<br>
          		<input type="checkbox" name="public" value="ok" checked>
        </label>
		
		<input type="hidden" name="check" value="<?php echo md5(date("Y-m-d").$_SESSION['login_user']);?>" />
		<input type="hidden" name="id" value="<?=$event_info[0]['id']?>" />
		<br> <input type="submit" value="Upload">
      </form>
	  
    </div>
	
  </body>
</html>
