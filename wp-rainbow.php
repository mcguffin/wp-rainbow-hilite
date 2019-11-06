<?php

/*
Plugin Name: WordPress Rainbow Hilite
Plugin URI: http://wordpress.org/plugins/wp-rainbow-hilite/
Description: WordPress Code Syntax coloring plugin.
Author: Jörn Lund
Version: 2.0.4
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

require_once __DIR__ . DIRECTORY_SEPARATOR . 'include/autoload.php';

Core\Core::instance( __FILE__ );




if ( is_admin() || defined( 'DOING_AJAX' ) ) {

	Admin\Admin::instance();
	Admin\Settings\Writing::instance();
	Admin\BlockEditor::instance();
}
