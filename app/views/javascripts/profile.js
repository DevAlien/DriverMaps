$(document).ready(function(){
	$('a[rel*=leanModal]').leanModal({ top : 200, closeButton: ".modal_close" });
	
	// FORM HIDDEN HEIGHT
	var formheight = $(window).height()-20-$('.fb-signup').height()-30;	
	
	$('#open-form-hidden').click(function() {
		$('#form-hidden').css("max-height", formheight + 'px');								  
	  	$('#form-hidden').slideDown('slow', function() {
			// Animation complete.
	  	});
	});
	$('#password').focus(function() {
	  $('#confirm-password').slideDown('slow', function() {
		// Animation complete.
	  });
	});
	
	// IF MAP EXIST
	if($('#map-canvas')) {
		// check viewport height
		var maxHeight = $(window).height();
		$('#map-canvas').height(maxHeight-150);
	}
	
	// Masonry
	var $container = $('#image-container');
	$container.imagesLoaded( function(){
	  $container.masonry({
		itemSelector : '.box',
		isAnimated: !Modernizr.csstransitions
	  });
	});

});