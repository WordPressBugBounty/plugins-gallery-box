<?php 
/*
 * @link              https://wpthemespace.com
 * @since             1.5.6
 * @package           Gallery box wordpress plugin    
 * description        Vimeo gallery dynamic style 
 *
 * @ Gallery box
 */

function gbox_vimeo_gallery_style($id){
	//simple gallery meta
$vimeo_settings = get_post_meta($id, 'vimeo_settings', true);
$vimeo_layout =  !empty( $vimeo_settings[0]['vimeo_layout'])  ? $vimeo_settings[0]['vimeo_layout'] : 'masonry';
$thumb_height =  !empty( $vimeo_settings[0]['thumb_height'])  ? $vimeo_settings[0]['thumb_height'] : '220';
//$you_thumb_size =  !empty( $vimeo_settings[0]['you_thumb_size'])  ? $vimeo_settings[0]['you_thumb_size'] : 'gbox-medium';
//$youtube_auto =  !empty( $vimeo_settings[0]['youtube_auto'])  ? $vimeo_settings[0]['youtube_auto'] : 'default';
//$you_column =  !empty( $vimeo_settings[0]['you_column'])  ? $vimeo_settings[0]['you_column'] : 'default';
//$you_hover =  !empty( $vimeo_settings[0]['you_hover'])  ? $vimeo_settings[0]['you_hover'] : 'default';
$vimeo_margin =  !empty( $vimeo_settings[0]['vimeo_margin'])  ? $vimeo_settings[0]['vimeo_margin'] : '0';
$vimeo_loadmore =  !empty( $vimeo_settings[0]['vimeo_loadmore'])  ? $vimeo_settings[0]['vimeo_loadmore'] : 'default';



 //Vimeo gallery options
 if( get_option('vimeo_style') ){
$gvimeo = get_option('vimeo_style');
}
$vimeo_title_back = isset( $gvimeo['vimeo_title_back'] ) ? $gvimeo['vimeo_title_back'] : '#000000';
$vimeo_border = isset( $gvimeo['vimeo_border'] ) ? $gvimeo['vimeo_border'] : 0; 
$vimeo_border_color = isset( $gvimeo['vimeo_border_color'] ) ? $gvimeo['vimeo_border_color'] :'#ffffff'; 
$vimeo_border_type = isset( $gvimeo['vimeo_border_type'] ) ? $gvimeo['vimeo_border_type'] :'solid';  
$vimeo_title_opacity = isset( $gvimeo['vimeo_title_opacity'] ) ? $gvimeo['vimeo_title_opacity'] : 50; 
$vimeo_title_color = isset( $gvimeo['vimeo_title_color'] ) ? $gvimeo['vimeo_title_color'] : '#ffffff'; 
$vimeo_title_font = isset( $gvimeo['vimeo_title_font'] ) ? $gvimeo['vimeo_title_font'] : 17 ;
$vimeo_title_transform = isset( $gvimeo['vimeo_title_transform'] ) ? $gvimeo['vimeo_title_transform'] :'uppercase' ;
$vimeo_title_padding = isset( $gvimeo['vimeo_title_padding'] ) ? $gvimeo['vimeo_title_padding'] :20;
$vimeo_btn_font = isset( $gvimeo['vimeo_btn_font'] ) ? $gvimeo['vimeo_btn_font'] :14 ;
$vimeo_btn_color = isset( $gvimeo['vimeo_btn_color'] ) ? $gvimeo['vimeo_btn_color'] :'#ffffff' ;
$vimeo_btn_border = isset( $gvimeo['vimeo_btn_border'] ) ? $gvimeo['vimeo_btn_border'] :'#ffffff' ;
//since 2.3.1 load more options
$vimeo_load_button = isset( $gvimeo['vimeo_load_button'] ) ? $gvimeo['vimeo_load_button'] :'enable';
$vimeo_item_number = isset( $gvimeo['vimeo_item_number'] ) ? $gvimeo['vimeo_item_number'] :10;

$vimeo_load_position = isset( $gvimeo['vimeo_load_position'] ) ? $gvimeo['vimeo_load_position'] :'full';
$vimeo_load_color = isset( $gvimeo['vimeo_load_color'] ) ? $gvimeo['vimeo_load_color'] :'#000000';
$vimeo_load_bgcolor = isset( $gvimeo['vimeo_load_bgcolor'] ) ? $gvimeo['vimeo_load_bgcolor'] :'#cccccc';
$vimeo_load_color_hover = isset( $gvimeo['vimeo_load_color_hover'] ) ? $gvimeo['vimeo_load_color_hover'] :'#ffffff';
$vimeo_load_bgcolor_hover = isset( $gvimeo['vimeo_load_bgcolor_hover'] ) ? $gvimeo['vimeo_load_bgcolor_hover'] :'#555555';






//load more button
        if($vimeo_loadmore !=='default'){ 
            $vimeo_loadbtn = $vimeo_loadmore;
        }else{ 
            $vimeo_loadbtn = $vimeo_load_button;
        }

?>
<style type="text/css">
<?php if( $vimeo_layout == 'fixed' ): ?>
	#boxGallery .vimeo-gallery<?php echo esc_attr($id); ?> img{
		height: <?php echo esc_attr($thumb_height); ?>px;
	}
<?php endif; ?>

<?php if( !empty($vimeo_margin) ): ?>
	#boxGallery .gbox-margin<?php echo esc_attr($id); ?>{
		position: relative;
	}
	#boxGallery .gbox-margin<?php echo esc_attr($id); ?>{
		margin:0 <?php echo esc_attr($vimeo_margin); ?>px <?php echo esc_attr($vimeo_margin); ?>px 0 ;
		overflow:hidden;
	}
<?php endif; ?>
<?php if( $vimeo_border > 0 ): ?>
	#boxGallery .vimeo-gallery<?php echo esc_attr($id); ?>{
		border:<?php echo esc_attr($vimeo_border); ?>px <?php echo esc_attr($vimeo_border_type); ?> <?php echo esc_attr($vimeo_border_color); ?> ;
	}
<?php endif; ?>
	#boxGallery .vimeo-gallery<?php echo esc_attr($id); ?> h2{
		color: <?php echo esc_attr( $vimeo_title_color ); ?>;
		font-size: <?php echo esc_attr( $vimeo_title_font ); ?>px;
		text-transform: <?php echo esc_attr( $vimeo_title_transform ); ?>;
		padding: <?php echo esc_attr( $vimeo_title_padding ); ?>px;

	}
	#boxGallery .vimeo-gallery<?php echo esc_attr($id); ?> h2:before{
		background-color: <?php echo esc_attr( $vimeo_title_back ); ?>;
		opacity: 0.<?php echo esc_attr( $vimeo_title_opacity ); ?>;

	}
	#boxGallery .vimeo-gallery<?php echo esc_attr($id); ?>.hover button.info,
	#boxGallery .vimeo-gallery<?php echo esc_attr($id); ?>.hover .gbox-icon i{
			color: <?php echo esc_attr($vimeo_btn_color); ?>;
	    	border: 1px solid <?php echo esc_attr($vimeo_btn_border); ?>;
	    	font-size: <?php echo esc_attr($vimeo_btn_font); ?>px;
		}
<?php if( $vimeo_loadbtn == 'enable' ): ?>
	<?php if( $vimeo_load_position != 'full' ): ?>
		#gbload-btn<?php echo esc_attr($id); ?>{
			text-align:<?php echo esc_attr($vimeo_load_position); ?>;
		}
	<?php else:; ?>
		#load-more<?php echo esc_attr($id); ?>{
			display: block;
		}
		#load-more<?php echo esc_attr($id); ?>{
			display: block;
			width: 100%
		}
	<?php endif; ?>
		#load-more<?php echo esc_attr($id); ?>{
			background: <?php echo esc_attr($vimeo_load_bgcolor); ?>;
			color: <?php echo esc_attr($vimeo_load_color); ?>;
		}
		#load-more<?php echo esc_attr($id); ?>:hover{
			background: <?php echo esc_attr($vimeo_load_bgcolor_hover); ?>;
			color: <?php echo esc_attr($vimeo_load_color_hover); ?>;
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
add_action('gbox_vimeo_style', 'gbox_vimeo_gallery_style');