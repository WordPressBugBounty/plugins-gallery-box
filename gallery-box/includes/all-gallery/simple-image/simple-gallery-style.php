<?php 
/*
 * @link              https://wpthemespace.com
 * @since             1.5.6
 * @package           Gallery box wordpress plugin    
 * description        Simple image gallery dynamic style 
 *
 * @ Gallery box
 */

function gbox_simple_gallery_style($id){
	//simple gallery meta
$simg_main = get_post_meta( get_the_ID(), 'simg_main', 1 );
$gbox_simg_layout_type =  !empty( $simg_main[0]['simg_layout_type'])  ? $simg_main[0]['simg_layout_type'] : 'masonry_layout';
$gbox_simg_custom_height =  !empty( $simg_main[0]['simg_custom_height'])  ? $simg_main[0]['simg_custom_height'] : '220';
$simg_img_margin =  !empty( $simg_main[0]['simg_img_margin'])  ? $simg_main[0]['simg_img_margin'] : '0';
$gbox_simg_loadmore =  !empty( $simg_main[0]['simg_loadmore'])  ? $simg_main[0]['simg_loadmore'] : 'default';



if($gbimage = get_option('img_style')){
	$gbimage = get_option('img_style');
}
$img_border = ( isset( $gbimage['img_border'] ) ) ? $gbimage['img_border'] : '0';
$img_border_color = ( isset( $gbimage['img_border_color'] ) ) ? $gbimage['img_border_color'] : '#ffffff';
$img_border_type = ( isset( $gbimage['img_border_type'] ) ) ? $gbimage['img_border_type'] : 'solid';
//loadmore button style options
$default_img_load_button = isset( $gb_image['img_load_button'] ) ? $gb_image['img_load_button'] :'enable';
$gbox_img_load_position = ( isset( $gbimage['img_load_position'] ) ) ? $gbimage['img_load_position'] : 'full'; 
$img_load_color = ( isset( $gbimage['img_load_color'] ) ) ? $gbimage['img_load_color'] : '#000000'; 
$img_load_bgcolor = ( isset( $gbimage['img_load_bgcolor'] ) ) ? $gbimage['img_load_bgcolor'] : '#cccccc'; 
$img_load_color_hover = ( isset( $gbimage['img_load_color_hover'] ) ) ? $gbimage['img_load_color_hover'] : '#ffffff'; 
$img_load_bgcolor_hover = ( isset( $gbimage['img_load_bgcolor_hover'] ) ) ? $gbimage['img_load_bgcolor_hover'] : '##555555'; 

//load more button
        if($gbox_simg_loadmore !=='default'){ 
            $img_load_button = $gbox_simg_loadmore;
        }else{ 
            $img_load_button = $default_img_load_button;
        }

?>
<style type="text/css">
<?php if( $gbox_simg_layout_type == 'normal_layout' ): ?>
	#boxGallery .gb-simple<?php echo esc_attr($id); ?> img{
		height: <?php echo esc_attr($gbox_simg_custom_height); ?>px;
	}
<?php endif; ?>
<?php if( $gbox_simg_layout_type == 'carousel_fixed' ): ?>
	#boxGallery .gbox-carosuel<?php echo esc_attr($id); ?> img{
		height: <?php echo esc_attr($gbox_simg_custom_height); ?>px;
	}
<?php endif; ?>
<?php if( !empty($simg_img_margin) ): ?>
	#boxGallery .gb-simple<?php echo esc_attr($id); ?> img{
		padding:0 <?php echo esc_attr($simg_img_margin); ?>px <?php echo esc_attr($simg_img_margin); ?>px 0 ;
	}
<?php endif; ?>
<?php if( $img_border > 0 ): ?>
	#boxGallery .gb-simple<?php echo esc_attr($id); ?> img{
		border:<?php echo esc_attr($img_border); ?>px <?php echo esc_attr($img_border_type); ?> <?php echo esc_attr($img_border_color); ?> ;
	}
<?php endif; ?>
<?php if( $img_load_button == 'enable' ): ?>
	<?php if( $gbox_img_load_position != 'full' ): ?>
		#gbload-btn<?php echo esc_attr($id); ?>{
			text-align:<?php echo esc_attr($gbox_img_load_position); ?>;
		}
	<?php else:; ?>
		#gbload-btn<?php echo esc_attr($id); ?>{
			display: block;
		}
		#load-more<?php echo esc_attr($id); ?>{
			display: block;
			width: 100%
		}
	<?php endif; ?>
		#load-more<?php echo esc_attr($id); ?>{
			background: <?php echo esc_attr($img_load_bgcolor); ?>;
			color: <?php echo esc_attr($img_load_color); ?>;
		}
		#load-more<?php echo esc_attr($id); ?>:hover{
			background: <?php echo esc_attr($img_load_color_hover); ?>;
			color: <?php echo esc_attr($img_load_bgcolor_hover); ?>;
		}
<?php endif; ?>

 <?php
 $custom_css = get_post_meta($id, 'custom_css', true);
 	if(!empty($custom_css)){
  echo wp_kses_post($custom_css); 
 	}

  ?>


</style>

<?php
}
add_action('gbox_simple_img_style', 'gbox_simple_gallery_style');