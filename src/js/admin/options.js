(function($) {

	$(document).on('change','#select-wp-rainbow-theme',function(){
		var css_url = wprainbow_options.theme_directory_url + $(this).val() + ".css";
		if ( ! $('#wp-rainbow-css-css').length ) {
			$('<link id="wp-rainbow-css-css" rel="stylesheet" type="text/css" media="all">').appendTo('body');
		}
		$('#wp-rainbow-css-css').attr('href',css_url);

//		Rainbow.color();

	});


	$(document).on('click','#wprainbow-toggle-sample-code',function(e) {

		$(this).closest('.wprainbow-set-theme').find( '.sample' ).toggleClass( 'expanded' );

		e.preventDefault();

		return false;
	});
})(jQuery);
