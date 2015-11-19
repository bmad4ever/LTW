<?php
session_start();

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
}

?>