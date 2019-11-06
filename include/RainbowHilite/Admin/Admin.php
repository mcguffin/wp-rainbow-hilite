<?php
/**
 *	@package RainbowHilite\Admin
 *	@version 1.0.0
 *	2018-09-22
 */

namespace RainbowHilite\Admin;

if ( ! defined('ABSPATH') ) {
	die('FU!');
}

use RainbowHilite\Asset;
use RainbowHilite\Core;


class Admin extends Core\Singleton {

	private $core;

	/**
	 *	@inheritdoc
	 */
	protected function __construct() {

		$this->core = Core\Core::instance();
	//	TinyMce\Rainbow\Rainbow::instance();

		add_action( 'admin_print_scripts', array( $this , 'enqueue_assets' ) );
		add_action( 'print_media_templates', [ $this , 'print_media_templates' ] );
		add_action( 'wp_footer', [ $this , 'maybe_print_media_templates' ] );

	}


	/**
	 *	Enqueue options Assets
	 *	@action admin_print_scripts
	 */
	public function enqueue_assets() {
		Asset\Asset::get('css/admin/main.css')->enqueue();

		Asset\Asset::get('js/admin.js')
			->deps( array( 'jquery' ) )
			->localize( array(
				/* Script Localization */
			) )
			->enqueue();
	}

	/**
	 *	@action wp_footer
	 */
	public function maybe_print_media_templates() {
		if ( ! did_action( 'print_media_templates' ) ) {
			$this->print_media_templates();
		}
	}

	/**
	 *	@action print_media_templates
	 */
	public function print_media_templates() {
		$glob = trailingslashit( $this->core->get_plugin_dir() ) . 'include/template/media/*.php';

		foreach ( glob( $glob ) as $template ) {
			include $template;
		}
	}

}
