<?php

namespace RainbowHilite\Admin;
use RainbowHilite\Core;


class Admin extends Core\Singleton {

	private $core;

	/**
	 *	Private constructor
	 */
	protected function __construct() {

		$this->core = Core\Core::instance();
		TinyMce\Rainbow\Rainbow::instance();

		add_action( 'admin_init', array( $this , 'admin_init' ) );
	}


	/**
	 * Admin init
	 */
	function admin_init() {
	}

	/**
	 * Enqueue options Assets
	 */
	function enqueue_assets() {
		wp_enqueue_style( 'rainbow_hilite-admin' , $this->core->get_asset_url( '/css/admin.css' ) );

		wp_enqueue_script( 'rainbow_hilite-admin' , $this->core->get_asset_url( 'js/admin.js' ) );
		wp_localize_script('rainbow_hilite-admin' , 'rainbow_hilite_admin' , array(
		) );
	}

}

