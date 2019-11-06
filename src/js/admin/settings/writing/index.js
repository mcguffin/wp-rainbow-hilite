import $ from 'jquery';

$(document).on('change','#select-wp-rainbow-theme',e => {

	var css_url = wprainbow_options.theme_directory_url + $( e.target ).val() + ".css",
		$link = $(`#${wprainbow_options.theme_handle}-css`);

	if ( ! $link.length ) {
		$link = $(`<link id="${wprainbow_options.theme_handle}-css" rel="stylesheet" type="text/css" media="all">`).appendTo('head');
	}

	$link.attr('href',css_url);

});
