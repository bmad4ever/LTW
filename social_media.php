<?php

  var $link='http://'.$_SERVER['HTTP_HOST'].'event.php?id=';
  
  //Add facebook share button
  function facebook_share(%id) {
  $link=$link.%id;  

  echo  '<div id="fb-root"></div>
      <script>(function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1";
        fjs.parentNode.insertBefore(js, fjs);
      }(document, 'script', 'facebook-jssdk'));</script>

      
      <div class="fb-share-button" 
          data-href=".$link." 
        data-layout="button_count">
      </div>';
  }

?>
