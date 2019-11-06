<?php
/**
 *	@package PPFontAwesome\Admin
 *	@version 1.0.0
 *	2018-09-22
 */

namespace RainbowHilite\Admin;

if ( ! defined('ABSPATH') ) {
	die('FU!');
}

use RainbowHilite\Asset;
use RainbowHilite\Core;

class BlockEditor extends Core\Singleton {

	/**
	 *	@inheritdoc
	 */
	protected function __construct() {
		add_action( 'enqueue_block_editor_assets', [ $this, 'enqueue_block_assets' ] );
	}



	/**
	 *	Enqueue Block Assets
	 *
	 *	@action enqueue_block_editor_assets
	 */
	public function enqueue_block_assets() {

		Asset\Asset::get('js/admin/block-editor.js')
			->deps( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor' )
			->enqueue();

	}

}
