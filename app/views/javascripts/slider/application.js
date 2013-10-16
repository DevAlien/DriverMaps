$(document).ready(function() {
  $(document).on('init.slides', function() {
    $('.loading-container').fadeOut(function() {
      $(this).remove();
    });
  });

  $('#slides').superslides({
    play: 5000,
	slide_easing: 'easeInOutCubic',
    slide_speed: 800,
    pagination: false,
    hashchange: false,
    scrollable: true
  });
});