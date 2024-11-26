<?php
/*
 * Add inline css 
 *
 * 
 */
if (!function_exists('gbox_admin_inline_css')) :
    function gbox_admin_inline_css()
    {

        global $pagenow;

        if (!in_array($pagenow, array('post-new.php', 'post.php'))) {
            return;
        }

        global $post;
        $post_id = $post->ID;

        //simple image gallery meta
        $gbox_simple_imgs = get_post_meta($post_id, 'simple_imgs', true);
        //advance image gallery meta
        $gbox_img_main = get_post_meta($post_id, 'img_main', true);
        $image_title =  !empty($gbox_img_main[0]['image_title'])  ? $gbox_img_main[0]['image_title'] : '';
        $image_small =  !empty($gbox_img_main[0]['image_small'])  ? $gbox_img_main[0]['image_small'] : '';
        //Portfolio  gallery meta
        $gbox_portfo_main = get_post_meta($post_id, 'portfo_main', true);
        $portfolio_title =  !empty($gbox_portfo_main[0]['portfolio_title'])  ? $gbox_portfo_main[0]['portfolio_title'] : '';
        $port_img =  !empty($gbox_portfo_main[0]['port_img'])  ? $gbox_portfo_main[0]['port_img'] : '';


        //Youtube gallery meta
        $gbox_youtube_main = get_post_meta($post_id, 'youtube_main', true);
        $you_url =  !empty($gbox_youtube_main[0]['you_url'])  ? $gbox_youtube_main[0]['you_url'] : '';


        //Vimeo gallery meta
        $gbox_vimeo_main = get_post_meta($post_id, 'vimeo_main', true);
        $vimeo_url =  !empty($gbox_vimeo_main[0]['vimeo_url'])  ? $gbox_vimeo_main[0]['vimeo_url'] : '';

        //Soundcloud gallery meta
        $gbox_Soundcloud_main = get_post_meta($post_id, 'Soundcloud_main', true);
        $sound_id =  !empty($gbox_Soundcloud_main[0]['sound_id'])  ? $gbox_Soundcloud_main[0]['sound_id'] : '';

        //Iframe gallery meta
        $gbox_iframe_main = get_post_meta($post_id, 'iframe_main', true);
        $iframe_url =  !empty($gbox_iframe_main[0]['iframe_url'])  ? $gbox_iframe_main[0]['iframe_url'] : '';

        $script = '';
?>
        <script type="text/javascript">
            (function($) {
                "use strict";
                $(document).ready(function() {
                    <?php
                    //quick image gallery
                    if (!empty($gbox_simple_imgs)) {
                        echo "$('#m_gallery .cmb-tabs ul li.cmb-tab-simg').addClass('cmb-tab-active');";
                        echo "$('#cmb2-metabox-m_gallery .cmb-tab-panel-gbox_welcome').removeClass('show');";
                        echo "$('#cmb2-metabox-m_gallery .cmb-tab-panel-simg').addClass('show');";
                    }
                    //Advance image gallery
                    if (!empty($image_title) || !empty($image_small)) {
                        echo "$('#m_gallery .cmb-tabs ul li.cmb-tab-image').addClass('cmb-tab-active');";
                        echo "$('#cmb2-metabox-m_gallery .cmb-tab-panel-gbox_welcome').removeClass('show');";
                        echo "$('#cmb2-metabox-m_gallery .cmb-tab-panel-image').addClass('show');";
                    }
                    //portfoli gallery
                    if (!empty($portfolio_title) || !empty($port_img)) {
                        echo "$('#m_gallery .cmb-tabs ul li.cmb-tab-portfolio ').addClass('cmb-tab-active');";
                        echo "$('#cmb2-metabox-m_gallery .cmb-tab-panel-gbox_welcome').removeClass('show');";
                        echo "$('#cmb2-metabox-m_gallery .cmb-tab-panel-portfolio').addClass('show');";
                    }
                    if (!empty($you_url)) {/*
    echo "$('#m_gallery .cmb-tabs ul li.cmb-tab-youtube').addClass('cmb-tab-active');";
    echo "$('#cmb2-metabox-m_gallery .cmb-tab-panel-gbox_welcome').removeClass('show');";
    echo "$('#cmb2-metabox-m_gallery .cmb-tab-panel-youtube').addClass('show');"; 
     */
                    }
                    if (!empty($vimeo_url)) {/*
    echo "$('#m_gallery .cmb-tabs ul li.cmb-tab-vimeo').addClass('cmb-tab-active');";
    echo "$('#cmb2-metabox-m_gallery .cmb-tab-panel-gbox_welcome').removeClass('show');";
    echo "$('#cmb2-metabox-m_gallery .cmb-tab-panel-vimeo').addClass('show');";        
*/
                    }
                    if (!empty($iframe_url)) {
                        echo "$('#m_gallery .cmb-tabs ul li.cmb-tab-iframe').addClass('cmb-tab-active');";
                        echo "$('#cmb2-metabox-m_gallery .cmb-tab-panel-gbox_welcome').removeClass('show');";
                        echo "$('#cmb2-metabox-m_gallery .cmb-tab-panel-iframe').addClass('show');";
                    }
                    ?>
                });
            }(jQuery));
        </script>
<?php

    }
    add_action('admin_footer', 'gbox_admin_inline_css');
endif;


//update noticed 

function prefix_ms_plugin_update_message($file, $plugin)
{
    if (is_multisite() && version_compare($plugin['Version'], $plugin['new_version'], '<')) {
        $wp_list_table = _get_list_table('WP_Plugins_List_Table');
        printf(
            '<tr class="plugin-update-tr"><td colspan="%s" class="plugin-update update-message notice inline notice-warning notice-alt"><div class="update-message"><h4 style="margin: 0; font-size: 14px;">%s</h4>%s</div></td></tr>',
            $wp_list_table->get_column_count(),
            $plugin['Name'],
            wpautop($plugin['upgrade_notice'])
        );
    }
}
add_action('after_plugin_row_wp-gallery-box/gallery-box.php', 'prefix_ms_plugin_update_message', 10, 2);
