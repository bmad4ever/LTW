//adaptado de http://php.net/manual/en/function.preg-match.php 

<?php 
    $scheme_match = "((https?|ftp)\:\/\/)?"; // SCHEME 
    $userNpass_match= "([a-z0-9+!*(),;?&=\$_.-]+(\:[a-z0-9+!*(),;?&=\$_.-]+)?@)?"; // User and Pass 
    $hostNip_match= "([a-z0-9-.]*)\.([a-z]{2,3})"; // Host or IP 
    $port_match= "(\:[0-9]{2,5})?"; // Port 
    $path_match= "(\/([a-z0-9+\$_-]\.?)+)*\/?"; // Path 
    $getquery_match= "(\?[a-z+&\$_.-][a-z0-9;:@&%=+\/\$_.-]*)?"; // GET Query 
    $anchor_match= "(#[a-z_.-][a-z0-9+\$_.-]*)?"; // Anchor 
?> 

<?php 

function getNameOrPass(input)
{
       if(preg_match("/^$regex$/", input)) 
               return true; 
       return false;
}

function cleanUserTextTags(text)
{
	 return strip_tags($row['introduction'],$allowedtags);
}

?>
