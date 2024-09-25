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
		$head = __('Vimeo Video gallery now only available in pro version.','gbox');
	$msm = __('please update pro then Your gallery will appear once again without any change.','gbox');

	printf('<div class="upgrade-output"><h2 class="pro-outpot">%s</h2><h5 class="upgrade-txt"> %s</h5><a target="blank" href="'.esc_url('https://wpthemespace.com/product/gallery-box-pro/').'" class="upgrade-btn">'.esc_html('Upgrade Pro').'</a></div>',$head,$msm);

	return;


$vimeo_group = get_post_meta(get_the_ID(), 'vimeo_main', true);

	//Vimeo settings 
	$vimeo_settings = get_post_meta($id, 'vimeo_settings', true);
	//$vimeo_layout =  !empty( $vimeo_settings[0]['vimeo_layout'])  ? $vimeo_settings[0]['vimeo_layout'] : 'masonry';
	//$thumb_height =  !empty( $vimeo_settings[0]['thumb_height'])  ? $vimeo_settings[0]['thumb_height'] : '220';
	$vimeo_thumb_size =  !empty( $vimeo_settings[0]['vimeo_thumb_size'])  ? $vimeo_settings[0]['vimeo_thumb_size'] : 'gbox-medium';
	$vimeo_auto =  !empty( $vimeo_settings[0]['vimeo_auto'])  ? $vimeo_settings[0]['vimeo_auto'] : 'default';
	$vimeo_column_set =  !empty( $vimeo_settings[0]['vimeo_column'])  ? $vimeo_settings[0]['vimeo_column'] : 'default';
	$vimeo_hover =  !empty( $vimeo_settings[0]['vimeo_hover'])  ? $vimeo_settings[0]['vimeo_hover'] : 'default';
	//$vimeo_margin =  !empty( $vimeo_settings[0]['vimeo_margin'])  ? $vimeo_settings[0]['vimeo_margin'] : '0';
	$vimeo_loadmore =  !empty( $vimeo_settings[0]['vimeo_loadmore'])  ? $vimeo_settings[0]['vimeo_loadmore'] : 'default';
	$video_icon =  !empty( $vimeo_settings[0]['video_icon'])  ? $vimeo_settings[0]['video_icon'] : 'show';
	$gbox_vimeo_order =  !empty( $vimeo_settings[0]['gbox_vimeo_order'])  ? $vimeo_settings[0]['gbox_vimeo_order'] : 'asc';


	if(get_option('vimeo_style')){
		$gvimeo = get_option('vimeo_style');
	}
        //loadmore options
    $vimeo_load_button = isset( $gvimeo['vimeo_load_button'] ) ? $gvimeo['vimeo_load_button'] :'enable';
    $vimeo_autoplay = isset( $gvimeo['vimeo_autoplay'] ) ? $gvimeo['vimeo_autoplay'] :'yes';
    $vimeo_item_number = isset( $gvimeo['vimeo_item_number'] ) ? $gvimeo['vimeo_item_number'] :10;
        //loadmore end
	$vimeo_column =  isset( $gvimeo['vimeo_column'] )  ? $gvimeo['vimeo_column'] : 3;
	$vimeo_animation = isset( $gvimeo['vimeo_animation'] )  ? $gvimeo['vimeo_animation'] : 'ehover3'; //end setting options

//Vimeo column
	if( $vimeo_column_set != 'default' ){
		$vim_column = $vimeo_column_set;
	}else{
		$vim_column = $vimeo_column;
	}
//Vimeo video autoplay
	if( $vimeo_auto != 'default' ){
		$vimeo_atplay = $vimeo_auto;
	}else{
		$vimeo_atplay = $vimeo_autoplay;
	}
//Vimeo video hover animation
	if( $vimeo_hover != 'default' ){
		$vim_animation = $vimeo_hover;
	}else{
		$vim_animation = $vimeo_animation;
	}

// galery script and style
 do_action( 'gbox_vimeo_script', $id );
 do_action( 'gbox_vimeo_style', $id );

if( $gbox_vimeo_order == 'desc' ){
 $vimeo_group = array_reverse($vimeo_group);
}

foreach ( (array) $vimeo_group as $key => $vimeo_main ):
	$vimeo_title = !empty( $vimeo_main['vimeo_title'])   ? $vimeo_main['vimeo_title'] :__('Vimeo title');
	$vimeo_caption = !empty( $vimeo_main['vimeo_caption'])   ? $vimeo_main['vimeo_caption'] : $vimeo_title;
	$vimeo_image = isset($vimeo_main['vimeo_image_id']) ? wp_get_attachment_image_src($vimeo_main['vimeo_image_id'], $vimeo_thumb_size ):'' ;
	$vimeo_url = !empty( $vimeo_main['vimeo_url'])  ? $vimeo_main['vimeo_url'] :''; 
        
	$vim_width = !empty( $vimeo_main['vim_width'])  ? $vimeo_main['vim_width'] :'600'; 
	$vim_height = !empty( $vimeo_main['vim_height'])  ? $vimeo_main['vim_height'] :'400'; 
	$vimeo_button = !empty( $vimeo_main['vimeo_button'])  ? $vimeo_main['vimeo_button'] :__('Show video','gbox');

//vimeo video id 
	if( $vimeo_url ){
    $vimeo_id = gbox_vimeo_url_id($url = $vimeo_url);
	}else{
    $vimeo_id = '';
	}
   
        
		if(!empty($vimeo_id ) ){
			$arrContextOptions=array(
			    "ssl"=>array(
			        "verify_peer"=>false,
			        "verify_peer_name"=>false,
			    ),
			);
		$vimg_default = unserialize(file_get_contents('http://vimeo.com/api/v2/video/'.$vimeo_id.'.php', false, stream_context_create($arrContextOptions)));
		}else{ 
		$vimg_default ='';
		}
       
		?>

		<div class="gcolumn-<?php echo esc_attr($vim_column); ?> Vimeo-gallery vimeo-gallery<?php echo esc_attr($id); ?> hover <?php echo esc_attr($vim_animation); ?> gb-masonry<?php echo esc_attr($id); ?>">
		<div class="gbox-margin<?php echo esc_attr($id); ?>">
		<a class="gb-light" <?php if( $vimeo_atplay == 'yes' ): ?>data-autoplay="true"<?php endif; ?> data-vbtype="video" href="//vimeo.com/<?php echo esc_attr($vimeo_id); ?>" data-gall="vimgallery<?php echo esc_attr($id); ?>" title="<?php echo esc_attr($vimeo_caption); ?>">
	
			<?php if ($vimeo_image): ?>
			<img src="<?php echo esc_url($vimeo_image[0]);?>" alt="<?php echo esc_attr($vimeo_caption);?>" title="<?php echo esc_attr($vimeo_caption);?>" />
			 <?php elseif(!empty($vimg_default[0])): ?>
			 <img src="<?php echo esc_url($vimg_default[0]['thumbnail_large']);?>" alt="<?php echo esc_attr($vimeo_caption);?>" title="<?php echo esc_attr($vimeo_caption);?>" />
			 <?php else: ?>
			 	<img src="<?php echo  GALLERY_BOX_URL.'images/image-small.jpg'; ?>" alt="<?php echo esc_attr('default images');?>" title="<?php echo esc_attr('default images');?>" />
			 <?php endif; ?>
			 <?php if( $video_icon == 'show' ): ?>
			 <div class="play-btn"><i class="fa fa-play-circle-o"></i></div>
			<?php endif; ?>
		<div class="overlay">
			<h2><?php echo esc_html($vimeo_title); ?></h2>
			<button class="info">
				<?php echo esc_html($vimeo_button); ?>
			</button>
		</div>
		</a>
		</div>
		</div><!--# Vimeo gallery -->
		
		<?php
		endforeach;

}
add_action( 'gallery_box_vimeo', 'gallery_box_vimeo_gallery' );