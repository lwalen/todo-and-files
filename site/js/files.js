
$(document).ready(function() {

	$('.files').on({
		mouseenter: function() {
			$(this).children('.download').css('display', 'inline');
		},
		mouseleave: function() {
			$(this).children('.download').css('display', 'none');
		}
	}, '.file');

});
