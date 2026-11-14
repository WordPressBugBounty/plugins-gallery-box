<?php

/**
 * Admin info, Black Friday offer, and AI Marketing notice for Gallery Box plugin
 */

if (!defined('ABSPATH')) {
    exit;
}

class gboxAdminInfo
{
    const NOTICE_DISMISSED_META_KEY = 'mg_bf_offer_notice_v4_dismissed';
    const AIME_DISMISSED_META_KEY   = 'mg_aime_promo_notice_v1_dismissed';
    const ROTATION_TURN_META_KEY    = 'mg_admin_notice_rotation_turn';
    const AIME_PLUGIN_SLUG          = 'ai-marketing-expert';
    const DISMISS_DAYS              = 25;

    /**
     * Per-request cache so a single HTTP load never flips rotation turn twice.
     *
     * @var string|null
     */
    private static $resolved_notice = null;

    /**
     * @var bool
     */
    private static $has_resolved = false;

    public static function init()
    {
        add_action('admin_notices', [__CLASS__, 'display_sales_notice']);
        add_action('init', [__CLASS__, 'handle_notice_dismiss']);
        add_action('admin_enqueue_scripts', [__CLASS__, 'gbox_admin_scripts']);
        add_action('wp_ajax_gallerybox_dismiss_sales_notice', [__CLASS__, 'ajax_dismiss_sales_notice']);
        add_action('wp_ajax_gallerybox_install_aime_plugin', [__CLASS__, 'ajax_install_aime_plugin']);
    }

    /**
     * Map valid notice identifiers to their per-user dismissal meta keys.
     *
     * @return array<string, string>
     */
    private static function get_notice_meta_keys()
    {
        return array(
            'offer' => self::NOTICE_DISMISSED_META_KEY,
            'aime'  => self::AIME_DISMISSED_META_KEY,
        );
    }

    /**
     * Check whether a specific notice is still within its 25-day dismissal window.
     *
     * @param int    $user_id    Current user ID.
     * @param string $notice_key Notice type key ('offer' or 'aime').
     * @return bool
     */
    private static function is_notice_dismissed($user_id, $notice_key)
    {
        $meta_keys = self::get_notice_meta_keys();
        if (!$user_id || !isset($meta_keys[$notice_key])) {
            return false;
        }

        $hide_time = get_user_meta($user_id, $meta_keys[$notice_key], true);
        if (empty($hide_time)) {
            return false;
        }

        $dismissed_timestamp = is_numeric($hide_time) ? (int)$hide_time : strtotime($hide_time);
        $day_in_seconds      = defined('DAY_IN_SECONDS') ? DAY_IN_SECONDS : 86400;

        return (time() - $dismissed_timestamp) < (self::DISMISS_DAYS * $day_in_seconds);
    }

    /**
     * Find installed AI Marketing Expert plugin file if present (Free or Pro).
     *
     * @return string|false Plugin basename relative to plugins dir, or false if not installed.
     */
    private static function get_aime_plugin_file()
    {
        if (!function_exists('get_plugins')) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }

        $installed_plugins = get_plugins();
        $target_slugs      = array(self::AIME_PLUGIN_SLUG, 'ai-marketing-expert-pro');

        foreach ($installed_plugins as $plugin_file => $plugin_data) {
            $plugin_dir = dirname($plugin_file);
            if (in_array($plugin_dir, $target_slugs, true) || in_array($plugin_file, array('ai-marketing-expert.php', 'ai-marketing-expert-pro.php'), true)) {
                return $plugin_file;
            }
        }

        return false;
    }

    /**
     * Determine whether the Black Friday / Pro Offer notice can be shown.
     *
     * @param int $user_id Current user ID.
     * @return bool
     */
    private static function is_offer_notice_eligible($user_id)
    {
        if (in_array('gallery-box-pro/gallery-box-pro.php', apply_filters('active_plugins', get_option('active_plugins')))) {
            return false;
        }

        return !self::is_notice_dismissed($user_id, 'offer');
    }

    /**
     * Determine whether the AI Marketing Expert 1-click install notice can be shown.
     *
     * @param int $user_id Current user ID.
     * @return bool
     */
    private static function is_aime_notice_eligible($user_id)
    {
        if (!current_user_can('install_plugins')) {
            return false;
        }

        // Do not show if AI Marketing Expert is already installed (active or inactive)
        if (false !== self::get_aime_plugin_file()) {
            return false;
        }

        return !self::is_notice_dismissed($user_id, 'aime');
    }

    /**
     * Return all currently eligible notice identifiers.
     *
     * @param int $user_id Current user ID.
     * @return string[]
     */
    private static function get_eligible_notices($user_id)
    {
        $eligible = array();

        if (self::is_offer_notice_eligible($user_id)) {
            $eligible[] = 'offer';
        }

        if (self::is_aime_notice_eligible($user_id)) {
            $eligible[] = 'aime';
        }

        return $eligible;
    }

    /**
     * Pick a single notice for this page load, alternating per reload when both are eligible.
     *
     * @param int $user_id Current user ID.
     * @return string|null Notice type ('offer' or 'aime') or null when none are eligible.
     */
    private static function pick_notice_for_reload($user_id)
    {
        if (self::$has_resolved) {
            return self::$resolved_notice;
        }

        self::$has_resolved = true;

        $eligible = self::get_eligible_notices($user_id);
        if (empty($eligible)) {
            self::$resolved_notice = null;
            return null;
        }

        if (count($eligible) === 1) {
            self::$resolved_notice = $eligible[0];
            return self::$resolved_notice;
        }

        $last_turn = get_user_meta($user_id, self::ROTATION_TURN_META_KEY, true);
        $next_turn = ('offer' === $last_turn) ? 'aime' : 'offer';

        update_user_meta($user_id, self::ROTATION_TURN_META_KEY, $next_turn);

        self::$resolved_notice = $next_turn;
        return self::$resolved_notice;
    }

    /**
     * Display one admin notice per reload (alternating Black Friday Offer <-> AI Marketing Expert).
     */
    public static function display_sales_notice()
    {
        if (
            is_network_admin()
            || is_user_admin()
            || wp_doing_ajax()
            || wp_doing_cron()
            || (function_exists('wp_is_json_request') && wp_is_json_request())
            || (defined('REST_REQUEST') && REST_REQUEST)
            || !current_user_can('manage_options')
            || defined('WPTS_ADMIN_NOTICE_RENDERED')
        ) {
            return;
        }

        $user_id     = get_current_user_id();
        $notice_type = self::pick_notice_for_reload($user_id);

        if (!$notice_type) {
            return;
        }

        if (!defined('WPTS_ADMIN_NOTICE_RENDERED')) {
            define('WPTS_ADMIN_NOTICE_RENDERED', true);
        }

        if ('aime' === $notice_type) {
            self::render_notice_markup(self::get_aime_notice_config());
            return;
        }

        self::render_notice_markup(self::get_offer_notice_config());
    }

    /**
     * Build configuration for the Black Friday / $69 VIP All-Access Offer notice.
     *
     * @return array<string, mixed>
     */
    private static function get_offer_notice_config()
    {
        $checkout_url = 'https://wpthemespace.com/checkout/?add-to-cart=19051';
        $packages_url = 'https://wpthemespace.com/#pricing';
        $dismiss_url  = wp_nonce_url(add_query_arg('gbox_notice_dismiss', 'offer'), 'gbox_dismiss_notice_offer');

        return array(
            'type'           => 'offer',
            'wrapper_class'  => 'mg-sales-notice',
            'badges'         => array(
                array(
                    'class' => 'mg-sales-badge-deal',
                    'icon'  => '',
                    'text'  => __('🔥 BLACK FRIDAY · UP TO 83% OFF', 'gallery-box'),
                ),
                array(
                    'class' => 'mg-sales-badge-pro',
                    'icon'  => '⚡',
                    'text'  => __('HOT DEAL · TOP PICK', 'gallery-box'),
                ),
                array(
                    'class' => 'mg-sales-badge-new',
                    'icon'  => '🚀',
                    'text'  => __('VIP ALL-ACCESS PASS', 'gallery-box'),
                ),
            ),
            'original_price' => '$399',
            'current_price'  => __('$69 Lifetime', 'gallery-box'),
            'save_tag'       => __('SAVE 83%', 'gallery-box'),
            'title'          => __('Black Friday Special: Get All WPThemeSpace Pro Plugins & Themes for Just $69 (VIP All-Access Pass)', 'gallery-box'),
            'desc'           => __('Unlock unlimited websites lifetime access to all current and future Pro plugins & themes by WPThemeSpace. Includes Magical Addons Pro, Gallery Box Pro, AI Marketing Expert, Easy Share Solution Pro, WPEPP Pro, plus lifetime updates and priority agency VIP support!', 'gallery-box'),
            'features'       => array(
                array('icon' => '💎', 'label' => __('All Pro Plugins & Themes Included', 'gallery-box'), 'bold' => true),
                array('icon' => '♾️', 'label' => __('Unlimited Websites License', 'gallery-box'), 'bold' => true),
                array('icon' => '🚀', 'label' => __('Lifetime Updates & VIP Support', 'gallery-box'), 'bold' => false),
                array('icon' => '⚡', 'label' => __('1-Click Instant Activation', 'gallery-box'), 'bold' => false),
                array('icon' => '🔥', 'label' => __('Hot Deal: $69 Lifetime VIP Pass', 'gallery-box'), 'bold' => true),
            ),
            'primary_action' => array(
                'tag'    => 'a',
                'href'   => $checkout_url,
                'target' => '_blank',
                'class'  => 'mg-sales-btn mg-sales-btn-primary',
                'icon'   => '⚡',
                'label'  => __('Quick Buy $69 VIP Pass', 'gallery-box'),
                'arrow'  => true,
            ),
            'secondary_action' => array(
                'href'   => $packages_url,
                'target' => '_blank',
                'icon'   => '📦',
                'label'  => __('Select Package', 'gallery-box'),
            ),
            'dismiss_url'    => $dismiss_url,
        );
    }

    /**
     * Build configuration for the AI Marketing Expert 1-Click Install notice.
     *
     * @return array<string, mixed>
     */
    private static function get_aime_notice_config()
    {
        $wporg_url   = 'https://wordpress.org/plugins/ai-marketing-expert/';
        $dismiss_url = wp_nonce_url(add_query_arg('gbox_notice_dismiss', 'aime'), 'gbox_dismiss_notice_aime');

        return array(
            'type'           => 'aime',
            'wrapper_class'  => 'mg-sales-notice mg-aime-notice',
            'badges'         => array(
                array(
                    'class' => 'mg-aime-badge-free',
                    'icon'  => '🤖',
                    'text'  => __('FREE AI MARKETING PLUGIN', 'gallery-box'),
                ),
                array(
                    'class' => 'mg-sales-badge-deal',
                    'icon'  => '',
                    'text'  => __('🔥 TRENDING ON WORDPRESS', 'gallery-box'),
                ),
                array(
                    'class' => 'mg-sales-badge-new',
                    'icon'  => '⚡',
                    'text'  => __('1-CLICK WORDPRESS.ORG INSTALL', 'gallery-box'),
                ),
            ),
            'original_price' => '$99/mo SaaS',
            'current_price'  => __('FREE', 'gallery-box'),
            'save_tag'       => __('100% FREE TO START', 'gallery-box'),
            'title'          => __('Supercharge Your Site with AI Marketing Expert — Free Inside WordPress', 'gallery-box'),
            'desc'           => __('Automate your WordPress marketing with the free AI Marketing Expert plugin from WordPress.org. Generate Rank Math-ready AI blog posts, run automated email campaigns, launch a 24/7 AI chatbot, schedule social posts, and track SEO — with one click directly from here.', 'gallery-box'),
            'features'       => array(
                array('icon' => '✉️', 'label' => __('AI Email Marketing & CRM', 'gallery-box'), 'bold' => true),
                array('icon' => '✍️', 'label' => __('AI SEO Content Generator', 'gallery-box'), 'bold' => true),
                array('icon' => '💬', 'label' => __('24/7 AI Chatbot', 'gallery-box'), 'bold' => false),
                array('icon' => '📣', 'label' => __('Social Media Scheduler', 'gallery-box'), 'bold' => false),
                array('icon' => '📈', 'label' => __('SEO Intelligence', 'gallery-box'), 'bold' => false),
                array('icon' => '⚙️', 'label' => __('Free AI & SMTP Support', 'gallery-box'), 'bold' => false),
            ),
            'primary_action' => array(
                'tag'    => 'button',
                'href'   => '',
                'target' => '',
                'class'  => 'mg-sales-btn mg-sales-btn-primary mg-aime-install-btn',
                'icon'   => '⚡',
                'label'  => __('Install AI Marketing Expert Free', 'gallery-box'),
                'arrow'  => false,
            ),
            'secondary_action' => array(
                'href'   => $wporg_url,
                'target' => '_blank',
                'icon'   => '🔌',
                'label'  => __('View on WordPress.org', 'gallery-box'),
            ),
            'dismiss_url'    => $dismiss_url,
        );
    }

    /**
     * Render a styled, dismissible admin notice card from a structured config.
     *
     * @param array<string, mixed> $config Notice rendering configuration.
     */
    private static function render_notice_markup($config)
    {
        $notice_type = $config['type'];
        $primary     = $config['primary_action'];
        $secondary   = $config['secondary_action'];
        ?>
        <div class="notice <?php echo esc_attr($config['wrapper_class']); ?> is-dismissible" data-notice-type="<?php echo esc_attr($notice_type); ?>">
            <div class="mg-sales-inner">
                <div class="mg-sales-header">
                    <div class="mg-sales-badges">
                        <?php foreach ($config['badges'] as $badge) : ?>
                            <span class="mg-sales-badge <?php echo esc_attr($badge['class']); ?>">
                                <?php if (!empty($badge['icon'])) : ?>
                                    <span class="mg-sales-badge-icon"><?php echo esc_html($badge['icon']); ?></span>
                                <?php endif; ?>
                                <?php echo esc_html($badge['text']); ?>
                            </span>
                        <?php endforeach; ?>
                    </div>
                    <div class="mg-sales-pricing">
                        <span class="mg-sales-original-price"><del><?php echo esc_html($config['original_price']); ?></del></span>
                        <span class="mg-sales-current-price"><?php echo esc_html($config['current_price']); ?></span>
                        <span class="mg-sales-save-tag"><?php echo esc_html($config['save_tag']); ?></span>
                    </div>
                </div>

                <div class="mg-sales-content">
                    <h3 class="mg-sales-title">
                        <?php echo esc_html($config['title']); ?>
                    </h3>
                    <p class="mg-sales-desc">
                        <?php echo esc_html($config['desc']); ?>
                    </p>
                    <div class="mg-sales-features">
                        <?php foreach ($config['features'] as $feature) : ?>
                            <span class="mg-sales-feature">
                                <span class="mg-sales-feature-icon"><?php echo esc_html($feature['icon']); ?></span>
                                <?php if (!empty($feature['bold'])) : ?>
                                    <strong><?php echo esc_html($feature['label']); ?></strong>
                                <?php else : ?>
                                    <?php echo esc_html($feature['label']); ?>
                                <?php endif; ?>
                            </span>
                        <?php endforeach; ?>
                    </div>

                    <?php if ('aime' === $notice_type) : ?>
                        <div class="mg-aime-feedback" aria-live="polite" style="display:none;"></div>
                    <?php endif; ?>

                    <div class="mg-sales-actions">
                        <?php if ('button' === $primary['tag']) : ?>
                            <button type="button" class="<?php echo esc_attr($primary['class']); ?>">
                                <span class="mg-sales-btn-shine"></span>
                                <span class="mg-sales-btn-icon"><?php echo esc_html($primary['icon']); ?></span>
                                <span class="mg-sales-btn-text"><?php echo esc_html($primary['label']); ?></span>
                            </button>
                        <?php else : ?>
                            <a href="<?php echo esc_url($primary['href']); ?>" class="<?php echo esc_attr($primary['class']); ?>"<?php echo !empty($primary['target']) ? ' target="' . esc_attr($primary['target']) . '" rel="noopener noreferrer"' : ''; ?>>
                                <span class="mg-sales-btn-shine"></span>
                                <span class="mg-sales-btn-icon"><?php echo esc_html($primary['icon']); ?></span>
                                <span class="mg-sales-btn-text"><?php echo esc_html($primary['label']); ?></span>
                                <?php if (!empty($primary['arrow'])) : ?>
                                    <span class="mg-sales-btn-arrow">→</span>
                                <?php endif; ?>
                            </a>
                        <?php endif; ?>

                        <?php if (!empty($secondary)) : ?>
                            <a href="<?php echo esc_url($secondary['href']); ?>" class="mg-sales-btn mg-sales-btn-secondary"<?php echo !empty($secondary['target']) ? ' target="' . esc_attr($secondary['target']) . '" rel="noopener noreferrer"' : ''; ?>>
                                <span class="mg-sales-btn-icon"><?php echo esc_html($secondary['icon']); ?></span>
                                <?php echo esc_html($secondary['label']); ?>
                            </a>
                        <?php endif; ?>

                        <a href="<?php echo esc_url($config['dismiss_url']); ?>" class="mg-sales-btn mg-sales-btn-dismiss mg-sales-dismiss-btn" data-notice="<?php echo esc_attr($notice_type); ?>">
                            <?php esc_html_e('Maybe Later', 'gallery-box'); ?>
                        </a>
                    </div>
                </div>
            </div>
            <button type="button" class="notice-dismiss"><span class="screen-reader-text"><?php esc_html_e('Dismiss this notice.', 'gallery-box'); ?></span></button>
        </div>
        <?php
    }

    /**
     * Handle notice dismissal via URL param fallback.
     */
    public static function handle_notice_dismiss()
    {
        if (!is_admin() || !current_user_can('manage_options')) {
            return;
        }

        $notice_key = '';
        $nonce_name = '';

        if (isset($_GET['gbox_notice_dismiss']) && isset($_GET['_wpnonce'])) {
            $candidate = sanitize_key(wp_unslash($_GET['gbox_notice_dismiss']));
            $meta_keys = self::get_notice_meta_keys();
            if (isset($meta_keys[$candidate])) {
                $notice_key = $candidate;
                $nonce_name = 'gbox_dismiss_notice_' . $candidate;
            }
        } elseif (isset($_GET['mg_notice_dismiss']) && isset($_GET['_wpnonce'])) {
            $candidate = sanitize_key(wp_unslash($_GET['mg_notice_dismiss']));
            $meta_keys = self::get_notice_meta_keys();
            if (isset($meta_keys[$candidate])) {
                $notice_key = $candidate;
                $nonce_name = 'mg_dismiss_notice_' . $candidate;
            }
        }

        if ($notice_key && wp_verify_nonce(sanitize_text_field(wp_unslash($_GET['_wpnonce'])), $nonce_name)) {
            $user_id   = get_current_user_id();
            $meta_keys = self::get_notice_meta_keys();
            if ($user_id && isset($meta_keys[$notice_key])) {
                update_user_meta($user_id, $meta_keys[$notice_key], time());
            }
        }
    }

    /**
     * AJAX handler for independent 25-day notice dismissal ("Maybe Later").
     */
    public static function ajax_dismiss_sales_notice()
    {
        check_ajax_referer('gbox_bundle_notice_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => esc_html__('Permission denied', 'gallery-box')));
        }

        $notice_key = isset($_POST['notice']) ? sanitize_key(wp_unslash($_POST['notice'])) : 'offer';
        $meta_keys  = self::get_notice_meta_keys();
        if (!isset($meta_keys[$notice_key])) {
            wp_send_json_error(array('message' => esc_html__('Invalid notice identifier', 'gallery-box')));
        }

        $user_id = get_current_user_id();
        if ($user_id) {
            update_user_meta($user_id, $meta_keys[$notice_key], time());
        }

        wp_send_json_success(array('dismissed' => $notice_key));
    }

    /**
     * AJAX handler to install and activate AI Marketing Expert from WordPress.org in one click.
     */
    public static function ajax_install_aime_plugin()
    {
        check_ajax_referer('gbox_install_aime_nonce', 'nonce');

        if (!current_user_can('install_plugins')) {
            wp_send_json_error(array('message' => esc_html__('You do not have permission to install plugins.', 'gallery-box')));
        }

        // Check if already installed
        $plugin_file = self::get_aime_plugin_file();
        if (false === $plugin_file) {
            require_once ABSPATH . 'wp-admin/includes/file.php';
            require_once ABSPATH . 'wp-admin/includes/misc.php';
            require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
            require_once ABSPATH . 'wp-admin/includes/plugin-install.php';

            $api = plugins_api('plugin_information', array(
                'slug'   => self::AIME_PLUGIN_SLUG,
                'fields' => array(
                    'short_description' => false,
                    'sections'          => false,
                    'requires'          => false,
                    'rating'            => false,
                    'ratings'           => false,
                    'downloaded'        => false,
                    'last_updated'      => false,
                    'added'             => false,
                    'tags'              => false,
                    'compatibility'     => false,
                    'homepage'          => false,
                    'donate_link'       => false,
                ),
            ));

            if (is_wp_error($api) || empty($api->download_link)) {
                wp_send_json_error(array('message' => esc_html__('Could not fetch AI Marketing Expert from WordPress.org.', 'gallery-box')));
            }

            $skin     = new Automatic_Upgrader_Skin();
            $upgrader = new Plugin_Upgrader($skin);
            $result   = $upgrader->install($api->download_link);

            if (is_wp_error($result) || !$result) {
                $error_msg = is_wp_error($result)
                    ? $result->get_error_message()
                    : ($skin->get_errors()->has_errors()
                        ? $skin->get_errors()->get_error_message()
                        : esc_html__('Plugin installation failed. Please try installing from the Plugins page.', 'gallery-box'));
                wp_send_json_error(array('message' => $error_msg));
            }

            if (function_exists('wp_clean_plugins_cache')) {
                wp_clean_plugins_cache(true);
            }

            $plugin_file = self::get_aime_plugin_file();
        }

        $activated = false;
        if ($plugin_file && current_user_can('activate_plugins')) {
            if (is_plugin_active($plugin_file)) {
                $activated = true;
            } else {
                $activate_result = activate_plugin($plugin_file);
                if (!is_wp_error($activate_result)) {
                    $activated = true;
                }
            }
        }

        wp_send_json_success(array(
            'message'    => $activated
                ? esc_html__('AI Marketing Expert has been installed and activated!', 'gallery-box')
                : esc_html__('AI Marketing Expert has been installed! You can activate it from the Plugins page.', 'gallery-box'),
            'activated'  => $activated,
            'pluginsUrl' => admin_url('plugins.php'),
        ));
    }

    public static function gbox_admin_scripts()
    {
        if (!current_user_can('manage_options')) {
            return;
        }

        $is_settings_page = isset($_GET['page']) && 'gallery-box-options.php' === $_GET['page'];
        $user_id          = get_current_user_id();
        if (!$is_settings_page && empty(self::get_eligible_notices($user_id))) {
            return;
        }

        $version = defined('WP_DEBUG') && WP_DEBUG ? time() : '1.7.42';
        wp_enqueue_style('gbox-admin-info', plugins_url('/assets/css/gbox-admin-info.css', dirname(__FILE__)), array(), $version, 'all');
        wp_enqueue_script('gbox-admin-info', plugins_url('/assets/js/gbox-admin-info.js', dirname(__FILE__)), array('jquery'), $version, true);

        // Add AJAX URL & nonces for independent notice dismiss and 1-click plugin install
        wp_localize_script('gbox-admin-info', 'gboxAdminInfo', array(
            'ajaxurl'       => admin_url('admin-ajax.php'),
            'sales_nonce'   => wp_create_nonce('gbox_bundle_notice_nonce'),
            'install_nonce' => wp_create_nonce('gbox_install_aime_nonce'),
            'i18n'          => array(
                'installing'   => esc_html__('Installing & Activating...', 'gallery-box'),
                'installed'    => esc_html__('Installed & Activated!', 'gallery-box'),
                'reloadLabel'  => esc_html__('Reload Page', 'gallery-box'),
                'retryLabel'   => esc_html__('⚡ Try Again', 'gallery-box'),
                'installError' => esc_html__('Installation failed. Please install AI Marketing Expert from WordPress.org.', 'gallery-box'),
            ),
        ));
    }

    /**
     * Render the modern HTML+CSS+SVG AI Marketing Expert promo header banner on settings page.
     */
    public static function render_aime_header_banner()
    {
        $wporg_url   = 'https://wordpress.org/plugins/ai-marketing-expert/';
        $plugin_file = 'ai-marketing-expert/ai-marketing-expert.php';
        $is_active   = false;
        $is_installed = false;

        if (!function_exists('is_plugin_active')) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }

        if (is_plugin_active($plugin_file)) {
            $is_active    = true;
            $is_installed = true;
        } elseif (file_exists(WP_PLUGIN_DIR . '/' . $plugin_file)) {
            $is_installed = true;
        }
        ?>
        <div class="gbox-aime-banner">
            <div class="gbox-aime-banner-inner">
                <div class="gbox-aime-banner-icon-col">
                    <div class="gbox-aime-banner-icon-wrap">
                        <span class="gbox-aime-banner-pulse"></span>
                        <svg class="gbox-aime-banner-svg" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 2a2 2 0 0 1 2 2c0 .74-.4 1.39-1 1.73V7h2a5 5 0 0 1 5 5v1h1a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2h-1v1a3 3 0 0 1-3 3H7a3 3 0 0 1-3-3v-1H3a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h1v-1a5 5 0 0 1 5-5h2V5.73A2 2 0 0 1 10 4a2 2 0 0 1 2-2zM7.5 13a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3zm9 0a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3z" fill="currentColor"/>
                        </svg>
                    </div>
                </div>
                <div class="gbox-aime-banner-content-col">
                    <div class="gbox-aime-banner-badges">
                        <span class="gbox-aime-badge gbox-aime-badge-free">🤖 <?php esc_html_e('FREE AI MARKETING PLUGIN', 'gallery-box'); ?></span>
                        <span class="gbox-aime-badge gbox-aime-badge-hot">🔥 <?php esc_html_e('TRENDING ON WORDPRESS.ORG', 'gallery-box'); ?></span>
                    </div>
                    <div class="gbox-aime-banner-title">
                        <?php esc_html_e('AI Marketing Expert — Supercharge Your WordPress Growth on Autopilot', 'gallery-box'); ?>
                    </div>
                    <p class="gbox-aime-banner-desc">
                        <?php esc_html_e('Automate your site growth with free AI blog posts (Rank Math SEO ready), automated email campaigns, 24/7 AI chatbot, and social media scheduling.', 'gallery-box'); ?>
                    </p>
                    <div class="gbox-aime-banner-features">
                        <span class="gbox-aime-feat-pill">✉️ <?php esc_html_e('Email Automation', 'gallery-box'); ?></span>
                        <span class="gbox-aime-feat-pill">✍️ <?php esc_html_e('AI SEO Writer', 'gallery-box'); ?></span>
                        <span class="gbox-aime-feat-pill">💬 <?php esc_html_e('24/7 Chatbot', 'gallery-box'); ?></span>
                        <span class="gbox-aime-feat-pill">⚙️ <?php esc_html_e('Free AI & SMTP', 'gallery-box'); ?></span>
                    </div>
                </div>
                <div class="gbox-aime-banner-actions-col">
                    <div class="mg-aime-feedback" aria-live="polite" style="display:none;"></div>
                    <?php if ($is_active) : ?>
                        <a href="<?php echo esc_url(admin_url('admin.php?page=ai-marketing-expert')); ?>" class="gbox-aime-btn gbox-aime-btn-active">
                            <span class="gbox-aime-btn-icon">✅</span>
                            <span class="gbox-aime-btn-text"><?php esc_html_e('AI Marketing Expert', 'gallery-box'); ?></span>
                            <span class="gbox-aime-btn-arrow">&rarr;</span>
                        </a>
                    <?php else : ?>
                        <button type="button" class="gbox-aime-btn gbox-aime-btn-primary mg-aime-install-btn" data-action="<?php echo $is_installed ? 'activate' : 'install'; ?>">
                            <span class="mg-sales-btn-shine"></span>
                            <span class="mg-sales-btn-icon">⚡</span>
                            <span class="mg-sales-btn-text"><?php echo $is_installed ? esc_html__('Activate AI Marketing Expert', 'gallery-box') : esc_html__('Free 1-Click Install', 'gallery-box'); ?></span>
                        </button>
                        <a href="<?php echo esc_url($wporg_url); ?>" target="_blank" rel="noopener noreferrer" class="gbox-aime-btn gbox-aime-btn-secondary">
                            <span class="gbox-aime-btn-icon">🔌</span>
                            <span><?php esc_html_e('View on WordPress.org', 'gallery-box'); ?></span>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php
    }
}

gboxAdminInfo::init();
