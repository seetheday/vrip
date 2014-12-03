<?php
/**
 * The WordPress Plugin Boilerplate.
 *
 * A foundation off of which to build well-documented WordPress plugins that
 * also follow WordPress Coding Standards and PHP best practices.
 *
 * @package   Visual_Recipe_Index_Pro
 * @author    Simon Austin <simon@kremental.com>
 * @license   GPL-2.0+
 * @link      http://kremental.com
 * @copyright 2014 Kremental
 *
 * @wordpress-plugin
 * Plugin Name:       Visual Recipe Index Pro
 * Plugin URI:        http://kremental.com/visual-recipe-index
 * Description:       More powerful and easier to use than the free version, Visual Recipe Index Pro allows you quickly and easily create beautiful visual recipe indexes.
 * Version:           1.0.0
 * Author:            Kremental
 * Author URI:        http://kremental.com
 * Text Domain:       visual-recipe-index-pro-locale
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 * GitHub Plugin URI: https://github.com/seestheday/visual-recipe-index-pro
 * WordPress-Plugin-Boilerplate: v2.6.1
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/*----------------------------------------------------------------------------*
 * Public-Facing Functionality
 *----------------------------------------------------------------------------*/

require_once( plugin_dir_path( __FILE__ ) . 'public/class-visual-recipe-index-pro.php' );

/*
 * Register hooks that are fired when the plugin is activated or deactivated.
 * When the plugin is deleted, the uninstall.php file is loaded.
 *
 */
register_activation_hook( __FILE__, array( 'Visual_Recipe_Index_Pro', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'Visual_Recipe_Index_Pro', 'deactivate' ) );

add_action( 'plugins_loaded', array( 'Visual_Recipe_Index_Pro', 'get_instance' ) );
add_shortcode( 'vrip', array('Visual_Recipe_Index_Pro', 'vrip_shortcode_handler') );

/*----------------------------------------------------------------------------*
 * Dashboard and Administrative Functionality
 *----------------------------------------------------------------------------*/

/*
 *
 * If you want to include Ajax within the dashboard, change the following
 * conditional to:
 *
 * if ( is_admin() ) {
 *   ...
 * }
 *
 * The code below is intended to to give the lightest footprint possible.
 */
if ( is_admin() && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) ) {

	require_once( plugin_dir_path( __FILE__ ) . 'admin/class-visual-recipe-index-pro-admin.php' );
	add_action( 'plugins_loaded', array( 'Visual_Recipe_Index_Pro_Admin', 'get_instance' ) );

}
