//last_comment_id must be defined outside this script

function add_send_comment()
{
	$('#comments_header').aftter("<br><section id=\"addcomment\">Add New Comment<br></section>");
	
	comment_textarea = $('<textarea id="new_comment"></textarea><br>');
	$('#addcomment').append(comment_textarea);
	
	var upload_button = $('<input type="submit" value="send comment"><br>');
	upload_button.click(send_comment);
	$('#addcomment').append(upload_button);
	
}

//need 2 be used on JavaScript do update sender immediately
function send_comment()
{
	if(userid!='undefined' && username != 'undifined')
	{
		var upload_successfull="";
		$.getJSON("sendcomment.php", {'event_id':event_id,'userid':userid,
		'comment':document.getElementById("new_comment").value}, comment_sent);
	}
	else ; //invalid access

}

function comment_sent(data)
{
	if(data=="OK") //if comment was upload successfully update comments
			$.getJSON("retrievecomments.php", {'last_id': last_comment_id,'event_id':event_id}, function(comments){
		if(comments!=null && comments.length>0){
		last_comment_id=comments[0]['id'];
		//$('#comments').after(last_comment_id);
		comments.reverse();
		comments.forEach(display_new_comment);
		}
	}
	);
	else ;//invalid upload
	
}