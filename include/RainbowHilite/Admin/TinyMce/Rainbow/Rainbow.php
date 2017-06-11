<?php

namespace RainbowHilite\Admin\TinyMce\Rainbow;

use RainbowHilite\Admin\TinyMce;
use RainbowHilite\Core;

class Rainbow extends TinyMce\TinyMce {
	
	protected $module_name = 'wprainbow';

	protected $editor_buttons = array(
		'mce_buttons_2' => array(
			'wprainbow_codecontrol'	=> 1,
			'wprainbow'				=> 1,
		),
	);
	
	protected $toolbar_css	= true;
	protected $editor_css	= true;
	
	protected function __construct() {

		$core = Core\Core::instance();

		$enabled_langs = (array) get_option('wprainbow_languages');

		$langs = array(
			array(
				'text' => __('- None -'),
				'value' => '',
			),
			array(
				'text' => __('Generic'),
				'value' => 'generic',
			),
		);
		foreach ( $core->get_available_languages() as $name => $label ) {
			if ( in_array($name,$enabled_langs) )
			$langs[] = array(
				'text' => $label,
				'value' => $name,
			);
		}

		$this->plugin_params = array(
			'l10n' => array(
				'code_language'		=> __( 'Code Language', 'wp-rainbow-hilite' ),
				'line_numbers'		=> __( 'Line Numbers', 'wp-rainbow-hilite' ),
				'code_properties'	=> __( 'Code Properties', 'wp-rainbow-hilite' ),
				'starting_line'		=> __( 'Starting Line', 'wp-rainbow-hilite' ),
			),
			'languages'	=> $langs,
		);

		parent::__construct();
	}
}

