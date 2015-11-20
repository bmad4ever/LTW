<?php
include("header.php");
  
?>
<!DOCTYPE HTML>
<html>
  <head>
    <title>Insert event</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="style.css"> 
  </head>
  <body>
    <header>
	<? if(isset($_GET['errorMsg'])) echo $_GET['errorMsg'] ?>
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
					<option value="<?=$row['id']?>"><?=$row['name']?></option>
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
		
		<br> <input type="submit" name="Upload" value="DoUpload">
      </form>
	  
    </div>
	
  </body>
</html>
