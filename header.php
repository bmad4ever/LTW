<?php

/*note: a login user should have the id stored in 'login_user' and the username in 'login_username'*/
session_start();

/*adapted from http://stackoverflow.com/questions/520237/how-do-i-expire-a-php-session-after-30-minutes */
if (checkLogged() && isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 120)) {
    // last request was more than 2 minutes ago
    session_unset();     // unset $_SESSION variable for the run-time 
    session_destroy();   // destroy session data in storage
	header("Location: main.php");
	return '';
}
$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp


  $dbh = new PDO('sqlite:database.db');
  $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
  $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $stmt = $dbh->prepare("SELECT * from eventTypes");
  $stmt->execute();
  $event_type_names = $stmt->fetchAll();

function checkLogged()
{
	 if (isset($_SESSION['login_user'])
		 &&  $_SESSION['login_user']!=null
	 && isset($_SESSION['login_username'])
		 &&  $_SESSION['login_username']!=null
	 ) return true;
	return false;
}

function display_logged_form(){
echo '<ul id="loggedin_options"> 
<li> <a href="main.php">Home</a> </li>
<li> <a href="my_events.php">My Events</a> </li>
<li> <a href="inbox.php">Inbox</a> </li>
<li> <a href="create_event.php">Create event</a> </li>
</ul>';
 echo '  <h2>'.$_SESSION['login_username'].'</h2>';
 echo
 '   <FORM METHOD="LINK" ACTION="log_out.php">
<INPUT class="form_button" TYPE="submit" VALUE="LOGOUT">
</FORM>';
}

function display_login_form(){
	echo '<ul> 
<li> <a href="main.php">Home</a> </li>
	</ul>';
	
	$_SESSION['login_token'] = md5(uniqid(mt_rand(), true));
	echo
    '
	<form id="logNreg" action="log_in.php" method="post" enctype="multipart/form-data">
	<span>
    username:<input type="text" name="log_username">
	password:<input type="password" name="log_password">
        </span>
		<input type="hidden" name="login_token" value="'.$_SESSION['login_token'].'">
		<input type="hidden" name="choice" value="LOGIN">
			<input class="form_button" type="submit" value="LOGIN">
    </form>
	<input type="submit" class="form_button" id="registerbt" value="REGISTER">';
	display_register_form();
}

function display_register_form(){
	
	echo
    '<div id="register" title="Register form" style="display:none;"><form id="logNreg" action="log_in.php" method="post" enctype="multipart/form-data">

        <br>Username<br><input type="text" name="log_username">
        <br>Password<br><input type="password" name="log_password">
		<br>Confirm password<br><input type="password" name="log_password_conf">
		<br>Email address<br><input type="email" name="log_email">
		<input type="hidden" name="choice" value="REGISTER">
        <br><input class="form_button" type="submit" value="REGISTER">
    </form></div>';
	
	echo '<script type="text/javascript" src="register_form.js"></script>';

}

function login_header()
{
	
	
	echo '<nav>';
	
	if(session_status()===PHP_SESSION_ACTIVE && checkLogged())
		display_logged_form();
	else display_login_form();
	
		if(isset($_GET['errorMsg'])) 
	{	
		echo '<span>';
		echo $_GET['errorMsg'];
		echo '</span>';
	}
	
	echo '</nav><br>
		<noscript>
		This page needs JavaScript activated to fully work
		</noscript>
	<br>';
}

$login_validation_result_msg="";
function validate_user(){
	global $login_validation_result_msg;
	
	$login_validation_result_msg="MMMMM";
	
	if(!(session_status()===PHP_SESSION_ACTIVE)) return false;
	if( !checkLogged() )
	{
		$login_validation_result_msg="Username or Password is not correct.";
		return false;
	}
	
	$db = new PDO('sqlite:database.db');
	$stmt = $db->prepare('SELECT username,active FROM users WHERE id = ?');
	$stmt->execute(array($_SESSION['login_user'])); 
    $result=$stmt->fetchAll();
	
	if(	count($result)<1 || strtolower($result[0]['username']) != strtolower($_SESSION['login_username'])) 
	{
		$login_validation_result_msg="Invalid User.";
		return false;
	}
	
	if (count($result)!=1) 
	{
		$login_validation_result_msg="A problem occurred. Your account is blocked, please contact us.";
		return false;
	}
	
	if ($result[0]['active'] != 1)
	{    
		$login_validation_result_msg="This account has not been validated";
		return false;
	}
	
	return true;
}

function validate_date($date)
{
	 if($date == date("Y-m-d",null))
		return false;
	return true;
}

//echo get value if exists, or print var otherwise 
function echo_get($var)
{
	if(isset($_GET[$var])) echo $_GET[$var];
	else echo $var;
}

function meta_includes()
{

echo '<link rel="shortcut icon" href="css/icon.ico">';
echo '<script type="text/javascript" src="https://code.jquery.com/jquery-2.1.4.min.js"></script>';
echo '<script type="text/javascript" src="onload.js"></script>';
echo '<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/themes/start/jquery-ui.css">';
echo '<script type="text/javascript" src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>';


}

?>