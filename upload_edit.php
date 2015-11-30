<?php

 include('header.php');
 include('getInputSafe.php');
 include('bmp_converter.php');
  
 //this var should save previous inputs incase the upload fails, 
 //so that the user doesnt have to input everything again
 //$get_for_failed;
 
 if(/*$_POST['check']!=md5(date("Y-m-d").$_SESSION['log_user']) ||*/ !checkLogged() || !validate_user())
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
  $stmt = $dbh->prepare("UPDATE events
						SET eventtype=?,event_date=?,title=?,description=?,publico=?
						WHERE id_event=?");
  $stmt->execute(array($_POST['types'],$event_date,$clean_title,$clean_description,$public,$_POST['id']));
  //$event_id = $dbh->lastInsertId();
  
  header("Location: event.php?id=".$_POST['id']);  
?>