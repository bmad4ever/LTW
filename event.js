$().ready(loadDocument);

var images;
var image_len;
var image_path;
var image_2_show = 0;

function load_images()
{
/*	$.ajax({
                url: "load_event_image.php",
                type: "POST",
                data: {
                  
                }
                datatype: "json",
				imageLoaded
            });
*/	
//$('#event').prepend("load_event_image.php?id=" + $event_id);
$tg = "load_event_image.php";// + $event_id;
 //{data: "value"}
	//$.getJSON("load_event_image.php", { id : 5 }, imagesLoaded);
	//$.getJSON('load_event_image.php?callback=?','id=5',imagesLoaded);
	$.getJSON('get_event_images.php?callback=?','id='+$event_id,imagesLoaded);
}

function link_image(id,image)
{
	//'<a href="show_full_image.php?id='+
// images + '">
 return '<img id="' + id +'" src="'
 + image_path + image + '"/>';// </a>';  
}


function imagesLoaded(data) {
	image_len=1;
images = data.slice(2,data.length);
 image_len=data[0];
 image_path=data[1];
 
//debug$('#event').prepend(image_len  + "  ");
if(image_len>0)
//	$('#event').prepend('<img src="images/thumbs_medium/'
// + images[0] + '"/>');
 $('#event').prepend(link_image("main_image",images[0]));
}

function loadDocument() {

load_images();

$image_scr = "images/thumbs_medium/1.jpg";

$image = '<img src="';
$image += $image_scr;
$image += '"/>';

//$('#event').prepend($images[0]);
//$('#event').prepend($image_len);
}
