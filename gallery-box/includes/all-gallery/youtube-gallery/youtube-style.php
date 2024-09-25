<?php 
/*
 * @link              https://wpthemespace.com
 * @since             1.5.6
 * @package           Gallery box wordpress plugin    
 * description        Youtube gallery dynamic style 
 *
 * @ Gallery box
 */

//Youtube gallery style
function gbox_youtube_gallery_style($id){
	//simple gallery meta
$you_settings = get_post_meta($id, 'you_settings', true);
$you_layout =  !empty( $you_settings[0]['you_layout'])  ? $you_settings[0]['you_layout'] : 'masonry';
$thumb_height =  !empty( $you_settings[0]['thumb_height'])  ? $you_settings[0]['thumb_height'] : '220';
//$you_thumb_size =  !empty( $you_settings[0]['you_thumb_size'])  ? $you_settings[0]['you_thumb_size'] : 'gbox-medium';
//$youtube_auto =  !empty( $you_settings[0]['youtube_auto'])  ? $you_settings[0]['youtube_auto'] : 'default';
//$you_column =  !empty( $you_settings[0]['you_column'])  ? $you_settings[0]['you_column'] : 'default';
//$you_hover =  !empty( $you_settings[0]['you_hover'])  ? $you_settings[0]['you_hover'] : 'default';
$you_margin =  !empty( $you_settings[0]['you_margin'])  ? $you_settings[0]['you_margin'] : '0';
$you_loadmore =  !empty( $you_settings[0]['you_loadmore'])  ? $you_settings[0]['you_loadmore'] : 'default';



 //Youtube gallery options
 if($gbyoutube = get_option('youtube_style')){
$gbyoutube = get_option('youtube_style');
}
//$youtube_column = isset( $gbyoutube['youtube_column'] ) ? $gbyoutube['youtube_column'] : 'Three'; 
$you_title_back = isset( $gbyoutube['you_title_back'] ) ? $gbyoutube['you_title_back'] : '#000000';
$youtube_border = isset( $gbyoutube['youtube_border'] )? $gbyoutube['youtube_border'] : 0; 
$youtube_border_color = isset( $gbyoutube['youtube_border_color'] ) ? $gbyoutube['youtube_border_color'] :'#ffffff'; 
$youtube_border_type = isset( $gbyoutube['youtube_border_type'] ) ? $gbyoutube['youtube_border_type'] :'solid'; 
$you_title_opacity = isset( $gbyoutube['you_title_opacity'] ) ? $gbyoutube['you_title_opacity'] : 75; 
$you_title_color = isset( $gbyoutube['you_title_color'] ) ? $gbyoutube['you_title_color'] : '#ffffff'; 
$you_title_font = isset( $gbyoutube['you_title_font'] ) ? $gbyoutube['you_title_font'] : 17 ;
$you_title_transform = isset( $gbyoutube['you_title_transform'] ) ? $gbyoutube['you_title_transform'] :'uppercase' ;
$you_title_padding = isset( $gbyoutube['you_title_padding'] ) ? $gbyoutube['you_title_padding'] : 20;
$you_btn_font = isset( $gbyoutube['you_btn_font'] ) ? $gbyoutube['you_btn_font'] : 14;
$you_btn_color = isset( $gbyoutube['you_btn_color'] ) ? $gbyoutube['you_btn_color'] : '#ffffff';
$you_btn_border = isset( $gbyoutube['you_btn_border'] ) ? $gbyoutube['you_btn_border'] : '#ffffff';
//since 2.3.1 load more options
$you_load_button = isset( $gbyoutube['you_load_button'] ) ? $gbyoutube['you_load_button'] :'enable';

$you_load_position = isset( $gbyoutube['you_load_position'] ) ? $gbyoutube['you_load_position'] :'full';
$you_load_color = isset( $gbyoutube['you_load_color'] ) ? $gbyoutube['you_load_color'] :'#000000';
$you_load_bgcolor = isset( $gbyoutube['you_load_bgcolor'] ) ? $gbyoutube['you_load_bgcolor'] :'#cccccc';
$you_load_color_hover = isset( $gbyoutube['you_load_color_hover'] ) ? $gbyoutube['you_load_color_hover'] :'#ffffff';
$you_load_bgcolor_hover = isset( $gbyoutube['you_load_bgcolor_hover'] ) ? $gbyoutube['you_load_bgcolor_hover'] :'#555555';






//load more button
        if($you_loadmore !=='default'){ 
            $you_loadbtn = $you_loadmore;
        }else{ 
            $you_loadbtn = $you_load_button;
        }

?>
<style type="text/css">
<?php if( $you_layout == 'fixed' ): ?>
	#boxGallery .you-gallery<?php echo esc_attr($id); ?> img{
		height: <?php echo esc_attr($thumb_height); ?>px;
	}
<?php endif; ?>

<?php if( !empty($you_margin) ): ?>
	#boxGallery .you-gallery<?php echo esc_attr($id); ?> .you-margin{
		position: relative;
	}
	#boxGallery .you-gallery<?php echo esc_attr($id); ?> .you-margin,#boxGallery .you-gallery<?php echo esc_attr($id); ?> .overlay{
		margin:0 <?php echo esc_attr($you_margin); ?>px <?php echo esc_attr($you_margin); ?>px 0 ;
		overflow:hidden;
	}
<?php endif; ?>
<?php if( $youtube_border > 0 ): ?>
	#boxGallery .you-gallery<?php echo esc_attr($id); ?>{
		border:<?php echo esc_attr($youtube_border); ?>px <?php echo esc_attr($youtube_border_type); ?> <?php echo esc_attr($youtube_border_color); ?> ;
	}
<?php endif; ?>
	#boxGallery .you-gallery<?php echo esc_attr($id); ?> h2{
		color: <?php echo esc_attr( $you_title_color ); ?>;
		font-size: <?php echo esc_attr( $you_title_font ); ?>px;
		text-transform: <?php echo esc_attr( $you_title_transform ); ?>;
		padding: <?php echo esc_attr( $you_title_padding ); ?>px;

	}
	#boxGallery .you-gallery<?php echo esc_attr($id); ?> h2:before{
		background-color: <?php echo esc_attr( $you_title_back ); ?>;
		opacity: 0.<?php echo esc_attr( $you_title_opacity ); ?>;

	}
	#boxGallery .you-gallery<?php echo esc_attr($id); ?>.hover button.info,
	#boxGallery .you-gallery<?php echo esc_attr($id); ?>.hover .gbox-icon i{
			color: <?php echo esc_attr($you_btn_color); ?>;
	    	border: 1px solid <?php echo esc_attr($you_btn_border); ?>;
	    	font-size: <?php echo esc_attr($you_btn_font); ?>px;
		}
<?php if( $you_loadbtn == 'enable' ): ?>
	<?php if( $you_load_position != 'full' ): ?>
		#gbload-btn<?php echo esc_attr($id); ?>{
			text-align:<?php echo esc_attr($you_load_position); ?>;
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
			background: <?php echo esc_attr($you_load_bgcolor); ?>;
			color: <?php echo esc_attr($you_load_color); ?>;
		}
		#load-more<?php echo esc_attr($id); ?>:hover{
			background: <?php echo esc_attr($you_load_color_hover); ?>;
			color: <?php echo esc_attr($you_load_bgcolor_hover); ?>;
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
add_action('gbox_youtube_style', 'gbox_youtube_gallery_style');