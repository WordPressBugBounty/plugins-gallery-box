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

    $parsed_url = parse_url($url);
    if (!is_array($parsed_url) || !isset($parsed_url['host'])) {
        return false;
    }
    if (strcasecmp($parsed_url['host'], 'youtu.be') === 0)
    {
        #### (dontcare)://youtu.be/<video id>
        $video_id = isset($parsed_url['path']) ? substr($parsed_url['path'], 1) : false;
    }
    elseif (strcasecmp($parsed_url['host'], 'www.youtube.com') === 0)
    {
        if (isset($parsed_url['query']))
        {
            $query_params = array();
            parse_str($parsed_url['query'], $query_params);
            if (isset($query_params['v']))
            {
                #### (dontcare)://www.youtube.com/(dontcare)?v=<video id>
                $video_id = $query_params['v'];
            }
        }
        if ($video_id == false && isset($parsed_url['path']))
        {
            $path_parts = explode('/', substr($parsed_url['path'], 1));
            if (isset($path_parts[0]) && in_array($path_parts[0], array('e', 'embed', 'v')) && isset($path_parts[1]))
            {
                #### (dontcare)://www.youtube.com/(whitelist)/<video id>
                $video_id = $path_parts[1];
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
    $connected = fsockopen("www.vimeo.com", 443, $errno, $errstr, 5);
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
    $gbox_untitle = esc_html__('Untitled gallery id','gallery-box');
    $options['0'] = __('Select','gallery-box');
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
