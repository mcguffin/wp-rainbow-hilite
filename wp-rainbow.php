<?php

/*
Plugin Name: WordPress Rainbow Hilite
Plugin URI: http://wordpress.org/plugins/wp-rainbow-hilite/
Description: Code Syntax coloring using <a href="http://craig.is/making/rainbows">rainbow</a>.
Author: Jörn Lund
Version: 1.0.4
Author URI: https://github.com/mcguffin
License: GPL2

Text Domain: wp-rainbow-hilite
Domain Path: /languages/
*/

/*  Copyright 2014  Jörn Lund  (email : joern AT podpirate DOT org)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


/**
 * Plugin class
 *
 * Don't instantinate. Use `WPRainbow::get_instance()`
 *
 */
class WPRainbow {
	
	private static $_instance = null;
	
	private $_script_queue = array();
	
	
	/**
	 * Getting a singleton.
	 *
	 * @return object single instance of WPRainbow
	 */
	public static function get_instance(){
		if ( is_null( self::$_instance ) )
			self::$_instance = new self();
		return self::$_instance;
	}
	
	/**
	 * Private constructor
	 */
	private function __construct() {
		add_action( 'plugins_loaded' , array( &$this , 'plugin_loaded' ) );
		add_action( 'init' , array( &$this , 'init' ) );
		add_filter( 'wp_kses_allowed_html' , array( &$this , 'allow_pre_tag' ) , 10 , 2 );

		add_action( 'wp_enqueue_scripts' , array( &$this , 'enqueue_assets' ) , 20 );
		
		add_option('wprainbow_load_minified' , true );
		add_option('wprainbow_line_numbers' , false );
		add_option('wprainbow_languages' , array( 'css' , 'html' , 'php' , 'javascript' , 'java' , 'python' , 'shell' ) );
		add_option('wprainbow_theme' , 'github' );
		
	}
	public function plugin_loaded() {
		load_plugin_textdomain( 'wp-rainbow-hilite' , false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}
	/**
	 * Adding pre tag and rainbow attributes to list of allowed tags
	 */
	function allow_pre_tag( $allowedposttags , $context = '' ) {
		if ( $context == 'post' ) {
			if ( ! isset( $allowedposttags['pre'] ) )
				$allowedposttags['pre'] = array('width' => true , 
					'class' => true, 
					'id' => true, 
					'style' => true, 
					'title' => true, 
					'role' => true,
				);
			$allowedposttags['pre']['data-language'] = true;
			$allowedposttags['pre']['data-line'] = true;
		}
		return $allowedposttags;
	}
	/**
	 * Init hook.
	 * 
	 *  - Register assets
	 */
	function init() {
		
		$is_script_debug = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG;
		
		$dependencies = array( 'rainbow-core' );
		$load_minified = get_option( 'wprainbow_load_minified' );
		if ( $load_minified && ! $is_script_debug ) {
			$rainbow_script_url = plugins_url( '/js/rainbow-custom.min.js' , __FILE__ );
			$line_number_script_url = plugins_url( '/js/rainbow.linenumbers.min.js' , __FILE__ );
		} else {
			// enqueue core
			// respect WP SCRIPT_DEBUG constant
			$rainbow_script_url = plugins_url( '/js/dev/rainbow.js' , __FILE__ );
			$line_number_script_url = plugins_url( '/js/rainbow.linenumbers.js' , __FILE__ );
		}
		
		wp_register_script( 'rainbow-core' , $rainbow_script_url , array() , false , $load_minified );
		wp_register_script( 'rainbow-linenumbers' , $line_number_script_url , array() , false , $load_minified );
		
		$this->_script_queue[] = 'rainbow-core';
		if ( ! get_option( 'wprainbow_load_minified' ) || $is_script_debug ) {
			// enqueue language modules
			$languages = (array) get_option('wprainbow_languages');
			
			array_unshift( $languages , 'generic' );
			
			foreach ( $languages as $lang ) {
				$script_url = apply_filters('wprainbow_language_module_url' , plugins_url( "/js/dev/language/{$lang}.js" , __FILE__ ) , $lang );
				$handle = "rainbow-lang-{$lang}";
				wp_register_script( $handle , $script_url , array( 'rainbow-core' ) , false , $load_minified );
				$this->_script_queue[] = $handle;
			}
		}
		
		if ( get_option( 'wprainbow_line_numbers' ) )
			$this->_script_queue[] = 'rainbow-linenumbers';
		
		
		// register style
		$theme = get_option( 'wprainbow_theme' );
		$theme_url = apply_filters('wprainbow_theme_url' , plugins_url( "/css/themes/{$theme}.css" , __FILE__ ) , $theme );
		wp_register_style( 'wp-rainbow-css' , $theme_url );

		$linenumberfix_url = plugins_url( "/css/wp-rainbow-linenumbers-fix.css" , __FILE__ );
		wp_register_style( 'wp-rainbow-linenumber-fix' , $linenumberfix_url );
	}
	
	/**
	 *  Enqueue Frontend Scripts
	 */
	function enqueue_assets() {	
		foreach ( $this->_script_queue as $handle )
			wp_enqueue_script( $handle );
		
		wp_enqueue_style( 'wp-rainbow-css' );
		if ( get_option( 'wprainbow_line_numbers' ) )
			wp_enqueue_style( 'wp-rainbow-linenumber-fix' );
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
	function get_available_languages() {
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
	 *  Get Available css themes
	 *
	 *	Function is not intended to be called directly. Use `wprainbow_get_available_themes()` instead.
	 *
	 *	@use private
	 *
	 *	@return array Assoc containing all avaliable themes with theme slugs as key and prettified theme slugs values. 
	 */
	function get_available_themes() {
		$theme_instance = new WP_Theme( 'themes' , plugin_dir_path(__FILE__) . 'css' );
		$css_files = $theme_instance->get_files('css');
		$themes = array();
		foreach ( $css_files as $filename => $path ) {
			$filename = (basename($filename,'.css'));
			$themes[ $filename ] = ucwords(str_replace('-',' ',$filename));
		}
		return apply_filters( 'wprainbow_available_themes' , $themes );
	}
}

/**
 *  Get Available languages
 *
 *	@return array Assoc containing all avaliable themes with theme slugs as key and prettified theme slugs values. 
 */
function wprainbow_get_available_languages(){
	return WPRainbow::get_instance()->get_available_languages();
}

/**
 *  Get Available css themes
 *
 *	@return array Assoc containing all avaliable themes with theme slugs as key and prettified theme slugs values. 
 */
function wprainbow_get_available_themes(){
	return WPRainbow::get_instance()->get_available_themes();
}

function wprainbow_plugins_loaded() {
	if ( current_user_can('manage_options') )
		require_once plugin_dir_path(__FILE__) . '/inc/class-wp-rainbow-options.php';
}

if ( is_admin() ) {
	require_once plugin_dir_path(__FILE__) . '/inc/class-wp-rainbow-editor.php';
	add_action('plugins_loaded','wprainbow_plugins_loaded');
}


WPRainbow::get_instance();


