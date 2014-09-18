<?php



class WPRainbowOptions {
	private static $_instance = null;
	/**
	 * Setup which to WP options page the Rainbow options will be added.
	 */
	private $optionset = 'writing';
	
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
		add_action( 'admin_init' , array( &$this , 'register_settings' ) );
		add_action( "load-options-{$this->optionset}.php" , array( &$this , 'enqueue_style' ) );
	}
	
	/**
	 * Enqueue options CSS and JS
	 */
	function enqueue_style() {
		$is_script_debug = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG;

		wp_enqueue_style( 'wp-rainbow-options' , plugins_url( '/css/wp-rainbow-options.css' , dirname(__FILE__) ));
		WPRainbow::get_instance()->enqueue_assets();
		$options_js = $is_script_debug ? '/js/wp-rainbow-options.js' : '/js/wp-rainbow-options.min.js';

		wp_enqueue_script( 'wp-rainbow-options' , plugins_url( $options_js , dirname(__FILE__) ) );
		wp_localize_script('wp-rainbow-options' , 'wprainbow_options' , array(
			'theme_directory_url' => plugins_url( '/css/themes/' , dirname(__FILE__) )
		) );
	}
	
	
	/**
	 * Setup options page.
	 */
	function register_settings() {
		$settings_section = 'wprainbow_settings';
		register_setting( $this->optionset , 'wprainbow_theme' , array( &$this , 'sanitize_theme' ) );
		register_setting( $this->optionset , 'wprainbow_line_numbers' , 'absint' );
		register_setting( $this->optionset , 'wprainbow_languages' , array( &$this , 'sanitize_langs' ) );
		register_setting( $this->optionset , 'wprainbow_load_minified' , 'absint' );

		add_settings_section( $settings_section, __( 'Code Highlighting', 'wp-rainbow-hilite' ), array( $this, 'settings_description' ), $this->optionset );
		add_settings_field(
			'wprainbow_theme',
			__( 'Visual Theme', 'wp-rainbow-hilite' ),
			array( $this, 'select_theme' ),
			$this->optionset,
			$settings_section
		);
		add_settings_field(
			'wprainbow_line_numbers',
			__( 'Line Numbers', 'wp-rainbow-hilite' ),
			array( $this, 'line_numbers_checkbox' ),
			$this->optionset,
			$settings_section
		);
		add_settings_field(
			'wprainbow_languages',
			__( 'Enable Languages', 'wp-rainbow-hilite' ),
			array( $this, 'select_languages' ),
			$this->optionset,
			$settings_section
		);
		add_settings_field(
			'wprainbow_load_minified',
			__( 'Load Minified scripts', 'wp-rainbow-hilite' ),
			array( $this, 'load_minified_checkbox' ),
			$this->optionset,
			$settings_section
		);

	}

	/**
	 * Print some documentation for the optionset
	 */
	public function settings_description() {
		?>
		<div class="inside">
			<p><?php _e( 'Select a visual theme and setup available languages.', 'wp-rainbow-hilite' ); ?></p>
		</div>
		<?php
	}
	
	/**
	 * Output Theme selectbox
	 */
	public function select_theme(){
		$theme = get_option('wprainbow_theme');
		?><dic class="wprainbow-set-theme"><?php
		?><select name="wprainbow_theme" id="select-wp-rainbow-theme"><?php
			foreach ( wprainbow_get_available_themes() as $slug => $name ) {
				?><option value="<?php echo $slug ?>" <?php selected( $theme == $slug, true ,true) ?>><?php echo $name ?></option><?php
			}
		?></select><?php
		?><a class="button secondary" id="wprainbow-toggle-sample-code" href="#"><?php _e('Toggle Sample Code','wp-rainbow-hilite') ?></a><?php
?><pre class="sample" data-language="php">
/*
Example class doing stuff.
*/
class Foo {
	private $baz;
	public $quux;
	
	// this function is not useful
	function bar( $arg ) {
		$count = 0;
		if ( count($arg) ) {
			foreach( $arg as $i=>$item ) {
				$item .= '.suffix';
				$count += 1;
			}
		}
		return $count;
	}
}
</pre><?php
		?></div><?php
	}
	/**
	 * Output line numbers checkbox
	 */
	public function line_numbers_checkbox() {
		$enabled = get_option( 'wprainbow_line_numbers' );
		?><label for="wprainbow_line_numbers"><?php
			?><input type="checkbox" name="wprainbow_line_numbers" id="wprainbow_line_numbers" value="1" <?php checked($enabled,true,true) ?> /><?php
			_e( 'Show Line numbers.' , 'wp-rainbow-hilite' );
		?></label><?php
	}

	/**
	 * Output language select
	 */
	public function select_languages() {
		$langs = wprainbow_get_available_languages();
		$enabled = (array) get_option( 'wprainbow_languages' );
		?><p><?php
		foreach ( $langs as $slug => $label ) {
			$id = "wprainbow_languages-{$slug}";
			
			?><label class="wp-rainbow-language-item" for="<?php echo $id ?>"><?php
				?><input type="checkbox" name="wprainbow_languages[]" id="<?php echo $id ?>" value="<?php echo $slug ?>" <?php checked(in_array($slug,$enabled),true,true) ?> /><?php
				echo $label;
			?></label><?php
		}
		?></p><?php
		?><p class="description"><?php
			_e('This will customize the languages listbox in WordPress’ visual editor.','wp-rainbow-hilite');
		?></p><?php
	}
	
	/**
	 * Output the heckbox for load minified option
	 */
	public function load_minified_checkbox() {
		$enabled = get_option( 'wprainbow_load_minified' );
		?><label for="wprainbow_load_minified"><?php
			?><input type="checkbox" name="wprainbow_load_minified" id="wprainbow_load_minified" value="1" <?php checked($enabled,true,true) ?> /><?php
			_e( 'Check this if you want to load a minified Rainbow JS including all Languages.' , 'wp-rainbow-hilite' );
		?></label><?php
		?><p class="description"><?php
			_e('When disabled only enabled language modules above will be loaded. Enabling this option is a good thing when there is no other minification technique around.','wp-rainbow-hilite');
		?></p><?php
	}
	
	/**
	 * Check selected languages against available languages
	 *
	 * @return array sanitized list of languages
	 */
	function sanitize_langs( $langs ) {	
		$langs = array_map( 'trim' , $langs);
		$available_langs = wprainbow_get_available_languages();
		$sanitized_langs = array();
		foreach ( $langs as $i => $lang )
			if ( array_key_exists( $lang , $available_langs ) )
				$sanitized_langs[] = $lang;
		return $sanitized_langs;
	}
	/**
	 * Check selected theme against available themes
	 *
	 * @return array sanitized theme
	 */
	function sanitize_theme( $theme ) {
		// check existance
		$available_themes = wprainbow_get_available_themes();
		if ( array_key_exists( $theme , $available_themes ) )
			return $theme;
		return key($available_themes);
	}
}

WPRainbowOptions::get_instance();