<?php



class WPRainbowEditor {
	private static $_instance = null;
	
	/**
	 * Getting a singleton.
	 *
	 * @return object single instance of WPRainbow
	 */
	public static function get_instance() {
		if ( is_null( self::$_instance ) )
			self::$_instance = new self();
		return self::$_instance;
	}

	/**
	 * Private constructor
	 */
	private function __construct() {
		// extending mce
		add_filter( 'mce_buttons_2' , array(&$this,'mce_add_button'));
		add_filter( 'mce_external_plugins' , array(&$this,"mce_add_code_plugin") );
		
// 		foreach ( array('post.php','post-new.php') as $hook ) {
// 			add_action( "admin_head-$hook", array(&$this,'mce_localize') );
// 			add_action( "load-$hook", array(&$this,'enqueue_editor_css') );
// 		}
		add_action( "admin_init", array(&$this,'enqueue_editor_css') );
		add_action( 'wp_enqueue_editor' , array( &$this , 'mce_localize' ) );
		add_filter('mce_css' , array( &$this , 'mce_add_css' ) );
	}
	function enqueue_editor_css(){
		wp_enqueue_style( 'editor-css' , plugins_url( "/css/wp-rainbow-editor.css" , dirname(__FILE__) ) );
	}
	// extending mce
	function mce_add_button( $buttons ) {
		if ( get_option('wprainbow_line_numbers') )
			array_splice($buttons,1,0,"wprainbow_codecontrol");
		array_splice($buttons,1,0,"wprainbow");
		return $buttons;
	}
	function mce_add_code_plugin( $plugins_array ) {
		$is_script_debug = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG;
		$mce_js = $is_script_debug ? '/js/wp-rainbow-mce.js' : '/js/wp-rainbow-mce.min.js';
		$plugins_array['wprainbow'] = plugins_url($mce_js,dirname(__file__));
		return $plugins_array;
	}
	/**
	 *	@action 'wp_enqueue_editor'
	 */
	function mce_localize( $to_load ) {
		if ( $to_load['tinymce'] ) {
			$enabled_langs = (array) get_option('wprainbow_languages');
			$langs = array(
				(object) array(
					'text' => __('- None -'),
					'value' => '',
				),
			);
			foreach ( wprainbow_get_available_languages() as $name => $label ) {
				if ( in_array($name,$enabled_langs) )
				$langs[] = (object) array(
					'text' => $label,
					'value' => $name,
				);
			}
?>
<!-- TinyMCE Shortcode Plugin -->
<script type='text/javascript'>
var wprainbow = {
    'l10n': {
    	'code_language': "<?php _e('Code Language' , 'wp-rainbow-hilite' ) ?>",
    	'line_numbers': "<?php _e('Line Numbers' , 'wp-rainbow-hilite' ) ?>",
    	'code_properties': "<?php _e('Code Properties' , 'wp-rainbow-hilite' ) ?>",
    	'starting_line': "<?php _e('Starting Line' , 'wp-rainbow-hilite' ) ?>"
    },
    'languages' : <?php echo json_encode( $langs ) ?>,
    'enable_line_numbering' : <?php echo get_option('wprainbow_line_numbers') ? 'true' : 'false' ?>
};
</script>
<!-- TinyMCE Shortcode Plugin -->
<?php
		}
	}
	function mce_add_css( $styles ) {
		$styles .= ','. plugins_url( '/css/wp-rainbow-mce.css', dirname(__FILE__) ).'?'.time();
		return $styles;
	}
	
	// END mce
	
}


WPRainbowEditor::get_instance();