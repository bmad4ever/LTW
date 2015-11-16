<?php

  $dbh = new PDO('sqlite:database.db');
  $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
  $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  
  $stmt = $dbh->prepare("SELECT name from eventTypes");
  $stmt->execute();
  
  $images = $stmt->fetchAll();
  
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
    <nav>
      
	  <form action="upload.php" method="post" enctype="multipart/form-data">
        <label>Title:
          <input type="text" name="title">
        </label>
		<label>Type:
			<select name="types">
				<?foreach( $result as $row){?>
					<option value="<?=$row['name']?>"><?=$row['name']?></option>
				<?}?>
			</select>
        </label>
		<label>Description:
          <textarea name="description">
        </label>
		<label>Date:
          <input type="datetime" name="event_date">
        </label>
		
        <input type="file" name="image">
        <input type="submit" value="Upload">
      </form>
	  
    </nav>
  </body>
</html>
