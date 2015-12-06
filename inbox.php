<?php 
include('header.php');
include('print_events.php');
include('list_user_events.php');
include('list_registered_events.php');
include('list_invited_events.php');
?>

<html>
  <head>
    <title>Invitations Inbox</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="css/myStyle.css"> 
	<?php meta_includes(); ?>
  </head>
  
  <body>
    <header>
	<?php login_header(); ?>
      <h1>Invitations</h1>
    </header>
	
	<div id="invitations">
	<h2> You got invited to </h2>
	<br>
	<?php print_events(invited_events(),false,"");?>
	</div>
	
	</body>

</html>