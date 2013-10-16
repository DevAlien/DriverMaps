$(document).ready(function(event){
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

});

/* LOGIN CHECK */
$(function() {
	$("#loginForm").submit(function(event) { // login-form is submitted

		if($('#username').val() !== "") {
    		var username = $('#username').val();
		} else {
			var username = null;
		}
		if($('#pwd').val() !== "") {
    		var password = $('#pwd').val();
		} else {
			var password = null;
		}
		//if (username && password) {
			var values = {};		
			$('.grabbit').each(function() { values[this.name]=this.value; }); 
			
			var arrValue = { login : values };

			$.ajax({
			type: "POST",
			url: "login",
			contentType: 'application/json',
			dataType: "json",
			// send username and password as parameters to the PHP script
			data: JSON.stringify(arrValue),
			// script call was *not* successful
			error: function(XMLHttpRequest, textStatus, errorThrown) { 
			  $('#codeDev').text("responseText: " + XMLHttpRequest.responseText 
				+ ", textStatus: " + textStatus 
				+ ", errorThrown: " + errorThrown);
			  $('.messages').addClass("error");
			}, // error 
			// script call was successful 
			// data contains the JSON values returned by the PHP script 
			success: function(data){
			  if (data.response == false) { // script returned error
				$('.messages p').text(data.message);
				$('.messages').addClass("error");
			  } // if
			  else { // login was successful
				// REFRESH PAGE
				alert("DONE");
			  }
			}
		  });
		//}
		//else {
		  //$('.messages p').text("enter username and password");
		  //$('.messages').addClass("error");
		//} // else
		$('.messages').fadeIn();
		event.preventDefault();
		return false;
  	});
 });