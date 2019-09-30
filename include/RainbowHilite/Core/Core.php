<?php

namespace RainbowHilite\Core;

class Core extends Singleton {

	/**
	 *	Private constructor
	 */
	protected function __construct() {
		add_action( 'plugins_loaded' , array( $this , 'plugins_loaded' ) );
		add_action( 'wp_enqueue_scripts' , array( $this , 'enqueue_assets' ) );

		register_activation_hook( RAINBOW_HILITE_FILE, array( __CLASS__ , 'activate' ) );
		register_deactivation_hook( RAINBOW_HILITE_FILE, array( __CLASS__ , 'deactivate' ) );
		register_uninstall_hook( RAINBOW_HILITE_FILE, array( __CLASS__ , 'uninstall' ) );

		add_filter( 'the_content', array( $this, 'fix_pre_markup' ) );

		parent::__construct();

	}

	/**
	 *	@filter the_content
	 */
	function fix_pre_markup( $the_content ) {

		if ( false === strpos( $the_content, '<pre' ) ) {
			return $the_content;
		}
		$the_content = preg_replace_callback( '/<pre( ([^>]*)data-language([^>]*))>(?!<code)(.*)<\/pre>/imsU', array( $this, '_fix_markup_cb' ), $the_content );

		return $the_content;
	}

	private function _fix_markup_cb($matches) {
		$attr = $matches[1];
		$content = $matches[4];
		$code_attr = '';

		if ( preg_match( '/data-line="([-\d]+)"/', $attr, $match_attr ) ) {
			$code_attr = 'data-line="'.$match_attr[1].'"';
			$attr = str_replace( $code_attr, '', $attr );
		}

		return '<pre '.$attr.'><code '.$code_attr.'>'.$content . '</code></pre>';

		return $matches[0];
	}


	/**
	 *	Load frontend styles and scripts
	 *
	 *	@action wp_enqueue_scripts
	 */
	public function enqueue_assets() {

		// scripts
		$deps = array( 'rainbow', 'rainbow-linenumbers' );

		wp_register_script( 'rainbow', $this->get_asset_url( 'js/rainbow/rainbow.js' ), array(), null, true );
		wp_register_script( 'rainbow-linenumbers', $this->get_asset_url( 'js/rainbow.linenumbers/rainbow.linenumbers.js' ), array('rainbow'), null, true );

		$languages = get_option( 'wprainbow_languages' );
		foreach ( $languages as $language ) {
			$script_slug = 'rainbow-lang-'.$language;
			wp_enqueue_script( $script_slug, $this->get_asset_url( 'js/rainbow/language/'.$language.'.js' ), $deps, null, true );
		}

		// styles
		$theme = get_option( 'wprainbow_theme' );

		wp_register_style( 'rainbow-theme', $this->get_asset_url( 'css/rainbow/themes/' . $theme . '.css' ) );
		wp_enqueue_style( 'wp-rainbow', $this->get_asset_url( 'css/frontend/wp-rainbow.css' ), array( 'rainbow-theme' ) );

	}


	/**
	 *	Load text domain
	 *
	 *  @action plugins_loaded
	 */
	public function plugins_loaded() {

		load_plugin_textdomain( 'wp-rainbow-hilite' , false, RAINBOW_HILITE_DIRECTORY. 'languages' );

	}


	/**
	 *  Get Available css themes
	 *
	 *	Function is not intended to be called directly. Use `wprainbow_get_available_themes()` instead.
	 *
	 *	@use private
	 *
	 *	@return array Assoc containing all avaliable themes with theme slugs as key and prettified theme slugs values.
	 */
	public function get_available_themes() {

		$css_files = glob( RAINBOW_HILITE_DIRECTORY . 'css/rainbow/themes/*.css' );

		$themes = array();

		foreach ( $css_files as $filename ) {
			$filename = (basename( $filename, '.css' ));
			$themes[ $filename ] = ucwords(str_replace('-',' ',$filename));
		}

		return apply_filters( 'wprainbow_available_themes' , $themes );
	}

	/**
	 *  Get Available languages
	 *
	 *	Function is not intended to be called directly. Use `wprainbow_get_available_languages()` instead.
	 *
	 *	@use public
	 *
	 *	@return array Assoc containing all avaliable languages with language slugs as key and localized language Names as values.
	 */
	public function get_available_languages( ) {
		$langs = array(
			'c'				=> __('C','wp-rainbow-hilite'),
			'coffeescript'	=> __('coffeescript','wp-rainbow-hilite'),
			'csharp'		=> __('C#','wp-rainbow-hilite'),
			'css'			=> __('CSS','wp-rainbow-hilite'),
			'd'				=> __('D','wp-rainbow-hilite'),
			'go'			=> __('Go','wp-rainbow-hilite'),
			'haskell'		=> __('Haskell','wp-rainbow-hilite'),
			'html'			=> __('HTML','wp-rainbow-hilite'),
			'java'			=> __('Java','wp-rainbow-hilite'),
			'javascript'	=> __('JavaScript','wp-rainbow-hilite'),
			'lua'			=> __('Lua','wp-rainbow-hilite'),
			'php'			=> __('PHP','wp-rainbow-hilite'),
			'python'		=> __('Python','wp-rainbow-hilite'),
			'r'				=> __('R','wp-rainbow-hilite'),
			'ruby'			=> __('Ruby','wp-rainbow-hilite'),
			'scheme'		=> __('Scheme','wp-rainbow-hilite'),
			'shell'			=> __('Shell script','wp-rainbow-hilite'),
			'smalltalk'		=> __('Smalltalk','wp-rainbow-hilite'),
		);
		return apply_filters( 'wprainbow_available_languages' , $langs );
	}

	/**
	 *	Get asset url for this plugin
	 *
	 *	@param	string	$asset	URL part relative to plugin class
	 *	@return wp_enqueue_editor
	 */
	public function get_asset_url( $asset ) {
		return plugins_url( $asset, RAINBOW_HILITE_FILE );
	}

	public function get_asset_path( $asset ) {
		return RAINBOW_HILITE_DIRECTORY . $asset;
	}

	/**
	 *	Fired on plugin activation
	 */
	public static function activate() {
	}

	/**
	 *	Fired on plugin deactivation
	 */
	public static function deactivate() {
	}

	/**
	 *	Fired on plugin deinstallation
	 */
	public static function uninstall() {
	}

}
