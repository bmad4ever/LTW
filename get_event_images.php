<?php   
include("header.php");
/*$fname = $_GET['firstname'];
      if($fname=='Jeff')
      {
          //header("Content-Type: application/json");
         echo $_GET['callback'] . '(' . "{'fullname' : 'Jeff Hansen'}" . ')';

      }*/
$db = new PDO('sqlite:database.db');
  $stmt = $db->prepare('SELECT * FROM images WHERE event_id = ?');
  $stmt->execute(array($_SESSION['display_event_id']));  
  $images = $stmt->fetchAll();

  $num_img = count($images); 

//send num of images followed by the images to use  
  $result = array();
  $result[] = $num_img;
 // $result[] = "images/thumbs_medium/";
  foreach ($images as $img)
    $result[] = md5($img['id']) . "." . $img['extension'];  
  
   echo $_GET['callback'] . '(' .json_encode($result) . ')';
?>
