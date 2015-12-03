<?php
include('header.php');
include('getInputSafe.php');
$result;//users with given username (and password, depending on the method called)

sleep(1);//avoid spam

// ----------------------- VALIDATE OPERATION

if ($_SERVER["REQUEST_METHOD"] != "POST") 	{
	 header("location: main.php?errorMsg=".urlencode("Illegal call to log_in.php"));
	 return '';	
	}
	
	$postusername = htmlentities($_POST['log_username']);
	$postpass = htmlentities($_POST['log_password']);
	$postemail = htmlentities($_POST['log_email']);
	
	if( !isset($postusername)
	||!isset($postpass)
	||!isset($postemail)
	||$postusername===null
	||$postusername===""
	||$postpass===null
	||$postpass==="")
	{
	 header("location: main.php?errorMsg=".urlencode("Field is Empty!"));
	 return '';	
	}
	
// ----------------------- AUX FUNCS

function number_of_usersnamed()
{
	global $postusername;
	global $result;
	$db = new PDO('sqlite:database.db');
	$stmt = $db->prepare('SELECT id FROM users WHERE username = ?');
	$stmt->execute(array($postusername)); 
    $result=$stmt->fetchAll();
	return count($result);
}
function number_of_usersnamed_with_pass()
{
	global $postusername;
	global $postpass;
	global $result;
	$db = new PDO('sqlite:database.db');
	$stmt = $db->prepare('SELECT id FROM users WHERE username = ? and password = ?');
	$stmt->execute(array($postusername, md5($postpass))); 
    $result=$stmt->fetchAll();
	return count($result);
}

function number_of_users_with_email()
{
	global $postusername;
	global $postpass;
	global $postemail;
	global $result;
	$db = new PDO('sqlite:database.db');
	$stmt = $db->prepare('SELECT * FROM users WHERE email LIKE ?');
	$stmt->execute(array($postemail)); 
    $result=$stmt->fetchAll();
	return count($result);
}

// ----------------------- REGISTER CASE
	
 if($_POST['choice']=="REGISTER")
{
	if($_POST['log_password_conf']!=$postpass)
	{
		header("location: main.php?errorMsg=".urlencode("\"password\" field is different than \"confirm password\""));
		return '';
	}
	
	if((validateInput($mail_match,$postemail))===false)
	{
		header("location: main.php?errorMsg=".urlencode("\"email\" not accepted"));
		return '';
	}
	
	if(number_of_usersnamed() === 0)
	{
		if(number_of_users_with_email() === 0 ) {
			
			//generate activation code
			$length = 10;
			$code = "";
			$valid = "0123456789abcdefghijklmnopqrstuvwxyz";
			for ($i = 0; $i < $length; $i++) {
				$code.=$valid[mt_rand(0, strlen($valid))];
			}
		
			$dbh = new PDO('sqlite:database.db');
			$dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
			$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
			$stmt = $dbh->prepare("INSERT INTO users VALUES(NULL, ?,?,?,?,?)");
			$stmt->execute(array($postusername, md5($postpass),$postemail,0,$code));
			
			$msg = "Olá, ".$postusername.", sê bem-vindo ao EventBook!\n
			Para completares o teu registo, basta clicares no seguinte <a href='activation.php?code=".$code."'>endereço</a>.";
		
			mail($postemail,"Ativação de Conta",$msg,"From: EventBook <no-reply@eventbook.com");
		
			header("location: main.php");
			return '';
		}
		else {
			header("location: main.php?errorMsg=".urlencode("Email already in use"));
			return '';
		}
		
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
        $_SESSION['login_user'] = $aux;// $postusername;
		$_SESSION['login_username'] = $postusername;
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