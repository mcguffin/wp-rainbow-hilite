<?php
/**
 *	@package RainbowHilite\Admin\Settings
 *	@version 1.0.0
 *	2018-09-22
 */

namespace RainbowHilite\Admin\Settings;

if ( ! defined('ABSPATH') ) {
	die('FU!');
}

use RainbowHilite\Asset;
use RainbowHilite\Core;

class Writing extends Settings {

	private $optionset = 'writing';


	/**
	 *	@inheritdoc
	 */
	protected function __construct() {
		$this->core = Core\Core::instance();

		add_option( 'wprainbow_prism_theme', 'prism', '', false );

		add_action( "load-options-{$this->optionset}.php" , array( $this, 'enqueue_assets' ) );

		parent::__construct();

	}



	/**
	 * Enqueue settings Assets
	 *
	 *	@action load-options-{$this->optionset}.php
	 */
	public function enqueue_assets() {

		$this->core->enqueue_assets();

		Asset\Asset::get( 'css/settings/writing.css' )->enqueue();

		Asset\Asset::get( 'js/admin/settings/writing.js' )
			->deps('jquery')
			->localize( array(
				'theme_directory_url'	=> trailingslashit( $this->core->css->url_path ),
				'theme_handle'			=> $this->core->css->handle,
			), 'wprainbow_options' )
			->enqueue();

	}


	/**
	 *	Setup options.
	 *
	 *	@action admin_init
	 */
	public function register_settings() {

		$settings_section	= 'rainbow_hilite_settings';

		add_settings_section( $settings_section, __( 'Code Highlighting', 'wp-rainbow-hilite' ), null, $this->optionset );


		// more settings go here ...
		$option_name		= 'wprainbow_prism_theme';

		register_setting( $this->optionset, $option_name, array( $this, 'sanitize_theme' ) );

		add_settings_field(
			$option_name,
			__( 'Theme', 'wp-rainbow-hilite' ),
			array( $this, 'select_theme' ),
			$this->optionset,
			$settings_section
		);

	}

	/**
	 * Output Theme selectbox
	 */
	public function select_theme(){
		$theme = get_option('wprainbow_prism_theme');

		?>
		<div class="wprainbow-set-theme">

		<select name="wprainbow_prism_theme" id="select-wp-rainbow-theme">
			<?php
			foreach ( $this->core->get_available_themes() as $slug => $name ) {
				?>
					<option value="<?php esc_attr_e( $slug ) ?>" <?php selected( $theme == $slug, true ,true) ?>><?php esc_html_e( $name ) ?></option>
				<?php
			}
			?>
		</select>

<pre class="sample language-php line-numbers" data-start="123"><code>
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
</code></pre>
		</div><?php
	}


	/**
	 * Check selected theme against available themes
	 *
	 * @return array sanitized theme
	 */
	function sanitize_theme( $theme ) {
		// check existance
		$available_themes = $this->core->get_available_themes();
		if ( array_key_exists( $theme , $available_themes ) ) {
			return $theme;
		}
var_dump(array_key_exists( $theme , $available_themes ),$available_themes,$_POST);exit();

		return key( $available_themes );
	}



	/**
	 *	@inheritdoc
	 */
	public function activate() {
	}

	/**
	 *	@inheritdoc
	 */
	public function upgrade( $new_version, $old_version ) {
		if ( version_compare($old_version, '3.0.0', '<') ) {
			// change theme from rainbow > prism
			delete_option( 'wprainbow_theme' );
			delete_option( 'wprainbow_languages' );
		}
	}

	/**
	 *	@inheritdoc
	 */
	public function deactivate() {

	}

	/**
	 *	@inheritdoc
	 */
	public static function uninstall() {
		// TODO: Writing settings uninstall
		delete_option( 'wprainbow_prism_theme' );
	}

}
