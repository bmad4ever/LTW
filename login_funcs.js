//last_comment_id must be defined outside this script

//note: even if the user has access to these functions
//the php validates the operation with SESSION data

function add_send_comment()
{
	$('#show_regs_or_coms_button').before("<br><section id=\"addcomment\">Add New Comment<br></section><br>");
	
	comment_textarea = $('<textarea id="new_comment"></textarea><br>');
	$('#addcomment').append(comment_textarea);
	
	var upload_button = $('<input type="submit" value="send comment"><br>');
	upload_button.click(send_comment);
	$('#addcomment').append(upload_button);
	
}

//need 2 be used on JavaScript do update sender immediately
function send_comment()
{
		var upload_successfull="";
		$.getJSON("sendcomment.php", { /*'event_id':event_id,'userid':userid,send via session*/
		'comment':document.getElementById("new_comment").value}, comment_sent);
		document.getElementById("new_comment").value="";
}

function comment_sent(data)
{
	if(data=="OK") //if comment was upload successfully update comments
	load_comments(5);
	else ;//invalid upload
	
}