<?php

namespace RainbowHilite\Settings;
use RainbowHilite\Core;

class SettingsWriting extends Settings {

	private $optionset = 'writing';

	private $core;


	/**
	 *	Constructor
	 */
	protected function __construct() {

		$this->core = Core\Core::instance();

		add_option( 'wprainbow_prism_theme', 'prism' , '' , False );

		add_action( "load-options-{$this->optionset}.php" , array( $this, 'enqueue_assets' ) );

		parent::__construct();

	}


	/**
	 * Enqueue options CSS and JS
	 */
	function enqueue_assets() {

		$this->core->enqueue_assets();

		Asset\Asset::get( 'css/settings/writing.css' )->enqueue();

		Asset\Asset::get( 'js/admin/options.js' )
			->deps('jquery')
			->localize( array(
				'theme_directory_url' => $this->core->get_asset_url( '/css/prism/themes/' )
			), 'wprainbow_options' );

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
		$theme = get_option('wprainbow_theme');
		?>
		<div class="wprainbow-set-theme">

		<select name="wprainbow_theme" id="select-wp-rainbow-theme">
			<?php
			foreach ( $this->core->get_available_themes() as $slug => $name ) {
				?>
					<option value="<?php esc_attr_e( $slug ) ?>" <?php selected( $theme == $slug, true ,true) ?>><?php esc_html_e( $name ) ?></option>
				<?php
			}
			?>
		</select>

		<a class="button secondary" id="wprainbow-toggle-sample-code" href="#"><?php esc_html_e('Toggle Sample Code','wp-rainbow-hilite') ?></a>

<pre class="sample" data-language="php"><code>
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
	 * Output language select
	 */
	public function select_languages() {
		$langs = $this->core->get_available_languages();
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
	 * Check selected languages against available languages
	 *
	 * @return array sanitized list of languages
	 */
	function sanitize_langs( $langs ) {

		$langs = array_map( 'trim' , $langs);

		$available_langs = $this->core->get_available_languages();

		$sanitized_langs = array();

		foreach ( $langs as $i => $lang ) {
			if ( array_key_exists( $lang , $available_langs ) ) {
				$sanitized_langs[] = $lang;
			}
		}

		return $sanitized_langs;
	}


	/**
	 * Check selected theme against available themes
	 *
	 * @return array sanitized theme
	 */
	function sanitize_theme( $theme ) {
		// check existance
		$available_themes = $this->core->get_available_themes();
		if ( array_key_exists( $theme , $available_themes ) )
			return $theme;
		return key($available_themes);
	}




}
