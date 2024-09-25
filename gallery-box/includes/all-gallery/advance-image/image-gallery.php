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
require_once( GALLERY_BOX_PATH. '/includes/all-gallery/advance-image/advance-image-script.php');
require_once( GALLERY_BOX_PATH. '/includes/all-gallery/advance-image/advance-image-style.php');

function gallery_box_image_gallery($id){
	$img_group = get_post_meta($id, 'img_main', true);
      
		//setting options
		 if($gimage = get_option('img_style')){
		$gimage = get_option('img_style');
		}
		$default_img_column = ( isset( $gimage['img_column'] ) ) ? $gimage['img_column'] : 3;
        $default_img_load_button = isset( $gimage['img_load_button'] ) ? $gimage['img_load_button'] :'enable';
        $img_item_number = isset( $gimage['img_item_number'] ) ? $gimage['img_item_number'] :10;

		
		$default_img_animation = ( isset( $gimage['img_animation'] ) ) ? $gimage['img_animation'] : 'ehover1'; //end setting options



//unique setting value start

$settings_main = get_post_meta($id, 'settings_main', true);
$gbox_layout_type =  !empty( $settings_main[0]['layout_type'])  ? $settings_main[0]['layout_type'] : 'masonry';
$gbox_adimg_img_size =  !empty( $settings_main[0]['adimg_img_size'])  ? $settings_main[0]['adimg_img_size'] : 'gbox-medium';
$gbox_img_link_type =  !empty( $settings_main[0]['gbox_img_link_type'])  ? $settings_main[0]['gbox_img_link_type'] : 'light';
$uni_img_hover =  !empty( $settings_main[0]['uni_img_hover'])  ? $settings_main[0]['uni_img_hover'] : 'default';
$uniqe_loadmore =  !empty( $settings_main[0]['uniqe_loadmore'])  ? $settings_main[0]['uniqe_loadmore'] : 'default';
$uni_img_column =  !empty( $settings_main[0]['uni_img_column'])  ? $settings_main[0]['uni_img_column'] : 'default';
$gbox_img_order =  !empty( $settings_main[0]['gbox_img_order'])  ? $settings_main[0]['gbox_img_order'] : 'asc';

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
 do_action( 'gbox_advance_img_style', $id );
 do_action( 'gbox_advance_img_script', $id );
 
if( $gbox_img_order == 'desc' ){
 $img_group = array_reverse($img_group);
}

$gbox_count = 0;     
foreach ( (array) $img_group as  $key =>$img_main ):

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
    $gbox_adimg_size = $gbox_adimg_img_size;
}


		$image_title =  !empty( $img_main['image_title'])  ? $img_main['image_title'] : __('Image gallery','gbox');
		$img_caption =  !empty( $img_main['img_caption'])  ? $img_main['img_caption'] : $image_title ;
		$link_url =  !empty( $img_main['link_url'])  ? $img_main['link_url'] : '#' ;
        $image_small = isset($img_main['image_small_id']) ? wp_get_attachment_image_src($img_main['image_small_id'], $gbox_adimg_size ):''; 
		$image_light_default = isset($img_main['image_small_id']) ? wp_get_attachment_image_src($img_main['image_small_id'], 'large' ):''; 
		$image_light = isset($img_main['image_light_id']) ? wp_get_attachment_image_src($img_main['image_light_id'], 'large' ):'';
		$img_btn_text =  !empty( $img_main['img_btn_text'])   ? $img_main['img_btn_text'] :__('View large','gbox');
        
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
            if($img_caption){
                $caption_title = $img_caption;
            }else{
                $caption_title = $image_title;
            }


            
            if($gbox_img_link_type =='light'):
		?>
	<div class="gcolumn-<?php echo esc_attr($img_column); ?> images-gallery adimg<?php echo esc_attr($id); ?> gb-masonry<?php echo esc_attr($id); ?>">
         <div class="gbox-margin<?php echo esc_attr($id); ?> hover <?php echo esc_attr($img_animation); ?>">
            <a class="gb-light" href="<?php if($image_light){echo esc_url($image_light[0]);}else{echo esc_url($gallery_image_def);} ?>" title="<?php echo esc_attr($caption_title); ?>" data-gall="adimg<?php echo esc_attr($id); ?>">
            <img src="<?php echo esc_url($gallery_image); ?>" alt="<?php echo esc_attr($img_caption); ?>"  height="<?php if(!empty($image_small[2])){echo esc_attr( $image_small[2]);} ?>" width="<?php if(!empty($image_small[1])){ echo esc_attr( $image_small[1]); } ?>" title="<?php echo esc_attr($img_caption); ?>"/>
                <div class="overlay">
                    <div class="gb-middle">
                        <div class="gb-inner">
                            <h2 class="you-title"><?php echo esc_html($image_title); ?></h2>
                             <button class="info">
                            <?php echo esc_html($img_btn_text); ?> 
                       
                    </button>
                    </div>
                    </div>
                    
                </div>	
            </a>
        </div>
	</div><!--# Regular images -->
        <?php 
        //Image and link type
        elseif($gbox_img_link_type =='link_light'):
        ?>
    <div class="gcolumn-<?php echo esc_attr($img_column); ?> images-gallery adimg<?php echo esc_attr($id); ?>  secend-item gb-masonry<?php echo esc_attr($id); ?>">
        <div class="gbox-margin<?php echo esc_attr($id); ?> hover <?php echo esc_attr($img_animation); ?>">
            <img src="<?php echo esc_url($gallery_image); ?>" alt="<?php echo esc_attr($img_caption); ?>"  height="<?php if(!empty($image_small[2])){echo esc_attr( $image_small[2]);} ?>" width="<?php if(!empty($image_small[1])){ echo esc_attr( $image_small[1]); } ?>" title="<?php echo esc_attr($img_caption); ?>"/>
                <div class="overlay">
                    <h2 class="you-title"><?php echo esc_html($image_title); ?></h2>
                     <div class="gbox-icon"> 
                        <a href="<?php echo esc_url($link_url); ?>" target="_blank"><i class="fa fa-link"></i></a>
                        <a class="gb-light gbox-search" href="<?php if($image_light){echo esc_url($image_light[0]);}else{echo esc_url($gallery_image_def);} ?>" title="<?php echo esc_attr($caption_title); ?>"><i class="fa fa-search"></i></a>
                    </div>
                </div>	
        </div>	
	</div><!--# images and link -->

        <?php 
        elseif($gbox_img_link_type =='link_only'):
        // link only image gallery
        ?>
        <div class="gcolumn-<?php echo esc_attr($img_column); ?> images-gallery adimg<?php echo esc_attr($id); ?>  secend-item gb-masonry<?php echo esc_attr($id); ?>">
            <div class="gbox-margin<?php echo esc_attr($id); ?> hover <?php echo esc_attr($img_animation); ?>">
            <img src="<?php echo esc_url($gallery_image); ?>" alt="<?php echo esc_attr($img_caption); ?>"  height="<?php if(!empty($image_small[2])){echo esc_attr( $image_small[2]);} ?>" width="<?php if(!empty($image_small[1])){ echo esc_attr( $image_small[1]); } ?>" title="<?php echo esc_attr($img_caption); ?>"/>
                <div class="overlay">
                    <h2 class="you-title"><?php echo esc_html($image_title); ?></h2>
                     <div class="gbox-icon"> 
                        <a href="<?php echo esc_url($link_url); ?>" target="_blank"><i class="fa fa-link"></i> <img title="<?php echo esc_attr($img_caption); ?>" /></a>
                    </div>
                </div>	
            </div>	
		</div><!--# link only images -->

        <?php 
        elseif($gbox_img_link_type =='tit_only'):
        // link only image gallery
        ?>
        <div class="gcolumn-<?php echo esc_attr($img_column); ?> images-gallery adimg<?php echo esc_attr($id); ?>  secend-item gb-masonry<?php echo esc_attr($id); ?>">
            <div class="gbox-margin<?php echo esc_attr($id); ?> hover <?php echo esc_attr($img_animation); ?>">
            <img src="<?php echo esc_url($gallery_image); ?>" alt="<?php echo esc_attr($img_caption); ?>"  height="<?php if(!empty($image_small[2])){echo esc_attr( $image_small[2]);} ?>" width="<?php if(!empty($image_small[1])){ echo esc_attr( $image_small[1]); } ?>" title="<?php echo esc_attr($img_caption); ?>"/>
                <div class="overlay">
                    <h2 class="you-title"><?php echo esc_html($image_title); ?></h2>
                </div>	
            </div>	
		</div><!--# Regular images -->

        <?php else: 
        // image only gallery
        ?>
         <div class="gcolumn-<?php echo esc_attr($img_column); ?> images-gallery adimg<?php echo esc_attr($id); ?>  secend-item gb-masonry<?php echo esc_attr($id); ?>">
            <div class="gbox-margin<?php echo esc_attr($id); ?> hover">
                <a href="<?php echo esc_url($link_url); ?>" target="_blank">
                    <img src="<?php echo esc_url($gallery_image); ?>" alt="<?php echo esc_attr($img_caption); ?>"  height="<?php if(!empty($image_small[2])){echo esc_attr( $image_small[2]);} ?>" width="<?php if(!empty($image_small[1])){ echo esc_attr( $image_small[1]); } ?>" title="<?php echo esc_attr($img_caption); ?>"/>
                </a>
                
            </div>	
		</div><!--# Regular images -->
		<?php
        endif;
		endforeach;
  
}
add_action( 'gallery_box_image', 'gallery_box_image_gallery' );