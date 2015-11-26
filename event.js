//last_comment_id must be defined outside this script

$().ready(loadDocument);

//images related variables
//var displayed_image;
var images;
var image_len;
var image_path;
var image_2_show;

//comments rlated variables
var last_comment_id;


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
tg = "load_event_image.php";// + $event_id;
 //{data: "value"}
	//$.getJSON("load_event_image.php", { id : 5 }, imagesLoaded);
	//$.getJSON('load_event_image.php?callback=?','id=5',imagesLoaded);
	$.getJSON('get_event_images.php?callback=?','id='+event_id,imagesLoaded);
}

function link_image(path,id,image)
{
	//'<a href="show_full_image.php?id='+
// images + '">
 return '<img id="' + id +'" src="'
 + path + image + '"/>';// </a>';  
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
 $('#event').prepend(link_image("images/thumbs_small/","main_image",images[0]));
// $('#event').after(link_image("slider_img",images[0]));
create_image_slider();
}

function create_image_slider()
{
 var next = $('<input id="next_image_button" value=">">');
 next.click(move_slider_plus1);
 $('#event').after(next); 
 
 var displayed_image = $(link_image(image_path,"slider_img",images[0]));
 //displayed_image.src("dfghj");
 $('#event').after(displayed_image);
 
  var prev = $('<input id="prev_image_button" value="<">');
 prev.click(move_slider_minus1);
 $('#event').after(prev); 
}


function move_slider_plus1()
{ update_create_image_slider(1); }

function move_slider_minus1()
{ update_create_image_slider(-1); }

function update_create_image_slider(move)
{
	image_2_show=(image_2_show+move)%image_len;
	if(image_2_show<0) image_2_show = image_len-1;
	$("#slider_image img").attr("src",image_path + images[image_2_show]);
}

function loadDocument() {

 image_2_show=0;

//general functionalities 
load_images();

window.setInterval(refresh, 5000);
//if(image_len>1)

//login functionalities
if(typeof(add_send_comment) == "function") add_send_comment();



}

//update comments and images
function refresh() {
	//update comments
	$.getJSON("retrievecomments.php", {'last_id': last_comment_id,'event_id':event_id}, function(comments){
		if(comments!=null && comments.length>0){
		last_comment_id=comments[0]['id'];
		//$('#comments').after(last_comment_id);
		comments.reverse();
		comments.forEach(display_new_comment);
		}
	}
	);
	//update images
}

function display_new_comment(com){
$('#comments').after("<div class='comment'>"+ com['username'] +" on "+ com['date_comment'] +"<br>"+ com['comment_text'] +"</div>");
}

