<?php
	include("header.php");
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
	
		<form id="recover" action="recovery_send_code.php" method="post" enctype="multipart/form-data">
			Email address<br><input type="email" name="log_email">
			<br><input class="form_button" type="submit" value="Recover">
		</form>
	
	</div>
	
	
  </body>

</html>
