<?php 
/*
 * @link              https://wpthemespace.com
 * @since             1.5.6
 * @package           Gallery box wordpress plugin    
 * description        Youtube gallery output
 *
 * @ Gallery box
 */

// Add Youtube gallery script and style
require_once( GALLERY_BOX_PATH. '/includes/all-gallery/youtube-gallery/youtube-script.php');
require_once( GALLERY_BOX_PATH. '/includes/all-gallery/youtube-gallery/youtube-style.php');

function gallery_box_youtube_gallery($id){

	$head = __('Youtube Video gallery now only available in pro version.','gbox');
	$msm = __('please update pro then Your gallery will appear once again without any change.','gbox');

	printf('<div class="upgrade-output"><h2 class="pro-outpot">%s</h2><h5 class="upgrade-txt"> %s</h5><a target="blank" href="'.esc_url('https://wpthemespace.com/product/gallery-box-pro/').'" class="upgrade-btn">'.esc_html('Upgrade Pro').'</a></div>',$head,$msm);

	return;

	//Youtube settings 
	$you_settings = get_post_meta($id, 'you_settings', true);
	//$you_layout =  !empty( $you_settings[0]['you_layout'])  ? $you_settings[0]['you_layout'] : 'masonry';
	//$thumb_height =  !empty( $you_settings[0]['thumb_height'])  ? $you_settings[0]['thumb_height'] : '220';
	//$you_margin =  !empty( $you_settings[0]['you_margin'])  ? $you_settings[0]['you_margin'] : '0';
	$you_thumb_size =  !empty( $you_settings[0]['you_thumb_size'])  ? $you_settings[0]['you_thumb_size'] : 'gbox-medium';
	$youtube_autoplay =  !empty( $you_settings[0]['youtube_auto'])  ? $you_settings[0]['youtube_auto'] : 'default';
	$you_column =  !empty( $you_settings[0]['you_column'])  ? $you_settings[0]['you_column'] : 'default';
	$you_hover =  !empty( $you_settings[0]['you_hover'])  ? $you_settings[0]['you_hover'] : 'default';
	$you_loadmore =  !empty( $you_settings[0]['you_loadmore'])  ? $you_settings[0]['you_loadmore'] : 'default';
	$video_icon =  !empty( $you_settings[0]['video_icon'])  ? $you_settings[0]['video_icon'] : 'show';
	$gbox_you_order =  !empty( $you_settings[0]['gbox_you_order'])  ? $you_settings[0]['gbox_you_order'] : 'asc';

	$youtube_group = get_post_meta($id, 'youtube_main', true);
		//Youtube settings options
		if( get_option('youtube_style') ){
			$gbyoutube = get_option('youtube_style');
		}
		$youtube_column = isset( $gbyoutube['youtube_column'] ) ? $gbyoutube['youtube_column'] : 3;
		$youtube_auto = isset( $gbyoutube['youtube_auto'] ) ? $gbyoutube['youtube_auto'] : 'yes';
        //Youtube loadmore options
        $you_load_button = isset( $gbyoutube['you_load_button'] ) ? $gbyoutube['you_load_button'] :'enable';
        $you_item_number = isset( $gbyoutube['you_item_number'] ) ? $gbyoutube['you_item_number'] :10;
      
		$you_animation = isset( $gbyoutube['you_animation'] ) ? $gbyoutube['you_animation'] : 'ehover13'; //end setting options

//youtube column
	if( $you_column != 'default' ){
		$youtube_column = $you_column;
	}else{
		$youtube_column = $youtube_column;
	}
//youtube video autoplay
	if( $youtube_autoplay != 'default' ){
		$gboxyou_autoplay = $youtube_autoplay;
	}else{
		$gboxyou_autoplay = $youtube_auto;
	}
//youtube video hover animation
	if( $you_hover != 'default' ){
		$you_animation = $you_hover;
	}else{
		$you_animation = $you_animation;
	}

// galery script and style
 do_action( 'gbox_youtube_script', $id );
 do_action( 'gbox_youtube_style', $id );
 
if( $gbox_you_order == 'desc' ){
 $youtube_group = array_reverse($youtube_group);
}
	foreach ( (array) $youtube_group as $key => $youtube_main ):
		$You_title = !empty( $youtube_main['you_title']) ? $youtube_main['you_title'] : __('Youtube gallery','gbox');
		$you_caption = !empty( $youtube_main['You_caption']) ? $youtube_main['You_caption'] : $You_title;
		$you_image = isset($youtube_main['you_image_id']) ? wp_get_attachment_image_src($youtube_main['you_image_id'], $you_thumb_size ):'' ;
        
        //Add new youtube url
		$you_url = isset( $youtube_main['you_url']) ? $youtube_main['you_url'] :''; 
		$you_width = !empty( $youtube_main['you_width'])  ? $youtube_main['you_width'] :'600'; 
		$you_height = !empty( $youtube_main['you_height']) ? $youtube_main['you_height'] :'400'; 
		$youtube_button = !empty( $youtube_main['youtube_button']) ? $youtube_main['youtube_button'] :__('Show video','gbox'); 

        if($you_url){
        $you_id = get_gbox_youtube_id($you_url);
        }else{
        $you_id = '';
        }

        
		?>
		

		<div class="gcolumn-<?php echo esc_attr($youtube_column); ?> you-gallery you-gallery<?php echo esc_attr($id); ?> hover <?php echo esc_attr($you_animation); ?> gb-masonry<?php echo esc_attr($id); ?>">
		<div class="you-margin gbox-youtube">
			<a class="gb-light youlight" <?php if( $gboxyou_autoplay == 'yes' ): ?>data-autoplay="true"<?php endif; ?> data-vbtype="video" href="<?php echo esc_url($you_url); ?>" data-gall="yougallery<?php echo esc_attr($id); ?>" title="<?php echo esc_attr($you_caption); ?>">
			<?php if ($you_image): ?>
			<img src="<?php echo esc_url($you_image[0]);?>" 
			 alt="<?php the_title(); ?>" title="<?php echo esc_attr($you_caption); ?>" />
			 <?php else: ?>
			 <img src="<?php echo esc_url('//img.youtube.com/vi/'.$you_id.'/hqdefault.jpg');?>" 
			 alt="<?php the_title(); ?>" title="<?php echo esc_attr($you_caption); ?>" />
			 <?php endif; ?>
			 <?php if( $video_icon == 'show' ): ?>
			 <div class="play-btn"><i class="fa fa-play-circle-o"></i></div>
			<?php endif; ?>
				<div class="overlay you-overlay">
					<h2 class="youtube-title"><?php echo esc_html($You_title); ?></h2>
					<button class="info youtube-btn">
						<?php echo esc_html($youtube_button); ?>
					</button>
				</div>
			</a>
		</div>
		</div>
		<?php
		endforeach;

}
add_action( 'gallery_box_youtube', 'gallery_box_youtube_gallery' );