<?php 
/*
 * @link              https://wpthemespace.com
 * @since             1.5.6
 * @package           Gallery box wordpress plugin    
 * description        Advance imaeg gallery dynamic style 
 *
 * @ Gallery box
 */

// Advance image gallery
function gbox_advance_gallery_style($id){
	//simple gallery meta
$settings_main = get_post_meta($id, 'settings_main', true);
$gbox_layout_type =  !empty( $settings_main[0]['layout_type'])  ? $settings_main[0]['layout_type'] : 'masonry';
$img_custom_height =  !empty( $settings_main[0]['img_custom_height'])  ? $settings_main[0]['img_custom_height'] : '220';
$uniqe_loadmore =  !empty( $settings_main[0]['uniqe_loadmore'])  ? $settings_main[0]['uniqe_loadmore'] : 'default';
$img_right_margin =  !empty( $settings_main[0]['img_right_margin'])  ? $settings_main[0]['img_right_margin'] : '0';



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
$img_load_bgcolor_hover = ( isset( $gbimage['img_load_bgcolor_hover'] ) ) ? $gbimage['img_load_bgcolor_hover'] : '#555555';
//header style
$img_title_back = ( isset( $gbimage['img_title_back'] ) ) ? $gbimage['img_title_back'] : '#000000';
$img_title_opacity = ( isset( $gbimage['img_title_opacity'] ) ) ? $gbimage['img_title_opacity'] : '5';
$img_title_color = ( isset( $gbimage['img_title_color'] ) ) ? $gbimage['img_title_color'] : '#ffffff';
$img_title_font = ( isset( $gbimage['img_title_font'] ) ) ? $gbimage['img_title_font'] : '17';
$img_title_transform = ( isset( $gbimage['img_title_transform'] ) ) ? $gbimage['img_title_transform'] : 'uppercase';
$img_title_padding = ( isset( $gbimage['img_title_padding'] ) ) ? $gbimage['img_title_padding'] : '20';
//image button 
$img_btn_font = ( isset( $gbimage['img_btn_font'] ) ) ? $gbimage['img_btn_font'] : '14';
$img_btn_color = ( isset( $gbimage['img_btn_color'] ) ) ? $gbimage['img_btn_color'] : '#ffffff';
$img_btn_border = ( isset( $gbimage['img_btn_border'] ) ) ? $gbimage['img_btn_border'] : '#ffffff';

//load more button
        if($uniqe_loadmore !=='default'){ 
            $img_load_button = $uniqe_loadmore;
        }else{ 
            $img_load_button = $default_img_load_button;
        }

?>
<style type="text/css">
<?php if( $gbox_layout_type == 'n_gallery' ): ?>
	#boxGallery .adimg<?php echo esc_attr($id); ?> img{
		height: <?php echo esc_attr($img_custom_height); ?>px;
	}
<?php endif; ?>

<?php if( !empty($img_right_margin) ): ?>
	#boxGallery .gbox-margin<?php echo esc_attr($id); ?>{
		margin:0 <?php echo esc_attr($img_right_margin); ?>px <?php echo esc_attr($img_right_margin); ?>px 0 ;
	}
<?php endif; ?>
<?php if( $img_border > 0 ): ?>
	#boxGallery .adimg<?php echo esc_attr($id); ?> img{
		border:<?php echo esc_attr($img_border); ?>px <?php echo esc_attr($img_border_type); ?> <?php echo esc_attr($img_border_color); ?> ;
	}
<?php endif; ?>
	#boxGallery .adimg<?php echo esc_attr($id); ?> h2{
		color: <?php echo esc_attr( $img_title_color ); ?>;
		font-size: <?php echo esc_attr( $img_title_font ); ?>px;
		text-transform: <?php echo esc_attr( $img_title_transform ); ?>;
		padding: <?php echo esc_attr( $img_title_padding ); ?>px;

	}
	#boxGallery .adimg<?php echo esc_attr($id); ?> h2:before{
		background-color: <?php echo esc_attr( $img_title_back ); ?>;
		opacity: 0.<?php echo esc_attr( $img_title_opacity ); ?>;

	}
	#boxGallery .adimg<?php echo esc_attr($id); ?> .hover button.info,
	#boxGallery .adimg<?php echo esc_attr($id); ?> .hover .gbox-icon i{
			color: <?php echo esc_attr($img_btn_color); ?>;
	    	border: 1px solid <?php echo esc_attr($img_btn_border); ?>;
	    	font-size: <?php echo esc_attr($img_btn_font); ?>px;
		}
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
add_action('gbox_advance_img_style', 'gbox_advance_gallery_style');