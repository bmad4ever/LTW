<?php
include('header.php');
include('getInputSafe.php');
include('bmp_converter.php');

 if(/*$_POST['check']!=md5(date("Y-m-d").$_SESSION['log_user']) ||*/ !checkLogged() || !validate_user())
	{
		session_destroy();
        header("Location: main.php?errorMsg=".urlencode("Illegal Upload Image try!"));
		return '';
	}
 //error msgs - - - - - - - - - - - - - - - - - - - - - - - - - - -- - - - - -
 $image_type_error = "No valid image was selected. File must be of type gif, jpeg, png or bmp.";

// Database connection
  $dbh = new PDO('sqlite:database.db');
  $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
  $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

 	//validate image
	$image_type = exif_imagetype ($_FILES['image']['tmp_name']);
	if($image_type==null) {
        //header("Location: ". str_replace( "?errorMsg=".urlencode($image_type_error),"",$_SERVER['HTTP_REFERER'])."?errorMsg=".urlencode($image_type_error)); 
		return '';
		}
		
	$file_extension;
    switch ($image_type)
    {
		case IMAGETYPE_GIF:$file_extension="gif"; break;
		case IMAGETYPE_JPEG: $file_extension="jpg";  break;
		case IMAGETYPE_PNG:  $file_extension="png"; break;
		case IMAGETYPE_BMP:  $file_extension="jpg"; break;//will be converted into jpeg
        default: ;// header("Location: ". str_replace( "?errorMsg=".urlencode($image_type_error),"",$_SERVER['HTTP_REFERER'])."?errorMsg=".urlencode($image_type_error)); return '';  break;
    }

  //insert new image
   sleep(1);//avoid upload spamming
  $stmt = $dbh->prepare("INSERT INTO images VALUES(NULL, ?,?,?)");
  $stmt->execute(array($file_extension,$_SESSION['login_user'],$_POST['event_id']));

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

    switch ($image_type)
    {
		case IMAGETYPE_GIF: imagegif($medium, $mediumFileName); break;
		case IMAGETYPE_JPEG: imagejpeg($medium, $mediumFileName); break;
		case IMAGETYPE_PNG: imagepng($medium, $mediumFileName); break;
		case IMAGETYPE_BMP: imagejpeg($medium, $mediumFileName); break;
		default; break;
	}
	
header('Location: ' . str_replace( "errorMsg","pEM",$_SERVER['HTTP_REFERER']));
?>