<?php 
/*
 * @link              http://digitalkroy.com/click-to-top/
 * @since             1.0.0
 * @package           gallery box
 * description        gallery box visual composer support
 *
 * @link http://codex.wordpress.org/Function_Reference/register_post_type
 * @wordpress-plugin
 */
if ( ! function_exists( 'gallery_box_visual_term_optionss' ) ) : 
function gallery_box_visual_term_optionss() {
    $gposts =  get_posts(array(
	'post_type'   => 'gallery_box',
    'post_status'      => 'publish',
	'posts_per_page'   => -1,
	'suppress_filters' => true
));
        $tinyMCE_list = array();
		if($gposts) :
        foreach ($gposts as $gpost) :
			$post_ID = $gpost->ID;
			if(!empty($gpost->post_title)){
			$post_title = $gpost->post_title;
			}else{ 
			$post_title = 'Untitled gallery id -'.$post_ID ;
			}
			$tinyMCE_list[$post_title ] = $post_ID;
        endforeach;
		else:
		$tinyMCE_list['No gallery found'] = '';
		endif;
        $post_ID = $tinyMCE_list; 
		 
    return $post_ID; 
}
endif;
add_action( 'vc_before_init', 'Gallery_box_integrateWithVC' );
function Gallery_box_integrateWithVC() {
   vc_map( array(
      "name" => __( "Gallery Box", "gbox" ),
	  "description" => __( "Publish your gallery from Gallery Box", "gbox" ),
      "base" => "GalleryBox",
      "class" => "",
      "category" => __( "Content", "gbox"),
     "icon" => plugin_dir_url( dirname( __FILE__ ) ) ."/images/galleryBox-icon.png",
      "params" => array(
        array(
            "type" => "dropdown",
            "class" => "",
            "heading" => __( "Select your gallery", "ppc" ),
            "param_name" => "id",
           'value' => gallery_box_visual_term_optionss(),
            "description" => __( "First create gallery in Gallery Box menu and then select gallery for publish .", "ppc" )
         )
      )
   ) );
}