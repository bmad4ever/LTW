<?php 
include('header.php');
include('print_events.php');
include('list_user_events.php');
include('list_registered_events.php');
?>

<html>
  <head>
    <title>Edit event</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="css/myStyle.css"> 
  </head>
  
  <body>
    <header>
	<?php login_header(); ?>
      <h1>My Events</h1>
    </header>
	
	<div class="left_float">
	<h2> Events Created </h2>
	<br>
	<?php print_events(user_events(),false,"");?>
	</div>
	
	<div class="right_float" >
	<h2> Participating In </h2>
	<br>
	<?php print_events(registered_events(),false,"");?>
	</div>
	
	</body>

</html>