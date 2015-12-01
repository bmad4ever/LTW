<?php
/*note: a login user should have the id stored in 'login_user' and the username in 'login_username'*/
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
<li> <a href="create_event.php">Create event</a> </li>
</ul>';
 echo '  <h2>'.$_SESSION['login_username'].'</h2>';
 echo
 '   <FORM METHOD="LINK" ACTION="log_out.php">
<INPUT class="form_button" TYPE="submit" VALUE="LOGOUT">
</FORM>';
}

function display_login_form(){
	echo
    '
	<form id="logNreg" action="log_in.php" method="post" enctype="multipart/form-data">
	<span>
    username:<input type="text" name="log_username">
	password:<input type="password" name="log_password">
        </span>
		<input type="hidden" name="prev_page_validation" value="siadoNMWFI193468bubw">
			<input class="form_button" type="submit" name="choice" value="LOGIN">
    </form>
	';
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
	
	echo '</nav><br>';
}

function validate_user(){
  	
	if(!(session_status()===PHP_SESSION_ACTIVE)) return false;
	if( !checkLogged() ) return false;
	
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

//echo get value if exists, or print var otherwise 
function echo_get($var)
{
	if(isset($_GET[$var])) echo $_GET[$var];
	else echo $var;
}
?>