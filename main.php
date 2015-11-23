<?php 
include('header.php');

function display_register_form(){
	
	echo
    '<form id="logNreg" action="log_in.php" method="post" enctype="multipart/form-data">

        <br>username<br><input type="text" name="log_username"></text>
        <br>password<br><input type="password" name="log_password"></text>
		<br>confirm password<br><input type="password" name="log_password_conf"></text>
        <br><input class="form_button" type="submit" name="choice" value="REGISTER">
    </form>';

}

function main_display()
{
	$status = session_status();
	
	switch($status)
	{
		case PHP_SESSION_DISABLED:display_login_form(); break;
		case PHP_SESSION_ACTIVE:
			if(checkLogged()) ;
			else {session_destroy(); display_register_form();}
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

    <header> 
	<? login_header(); ?>
	<h1>Le EventBook</h1> 
	</header>
	
    <br>
		<? main_display(); ?>
    <br>
    <br>
    <a href="list_events.php?page=1">Check all public events</a>
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