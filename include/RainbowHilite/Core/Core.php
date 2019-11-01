<?php
/**
 *	@package RainbowHilite\Core
 *	@version 1.0.1
 *	2018-09-22
 */

namespace RainbowHilite\Core;

if ( ! defined('ABSPATH') ) {
	die('FU!');
}
use RainbowHilite\Asset;

class Core extends Plugin implements CoreInterface {

	/**
	 *	@inheritdoc
	 */
	protected function __construct() {

		add_action( 'init' , array( $this , 'init' ) );

		add_action( 'wp_enqueue_scripts' , array( $this , 'enqueue_assets' ) );

		$args = func_get_args();
		parent::__construct( ...$args );
	}

	/**
	 *	Load frontend styles and scripts
	 *
	 *	@action wp_enqueue_scripts
	 */
	public function enqueue_assets() {
		Asset\Asset::get( 'js/main.js' )->enqueue();
		Asset\Asset::get( 'css/main.css' )->enqueue();
		Asset\Asset::get( 'css/prism/themes/prism.css' )->enqueue();
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
	 *	@param string $template
	 */
	public function locate_template( $template ) {
		if ( $located = locate_template( 'pp/' . $template, false, false ) ) {
			return $located;
		}
		foreach ( $this->get_asset_roots() as $dir => $url ) {
			$located = $dir . '/templates/' . $template;
			if ( file_exists( $located ) ) {
				return $located;
			}
		}

		return false;
	}


	/**
	 *	Init hook.
	 *
	 *  @action init
	 */
	public function init() {
	}


}
