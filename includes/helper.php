<?php
/**
 *
 * Volcano Addons Elementor Helper Functions
 *
 */


/**
 * Determines whether a plugin is active.
 *
 * @param string $plugin Path to the plugin file relative to the plugins directory.
 *
 * @return bool True, if in the active plugins list. False, not in the list.
 * @uses is_plugin_active()
 */
function volcano_addons_is_plugin_active( $plugin ) {
	if ( ! function_exists( 'is_plugin_active' ) ) {
		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	}

	return is_plugin_active( $plugin );
}

/**
 * @param string $slug
 *
 * @return string
 */

function volcano_addons_plugin_install_url( $slug ) {
	return admin_url( 'plugin-install.php?s=' . $slug . '&tab=search&type=term&volcano_addons_dependency=' . $slug );
}


/**
 * Parse arguments with defaults recursively.
 *
 * @param array|object $args Arguments to parse.
 * @param array|object $defaults Default set.
 *
 * @return array|object Parsed arguments.
 * @see wp_parse_args()
 *
 */
function volcano_addons_parse_args_recursive( $args, $defaults = [] ) {

	if ( is_object( $args ) ) {
		$merged = get_object_vars( $args );
	} elseif ( is_array( $args ) ) {
		$merged = $args;
	} else {
		wp_parse_str( $args, $merged );
	}

	if ( is_object( $defaults ) ) {
		$defaults = get_object_vars( $defaults );
	}

	if ( ! is_array( $defaults ) || empty( $defaults ) ) {
		return $merged;
	}

	if ( empty( $merged ) ) {
		return $defaults;
	}

	foreach ( $defaults as $key => $value ) {

		if ( ! isset( $merged[ $key ] ) ) {
			$merged[ $key ] = $value;
		} else {
			if ( is_array( $value ) && is_array( $merged[ $key ] ) ) {
				$merged[ $key ] = volcano_addons_parse_args_recursive( $merged[ $key ], $value );
			}
		}
	}

	return $merged;
}
