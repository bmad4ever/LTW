<?php
function print_events($events,$show_user,$title)
{
	echo '<div class="print_events">
		<table><th colspan="2">'.$title.'</th>';
		foreach($events as $row){
			echo '<tr>
				<td><a href="event.php?id="'.$row["id_event"].'"> <img class="list_img_thumbs" src="images/thumbs_small/'.md5($row["image_id"]).'.'.$row['extension'].'"> </a></td>
				<td>
					<p><a href="event.php?id='.$row['id_event'].'">'.$row['title'].' in '.$row['event_date'].'</a></p> | 
					<p>'.$row["name"];
			if ($show_user) echo ' by '.$row['username'];
			echo'</p> 
			</td>
			</tr>';
		}
		echo'</table>
	</div>';
}
?>