<?php

 include('header.php');
 include('getInputSafe.php');
 include('bmp_converter.php');
  
 //this var should save previous inputs incase the upload fails, 
 //so that the user doesnt have to input everything again
 //$get_for_failed;
 
 if( $_POST['create_event_token'] !== $_SESSION['create_event_token'] || !checkLogged() || !validate_user())
	{
		session_destroy();
        header("Location: main.php?errorMsg=".urlencode("Illegal Upload Event try!"));
		return '';
	}

 //error msgs - - - - - - - - - - - - - - - - - - - - - - - - - - -- - - - - -
 $image_type_error = "No valid image was selected. File must be of type gif, jpeg, png or bmp.";
 $chars_error =  "Please do not use symbols or special characters.";
 $invalid_title_error = "Invalid Title.";
 $invalid_title_error.= $chars_error;
$invalid_description_error="Invalid description.";
$invalid_description_error.= $chars_error;
$invalid_date="Invalid Date.";
$impossible_event_date="The chosen date has already passed.";

 //verify if input is valid - - - - - - - - -  - - - - - - - - - - - - - - -
	//validate date	
$event_date = date("Y-m-d", strtotime($_POST['event_date']));		
$current_datetime = date("Y-m-d H:i:s");

	if(!validate_date($event_date))
	{
		header("Location: create_event.php?errorMsg=".urlencode($invalid_date));
		return '';
	}
 
	if(strtotime($event_date) - strtotime($current_datetime)<0)
	{
		header("Location: create_event.php?errorMsg=".urlencode($impossible_event_date));
		return '';
	}
 
	//validate image
	$image_type = exif_imagetype ($_FILES['image']['tmp_name']);
	if($image_type==null) {
        header("Location: create_event.php?errorMsg=".urlencode($image_type_error));
		return '';
		}
		
	$file_extension;
    switch ($image_type)
    {
		case IMAGETYPE_GIF:$file_extension="gif"; break;
		case IMAGETYPE_JPEG: $file_extension="jpg";  break;
		case IMAGETYPE_PNG:  $file_extension="png"; break;
		case IMAGETYPE_BMP: /*will be converted into jpeg*/ $file_extension="jpg"; break;

		//case IMAGETYPE_WBMP: $original = imagewbmp($file);  $file_extension="bmp"; break;
		/*case IMAGETYPE_XBM: return '' ; break;//$original = imagexbm($file); break;
		case IMAGETYPE_SWF:
		case IMAGETYPE_PSD:
		case IMAGETYPE_BMP: 
		case IMAGETYPE_TIFF_II:
		case IMAGETYPE_TIFF_MM: 
		case IMAGETYPE_JPC: 
		case IMAGETYPE_JP: 
		case IMAGETYPE_JPX:
		case IMAGETYPE_JB: 
		case IMAGETYPE_SWC:
		case IMAGETYPE_IFF: 
		case IMAGETYPE_ICO: */

        default:  header("Location: create_event.php?errorMsg=".urlencode($image_type_error)); return '';  break;
    }

   // if(validateInput($title_match,$_POST['title'])) 
	if(validateInput($title_match, $_POST['title'])===false) { header("Location: create_event.php?errorMsg=".urlencode($invalid_title_error)); return '';}
	//if(validateInput($text_match,$_POST['description'])==false) { header("Location: create_event.php?errorMsg=".urlencode($invalid_description_error)); return '';}

	//input seems valid
  sleep(1);//avoid upload spamming
  //create new data - - - - - - - - - - - - - - - - - - - - - - - - -
  $clean_title = cleanUserTextTags($_POST['title']);
  $clean_description = cleanUserTextTags($_POST['description']);
 
  $public=0;
  if(isset($_POST['public'])) $public=1;
 
  $dbh = new PDO('sqlite:database.db');
  $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
  $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  //insert new event
  $stmt = $dbh->prepare("INSERT INTO events VALUES(NULL, ?,?,?,?,?,?,?)");
  $stmt->execute(array($_SESSION['login_user'], $_POST['types'],$current_datetime,
  $event_date,$clean_title,$clean_description,
  $public )  );
   $event_id = $dbh->lastInsertId();
  
  //insert new image
  $stmt = $dbh->prepare("INSERT INTO images VALUES(NULL, ?,?,?)");
  $stmt->execute(array($file_extension,$_SESSION['login_user'],$event_id));

  //get new image id (is it ok?)
  $image_id = $dbh->lastInsertId();
  $image_id = md5("$image_id");
  
  //save image file and thumbnails
  $originalFileName = "images/originals/$image_id.$file_extension";
  $smallFileName = "images/thumbs_small/$image_id.$file_extension";
  $mediumFileName = "images/thumbs_medium/$image_id.$file_extension";
  
  move_uploaded_file($_FILES['image']['tmp_name'], $originalFileName);
  
  	    switch ($image_type)
    {
		case IMAGETYPE_GIF: $original = imagecreatefromgif($originalFileName); break;
		case IMAGETYPE_JPEG: $original = imagecreatefromjpeg($originalFileName); break;
		case IMAGETYPE_PNG: $original =  imagecreatefrompng($originalFileName); break;
		case IMAGETYPE_BMP: $original = imagecreatefrombmp($originalFileName); break;
			default; break;
	}
 // $original = imagecreatefromjpeg($originalFileName);
  
  $width = imagesx($original);
  $height = imagesy($original);
  $square = min($width, $height);

  // Create small square thumbnail
  $small = imagecreatetruecolor(200, 200); 
  imagecopyresized($small, $original, 0, 0, ($width>$square)?($width-$square)/2:0, ($height>$square)?($height-$square)/2:0, 200, 200, $square, $square);
  //imagejpeg($small, $smallFileName);
  
  switch ($image_type)
    {
		case IMAGETYPE_GIF: imagegif($small, $smallFileName); break;
		case IMAGETYPE_JPEG: imagejpeg($small, $smallFileName); break;
		case IMAGETYPE_PNG: imagepng($small, $smallFileName); break;
		case IMAGETYPE_BMP: imagejpeg($small, $smallFileName); break;
		default; break;
	}
  
  $mediumwidth = $width;
  $mediumheight = $height;

  if ($mediumwidth > 400) {
    $mediumwidth = 400;
    $mediumheight = $mediumheight * ( $mediumwidth / $width );
  }

  $medium = imagecreatetruecolor($mediumwidth, $mediumheight); 
  imagecopyresized($medium, $original, 0, 0, 0, 0, $mediumwidth, $mediumheight, $width, $height);
  //imagejpeg($medium, $mediumFileName);

    switch ($image_type)
    {
		case IMAGETYPE_GIF: imagegif($medium, $mediumFileName); break;
		case IMAGETYPE_JPEG: imagejpeg($medium, $mediumFileName); break;
		case IMAGETYPE_PNG: imagepng($medium, $mediumFileName); break;
		case IMAGETYPE_BMP: imagejpeg($medium, $mediumFileName); break;
		default; break;
	}
  //echo "'event.php?id=\"".urlencode($event_id)."\"'"; return '';
  echo "<script type='text/javascript'>alert('Event created!');window.location.href = 'event.php?id=".urlencode($event_id)."';</script>";
  //header("Location: create_event.php");  
?>