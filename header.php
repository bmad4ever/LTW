<?php
session_start();

  $dbh = new PDO('sqlite:database.db');
  $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
  $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $stmt = $dbh->prepare("SELECT * from eventTypes");
  $stmt->execute();
  $event_type_names = $stmt->fetchAll();

function checkLogged()
{
	 if (isset($_SESSION['login_user'])
		 &&  $_SESSION['login_user']!=null) return true;
	return false;
}

function display_logout_form(){
 echo
 '<FORM METHOD="LINK" ACTION="log_out.php">
<BR><INPUT class="form_button" TYPE="submit" VALUE="LOGOUT">
</FORM>';
}

function login_header()
{
	if(session_status()===PHP_SESSION_ACTIVE && checkLogged())
		display_logout_form();
	else echo
    '
	<form id="logNreg" action="log_in.php" method="post" enctype="multipart/form-data">
	<span>
    username:<input type="text" name="log_username"></text>
	password:<input type="password" name="log_password"></text>
        </span>
			<input class="form_button" type="submit" name="choice" value="LOGIN">
    </form>
	';
		if(isset($_GET['errorMsg'])) 
	{	
		echo '<span>';
		echo $_GET['errorMsg'];
		echo '</span>';
	}
}

function validate_user(){
  	
	$db = new PDO('sqlite:database.db');
	$stmt = $db->prepare('SELECT username FROM users WHERE id = ?');
	$stmt->execute(array($_SESSION['login_user'])); 
    $result=$stmt->fetchAll();
	
	if (count($result)!=1 || 
	$result[0]['username'] != $_SESSION['login_username']
	) return false;
	
	return true;
}

function validate_date($date)
{
	 if($date == date("Y-m-d",null))
		return false;
	return true;
}

?>