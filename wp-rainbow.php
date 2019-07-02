<?php

/*
Plugin Name: WordPress Rainbow Hilite
Plugin URI: http://wordpress.org/plugins/wp-rainbow-hilite/
Description: Code Syntax coloring using <a href="http://craig.is/making/rainbows">rainbow</a>.
Author: Jörn Lund
Version: 2.0.2
Author URI: https://github.com/mcguffin
License: GPL3

Text Domain: wp-rainbow-hilite
Domain Path: /languages/
*/

/*  Copyright 2017  Jörn Lund  (email : joern AT podpirate DOT org)

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



namespace RainbowHilite;

define( 'RAINBOW_HILITE_FILE', __FILE__ );
define( 'RAINBOW_HILITE_DIRECTORY', plugin_dir_path(__FILE__) );

require_once RAINBOW_HILITE_DIRECTORY . 'include/vendor/autoload.php';

Core\Core::instance();




if ( is_admin() || defined( 'DOING_AJAX' ) ) {

	Admin\Admin::instance();
	Settings\SettingsWriting::instance();

}
