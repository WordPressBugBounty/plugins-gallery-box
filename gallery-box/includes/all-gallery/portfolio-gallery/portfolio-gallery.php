<?php 
/*
 * @link              https://wpthemespace.com
 * @since             1.5.6
 * @package           Gallery box wordpress plugin    
 * description        advance image gallery output
 *
 * @ Gallery box
 */


// Add image gallery script style
require_once( GALLERY_BOX_PATH. '/includes/all-gallery/portfolio-gallery/portfolio-script.php');
require_once( GALLERY_BOX_PATH. '/includes/all-gallery/portfolio-gallery/portfolio-style.php');

function gallery_box_portfolio_gallery($id){
	$portfo_main = get_post_meta($id, 'portfo_main', true);
      
		//setting options
		 if($gimage = get_option('img_style')){
		$gimage = get_option('img_style');
		}
		$default_img_column = ( isset( $gimage['img_column'] ) ) ? $gimage['img_column'] : 3;
        $default_img_load_button = isset( $gimage['img_load_button'] ) ? $gimage['img_load_button'] :'enable';
        $img_item_number = isset( $gimage['img_item_number'] ) ? $gimage['img_item_number'] :10;

		
		$default_img_animation = ( isset( $gimage['img_animation'] ) ) ? $gimage['img_animation'] : 'ehover1'; //end setting options



//unique setting value start

$port_settings = get_post_meta($id, 'port_settings', true);
$gbox_layout_type =  !empty( $port_settings[0]['layout_type'])  ? $port_settings[0]['layout_type'] : 'masonry';
$gbox_port_img_size =  !empty( $port_settings[0]['port_img_size'])  ? $port_settings[0]['port_img_size'] : 'gbox-medium';
$gbox_img_link_type =  !empty( $port_settings[0]['gbox_img_link_type'])  ? $port_settings[0]['gbox_img_link_type'] : 'light';
$uni_img_hover =  !empty( $port_settings[0]['uni_img_hover'])  ? $port_settings[0]['uni_img_hover'] : 'default';
$uniqe_loadmore =  !empty( $port_settings[0]['uniqe_loadmore'])  ? $port_settings[0]['uniqe_loadmore'] : 'default';
$uni_img_column =  !empty( $port_settings[0]['uni_img_column'])  ? $port_settings[0]['uni_img_column'] : 'default';
$gbox_portfolio_order =  !empty( $port_settings[0]['gbox_portfolio_order'])  ? $port_settings[0]['gbox_portfolio_order'] : 'asc';
$gbox_portfolio_link_tergat =  !empty( $port_settings[0]['gbox_portfolio_link_tergat'])  ? $port_settings[0]['gbox_portfolio_link_tergat'] : '_blank';

//unique setting value end


/*image column setup*/
    if($uni_img_column !== 'default'){ 
        $img_column = $uni_img_column;
    }else{ 
        $img_column = $default_img_column;
    }

/*Default image setup*/
        if($img_column == 4 || $img_column == 5 ){
		$gbox_default_img = GALLERY_BOX_URL.'images/image-small.jpg';

		}elseif($img_column == 3){
		$gbox_default_img = GALLERY_BOX_URL.'images/image-gallery.jpg';
		}else{
		$gbox_default_img = GALLERY_BOX_URL.'images/image-large.jpg';
		}

//img animation set
        if($uni_img_hover !=='default'){ 
            $img_animation = $uni_img_hover;
        }else{ 
            $img_animation = $default_img_animation;
        }
        
//load more button
        if($uniqe_loadmore !=='default'){ 
            $img_load_button = $uniqe_loadmore;
        }else{ 
            $img_load_button = $default_img_load_button;
        }        

// galery script and style
 do_action( 'gbox_portfolio_style', $id );
 do_action( 'gbox_portfoli_script', $id );
  // asc and desc order
if( $gbox_portfolio_order == 'desc' ){
 $portfo_main = array_reverse($portfo_main);
}

$gbox_count = 0;     
foreach ( (array) $portfo_main as  $key =>$portfolio ):

    //image size for masonry
if( $gbox_layout_type == 'masonry_two' ){
    $gbox_count++;
    if ( $gbox_count % 3 == 0 ){
        $gbox_adimg_size = 'gbox-horizontal';
    }elseif( $gbox_count % 2 == 0 ){
        $gbox_adimg_size = 'gbox-vertical';
    }else{
        $gbox_adimg_size = 'gbox-medium';
    }

}elseif( $gbox_layout_type == 'masonry_three' ){
    $gbox_count++;
    if ( $gbox_count % 3 == 0 ){
        $gbox_adimg_size = 'gbox-hlarge';
    }elseif( $gbox_count % 2 == 0 ){
        $gbox_adimg_size = 'gbox-large';
    }else{
        $gbox_adimg_size = 'gbox-medium';
    }

}else{
    $gbox_adimg_size = $gbox_port_img_size;
}


		$portfolio_title =  !empty( $portfolio['portfolio_title'])  ? $portfolio['portfolio_title'] : __('Portfolio gallery','gbox');
		$port_caption =  !empty( $portfolio['port_caption'])  ? $portfolio['port_caption'] : $portfolio_title ;
		$port_link =  !empty( $portfolio['port_link'])  ? $portfolio['port_link'] : '#' ;
        $image_small = isset($portfolio['port_img_id']) ? wp_get_attachment_image_src($portfolio['port_img_id'], $gbox_adimg_size ):''; 
		$image_light_default = isset($portfolio['port_img_id']) ? wp_get_attachment_image_src($portfolio['port_img_id'], 'large' ):''; 
		$image_light = isset($portfolio['image_light_id']) ? wp_get_attachment_image_src($portfolio['image_light_id'], 'large' ):'';
        
            if($image_small){
            $gallery_image = $image_small[0];
            }
            else{
            $gallery_image = $gbox_default_img;
            }
// Light image default		
			if(empty($image_light)){
			$gallery_image_def = $image_light_default[0];
			}else{
			$gallery_image_def = $gbox_default_img;
			}

            //lightbox caption set
            if($port_caption){
                $caption_title = $port_caption;
            }else{
                $caption_title = $portfolio_title;
            }

?>

    <div class="gcolumn-<?php echo esc_attr($img_column); ?> images-gallery portfolio<?php echo esc_attr($id); ?>  secend-item gb-masonry<?php echo esc_attr($id); ?>">
        <div class="gbox-margin<?php echo esc_attr($id); ?> hover <?php echo esc_attr($img_animation); ?>">
            <img src="<?php echo esc_url($gallery_image); ?>" alt="<?php echo esc_attr($port_caption); ?>" title="<?php echo esc_attr($port_caption); ?>"/>
                <div class="overlay">
                    <h2 class="you-title"><?php echo esc_html($portfolio_title); ?></h2>
                     <div class="gbox-icon"> 
                        <a href="<?php echo esc_url($port_link); ?>" target="<?php echo esc_attr($gbox_portfolio_link_tergat); ?>"><i class="fa fa-link"></i></a>
                        <a class="gb-light gbox-search" href="<?php if($image_light){echo esc_url($image_light[0]);}else{echo esc_url($gallery_image_def);} ?>" title="<?php echo esc_attr($caption_title); ?>" data-gall=port<?php echo esc_attr($id); ?>><i class="fa fa-search"></i></a>
                    </div>
                </div>	
        </div>	
	</div><!--# images and link -->
<?php 
endforeach;
  
}
add_action( 'gallery_box_portfolio', 'gallery_box_portfolio_gallery' );