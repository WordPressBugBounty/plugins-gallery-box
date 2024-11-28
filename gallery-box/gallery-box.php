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
 * Version:           1.7.37
 * Author:            Noor alam
 * Author URI:        http://wpthemespace.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       gbox
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
        load_plugin_textdomain('gbox', false, plugin_basename(dirname(__FILE__)) . '/languages');
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
 * Initialize the plugin tracker
 *
 * @return void
 */
function gallery_box_appsero_init_tracker()
{

    if (! class_exists('Appsero\Client')) {
        require_once __DIR__ . '/vendor/appsero/client/src/Client.php';
    }

    $client = new Appsero\Client('ad422d2c-3317-4462-a2df-d8f2c1eb7131', 'Gallery Box', __FILE__);

    // Active insights
    $client->insights()->init();
}

gallery_box_appsero_init_tracker();
