<?php 
/*
 * @link              https://wpthemespace.com
 * @since             1.5.6
 * @package           Gallery box wordpress plugin    
 * description        iframe gallery dynamic style 
 *
 * @ Gallery box
 */

function gbox_iframe_gallery_style($id){
	//simple gallery meta

$iframe_settings = get_post_meta(get_the_ID(), 'iframe_settings', true);
$iframe_margin =  !empty( $iframe_settings[0]['iframe_margin'])  ? $iframe_settings[0]['iframe_margin'] : '0';
$iframe_layout =  !empty( $iframe_settings[0]['iframe_layout'])  ? $iframe_settings[0]['iframe_layout'] : 'masonry';
$iframe_loadmore =  !empty( $iframe_settings[0]['iframe_loadmore'])  ? $iframe_settings[0]['iframe_loadmore'] : 'default';
$iframe_height =  !empty( $iframe_settings[0]['iframe_height'])  ? $iframe_settings[0]['iframe_height'] : '220';



 //iframe gallery options
 if( get_option('iframe_style') ){
$giframe = get_option('iframe_style');
}
$iframe_column = isset( $giframe['iframe_column'] ) ? $giframe['iframe_column'] : '3'; 
$iframe_border = isset( $giframe['iframe_border'] ) ? $giframe['iframe_border'] : 0; 
$iframe_border_color = isset( $giframe['iframe_border_color'] ) ? $giframe['iframe_border_color'] :'#ffffff'; 
$iframe_border_type =  isset( $giframe['iframe_border_type'] ) ? $giframe['iframe_border_type'] :'solid'; 
$iframe_title_back = isset( $giframe['iframe_title_back'] ) ? $giframe['iframe_title_back'] : '#000000'; 
$iframe_title_opacity = isset( $giframe['iframe_title_opacity'] ) ? $giframe['iframe_title_opacity'] : 75; 
$iframe_title_color = isset( $giframe['iframe_title_color'] ) ? $giframe['iframe_title_color'] : '#ffffff'; 
$iframe_title_font = isset( $giframe['iframe_title_font'] ) ? $giframe['iframe_title_font'] : 17 ;
$iframe_title_transform = isset( $giframe['iframe_title_transform'] ) ? $giframe['iframe_title_transform'] :'uppercase' ;
$iframe_title_padding = isset( $giframe['iframe_title_padding'] ) ? $giframe['iframe_title_padding'] :20;
$iframe_btn_font = isset( $giframe['iframe_btn_font'] ) ? $giframe['iframe_btn_font'] :14;
$iframe_btn_color = isset( $giframe['iframe_btn_color'] ) ? $giframe['iframe_btn_color'] :'#ffffff';
$iframe_btn_border = isset( $giframe['iframe_btn_border'] ) ? $giframe['iframe_btn_border'] :'#ffffff';
//since 2.3.1 load more options
$iframe_load_button = isset( $giframe['iframe_load_button'] ) ? $giframe['iframe_load_button'] :'enable';

$iframe_load_position = isset( $giframe['iframe_load_position'] ) ? $giframe['iframe_load_position'] :'full';
$iframe_load_color = isset( $giframe['iframe_load_color'] ) ? $giframe['iframe_load_color'] :'#000000';
$iframe_load_bgcolor = isset( $giframe['iframe_load_bgcolor'] ) ? $giframe['iframe_load_bgcolor'] :'#cccccc';
$iframe_load_color_hover = isset( $giframe['iframe_load_color_hover'] ) ? $giframe['iframe_load_color_hover'] :'#ffffff';
$iframe_load_bgcolor_hover = isset( $giframe['iframe_load_bgcolor_hover'] ) ? $giframe['iframe_load_bgcolor_hover'] :'#555555';






//load more button
        if($iframe_loadmore !=='default'){ 
            $iframe_loadbtn = $iframe_loadmore;
        }else{ 
            $iframe_loadbtn = $iframe_load_button;
        }

?>
<style type="text/css">
<?php if( $iframe_layout == 'fixed' ): ?>
	#boxGallery .iframe-gallery<?php echo esc_attr($id); ?> img{
		height: <?php echo esc_attr($iframe_height); ?>px;
	}
<?php endif; ?>

<?php if( !empty($iframe_margin) ): ?>
	#boxGallery .iframe-gallery<?php echo esc_attr($id); ?> .gbox-margin{
		position: relative;
	}
	#boxGallery .iframe-gallery<?php echo esc_attr($id); ?> .gbox-margin,#boxGallery .iframe-gallery<?php echo esc_attr($id); ?> .overlay{
		margin:0 <?php echo esc_attr($iframe_margin); ?>px <?php echo esc_attr($iframe_margin); ?>px 0 ;
		overflow:hidden;
	}
<?php endif; ?>
<?php if( $iframe_border > 0 ): ?>
	#boxGallery .iframe-gallery<?php echo esc_attr($id); ?>{
		border:<?php echo esc_attr($iframe_border); ?>px <?php echo esc_attr($iframe_border_type); ?> <?php echo esc_attr($iframe_border_color); ?> ;
	}
<?php endif; ?>
	#boxGallery .iframe-gallery<?php echo esc_attr($id); ?> h2{
		color: <?php echo esc_attr( $iframe_title_color ); ?>;
		font-size: <?php echo esc_attr( $iframe_title_font ); ?>px;
		text-transform: <?php echo esc_attr( $iframe_title_transform ); ?>;
		padding: <?php echo esc_attr( $iframe_title_padding ); ?>px;

	}
	#boxGallery .iframe-gallery<?php echo esc_attr($id); ?> h2:before{
		background-color: <?php echo esc_attr( $iframe_title_back ); ?>;
		opacity: 0.<?php echo esc_attr( $iframe_title_opacity ); ?>;

	}
	#boxGallery .iframe-gallery<?php echo esc_attr($id); ?>.hover button.info,
	#boxGallery .iframe-gallery<?php echo esc_attr($id); ?>.hover .gbox-icon i{
			color: <?php echo esc_attr($iframe_btn_color); ?>;
	    	border: 1px solid <?php echo esc_attr($iframe_btn_border); ?>;
	    	font-size: <?php echo esc_attr($iframe_btn_font); ?>px;
		}
<?php if( $iframe_loadbtn == 'enable' ): ?>
	<?php if( $iframe_load_position != 'full' ): ?>
		#gbload-btn<?php echo esc_attr($id); ?>{
			text-align:<?php echo esc_attr($iframe_load_position); ?>;
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
			background: <?php echo esc_attr($iframe_load_bgcolor); ?>;
			color: <?php echo esc_attr($iframe_load_color); ?>;
		}
		#load-more<?php echo esc_attr($id); ?>:hover{
			background: <?php echo esc_attr($iframe_load_bgcolor_hover); ?>;
			color: <?php echo esc_attr($iframe_load_color_hover); ?>;
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
add_action('gbox_iframe_style', 'gbox_iframe_gallery_style');