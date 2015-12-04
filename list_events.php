<?php
	include("header.php");
	
	if (isset($_GET['page'])){
		//$page=$_GET['page'];
		$page=filter_input(INPUT_GET,'page',FILTER_SANITIZE_NUMBER_INT);
	} else {
		$page=1;
	}

	$search_event_name='';
	if (isset($_GET['search_event_by_name']))
	{
		$search_event_name=$_GET['search_event_by_name'];
	}
	$search_event_name = "%".$search_event_name."%";
	
	//get number of pages
	$stmt = $dbh->prepare("SELECT COUNT(*) as count from events WHERE title LIKE ?");
	$stmt->execute(array($search_event_name));
	$pages = $stmt->fetch();
	$npages= $pages['count']/5;
	$npages = ceil($npages); //ceil rounds float up
	
  //get event
  $stmt = $dbh->prepare("SELECT * from (SELECT * FROM events WHERE title LIKE ?) as events 
   INNER JOIN users
   ON users.id=events.owner
   INNER JOIN eventTypes
   ON eventTypes.id=events.eventtype
   LEFT JOIN (
      Select images.event_id as image_event_id, images.extension,min(images.id) as image_id 
        from images 
        group by images.event_id
     ) 
   ON image_event_id = events.id_event 
   ORDER BY event_date
   LIMIT 5 OFFSET ?");
  $stmt->execute(array($search_event_name,5 * ($page - 1)));
  $events = $stmt->fetchAll();
  
?>
<!DOCTYPE HTML>
<html>

  <head>
    <title>Public events</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="css/myStyle.css">
<?php meta_includes(); ?>	
  </head>
  <body>

  
    <header>
		<?php login_header(); ?>
      <h1>Public events</h1>
	  
    </header>
	
	<div>
	
	<table>
	<tr> <td>
	<form action="list_events.php" method="get">
	<span>
    Search for partial or full name of the Event Title<br>
	<input type="text" name="search_event_by_name">
        </span>
			<input class="form_button" type="submit" value="SEARCH">
    </form>
	</td> </tr>
	
	<tr> <td>
	<!--div id="number_pages"-->
	Pages: <?php 
			for ($x = 1; $x <= $npages; $x++) {
				echo '<a href="list_events.php?page='.$x."search_event_by_name=".$search_event_name.'">'.$x.'</a> ';
			} 
		?>
	<!--/div-->
	</td></tr>
	
	</table>
	</div>
	
	<br>
	
	<div id="list_events">
		<table>
		<?php foreach($events as $row){?>
			<tr>
				<td><a href="event.php?id=<?=$row['id_event']?>"><?php echo "<img class='list_img_thumbs' src='images/thumbs_small/".md5($row['image_id']).".".$row['extension']."'>"?></a></td>
				<td>
					<p><a href="event.php?id=<?=$row['id_event']?>"><?=$row['title']?> @ <?=$row['event_date']?></a></p>
					<p><?=$row['name']?> by <?=$row['username']?></p>
				</td>
			</tr>
		<?php } ?>
		</table>
	</div>
	
	
  </body>

</html>
