(function($) {
	$(document).on('change','#select-wp-rainbow-theme',function(){
		var css_url = wprainbow_options.theme_directory_url + $(this).val() + ".css";
		$('#wp-rainbow-css-css').remove();
		$('<link id="wp-rainbow-css-css" rel="stylesheet" type="text/css" href="'+css_url+'" media="all">').appendTo('head');
	});


	$(document).on('click','#wprainbow-toggle-sample-code',function(e) {
		$(this).closest('.wprainbow-set-theme').toggleClass('sample-code');
		e.preventDefault();
		return false;
	});
})(jQuery);