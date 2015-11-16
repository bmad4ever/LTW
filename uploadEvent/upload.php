<?php
 
 include('getInputSafe.php');
 
 //error msgs - - - - - - - - - - - - - - - - - - - - - - - - - - -- - - - - -
 $image_type_error = "No valid image was selected. File must be of type gif, jpeg, png or bmp.";
 $chars_error =  "Please do not use symbols or special characters.";
 $invalid_title_error = "Invalid Title.";
 $invalid_title_error.= $chars_error;
 
 //verify if input is valid - - - - - - - - -  - - - - - - - - - - - - - - -
   // list($width, $height, $image_type) = getimagesize($_FILES['image']);
	$image_type = exif_imagetype ($_FILES['image']['tmp_name']);
	
	//validate image
	if($image_type==null) {
        header("Location: index.php?errorMsg=".urlencode($image_type_error));
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

        default:  header("Location: index.php?errorMsg=".urlencode($image_type_error)); return '';  break;
    }
	
  $dbh = new PDO('sqlite:upload.db');
  $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
  $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if(!validateInput($_POST['title'],$title_match)) { header("Location: index.php?errorMsg=".urlencode($invalid_title_error)); return '';}
  
  //input seems valid
  //create new data - - - - - - - - - - - - - - - - - - - - - - - - -
  $clean_title = cleanUserTextTags($_POST['title']);
  
  $stmt = $dbh->prepare("INSERT INTO images VALUES(NULL, ?,?)");
  $stmt->execute(array(clean_title,$file_extension));

  $id = $dbh->lastInsertId();


	
  $originalFileName = "images/originals/$id.$file_extension";
  $smallFileName = "images/thumbs_small/$id.$file_extension";
  $mediumFileName = "images/thumbs_medium/$id.$file_extension";

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
  
  header("Location: index.php");  
?>


<?php
//from http://forums.codewalkers.com/php-coding-7/how-to-convert-bmp-to-jpg-879135.html
function ConvertBMP2GD($src, $dest = false) {
if(!($src_f = fopen($src, "rb"))) {
return false;
}
if(!($dest_f = fopen($dest, "wb"))) {
return false;
}
$header = unpack("vtype/Vsize/v2reserved/Voffset", fread($src_f,14));
$info = unpack("Vsize/Vwidth/Vheight/vplanes/vbits/Vcompression/Vimagesize/Vxres/Vyres/Vncolor/Vimportant",fread($src_f, 40));

extract($info);
extract($header);

if($type != 0x4D42) { // signature "BM"
return false;
}

$palette_size = $offset - 54;
$ncolor = $palette_size / 4;
$gd_header = "";
// true-color vs. palette
$gd_header .= ($palette_size == 0) ? "\xFF\xFE" : "\xFF\xFF";
$gd_header .= pack("n2", $width, $height);
$gd_header .= ($palette_size == 0) ? "\x01" : "\x00";
if($palette_size) {
$gd_header .= pack("n", $ncolor);
}
// no transparency
$gd_header .= "\xFF\xFF\xFF\xFF";

fwrite($dest_f, $gd_header);

if($palette_size) {
$palette = fread($src_f, $palette_size);
$gd_palette = "";
$j = 0;
while($j < $palette_size) {
$b = $palette{$j++};
$g = $palette{$j++};
$r = $palette{$j++};
$a = $palette{$j++};
$gd_palette .= "$r$g$b$a";
}
$gd_palette .= str_repeat("\x00\x00\x00\x00", 256 - $ncolor);
fwrite($dest_f, $gd_palette);
}

$scan_line_size = (($bits * $width) + 7) >> 3;
$scan_line_align = ($scan_line_size & 0x03) ? 4 - ($scan_line_size &
0x03) : 0;

for($i = 0, $l = $height - 1; $i < $height; $i++, $l--) {
// BMP stores scan lines starting from bottom
fseek($src_f, $offset + (($scan_line_size + $scan_line_align) *
$l));
$scan_line = fread($src_f, $scan_line_size);
if($bits == 24) {
$gd_scan_line = "";
$j = 0;
while($j < $scan_line_size) {
$b = $scan_line{$j++};
$g = $scan_line{$j++};
$r = $scan_line{$j++};
$gd_scan_line .= "\x00$r$g$b";
}
}
else if($bits == 8) {
$gd_scan_line = $scan_line;
}
else if($bits == 4) {
$gd_scan_line = "";
$j = 0;
while($j < $scan_line_size) {
$byte = ord($scan_line{$j++});
$p1 = chr($byte >> 4);
$p2 = chr($byte & 0x0F);
$gd_scan_line .= "$p1$p2";
} $gd_scan_line = substr($gd_scan_line, 0, $width);
}
else if($bits == 1) {
$gd_scan_line = "";
$j = 0;
while($j < $scan_line_size) {
$byte = ord($scan_line{$j++});
$p1 = chr((int) (($byte & 0x80) != 0));
$p2 = chr((int) (($byte & 0x40) != 0));
$p3 = chr((int) (($byte & 0x20) != 0));
$p4 = chr((int) (($byte & 0x10) != 0));
$p5 = chr((int) (($byte & 0x08) != 0));
$p6 = chr((int) (($byte & 0x04) != 0));
$p7 = chr((int) (($byte & 0x02) != 0));
$p8 = chr((int) (($byte & 0x01) != 0));
$gd_scan_line .= "$p1$p2$p3$p4$p5$p6$p7$p8";
} $gd_scan_line = substr($gd_scan_line, 0, $width);
}

fwrite($dest_f, $gd_scan_line);
}
fclose($src_f);
fclose($dest_f);
return true;
}

function imagecreatefrombmp($filename) {
$tmp_name = tempnam("/tmp", "GD");
if(ConvertBMP2GD($filename, $tmp_name)) {
$img = imagecreatefromgd($tmp_name);
unlink($tmp_name);
return $img;
} return false;
} 
?>