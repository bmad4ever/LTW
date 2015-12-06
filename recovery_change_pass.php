<?php
include('header.php');
include('getInputSafe.php');

sleep(1);//avoid spam

$code = $_GET['code'];

function verifyCode() {
	global $code;
	$db = new PDO('sqlite:database.db');
	$stmt = $db->prepare('SELECT * FROM recovery WHERE code=?');
	$stmt->execute(array($code)); 
    $result=$stmt->fetchAll();
	
	if (count($result) == 0) {
		header("main.php");
	}
	else {
		return $result[0]['user_id'];
	}
}

$id=verifyCode();

if($id==0) {
	echo "<script type='text/javascript'>alert('Code is not valid. Returning to main page.');window.location.href = 'main.php';</script>";
}


?>

<!DOCTYPE HTML>
<html>

  <head>
    <title>Recover Password</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="css/myStyle.css">
<?php meta_includes(); ?>	
  </head>
  <body>

  
    <header>
		<?php login_header(); ?>
      <h1>Recover Password</h1>
	  
    </header>
	
	<div>
	
		<form id="recover" action="recovery_set_pass.php" method="post" enctype="multipart/form-data">
			Password<br><input type="password" name="log_password">
			<br>Confirm password<br><input type="password" name="log_password_conf">
			<input type="hidden" name="id" value="<?=$id?>" />
			<input type="hidden" name="code" value="<?=$code?>" />
			<br><input class="form_button" type="submit" value="Set password">
		</form>
	
	</div>
	
	
  </body>

</html>