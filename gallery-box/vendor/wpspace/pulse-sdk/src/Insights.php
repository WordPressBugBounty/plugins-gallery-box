<?php
/**
 * Pulse SDK Insights – tracking, opt-in notice, weekly cron.
 *
 * @package WPSpace\Pulse
 */

namespace WPSpace\Pulse;

/**
 * Class Insights
 */
class Insights {

	/**
	 * Client instance.
	 *
	 * @var Client
	 */
	protected $client;

	/**
	 * Whether to show the opt-in notice.
	 *
	 * @var bool
	 */
	private $notice_dismissed = false;

	/**
	 * Extra data to include in tracking payload.
	 *
	 * @var array
	 */
	private $extra_data = array();

	/**
	 * Constructor.
	 *
	 * @param Client $client The Client instance.
	 */
	public function __construct( Client $client ) {
		$this->client = $client;
	}

	/**
	 * Initialise tracking hooks. Call this in your plugin bootstrap.
	 *
	 * @return $this
	 */
	public function init() {
		if ( ! is_admin() ) {
			return $this;
		}

		// Plugin lifecycle hooks.
		register_activation_hook( $this->client->file, array( $this, 'activate_plugin' ) );
		register_deactivation_hook( $this->client->file, array( $this, 'deactivation_cleanup' ) );

		// Deactivation feedback modal.
		add_filter(
			'plugin_action_links_' . $this->client->basename,
			array( $this, 'plugin_action_links' )
		);
		add_action( 'admin_footer', array( $this, 'deactivate_scripts' ) );

		// Opt-in notice and handler.
		add_action( 'admin_notices', array( $this, 'admin_notice' ) );
		add_action( 'admin_init', array( $this, 'handle_optin_optout' ) );

		// Weekly cron.
		$hook = $this->client->slug . '_pulse_tracker_event';
		add_action( $hook, array( $this, 'send_tracking_data' ) );
		add_filter( 'cron_schedules', array( $this, 'add_weekly_schedule' ) );

		// AJAX: deactivation reason.
		add_action(
			'wp_ajax_' . $this->client->slug . '_pulse_deactivate',
			array( $this, 'deactivation_reason_submission' )
		);

		// One-time sync for existing installs that predate the SDK.
		// Fires once, then sets a flag so it never runs again.
		add_action( 'admin_init', array( $this, 'maybe_sync_existing' ) );

		return $this;
	}

	/**
	 * Add extra key/value pairs to every tracking request.
	 *
	 * @param array $data Associative array of extra data.
	 * @return $this
	 */
	public function add_extra( array $data ) {
		$this->extra_data = array_merge( $this->extra_data, $data );

		return $this;
	}

	/**
	 * Sync existing installs that predate the SDK.
	 *
	 * Runs once per site. Sends basic data (or full data if already opted in)
	 * so the site appears in the PluginPulse dashboard.
	 *
	 * @return void
	 */
	public function maybe_sync_existing() {
		$flag = $this->client->slug . '_pulse_synced';

		if ( get_option( $flag ) ) {
			return;
		}

		// Mark as synced first to avoid re-runs on failure.
		update_option( $flag, '1' );

		$allowed = get_option( $this->client->slug . '_pulse_tracking', '' );

		if ( 'yes' === $allowed ) {
			$data          = $this->get_tracking_data();
			$data['event'] = 'activate';
		} else {
			$data          = $this->get_basic_tracking_data();
			$data['event'] = 'activate';
		}

		$this->client->send_request( $data, 'track' );
	}

	// ------------------------------------------------------------------
	// Opt-in Notice
	// ------------------------------------------------------------------

	/**
	 * Show opt-in admin notice (once per plugin).
	 *
	 * @return void
	 */
	public function admin_notice() {
		if ( 'hide' === get_option( $this->client->slug . '_pulse_notice' ) ) {
			return;
		}

		$opted = get_option( $this->client->slug . '_pulse_tracking' );

		if ( 'yes' === $opted || 'no' === $opted ) {
			return;
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$optin_url = wp_nonce_url(
			add_query_arg( $this->client->slug . '_pulse_optin', 'yes' ),
			$this->client->slug . '_pulse_nonce'
		);

		$optout_url = wp_nonce_url(
			add_query_arg( $this->client->slug . '_pulse_optin', 'no' ),
			$this->client->slug . '_pulse_nonce'
		);

		$notice = sprintf(
			'<div class="updated notice pulse-sdk-notice" style="padding:12px 15px;">
				<p style="font-size:14px;margin:0 0 8px 0;"><strong>%1$s</strong></p>
				<p style="margin:0 0 10px 0;">%2$s</p>
				<p style="margin:0;">
					<a href="%3$s" class="button button-primary">%4$s</a>&nbsp;
					<a href="%5$s" class="button button-secondary">%6$s</a>
				</p>
			</div>',
			esc_html( $this->client->name ),
			esc_html__( 'Want to help us improve? Allow us to collect non-sensitive diagnostic data and usage information.', 'pulse-sdk' ),
			esc_url( $optin_url ),
			esc_html__( 'Allow', 'pulse-sdk' ),
			esc_url( $optout_url ),
			esc_html__( 'No thanks', 'pulse-sdk' )
		);

		echo $notice; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped above.
	}

	/**
	 * Handle opt-in/opt-out from URL parameters.
	 *
	 * @return void
	 */
	public function handle_optin_optout() {
		$key = $this->client->slug . '_pulse_optin';

		if ( ! isset( $_GET[ $key ] ) ) {
			return;
		}

		if ( ! wp_verify_nonce(
			sanitize_key( wp_unslash( $_GET['_wpnonce'] ?? '' ) ),
			$this->client->slug . '_pulse_nonce'
		) ) {
			return;
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$choice = sanitize_text_field( wp_unslash( $_GET[ $key ] ) );

		if ( 'yes' === $choice ) {
			$this->optin();
		} else {
			$this->optout();
		}

		update_option( $this->client->slug . '_pulse_notice', 'hide' );

		wp_safe_redirect( remove_query_arg( array( $key, '_wpnonce' ) ) );
		exit;
	}

	/**
	 * Opt in: enable tracking, schedule cron, send immediately.
	 *
	 * @return void
	 */
	public function optin() {
		update_option( $this->client->slug . '_pulse_tracking', 'yes' );

		$this->clear_schedule_event();
		$this->schedule_event();

		// Send an activate event so the server knows this plugin is active.
		$data          = $this->get_tracking_data();
		$data['event'] = 'activate';
		$this->client->send_request( $data, 'track' );

		update_option( $this->client->slug . '_pulse_last_send', time() );
	}

	/**
	 * Opt out: disable tracking, unschedule cron, send basic data.
	 *
	 * @return void
	 */
	public function optout() {
		update_option( $this->client->slug . '_pulse_tracking', 'no' );

		$this->clear_schedule_event();

		// Still send basic data so site appears in the dashboard.
		$data          = $this->get_basic_tracking_data();
		$data['event'] = 'activate';
		$this->client->send_request( $data, 'track' );
	}

	// ------------------------------------------------------------------
	// Tracking Data
	// ------------------------------------------------------------------

	/**
	 * Send tracking data to the PluginPulse server.
	 *
	 * @param bool $override_last_send Skip interval check.
	 * @return void
	 */
	public function send_tracking_data( $override_last_send = false ) {
		if ( 'yes' !== get_option( $this->client->slug . '_pulse_tracking', 'no' ) ) {
			return;
		}

		// Respect 7-day minimum interval (unless forced).
		if ( ! $override_last_send ) {
			$last = get_option( $this->client->slug . '_pulse_last_send' );
			if ( $last && ( time() - (int) $last ) < ( DAY_IN_SECONDS * 7 ) ) {
				return;
			}
		}

		$data          = $this->get_tracking_data();
		$data['event'] = 'tracking';

		$this->client->send_request( $data, 'track' );

		update_option( $this->client->slug . '_pulse_last_send', time() );
	}

	/**
	 * Collect all tracking data.
	 *
	 * @return array
	 */
	public function get_tracking_data() {
		$current_user = wp_get_current_user();

		$data = array(
			'hash'             => $this->client->hash,
			'slug'             => $this->client->slug,
			'url'              => esc_url( home_url() ),
			'site'             => $this->get_site_name(),
			'admin_email'      => get_option( 'admin_email' ),
			'first_name'       => $current_user->first_name ?? '',
			'last_name'        => $current_user->last_name ?? '',
			'version'          => $this->get_plugin_version(),
			'is_local'         => $this->client->is_local_server(),
			'ip_address'       => $this->get_user_ip_address(),
			'tracking_skipped' => false,
		);

		// Server, WP, users, plugins data.
		$data['server']           = $this->get_server_info();
		$data['wp']               = $this->get_wp_info();
		$data['users']            = $this->get_user_counts();
		$data['active_plugins']   = count( get_option( 'active_plugins', array() ) );
		$data['inactive_plugins'] = 0;

		if ( ! empty( $this->extra_data ) ) {
			$data['extra'] = $this->extra_data;
		}

		return apply_filters( 'pulse_tracking_data', $data, $this->client );
	}

	/**
	 * Collect basic (non-diagnostic) tracking data.
	 *
	 * Sent when the user has NOT opted in. Only includes identifying
	 * information: name, email, site URL, IP (for country), and
	 * plugin version. No server/WP/plugin diagnostic info.
	 *
	 * @return array
	 */
	public function get_basic_tracking_data() {
		$current_user = wp_get_current_user();

		return array(
			'hash'             => $this->client->hash,
			'slug'             => $this->client->slug,
			'url'              => esc_url( home_url() ),
			'site'             => $this->get_site_name(),
			'admin_email'      => get_option( 'admin_email' ),
			'first_name'       => $current_user->first_name ?? '',
			'last_name'        => $current_user->last_name ?? '',
			'version'          => $this->get_plugin_version(),
			'is_local'         => $this->client->is_local_server(),
			'ip_address'       => $this->get_user_ip_address(),
			'tracking_skipped' => true,
		);
	}

	// ------------------------------------------------------------------
	// Plugin Lifecycle
	// ------------------------------------------------------------------

	/**
	 * Runs on plugin activation.
	 *
	 * Always sends basic data (name, email, site URL) so the site
	 * appears in the dashboard immediately. Full diagnostic data
	 * is only sent if the user has opted in.
	 *
	 * @return void
	 */
	public function activate_plugin() {
		$allowed = get_option( $this->client->slug . '_pulse_tracking', 'no' );

		if ( 'yes' === $allowed ) {
			$this->schedule_event();
			delete_option( $this->client->slug . '_pulse_last_send' );

			$data          = $this->get_tracking_data();
			$data['event'] = 'activate';
			$this->client->send_request( $data, 'track' );
		} else {
			// Send basic data even if not opted in so the site appears in the list.
			$data          = $this->get_basic_tracking_data();
			$data['event'] = 'activate';
			$this->client->send_request( $data, 'track' );
		}
	}

	/**
	 * Runs on plugin deactivation – clean up cron and notice.
	 *
	 * @return void
	 */
	public function deactivation_cleanup() {
		$this->clear_schedule_event();
		delete_option( $this->client->slug . '_pulse_notice' );
	}

	// ------------------------------------------------------------------
	// Deactivation Feedback Modal
	// ------------------------------------------------------------------

	/**
	 * Add CSS class to the "Deactivate" link so JS can intercept it.
	 *
	 * @param array $links Plugin action links.
	 * @return array
	 */
	public function plugin_action_links( $links ) {
		if ( array_key_exists( 'deactivate', $links ) ) {
			$links['deactivate'] = str_replace(
				'<a',
				'<a class="' . esc_attr( $this->client->slug ) . '-pulse-deactivate-link"',
				$links['deactivate']
			);
		}

		return $links;
	}

	/**
	 * Render the deactivation feedback modal in the admin footer.
	 *
	 * @return void
	 */
	public function deactivate_scripts() {
		global $pagenow;

		if ( 'plugins.php' !== $pagenow ) {
			return;
		}

		$reasons = $this->get_uninstall_reasons();
		$slug    = esc_attr( $this->client->slug );
		$nonce   = wp_create_nonce( $this->client->slug . '_pulse_deactivate_nonce' );
		?>
		<style>
			.pulse-dr-modal{position:fixed;z-index:99999;top:0;right:0;bottom:0;left:0;background:rgba(0,0,0,.5);display:none;overflow:auto}
			.pulse-dr-modal *{box-sizing:border-box}
			.pulse-dr-modal.modal-active{display:block}
			.pulse-dr-modal-wrap{max-width:580px;margin:8% auto;background:#fff;border-radius:6px;box-shadow:0 4px 20px rgba(0,0,0,.15)}
			.pulse-dr-modal-header{border-bottom:1px solid #e2e4e7;padding:18px 20px}
			.pulse-dr-modal-header h3{margin:0;color:#1e1e1e;font-size:15px;line-height:1.5}
			.pulse-dr-modal-body{padding:15px 20px 20px}
			.pulse-dr-modal-body ul{margin:0;padding:0;list-style:none}
			.pulse-dr-modal-body li{margin:0 0 8px;padding:0}
			.pulse-dr-modal-body li label{display:flex;align-items:center;gap:8px;cursor:pointer;font-size:13px}
			.pulse-dr-modal-body textarea{width:100%;min-height:60px;margin-top:10px;display:none}
			.pulse-dr-modal-footer{border-top:1px solid #e2e4e7;padding:15px 20px;text-align:right;display:flex;gap:8px;justify-content:flex-end}
			.pulse-dr-modal-footer a.skip{margin-right:auto;line-height:30px;color:#b32d2e;text-decoration:none}
			.pulse-dr-modal-footer a.skip:hover{text-decoration:underline}
		</style>
		<div class="pulse-dr-modal" id="<?php echo $slug; ?>-pulse-dr-modal">
			<div class="pulse-dr-modal-wrap">
				<div class="pulse-dr-modal-header">
					<h3><?php esc_html_e( 'If you have a moment, please let us know why you are deactivating:', 'pulse-sdk' ); ?></h3>
				</div>
				<div class="pulse-dr-modal-body">
					<ul>
						<?php foreach ( $reasons as $reason ) : ?>
						<li>
							<label>
								<input type="radio" name="pulse-selected-reason" value="<?php echo esc_attr( $reason['id'] ); ?>" data-placeholder="<?php echo esc_attr( $reason['placeholder'] ); ?>">
								<?php echo esc_html( $reason['text'] ); ?>
							</label>
						</li>
						<?php endforeach; ?>
					</ul>
					<textarea id="<?php echo $slug; ?>-pulse-reason-text" placeholder=""></textarea>
				</div>
				<div class="pulse-dr-modal-footer">
					<a href="#" class="skip"><?php esc_html_e( 'Skip & Deactivate', 'pulse-sdk' ); ?></a>
					<button class="button button-secondary pulse-cancel"><?php esc_html_e( 'Cancel', 'pulse-sdk' ); ?></button>
					<button class="button button-primary pulse-submit"><?php esc_html_e( 'Submit & Deactivate', 'pulse-sdk' ); ?></button>
				</div>
			</div>
		</div>
		<script>
		(function($){
			$(function(){
				var modal = $('#<?php echo $slug; ?>-pulse-dr-modal'),
					deactivateUrl = '';

				$('#the-list').on('click','a.<?php echo $slug; ?>-pulse-deactivate-link',function(e){
					e.preventDefault();
					deactivateUrl = $(this).attr('href');
					modal.addClass('modal-active');
					modal.find('a.skip').attr('href', deactivateUrl);
				});

				modal.on('click','button.pulse-cancel',function(e){
					e.preventDefault();
					modal.removeClass('modal-active');
				});

				modal.on('change','input[type=radio]',function(){
					var ph = $(this).data('placeholder') || '';
					var ta = modal.find('textarea');
					ta.attr('placeholder', ph).show().focus();
				});

				modal.on('click','button.pulse-submit',function(e){
					e.preventDefault();
					var btn = $(this);
					if(btn.hasClass('disabled')) return;
					var reason = $('input[name=pulse-selected-reason]:checked', modal).val() || 'none';
					var info   = modal.find('textarea').val() || '';
					$.ajax({
						url: ajaxurl,
						type:'POST',
						data:{
							action:'<?php echo $slug; ?>_pulse_deactivate',
							nonce:'<?php echo $nonce; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>',
							reason_id: reason,
							reason_info: info
						},
						beforeSend:function(){ btn.addClass('disabled').text('<?php echo esc_js( __( 'Processing...', 'pulse-sdk' ) ); ?>'); },
						complete:function(){ window.location.href = deactivateUrl; }
					});
				});
			});
		}(jQuery));
		</script>
		<?php
	}

	/**
	 * AJAX handler: save deactivation reason.
	 *
	 * @return void
	 */
	public function deactivation_reason_submission() {
		if ( ! isset( $_POST['nonce'] ) || ! isset( $_POST['reason_id'] ) ) {
			wp_send_json_error( 'Missing data.' );
		}

		if ( ! wp_verify_nonce(
			sanitize_key( wp_unslash( $_POST['nonce'] ) ),
			$this->client->slug . '_pulse_deactivate_nonce'
		) ) {
			wp_send_json_error( 'Nonce verification failed.' );
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( 'Forbidden.' );
		}

		$data                = $this->get_tracking_data();
		$data['event']       = 'deactivate';
		$data['reason_id']   = sanitize_text_field( wp_unslash( $_POST['reason_id'] ) );
		$data['reason_info'] = isset( $_POST['reason_info'] )
			? sanitize_textarea_field( wp_unslash( $_POST['reason_info'] ) )
			: '';

		$this->client->send_request( $data, 'deactivate' );

		do_action( $this->client->slug . '_pulse_deactivation_submitted', $data );

		wp_send_json_success();
	}

	// ------------------------------------------------------------------
	// Helpers
	// ------------------------------------------------------------------

	/**
	 * Uninstall / deactivation reasons list.
	 *
	 * @return array
	 */
	private function get_uninstall_reasons() {
		$reasons = array(
			array(
				'id'          => 'could-not-understand',
				'text'        => __( "I couldn't understand how to make it work", 'pulse-sdk' ),
				'placeholder' => __( 'Would you like us to assist you?', 'pulse-sdk' ),
			),
			array(
				'id'          => 'found-better-plugin',
				'text'        => __( 'I found a better plugin', 'pulse-sdk' ),
				'placeholder' => __( 'Which plugin?', 'pulse-sdk' ),
			),
			array(
				'id'          => 'not-have-that-feature',
				'text'        => __( 'Missing a specific feature', 'pulse-sdk' ),
				'placeholder' => __( 'Could you tell us more about that feature?', 'pulse-sdk' ),
			),
			array(
				'id'          => 'is-not-working',
				'text'        => __( 'Not working', 'pulse-sdk' ),
				'placeholder' => __( 'Could you tell us what is not working?', 'pulse-sdk' ),
			),
			array(
				'id'          => 'did-not-work-as-expected',
				'text'        => __( "Didn't work as expected", 'pulse-sdk' ),
				'placeholder' => __( 'What did you expect?', 'pulse-sdk' ),
			),
			array(
				'id'          => 'temporary-deactivation',
				'text'        => __( "It's a temporary deactivation", 'pulse-sdk' ),
				'placeholder' => '',
			),
			array(
				'id'          => 'other',
				'text'        => __( 'Other', 'pulse-sdk' ),
				'placeholder' => __( 'Please share the reason', 'pulse-sdk' ),
			),
		);

		return apply_filters( 'pulse_deactivation_reasons', $reasons, $this->client );
	}

	/**
	 * Get the plugin version from the plugin header.
	 *
	 * @return string
	 */
	private function get_plugin_version() {
		if ( ! function_exists( 'get_plugin_data' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		$plugin_data = get_plugin_data( $this->client->file );

		return $plugin_data['Version'] ?? '0.0.0';
	}

	/**
	 * Get server information.
	 *
	 * @return array
	 */
	private function get_server_info() {
		global $wpdb;

		$server = array();

		if ( isset( $_SERVER['SERVER_SOFTWARE'] ) ) {
			$server['software'] = sanitize_text_field( wp_unslash( $_SERVER['SERVER_SOFTWARE'] ) );
		}

		if ( function_exists( 'phpversion' ) ) {
			$server['php_version'] = phpversion();
		}

		$server['mysql_version']         = $wpdb->db_version();
		$server['php_max_upload_size']   = size_format( wp_max_upload_size() );
		$server['php_default_timezone']  = date_default_timezone_get();
		$server['php_curl']              = function_exists( 'curl_init' ) ? 'Yes' : 'No';

		return $server;
	}

	/**
	 * Get WordPress environment data.
	 *
	 * @return array
	 */
	private function get_wp_info() {
		$wp = array(
			'memory_limit' => WP_MEMORY_LIMIT,
			'debug_mode'   => ( defined( 'WP_DEBUG' ) && WP_DEBUG ) ? 'Yes' : 'No',
			'locale'       => get_locale(),
			'version'      => get_bloginfo( 'version' ),
			'multisite'    => is_multisite() ? 'Yes' : 'No',
			'theme_slug'   => get_stylesheet(),
		);

		$theme = wp_get_theme( $wp['theme_slug'] );

		$wp['theme_name']    = $theme->get( 'Name' );
		$wp['theme_version'] = $theme->get( 'Version' );

		return $wp;
	}

	/**
	 * Get user count totals by role.
	 *
	 * @return array
	 */
	private function get_user_counts() {
		$count_data = count_users();
		$counts     = array( 'total' => $count_data['total_users'] );

		foreach ( $count_data['avail_roles'] as $role => $num ) {
			if ( $num ) {
				$counts[ $role ] = $num;
			}
		}

		return $counts;
	}

	/**
	 * Get site name with fallbacks.
	 *
	 * @return string
	 */
	private function get_site_name() {
		$name = get_bloginfo( 'name' );

		if ( empty( $name ) ) {
			$name = get_bloginfo( 'description' );
			$name = wp_trim_words( $name, 3, '' );
		}

		if ( empty( $name ) ) {
			$name = esc_url( home_url() );
		}

		return $name;
	}

	/**
	 * Attempt to get the server's public IP address.
	 *
	 * @return string
	 */
	private function get_user_ip_address() {
		$response = wp_remote_get( 'https://icanhazip.com/', array( 'timeout' => 5 ) );

		if ( is_wp_error( $response ) ) {
			return '';
		}

		$ip = trim( wp_remote_retrieve_body( $response ) );

		if ( ! filter_var( $ip, FILTER_VALIDATE_IP ) ) {
			return '';
		}

		return $ip;
	}

	// ------------------------------------------------------------------
	// Cron Scheduling
	// ------------------------------------------------------------------

	/**
	 * Register 'weekly' interval if not already registered.
	 *
	 * @param array $schedules Cron schedules.
	 * @return array
	 */
	public function add_weekly_schedule( $schedules ) {
		if ( ! isset( $schedules['weekly'] ) ) {
			$schedules['weekly'] = array(
				'interval' => DAY_IN_SECONDS * 7,
				'display'  => __( 'Once Weekly', 'pulse-sdk' ),
			);
		}

		return $schedules;
	}

	/**
	 * Schedule the weekly tracking cron event.
	 *
	 * @return void
	 */
	private function schedule_event() {
		$hook = $this->client->slug . '_pulse_tracker_event';

		if ( ! wp_next_scheduled( $hook ) ) {
			wp_schedule_event( time(), 'weekly', $hook );
		}
	}

	/**
	 * Clear the weekly tracking cron event.
	 *
	 * @return void
	 */
	private function clear_schedule_event() {
		$hook = $this->client->slug . '_pulse_tracker_event';

		wp_clear_scheduled_hook( $hook );
	}
}
