<?php

include('header.php');

if(!checkLogged())
{
	header("Location: main.php");//?errorMsg=".urlencode("Illegal Access to Upload Event page!"));
	return '';
}

$_SESSION['create_event_token'] = md5(uniqid(mt_rand(), true));

?>
<!DOCTYPE HTML>
<html>
  <head>
    <title>Create event</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="css/myStyle.css"> 
  </head>
  <body>
    <header>
	<?php login_header(); ?>
      <h1>Create event</h1>
    </header>
	
    <div id="create_event_form">
      
	  <form action="upload.php" method="post" enctype="multipart/form-data">
        <label>Title:
          <input type="text" name="title" value="" placeholder="<?php echo_get('title'); ?>" >
        </label>
		<label>Type:
			<select name="types">
				<?php foreach( $event_type_names as $row){?>
					<option value="<?=$row['id']?>"><?=$row['name']?></option>
				<?php } ?>
			</select>
        </label>

		<label>Date:
          <input type="date" name="event_date"  placeholder="<?php echo_get('date'); ?>">
        </label>
		
        <input type="file" name="image">
		
		<label>Description:<br>
          <textarea name="description"></textarea>
        </label>
		
		<label>Public:<br>
          		<input type="checkbox" name="public" value="ok" checked>
        </label>
		
		<input type="hidden" name="create_event_token" value="<?= $_SESSION['create_event_token'] ?>" />
		<br> <input type="submit" value="Upload">
      </form>
	  
    </div>
	
  </body>
</html>
