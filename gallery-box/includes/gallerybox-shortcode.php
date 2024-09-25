<?php 
/*
 * @link              http://themeforest.digitalkroy.com/gallery-box/
 * @since             1.0.0
 * @package           Gallery box wordpress plugin    
 * description        All gallery output by this shortcode
 *
 * @ Gallery box
 */

 
 //All front display here
if ( ! function_exists( 'galleryBox_shortcode' ) ) : 
function galleryBox_shortcode($atts, $content = null){
ob_start();
global $post;
    $gallery_box = shortcode_atts( array(
        'id'=> '',
    ), $atts );

	//Query args
	$args = array(
		'post_type'  		=>	'gallery_box',
		'post_status'  		=>	'publish',
		'posts_per_page' 	=> 1,
		 'p'                => $gallery_box['id']
		
	);
	//start WP_Query
	$loop= new WP_Query($args);
	 $number=1;

?>
	
	<?php if ($loop -> have_posts() ) :  ?>
	<?php while ( $loop->have_posts()) :  $loop->the_post();
   $post_ID = $post->ID;
//for typography option
   if(get_option('Lightbox_settings')){
$gbox_lightbox = get_option('Lightbox_settings');
}
$gbox_use_typography = isset( $gbox_lightbox['use_typography'] ) ? $gbox_lightbox['use_typography'] : 'yes'; 
	?>
<div id="boxGallery" class="g-box<?php echo esc_attr($post_ID); ?> Gallery-container gallery-box <?php if($gbox_use_typography == 'yes'): ?>gbox-font<?php endif; ?>">
			<!-- Regular images -->
		<?php
//simple image gallery meta
$gbox_simple_imgs = get_post_meta(get_the_ID(), 'simple_imgs', true);
//advance image gallery meta
$gbox_img_main = get_post_meta(get_the_ID(), 'img_main', true);
$image_title =  !empty( $gbox_img_main[0]['image_title'])  ? $gbox_img_main[0]['image_title'] : '';
$image_small =  !empty( $gbox_img_main[0]['image_small'])  ? $gbox_img_main[0]['image_small'] : '';
//Portfolio  gallery meta
$gbox_portfo_main = get_post_meta(get_the_ID(), 'portfo_main', true);
$portfolio_title =  !empty( $gbox_portfo_main[0]['portfolio_title'])  ? $gbox_portfo_main[0]['portfolio_title'] : '';
$port_img =  !empty( $gbox_portfo_main[0]['port_img'])  ? $gbox_portfo_main[0]['port_img'] : '';


//Youtube gallery meta
$gbox_youtube_main = get_post_meta(get_the_ID(),'youtube_main', true);
$you_url =  !empty( $gbox_youtube_main[0]['you_url'])  ? $gbox_youtube_main[0]['you_url'] : '';


//Vimeo gallery meta
$gbox_vimeo_main = get_post_meta(get_the_ID(), 'vimeo_main', true);
$vimeo_url =  !empty( $gbox_vimeo_main[0]['vimeo_url'])  ? $gbox_vimeo_main[0]['vimeo_url'] : '';

//Soundcloud gallery meta
$gbox_Soundcloud_main = get_post_meta(get_the_ID(), 'Soundcloud_main', true);
$sound_id =  !empty( $gbox_Soundcloud_main[0]['sound_id'])  ? $gbox_Soundcloud_main[0]['sound_id'] : '';

//Iframe gallery meta
$gbox_iframe_main = get_post_meta(get_the_ID(), 'iframe_main', true);
$iframe_url =  !empty( $gbox_iframe_main[0]['iframe_url'])  ? $gbox_iframe_main[0]['iframe_url'] : '';

        
		if(!empty($gbox_simple_imgs)){
			// Simple image gallery 
       		 do_action( 'gallery_box_simple_image', get_the_ID() );
		}
        
		if( !empty($image_title) || !empty($image_small) ){
			// Advance image gallery 
            do_action( 'gallery_box_image', get_the_ID() );

        }
		if( !empty($portfolio_title) || !empty($port_img) ){
			// portfolio gallery 
            do_action( 'gallery_box_portfolio', get_the_ID() );

        }

        if(!empty($you_url)){
        //youtube gallery
		do_action( 'gallery_box_youtube', get_the_ID() );
		}
        
		if(!empty($vimeo_url)){
			//vimeo gallery
		  do_action( 'gallery_box_vimeo', get_the_ID() );
		}
        
		if(!empty($sound_id)){
			//Soundcloud gallery
		esc_html_e( 'Soundcloud Gallery No longer Available!!','gbox' );
		} 
        
		if(!empty($iframe_url)){
			//iframe gallery
		  do_action( 'gallery_box_iframe', get_the_ID() );
		}
		?>
		
    </div>
<?php endwhile; ?> 

<?php wp_reset_postdata(); ?>
 <?php else: ?>
 <div class="gbox-error">
 <?php esc_html_e('No gallery item found!','gbox'); ?>
 </div>
 <?php endif; ?>

 <?php 
 $galleryBox = ob_get_clean(); 
return $galleryBox;
}
add_shortcode('GalleryBox','galleryBox_shortcode');
add_shortcode('gallerybox','galleryBox_shortcode');
endif;