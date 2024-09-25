<?php 
/*
 * @link              https://wpthemespace.com
 * @since             1.5.6
 * @package           Gallery box wordpress plugin    
 * description        iframe gallery output
 *
 * @ Gallery box
 */

// Add image gallery script style
require_once( GALLERY_BOX_PATH. '/includes/all-gallery/iframe-gallery/iframe-script.php');
require_once( GALLERY_BOX_PATH. '/includes/all-gallery/iframe-gallery/iframe-style.php');

function gallery_box_iframe_gallery($id){
$iframe_group = get_post_meta(get_the_ID(), 'iframe_main', true);
$iframe_settings = get_post_meta(get_the_ID(), 'iframe_settings', true);
$iframe_image_size =  !empty( $iframe_settings[0]['iframe_thumb_size'])  ? $iframe_settings[0]['iframe_thumb_size'] : 'gbox-medium';
$iframe_column_set =  !empty( $iframe_settings[0]['iframe_column'])  ? $iframe_settings[0]['iframe_column'] : 'default';
$iframe_hover =  !empty( $iframe_settings[0]['iframe_hover'])  ? $iframe_settings[0]['iframe_hover'] : 'default';
$gbox_iframe_order =  !empty( $iframe_settings[0]['gbox_iframe_order'])  ? $iframe_settings[0]['gbox_iframe_order'] : 'asc';

//$iframe_margin =  !empty( $iframe_settings[0]['iframe_margin'])  ? $iframe_settings[0]['iframe_margin'] : '0';
//$iframe_layout =  !empty( $iframe_settings[0]['iframe_layout'])  ? $iframe_settings[0]['iframe_layout'] : 'masonry';
$iframe_loadmore =  !empty( $iframe_settings[0]['iframe_loadmore'])  ? $iframe_settings[0]['iframe_loadmore'] : 'default';
//$iframe_height =  !empty( $iframe_settings[0]['iframe_height'])  ? $iframe_settings[0]['iframe_height'] : '220';


        
	if($giframe = get_option('iframe_style')){
		$giframe = get_option('iframe_style');
	}
        //loadmore options
        $iframe_load_button = isset( $giframe['iframe_load_button'] ) ? $giframe['iframe_load_button'] :'enable';
        $iframe_item_number = isset( $giframe['iframe_item_number'] ) ? $giframe['iframe_item_number'] :10;
        //loadmore end
		$iframe_column_option = isset( $giframe['iframe_column'] ) ? $giframe['iframe_column'] : 3;
//iframe column set		
	if( $iframe_column_set != 'default' ){
		$iframe_column = $iframe_column_set;
	}else{
		$iframe_column = $iframe_column_option;
	}



		if($iframe_column == 4 ){
		$iframe_defailt_img = GALLERY_BOX_URL.'images/iframe-small.jpg';
		}elseif($iframe_column == 3){
		$iframe_defailt_img = GALLERY_BOX_URL.'images/image-gallery.jpg';
		}else{
		$iframe_defailt_img = GALLERY_BOX_URL.'images/image-large.jpg';
		}		
		$iframe_animation_option = isset( $giframe['iframe_animation'] ) ? $giframe['iframe_animation'] : 'ehover5'; //end setting options
//iframe hover set		
	if( $iframe_hover != 'default' ){
		$iframe_animation = $iframe_hover;
	}else{
		$iframe_animation = $iframe_animation_option;
	}
// galery script and style
 do_action( 'gbox_iframe_script', $id );
 do_action( 'gbox_iframe_style', $id );
 // asc and desc order
if( $gbox_iframe_order == 'desc' ){
 $iframe_group = array_reverse($iframe_group);
}

		//sort($iframe_group);
		foreach ( (array) $iframe_group as $key => $iframe_main ):
		
		$iframe_title = !empty( $iframe_main['iframe_title']) ? $iframe_main['iframe_title'] : __('iframe title','gbox');
		$iframe_caption = !empty( $iframe_main['iframe_caption']) ? $iframe_main['iframe_caption'] : $iframe_title ;
		
		$Iframe_image = isset($iframe_main['Iframe_image_id']) ? wp_get_attachment_image_src($iframe_main['Iframe_image_id'], $iframe_image_size ):'' ;


		$iframe_url = !empty( $iframe_main['iframe_url']) ? $iframe_main['iframe_url'] : get_home_url(); 
		
		$iframe_button = !empty( $iframe_main['iframe_button']) ? $iframe_main['iframe_button'] :__('View site','gbox'); 
		?>
		<div class="gcolumn-<?php echo esc_attr($iframe_column); ?> iframe-gallery iframe-gallery<?php echo esc_attr($id); ?> hover <?php echo esc_attr($iframe_animation); ?> gb-masonry<?php echo esc_attr($id); ?>">
		<div class="gbox-margin gbox-iframe">
			<a class="gb-light youlight" data-vbtype="iframe" href="<?php echo esc_url($iframe_url); ?>" data-gall="iframeg<?php echo esc_attr($id); ?>" title="<?php echo esc_attr($iframe_caption); ?>">
		<?php if ($Iframe_image): ?>
			<img src="<?php echo esc_url($Iframe_image[0]);?>" alt="<?php echo esc_attr($iframe_caption); ?>" title="<?php echo esc_attr($iframe_caption); ?>" />
			 <?php else: ?>
			 <img src="<?php echo esc_url($iframe_defailt_img);?>" alt="<?php echo esc_attr($iframe_caption); ?>" title="<?php echo esc_attr($iframe_caption); ?>" />
		<?php endif; ?>
			<div class="overlay">
				<h2><?php echo esc_html($iframe_title); ?></h2>
				<button class="info">
					<?php echo esc_html($iframe_button); ?>
				</button>
			</div>
		</a>
		</div>
		</div><!--# IFRAME gallery -->
		<?php
		endforeach;
		
}
add_action( 'gallery_box_iframe', 'gallery_box_iframe_gallery' );