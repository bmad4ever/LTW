<?php
include('header.php');
$result;//users with given username (and password, depending on the method called)

sleep(1);//avoid spam

// ----------------------- VALIDATE OPERATION

if ($_SERVER["REQUEST_METHOD"] != "POST") 	{
	 header("location: main.php?errorMsg=".urlencode("Illegal call to log_in.php"));
	 return '';	
	}
	
	if( !isset($_POST['log_username'])
	||!isset($_POST['log_password'])
	||$_POST['log_username']===null
	||$_POST['log_username']===""
	||$_POST['log_password']===null
	||$_POST['log_password']==="")
	{
	 header("location: main.php?errorMsg=".urlencode("Field is Empty!"));
	 return '';	
	}
	
// ----------------------- AUX FUNCS

function number_of_usersnamed()
{
	global $result;
	$db = new PDO('sqlite:database.db');
	$stmt = $db->prepare('SELECT id FROM users WHERE username = ?');
	$stmt->execute(array($_POST['log_username'])); 
    $result=$stmt->fetchAll();
	return count($result);
}
function number_of_usersnamed_with_pass()
{
	global $result;
	$db = new PDO('sqlite:database.db');
	$stmt = $db->prepare('SELECT id FROM users WHERE username = ? and password = ?');
	$stmt->execute(array($_POST['log_username'], md5($_POST['log_password']))); 
    $result=$stmt->fetchAll();
	return count($result);
}

// ----------------------- REGISTER CASE
	
 if($_POST['choice']=="REGISTER")
{
	if($_POST['log_password_conf']!=$_POST['log_password'])
	{
		header("location: main.php?errorMsg=".urlencode("\"password\" field is different than \"confirm password\""));
		return '';
	}
	
	if(number_of_usersnamed() === 0 )
	{
		$dbh = new PDO('sqlite:database.db');
		$dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
		$stmt = $dbh->prepare("INSERT INTO users VALUES(NULL, ?,?)");
		$stmt->execute(array($_POST['log_username'], md5($_POST['log_password'])));
		
		//header("location: main.php?regok="); will proceed to login
	}
	else {
		header("location: main.php?errorMsg=".urlencode("Username already in use"));
	return '';
	}
}
else if($_POST['choice']!="LOGIN") 
{
	echo "INVALID ACCESS";
	return '';
}

// ----------------------- LOGIN CASE

if($_POST['choice']=="LOGIN" && $_POST['prev_page_validation'] !== "siadoNMWFI193468bubw" ){
	header("location: main.php?errorMsg=".urlencode("Tried to Login from unknown source."));
return;
}

    // If result matched $myusername and $mypassword, table row must be 1 row
    if (number_of_usersnamed_with_pass() == 1) {
        //session_register("myusername");
		$aux = $result[0]['id'];
        $_SESSION['login_user'] = $aux;// $_POST['log_username'];
		$_SESSION['login_username'] = $_POST['log_username'];
		//print for debug purposes, can be removed later
		if(validate_user())	
			header('Location: ' . str_replace( "errorMsg","pEM",$_SERVER['HTTP_REFERER']));//header("location: main.php?logok=$aux");
    } else {
        header("location: main.php?errorMsg=".urlencode("Your username or password is Invalid"));
		//???session_destroy();???
    }


//should check $_SERVER['HTTP_REFERER']

//usefull funcs
//session_regenerate_id(true);
// remove all session variables
//session_unset(); 
// destroy the session 
//session_destroy(); 
?>