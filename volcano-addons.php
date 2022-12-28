<?php
/**
 * Addon elements for Elementor Page Builder.
 *
 * Plugin Name: volcano Addons
 * Description: Addon elements for Elementor Page Builder & Gutenberg.
 * Plugin URI:
 * Version: 1.0.0
 * Author: Nazmul Hasan
 * Author URI:
 * Text Domain: volcano-addons
 * Domain Path: /languages/
 *
 * [PHP]
 * Requires PHP: 7.1
 *
 * [WP]
 * Requires at least: 5.2
 * Tested up to: 6.0
 *
 * [Elementor]
 * Elementor requires at least: 3.2.5
 * Elementor tested up to: 3.6
 *
 * [WC]
 * WC requires at least: 5.9
 * WC tested up to: 7.1
 *
 * @package volcano-addons
 */

namespace VolcanoAddons;

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

if ( ! defined( 'VOLCANO_ADDONS_VERSION' ) ) {
	/**
	 * Plugin Version.
	 */
	define( 'VOLCANO_ADDONS_VERSION', '1.0.0' );
}
if ( ! defined( 'VOLCANO_ADDONS_FILE' ) ) {
	/**
	 * Plugin File Ref.
	 */
	define( 'VOLCANO_ADDONS_FILE', __FILE__ );
}
if ( ! defined( 'VOLCANO_ADDONS_BASE' ) ) {
	/**
	 * Plugin Base Name.
	 */
	define( 'VOLCANO_ADDONS_BASE', plugin_basename( VOLCANO_ADDONS_FILE ) );
}
if ( ! defined( 'VOLCANO_ADDONS_PATH' ) ) {
	/**
	 * Plugin Dir Ref.
	 */
	define( 'VOLCANO_ADDONS_PATH', plugin_dir_path( VOLCANO_ADDONS_FILE ) );
}
if ( ! defined( 'VOLCANO_ADDONS_URL' ) ) {
	/**
	 * Plugin URL.
	 */
	define( 'VOLCANO_ADDONS_URL', plugin_dir_url( VOLCANO_ADDONS_FILE ) );
}
if ( ! defined( 'VOLCANO_ADDONS_WIDGETS_PATH' ) ) {
	/**
	 * Widgets Dir Ref.
	 */
	define( 'VOLCANO_ADDONS_WIDGETS_PATH', VOLCANO_ADDONS_PATH . 'widgets/' );
}
if ( ! class_exists( 'VolcanoAddons\Volcano_Addons', false ) ) {
	require_once VOLCANO_ADDONS_PATH . 'class-volcano-addons.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
}


/**
 * Initialize the plugin
 *
 * @return Volcano_Addons|null
 */
function volcano_addons() {
	return Volcano_Addons::instance();
}

// Kick it off.
volcano_addons();

// End of file volcano-addons.php.
