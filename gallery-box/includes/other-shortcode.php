<?php
/*
*
* Other shortcode for Gallery Box 
*
*
*/

//single Youtube video shortcode
if ( ! function_exists( 'n_single_youtube_gbox' ) ) : 
function n_single_youtube_gbox( $atts, $content = null ) {
    $g_youtube = shortcode_atts( array(
        'id' => 'CXkl1FgeM7M',
        'height' => 300,
        'width' => 800,
        'caption' => __('simple Youtube video','gbox')
       
    ), $atts );
	$youtube_url='//youtu.be/'.$g_youtube['id'];
	$youtube_img_url='//img.youtube.com/vi/'.$g_youtube['id'].'/0.jpg';
	return '<div class="gallery-box"><a class="gclick" href="'.esc_url($youtube_url).'" data-poptrox="youtube,'.esc_attr($g_youtube['width']).'x'.esc_attr($g_youtube['height']).'"><img src="'.esc_url($youtube_img_url).'"  width="'.esc_attr($g_youtube['width']).'" height="'.esc_attr($g_youtube['height']).'" alt="'.esc_attr($g_youtube['caption']).'" title="'.esc_attr($g_youtube['caption']).'" /></a></div>';
}
add_shortcode('gbox_youtube','n_single_youtube_gbox');
endif;

//single Vimeo video shortcode
if ( ! function_exists( 'n_single_vimeo_gbox' ) ) : 
function n_single_vimeo_gbox( $atts, $content = null ) {
    $g_vimeo = shortcode_atts( array(
        'id' => '121840700',
        'height' => 400,
        'width' => 800,
        'caption' => __('simple Vimeo video','gbox')
    ), $atts );
	$vimeo_url='//vimeo.com/'.$g_vimeo['id'];
	$vimeo_img_url='//i.vimeocdn.com/video/'.$g_vimeo['id'].'_640.jpg';
	return '<div class="gallery-box"><a class="gclick" href="'.esc_url($vimeo_url).'" data-poptrox="vimeo,'.esc_attr($g_vimeo['width']).'x'.esc_attr($g_vimeo['height']).'"><img src="'.esc_url($vimeo_img_url).'"  width="'.esc_attr($g_vimeo['width']).'" height="'.esc_attr($g_vimeo['height']).'" alt="'.esc_attr($g_vimeo['caption']).'" title="'.esc_attr($g_vimeo['caption']).'" /></a></div>';
}
add_shortcode('gbox_vimeo','n_single_vimeo_gbox');
endif;