<?php
include("header.php");
include("getInputSafe.php");

	//$valid_user = validate_user();

if (!validate_user()) {
	header("Location: main.php?errorMsg=".urlencode("Register Event User Not Logged In!"));
	
} else {
	
	/* Verifica se existe $_POST['event_id'] e $_POST['user_id'] e filtra como valor inteiro  */
	if(isset($_POST['user_id']) && isset($_POST['event_id'])) {
		$user_id=filter_input(INPUT_POST,'user_id',FILTER_SANITIZE_NUMBER_INT);
		$event_id=filter_input(INPUT_POST,'event_id',FILTER_SANITIZE_NUMBER_INT);
	}
	
	$dbh = new PDO('sqlite:database.db');
	$dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	//insert new event
	$stmt = $dbh->prepare("INSERT INTO registers (user_id,event_id) VALUES (?,?)");
	$stmt->execute(array($user_id,$event_id));
	header('Location: event.php?id='.$event_id.'');
}


?>