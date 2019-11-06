<?php

namespace RainbowHilite\Core;

$languages = [];
Core::instance()->get_available_languages();
?>
<script type="text/html" id="tmpl-wprainbow-code-settings">

	<div class="wprainbow-select-language">
	<?php

	foreach ( $languages as $id => $language ) {
		?>
		<input id="wprainbow-select-language-<?php esc_attr_e( $id ) ?>" type="radio" name="wprainbow-select-language" value="<?php esc_attr_e( $id ); ?>" />
		<label for="wprainbow-select-language-<?php esc_attr_e( $id ) ?>">
			<strong class="title">
				<?php esc_html_e( $language['title'] ); ?>
			</strong>
			<?php if ( isset( $language['aliasTitles'] ) ) {
				?>
				<small class="alias-titles"><?php esc_html_e( implode( ', ', $language['aliasTitles']) ); ?></small>
				<?php
			} ?>
			<?php if ( isset( $language['alias'] ) ) {
				?>
				<span class="alias visually-hidden"><?php esc_html_e( implode( ' ', $language['alias']) ); ?></span>
				<?php
			} ?>
		</label>
		<?php
	}

	?>
	</div>
</script>
