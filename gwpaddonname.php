<?php
/**
 * Plugin Name: GravityWP - Addon Name
 * Plugin URI: https://github.com/gravitywpcom/gravitywp-addon-name
 * Description: SHORT DESCRIPTION
 * Version: 1.0-beta.1
 * Requires PHP: 7.0
 * Author: GravityWP
 * Author URI: https://github.com/gravitywpcom/gravitywp-addon-name/graphs/contributors
 * License: GPL-3.0+
 * Text Domain: gravitywpaddonname
 * Domain Path: /languages
 *
 * ------------------------------------------------------------------------
 * Copyright 2021 GravityWP.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see http://www.gnu.org/licenses.
 *
 * @package    GravityWP_Addon_Name
 * @subpackage Classes/GWP_Addon_Name_Bootstrap
 */

defined( 'ABSPATH' ) || die();

// Defines the current version of the GravityWP Addon Name Add-On.
define( 'GWP_GWP_ADDON_NAME_FILE', __FILE__ );

// Defines the current version of the GravityWP Addon Name Add-On.
define( 'ADDON_NAME_VERSION', '1.0-beta.1' );

// Defines the minimum version of Gravity Forms required to run GravityWP Addon Name Add-On.
define( 'GWP_ADDON_NAME_MIN_GF_VERSION', '2.5' );

// Initialize the autoloader.
require_once 'includes/autoload.php';

// After Gravity Forms is loaded, load the Add-On.
add_action( 'gform_loaded', array( 'GWP_Addon_Name_Bootstrap', 'load_addon' ), 5 );

/**
 * Loads the GravityWP Addon Name Add-On.
 *
 * Includes the main class and registers it with GFAddOn.
 *
 * @since 1.0
 */
class GWP_Addon_Name_Bootstrap {

	/**
	 * Loads the required files.
	 *
	 * @since  1.0
	 */
	public static function load_addon() {

		// Autoloader for vendor libraries.
		require_once __DIR__ . '/lib/autoload.php';

		if ( ! method_exists( 'GFForms', 'include_addon_framework' ) ) {
			return;
		}

		// Registers the class name with GFAddOn.
		GFAddOn::register( GravityWP\GravityWP_Addon_Name\Addon_Name::class );

	}

}

/**
 * Returns an instance of the Addon_Name class
 *
 * @since  1.0
 *
 * @return GravityWP\GravityWP_Addon_Name\Addon_Name|bool An instance of the Addon_Name class
 */
function gwp_addon_name() {

	return class_exists( 'GravityWP\GravityWP_Addon_Name\Addon_Name' ) ? GravityWP\GravityWP_Addon_Name\Addon_Name::get_instance() : false;

}
