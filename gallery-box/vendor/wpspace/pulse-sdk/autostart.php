<?php
/**
 * Pulse SDK auto-start.
 *
 * Initializes PluginPulse tracking with embedded configuration.
 * Keep config here instead of the main plugin file for security.
 *
 * @package WPSpace\Pulse
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

add_action( 'init', function () {
	if ( ! class_exists( 'WPSpace\Pulse\Client' ) ) {
		require_once __DIR__ . '/src/Client.php';
		require_once __DIR__ . '/src/Insights.php';
	}

	// --- Configuration ---
	$hash     = 'b27eee03-f67b-4037-99cf-6c6facbea382';
	$name     = 'Gallery Box';
	$api_url  = 'https://lice.wpthemium.com';

	// Resolve the main plugin file from this SDK's vendor location.
	$plugin_file = dirname( __DIR__, 3 ) . '/gallery-box.php';

	if ( ! file_exists( $plugin_file ) ) {
		return;
	}

	$client = new \WPSpace\Pulse\Client( $hash, $name, $plugin_file );
	$client->set_api_url( $api_url );
	$client->insights()->init();
}, 0 );
