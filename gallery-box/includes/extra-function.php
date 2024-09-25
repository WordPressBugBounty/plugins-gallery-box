<?php 
/*
 * @link              https://wpthemespace.com
 * @since             1.0.0
 * @package           Gallery box wordpress plugin    
 * description        Extra function for gallery box
 *
 * @ Gallery box
 */

//function for youtube video id
 // Youtube video id filtring 
if ( ! function_exists( 'get_gbox_youtube_id' ) ) :
function get_gbox_youtube_id($url)
{
    $video_id = false;

    $url = parse_url($url);
    if (strcasecmp($url['host'], 'youtu.be') === 0)
    {
        #### (dontcare)://youtu.be/<video id>
        $video_id = substr($url['path'], 1);
    }
    elseif (strcasecmp($url['host'], 'www.youtube.com') === 0)
    {
        if (isset($url['query']))
        {
            parse_str($url['query'], $url['query']);
            if (isset($url['query']['v']))
            {
                #### (dontcare)://www.youtube.com/(dontcare)?v=<video id>
                $video_id = $url['query']['v'];
            }
        }
        if ($video_id == false)
        {
            $url['path'] = explode('/', substr($url['path'], 1));
            if (in_array($url['path'][0], array('e', 'embed', 'v')))
            {
                #### (dontcare)://www.youtube.com/(whitelist)/<video id>
                $video_id = $url['path'][1];
            }
        }
    }

    return $video_id;
}
endif;

// Vimeo id filter by this function
 
if ( ! function_exists( 'gbox_vimeo_url_id' ) ) :
    function gbox_vimeo_url_id($url = '') {
        $regs = array();
        $id = '';
        if (preg_match('%^https?:\/\/(?:www\.|player\.)?vimeo.com\/(?:channels\/(?:\w+\/)?|groups\/([^\/]*)\/videos\/|album\/(\d+)\/video\/|video\/|)(\d+)(?:$|\/|\?)(?:[?]?.*)$%im', $url, $regs)) {
            $id = $regs[3];
        }
        return $id;
    
    }

endif;



function isVimeoConnected()
{
    // use 80 for http or 443 for https protocol
    $connected = @fsockopen("https://www.vimeo.com/", 443);
    if ($connected){
        fclose($connected);
        return true; 
    }
    return false;
}

/**
 * Get Post List
 * return array
 */
function gbox_gallery_list( $post_type = 'gallery_box' ){
    $options = array();
    $gbox_untitle = esc_html__('Untitled gallery id','gbox');
    $options['0'] = __('Select','magical-addons-for-elementor');
   // $perpage = wooaddons_get_option( 'loadproductlimit', 'wooaddons_others_tabs', '20' );
    $all_post = array( 'posts_per_page' => -1, 'post_type'=> $post_type );
    $post_terms = get_posts( $all_post );
    if ( ! empty( $post_terms ) && ! is_wp_error( $post_terms ) ){
        foreach ( $post_terms as $term ) {
            if(empty($term->post_title)){
                $options[ $term->ID ] = $gbox_untitle.'-'.$term->ID;
            }else{
                $options[ $term->ID ] = $term->post_title;
            }
        }
        return $options;
    }
}