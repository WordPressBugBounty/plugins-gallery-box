<?php 
/*
 * @link              https://wpthemespace.com
 * @since             1.5.6
 * @package           Gallery box wordpress plugin    
 * description        Vimeo gallery output
 *
 * @ Gallery box
 */

// Add image gallery script style
require_once( GALLERY_BOX_PATH. '/includes/all-gallery/vimeo-gallery/vimeo-script.php');
require_once( GALLERY_BOX_PATH. '/includes/all-gallery/vimeo-gallery/vimeo-style.php');

function gallery_box_vimeo_gallery($id){
	$head = __('Vimeo Video gallery now only available in pro version.','gallery-box');
	$msm = __('please update pro then Your gallery will appear once again without any change.','gallery-box');

	printf('<div class="upgrade-output"><h2 class="pro-outpot">%s</h2><h5 class="upgrade-txt"> %s</h5><a target="blank" href="'.esc_url('https://wpthemespace.com/product/gallery-box-pro/').'" class="upgrade-btn">'.esc_html('Upgrade Pro').'</a></div>',$head,$msm);

}
add_action( 'gallery_box_vimeo', 'gallery_box_vimeo_gallery' );
