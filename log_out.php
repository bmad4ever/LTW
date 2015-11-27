<?php 
session_start();
header('Location: ' . str_replace( "errorMsg","pEM",$_SERVER['HTTP_REFERER']));
session_destroy(); 
?>