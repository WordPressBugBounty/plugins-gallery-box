<?php 
/*
 * @link              https://wpthemespace.com
 * @since             1.5.6
 * @package           Gallery box wordpress plugin    
 * description        Simple gallery images output
 *
 * @ Gallery box
 */

// Add image gallery script and script
require_once( GALLERY_BOX_PATH. '/includes/all-gallery/simple-image/simple-gallery-script.php');
require_once( GALLERY_BOX_PATH. '/includes/all-gallery/simple-image/simple-gallery-style.php');


function gallery_box_simple_image_gallery($id){
	//metabox value

	$gbox_simple_imgs = get_post_meta($id, 'simple_imgs', true);

$simg_main = get_post_meta( get_the_ID(), 'simg_main', 1 );
$gbox_simg_layout_type =  !empty( $simg_main[0]['simg_layout_type'])  ? $simg_main[0]['simg_layout_type'] : 'masonry_layout';
$gbox_simg_column =  !empty( $simg_main[0]['simg_column'])  ? $simg_main[0]['simg_column'] : 'default';
$simg_img_size =  !empty( $simg_main[0]['simg_img_size'])  ? $simg_main[0]['simg_img_size'] : 'gbox-medium';
$gbox_simg_lightbox =  !empty( $simg_main[0]['simg_lightbox'])  ? $simg_main[0]['simg_lightbox'] : 'light_show';


		//setting options
if($gimage = get_option('img_style')){
	$gimage = get_option('img_style');
}
	$default_img_column = ( isset( $gimage['img_column'] ) ) ? $gimage['img_column'] : 3;
    

		


//Lightbox options
if(get_option('Lightbox_settings')){
	$gbox_lightbox = get_option('Lightbox_settings');
}
$gbox_show_arrow = isset( $gbox_lightbox['show_arrow'] ) ? $gbox_lightbox['show_arrow'] : 'yes'; 


/*image column setup*/
    if($gbox_simg_column !== 'default'){ 
        $img_column = $gbox_simg_column;
    }else{ 
        $img_column = $default_img_column;
    }
     

?>



<?php	
 do_action( 'gbox_simple_img_style', $id );
 do_action( 'gbox_simple_img_script', $id );

//image size set masonry layout
if( $gbox_simple_imgs ):
 $gbox_count = 0;

if( $gbox_simg_layout_type == 'carousel_slider' || $gbox_simg_layout_type == 'carousel_fixed' ):
 ?>
<div class="gbox-carosuel<?php echo esc_attr($id); ?> <?php if($gbox_simg_layout_type == 'carousel_fixed'): ?>fixed-height <?php endif; ?>">
<?php
endif;// check carousel slider end
foreach ( (array) $gbox_simple_imgs as $attachment_id => $attachment_url ):

	$gbox_thumb = wp_get_attachment_image_src( $attachment_id, 'large' );
if( $gbox_simg_layout_type == 'masonry_layout' ){
	$gbox_count++;
	if ( $gbox_count % 3 == 0 ){
  		$gbox_simg_size = 'gbox-large';
	}elseif( $gbox_count % 2 == 0 ){
		$gbox_simg_size = 'medium';
	}else{
		$gbox_simg_size = 'gbox-medium';
	}

}elseif( $gbox_simg_layout_type == 'masonry_layout_two' ){
	$gbox_count++;
	if ( $gbox_count % 3 == 0 ){
  		$gbox_simg_size = 'gbox-horizontal';
	}elseif( $gbox_count % 2 == 0 ){
		$gbox_simg_size = 'gbox-vertical';
	}else{
		$gbox_simg_size = 'gbox-medium';
	}

}elseif( $gbox_simg_layout_type == 'masonry_layout_three' ){
	$gbox_count++;
	if ( $gbox_count % 3 == 0 ){
  		$gbox_simg_size = 'gbox-hlarge';
	}elseif( $gbox_count % 2 == 0 ){
		$gbox_simg_size = 'gbox-large';
	}else{
		$gbox_simg_size = 'gbox-medium';
	}

}else{
	$gbox_simg_size = $simg_img_size;
}
	
if( $gbox_simg_layout_type == 'carousel_slider' || $gbox_simg_layout_type == 'carousel_fixed' ): ?>
		<div class="gb-simple<?php echo esc_attr($id); ?> images-gallery simg">
            <?php if($gbox_simg_lightbox == 'light_show'): ?>
            <a class="gb-light light" href="<?php echo esc_url($gbox_thumb[0]); ?>" <?php if($gbox_show_arrow == 'yes'): ?>data-gall="gallery"<?php endif; ?>>
            <?php echo wp_get_attachment_image( $attachment_id, $gbox_simg_size ); ?>
            </a>
            <?php else: ?>
				<?php echo wp_get_attachment_image( $attachment_id, $gbox_simg_size ); ?>
            <?php endif; ?>
		</div><!--# Regular images -->

<?php else: ?>
		<div class="gb-simple<?php echo esc_attr($id); ?> gcolumn-<?php echo esc_attr($img_column); ?> images-gallery simg gb-masonry<?php echo esc_attr($id); ?>">
            <?php if($gbox_simg_lightbox == 'light_show'): ?>
            <a class="gb-light light" href="<?php echo esc_url($gbox_thumb[0]); ?>" <?php if($gbox_show_arrow == 'yes'): ?>data-gall="gallery"<?php endif; ?>>
            <?php echo wp_get_attachment_image( $attachment_id, $gbox_simg_size ); ?>
            </a>
            <?php else: ?>
				<?php echo wp_get_attachment_image( $attachment_id, $gbox_simg_size ); ?>
            <?php endif; ?>
		</div><!--# Regular images -->
<?php endif // slider and masonry layout end ?>     
		<?php
		endforeach; 
if( $gbox_simg_layout_type == 'carousel_slider' || $gbox_simg_layout_type == 'carousel_fixed' ):
		 ?>
</div>
<?php
endif;// check carousel slider end
endif;// check gbox_simple_imgs end

}
add_action( 'gallery_box_simple_image', 'gallery_box_simple_image_gallery' );