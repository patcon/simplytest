jQuery(function($){
	$(document).ready(function(){
    var flag = $('#header-help-flag')
    flag.once(function(){
      flag.show();
      flag.mousedown(function(){
        $(this).fadeOut();
        $('#homepage-featured').slideUp(200, function(){
          $('#header-help').slideDown();
        });
      });
      $('#header-help').mousedown(function(){
        $('#header-help').slideUp(200, function(){
          $('#homepage-featured').slideDown();
          flag.fadeIn();
        });
      });
    })
	});
});
