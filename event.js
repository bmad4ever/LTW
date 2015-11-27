//last_comment_id must be defined outside this script

$().ready(loadDocument);

//images related variables
//var displayed_image;
var images;
var image_len;
var image_path;
var image_2_show;
var img_slider_created;

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

images = data.slice(2,data.length);
 image_len=data[0];
 image_path=data[1];
 
 if (!img_slider_created)
{
	//debug$('#event').prepend(image_len  + "  ");
	if(image_len>0)
	//	$('#event').prepend('<img src="images/thumbs_medium/'
	// + images[0] + '"/>');
	$('#event').prepend(link_image("images/thumbs_medium/","main_image",images[0]));
	// $('#event').after(link_image("slider_img",images[0]));
	create_image_slider();
}

}

function create_image_slider()
{
 var next = $('<input class="slider_button" value=">">');
 next.click(move_slider_plus1);
 $('#image_slider').prepend(next); 
 
 var sliderimg = $(link_image("images/thumbs_small/","slider_img",images[0]))
  sliderimg.click(update_highres_src);
 $('#image_slider').prepend(sliderimg);
 
 var highres = $(link_image("images/originals/","high_res_img",images[0]));
 $('#pseudo_chat').after(highres);
 
  var prev = $('<input class="slider_button" value="<">');
 prev.click(move_slider_minus1);
 $('#image_slider').prepend(prev); 
 
 img_slider_created=true;
}


function move_slider_plus1()
{ update_create_image_slider(1); }

function move_slider_minus1()
{ update_create_image_slider(-1); }

function update_create_image_slider(move)
{
	image_2_show=(image_2_show+move)%image_len;
	if(image_2_show<0) image_2_show = image_len-1;
	$("#slider_img").fadeOut(550);
	window.setTimeout(function(){ 
	document.getElementById("slider_img").src="images/thumbs_small/" + images[image_2_show];
	$("#slider_img").fadeIn(500);
	},500);
}

function update_highres_src()
{
	$("#high_res_img").fadeOut(500);
	window.setTimeout(function(){ 
	document.getElementById("high_res_img").src="images/originals/" + images[image_2_show];
	$("#high_res_img").fadeIn(500);
	},500);
}

function loadDocument() {
	//start variables
img_slider_created=false;
 image_2_show=0;

//general functionalities 
refresh();
window.setInterval(refresh, 5000);

//login functionalities
if(typeof(add_send_comment) == "function") add_send_comment();

}

//update comments and images
function refresh() {
	//update images
	load_images();
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
}

function display_new_comment(com){
var newcomment=$("<div class='comment'><h2>"+ com['username'] +" on "+ com['date_comment'] +"</h2><section>"+ com['comment_text'] +"</section></div>");
newcomment.fadeIn(500);
$('#comments').after(newcomment);

}

