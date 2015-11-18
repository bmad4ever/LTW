<?php
function number_of_usersnamed()
{
	$db = new PDO('sqlite:database.db');
	$stmt = $db->prepare('SELECT id FROM users WHERE username = ?');
	$stmt->execute(array($_POST['log_username'])); 
    $result=$stmt->fetchAll();
	return count($result);
}
function number_of_usersnamed_with_pass()
{
	$db = new PDO('sqlite:database.db');
	$stmt = $db->prepare('SELECT id FROM users WHERE username = ? and password = ?');
	$stmt->execute(array($_POST['log_username'], md5($_POST['log_password']))); 
    $result=$stmt->fetchAll();
	return count($result);
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

 if($_POST['choice']=="REGISTER")
{
	if(number_of_usersnamed() === 0 )
	{
		$dbh = new PDO('sqlite:database.db');
		$dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
		$stmt = $dbh->prepare("INSERT INTO users VALUES(NULL, ?,?)");
		$stmt->execute(array($_POST['log_username'], md5($_POST['log_password'])));
		
		$id = $dbh->lastInsertId();
		header("location: main.php?regok=");
	}
	else header("location: main.php?errorMsg=".urlencode("Username already in use"));
	 return '';
}
else if($_POST['choice']!="LOGIN") 
{
	echo "INVALID ACCESS";
	return '';
}
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // If result matched $myusername and $mypassword, table row must be 1 row
    if (number_of_usersnamed_with_pass() == 1) {
        //session_register("myusername");
        $_SESSION['login_user'] = $_POST['log_username'];
        header("location: xpto.php?logok=");
    } else {
        header("location: main.php?errorMsg=".urlencode("Your username or password is Invalid"));
		//???session_destroy();???
    }
}

//should check $_SERVER['HTTP_REFERER']

//usefull funcs
//session_regenerate_id(true);
// remove all session variables
//session_unset(); 
// destroy the session 
//session_destroy(); 
?>