<?php 
include('header.php');

function display_login_form(){
	
	echo
    '<form id="logNreg" action="log_in.php" method="post" enctype="multipart/form-data">

        <br>username:<br><input type="text" maxlength="30" name="log_username"></text>
        <br>password:<br><input type="password" maxlength="30" name="log_password"></text>
        <br>
        <input class="form_button" type="submit" name="choice" value="LOGIN">
        <br><input class="form_button" type="submit" name="choice" value="REGISTER">
    </form>';
	if(isset($_GET['errorMsg'])) 
	{	
		echo '<span>';
		echo $_GET['errorMsg'];
		echo '</span>';
	}
}

function display_logout_form(){
 echo
 '<FORM METHOD="LINK" ACTION="log_out.php">
<BR><INPUT class="form_button" TYPE="submit" VALUE="LOGOUT">
</FORM>';
}
	
function main_display()
{
	$status = session_status();
	
	switch($status)
	{
		case PHP_SESSION_DISABLED:display_login_form(); break;
		case PHP_SESSION_ACTIVE:
			if(checkLogged()) display_logout_form();
			else {session_destroy(); display_login_form();}
			break;
		case PHP_SESSION_NONE:display_login_form();  break;
			default; break;
	}
}

?>

<!DOCTYPE html>

<html>

<header>
<title> PROJECT LTW 2015/2016 </title>
<meta charset = "utf-8">
<link rel="stylesheet" href="./css/myStyle.css">
</header>

<body>

    <header> Le EventBook </header>
    <br>
		<? main_display(); ?>
    <br>
    <br>
    <a href="path...events.html">Check all public events</a>
	<br>
</body>

    <footer>
        <marquee behavior="scroll" direction="left">
            Last Event: show last event here or something similar
        </marquee>

       	<br>Date  
	<? echo date('l jS \of F Y h:i:s A')?>
<!-- echo date('l jS \of F Y h:i:s A'); 
		<!--print_r(getdate()); -->
	<!--echo date('Y-m-d H:i:s'); -->

    </footer>

</html>