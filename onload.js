$().ready(loadDocument);

function loadDocument() {
	
	$("form").submit(function() {
    $(this).submit(function() {
        return false;
    });
    $(this).find("input[type='submit']").attr('disabled', 'disabled').val('submiting'); 
	return true; 
});

}

function addLoadEvent(func) { 
 var oldonload = window.onload; 
 if (typeof window.onload != 'function') { 
   window.onload = func; 
 } else { 
   window.onload = function() { 
     if (oldonload) { 
       oldonload(); 
     } 
      func(); 
    } 
  } 
} 

 