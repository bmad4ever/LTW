<?php

  $link='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
  
  //Add facebook share button
  //Source: https://developers.facebook.com/docs/plugins/share-button
  function facebook_share($id) {
	global $link;

	?>
	<div id="fb-root"></div>
	<!-- Load Facebook SDK for JavaScript -->
    <script type="text/javascript">(function(d, s, id) {
      var js, fjs = d.getElementsByTagName(s)[0];
      if (d.getElementById(id)) return;
      js = d.createElement(s); js.id = id;
      js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.0";
      fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));</script>

    <!-- Your share button code -->
    <div class="fb-share-button" 
        data-href="<?=$link?>" 
        data-layout="button_count">
    </div>	
	
	<?
	
  }
  
  function twitter_share() {

	?>
	<a href="https://twitter.com/share" class="twitter-share-button"{count}>Tweet</a>
<script>!function(d,s,id){
	var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';
	if(!d.getElementById(id)){
		js=d.createElement(s);
		js.id=id;
		js.src=p+'://platform.twitter.com/widgets.js';
		fjs.parentNode.insertBefore(js,fjs);
		}
	}(document, 'script', 'twitter-wjs');</script>
	
	<?
	
  }

?>
