<?php

include('header.php');

if(!checkLogged())
{
	header("Location: main.php?errorMsg=".urlencode("Illegal Access to Upload Event page!"));
	return '';
}

?>
<!DOCTYPE HTML>
<html>
  <head>
    <title>Insert event</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="css/myStyle.css"> 
  </head>
  <body>
    <header>
	<? login_header(); ?>
      <h1><a href="index.php">Insert event</a></h1>
    </header>
	
    <div id="create_event_form">
      
	  <form action="upload.php" method="post" enctype="multipart/form-data">
        <label>Title:
          <input type="text" name="title">
        </label>
		<label>Type:
			<select name="types">
				<?foreach( $event_type_names as $row){?>
					<option value="<?=$row['name']?>"><?=$row['name']?></option>
				<?}?>
			</select>
        </label>

		<label>Date:
          <input type="date" name="event_date">
        </label>
		
        <input type="file" name="image">
		
		<label>Description:<br>
          <textarea name="description"></textarea>
        </label>
		
		<label>Public:<br>
          		<input type="checkbox" name="public" value="ok" checked>
        </label>
		
		<input type="hidden" name="check" value="<?php echo md5(date("Y-m-d").$_SESSION['log_user']);?>" />
		<br> <input type="submit" value="Upload">
      </form>
	  
    </div>
	
  </body>
</html>
