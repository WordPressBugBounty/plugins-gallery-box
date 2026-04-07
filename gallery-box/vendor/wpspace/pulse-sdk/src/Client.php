<?php
/**
 * Pulse SDK Client.
 *
 * Main entry point for the PluginPulse tracking SDK.
 * Drop-in replacement for Appsero\Client – same constructor signature.
 *
 * @package WPSpace\Pulse
 */

namespace WPSpace\Pulse;

/**
 * Class Client
 */
class Client {

	/**
	 * Plugin hash (UUID) registered in PluginPulse.
	 *
	 * @var string
	 */
	public $hash;

	/**
	 * Plugin nice name.
	 *
	 * @var string
	 */
	public $name;

	/**
	 * Main plugin file path.
	 *
	 * @var string
	 */
	public $file;

	/**
	 * Plugin slug (derived from basename).
	 *
	 * @var string
	 */
	public $slug;

	/**
	 * Plugin basename (e.g. "my-plugin/my-plugin.php").
	 *
	 * @var string
	 */
	public $basename;

	/**
	 * Project type: "plugin" (themes not supported).
	 *
	 * @var string
	 */
	public $type = 'plugin';

	/**
	 * PluginPulse server URL (no trailing slash).
	 *
	 * @var string
	 */
	private $api_url = '';

	/**
	 * Cached Insights instance.
	 *
	 * @var Insights|null
	 */
	private $insights_instance;

	/**
	 * Text domain for translations.
	 *
	 * @var string
	 */
	public $textdomain = 'pulse-sdk';

	/**
	 * Constructor – mirrors Appsero\Client signature.
	 *
	 * @param string $hash Plugin hash (UUID from PluginPulse dashboard).
	 * @param string $name Plugin display name.
	 * @param string $file Main plugin __FILE__.
	 */
	public function __construct( $hash, $name, $file ) {
		$this->hash = $hash;
		$this->name = $name;
		$this->file = $file;

		$this->set_basename_and_slug();
	}

	/**
	 * Set the PluginPulse server URL.
	 *
	 * Must be called before insights()->init() for the SDK to know
	 * where to send data.
	 *
	 * @param string $url Full URL to PluginPulse installation (e.g. https://example.com).
	 * @return $this
	 */
	public function set_api_url( $url ) {
		$this->api_url = untrailingslashit( $url );

		return $this;
	}

	/**
	 * Get the REST endpoint base URL.
	 *
	 * @return string
	 */
	public function endpoint() {
		if ( empty( $this->api_url ) ) {
			// Fallback: same site (useful if PluginPulse is on the same WP install).
			return rest_url( 'pluginpulse/v1/' );
		}

		return trailingslashit( $this->api_url ) . 'wp-json/pluginpulse/v1/';
	}

	/**
	 * Get or create the Insights tracker instance.
	 *
	 * @return Insights
	 */
	public function insights() {
		if ( ! $this->insights_instance ) {
			$this->insights_instance = new Insights( $this );
		}

		return $this->insights_instance;
	}

	/**
	 * Send an HTTP request to the PluginPulse server.
	 *
	 * @param array  $params   Data payload.
	 * @param string $route    API route (e.g. "track" or "deactivate").
	 * @param bool   $blocking Whether to wait for response.
	 * @return array|\WP_Error
	 */
	public function send_request( $params, $route, $blocking = false ) {
		return wp_remote_post(
			$this->endpoint() . $route,
			array(
				'method'      => 'POST',
				'timeout'     => 30,
				'redirection' => 5,
				'httpversion' => '1.1',
				'blocking'    => $blocking,
				'headers'     => array(
					'Content-Type' => 'application/json',
				),
				'body'        => wp_json_encode( $params ),
			)
		);
	}

	/**
	 * Derive basename and slug from the main plugin file.
	 *
	 * @return void
	 */
	private function set_basename_and_slug() {
		if ( strpos( $this->file, '/' ) !== false || strpos( $this->file, '\\' ) !== false ) {
			$this->basename = plugin_basename( $this->file );
		}

		$this->slug = $this->basename ? dirname( $this->basename ) : $this->name;

		// Normalise slug.
		if ( '.' === $this->slug ) {
			$this->slug = basename( $this->file, '.php' );
		}

		$this->slug = sanitize_key( $this->slug );
	}

	/**
	 * Translate a string (using WP i18n with the plugin's textdomain).
	 *
	 * @param string $text Text to translate.
	 * @return string
	 */
	public function __trans( $text ) {
		return __( $text, $this->textdomain ); // phpcs:ignore WordPress.WP.I18n.NonSingularStringLiteralText,WordPress.WP.I18n.NonSingularStringLiteralDomain
	}

	/**
	 * Echo translated string.
	 *
	 * @param string $text Text to translate and echo.
	 * @return void
	 */
	public function _etrans( $text ) {
		echo esc_html( $this->__trans( $text ) );
	}

	/**
	 * Check if running on localhost / dev environment.
	 *
	 * @return bool
	 */
	public function is_local_server() {
		$host        = isset( $_SERVER['HTTP_HOST'] ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_HOST'] ) ) : 'localhost';
		$ip          = isset( $_SERVER['SERVER_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['SERVER_ADDR'] ) ) : '127.0.0.1';
		$is_local_ip = in_array( $ip, array( '127.0.0.1', '::1' ), true );
		$is_local    = false !== strpos( $host, '.local' )
						|| false !== strpos( $host, '.test' )
						|| false !== strpos( $host, 'localhost' )
						|| $is_local_ip;

		return apply_filters( 'pulse_is_local', $is_local );
	}
}
