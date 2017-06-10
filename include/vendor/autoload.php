<?php

namespace RainbowHilite;

function __autoload( $class ) {
	if ( strpos( $class, 'RainbowHilite\\' ) === false ) {
		// not our plugin.
		return;
	}
	$ds = DIRECTORY_SEPARATOR;
	$file = RAINBOW_HILITE_DIRECTORY . 'include' . $ds . str_replace( '\\', $ds, $class ) . '.php';

	if ( file_exists( $file ) ) {
		require_once $file;
	} else {
		throw new \Exception( sprintf( 'Class `%s` could not be loaded. File `%s` not found.', $class, $file ) );
	}
}


spl_autoload_register( 'RainbowHilite\__autoload' );