<?php 
/*
 * @link              https://wpthemespace.com
 * @since             1.5.6
 * @package           Gallery box wordpress plugin    
 * description        Global hook for gallery box
 *
 * @ Gallery box
 */

// Gallery Box Lightbox 

function gbox_lightbox_active_script($id){
	//All lightbox options
//javascript code so the function need to call in script tag
if(get_option('Lightbox_settings')){
$gbox_lightbox = get_option('Lightbox_settings');
}
$gbox_loader_style = isset( $gbox_lightbox['loader_style'] ) ? $gbox_lightbox['loader_style'] : 'double-bounce'; 	
$gbox_light_border = isset( $gbox_lightbox['light_border'] ) ? $gbox_lightbox['light_border'] : '0'; 	
$gbox_light_bcolor = isset( $gbox_lightbox['light_bcolor'] ) ? $gbox_lightbox['light_bcolor'] : '#d2d2d2'; 	
$gbox_loader_color = isset( $gbox_lightbox['loader_color'] ) ? $gbox_lightbox['loader_color'] : '#b6b6b6'; 	

?>
   //lightbox opions
         $('.gb-light').venobox({
        border: '<?php echo esc_attr($gbox_light_border); ?>px',
        bgcolor: '<?php echo esc_attr($gbox_light_bcolor); ?>',
        spinColor: '<?php echo esc_attr($gbox_loader_color); ?>',  
        spinner: '<?php echo esc_attr($gbox_loader_style); ?>',   
        titlePosition: 'bottom'   
        }); 

<?php

}
add_action( 'gbox_lightbox_active', 'gbox_lightbox_active_script' );