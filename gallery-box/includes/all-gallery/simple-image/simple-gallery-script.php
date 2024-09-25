<?php 
/*
 * @link              https://wpthemespace.com
 * @since             1.5.6
 * @package           Gallery box wordpress plugin    
 * description        Simple image gallery dynamic script 
 *
 * @ Gallery box
 */

function gbox_simple_img_gallery_script($id){
    
//All metabox 
$simg_main = get_post_meta( get_the_ID(), 'simg_main', 1 );
$gbox_simg_lightbox =  !empty( $simg_main[0]['simg_lightbox'])  ? $simg_main[0]['simg_lightbox'] : 'light_show';
$gbox_simg_layout_type =  !empty( $simg_main[0]['simg_layout_type'])  ? $simg_main[0]['simg_layout_type'] : 'masonry_layout';


?>
<script type="text/javascript">

(function ($) {
	"use strict";
    $(document).ready(function(){
    <?php
        if( $gbox_simg_lightbox == 'light_show' ){
            do_action( 'gbox_lightbox_active', $id ); 
        }


    if( $gbox_simg_layout_type == 'carousel_slider' || $gbox_simg_layout_type == 'carousel_fixed' ){
         do_action( 'gbox_simple_img_carousel', $id );
    }else{
         do_action( 'gbox_masonry_loadmore_active', $id );
     }

     ?>


    });
}(jQuery));	

</script>
<?php
}
add_action('gbox_simple_img_script', 'gbox_simple_img_gallery_script');



function gbox_simple_img_carousel_script($id){
$simg_main = get_post_meta( get_the_ID(), 'simg_main', 1 );
$simg_car_nav =  !empty( $simg_main[0]['simg_car_nav'])  ? $simg_main[0]['simg_car_nav'] : '';
$simg_car_dot =  !empty( $simg_main[0]['simg_car_dot'])  ? $simg_main[0]['simg_car_dot'] : '';
$simg_car_auto =  !empty( $simg_main[0]['simg_car_auto'])  ? $simg_main[0]['simg_car_auto'] : '';
$simg_car_imgnum =  !empty( $simg_main[0]['simg_car_imgnum'])  ? $simg_main[0]['simg_car_imgnum'] : '3';

?>

$('.gbox-carosuel<?php echo esc_attr($id); ?>').slick({
  slidesToShow: <?php echo esc_attr($simg_car_imgnum); ?>,
  infinite: true,
  <?php if( !empty($simg_car_dot)): ?>
  dots: true,
  <?php else: ?>
  dots: false,
  <?php endif; ?>
  <?php if( empty($simg_car_nav)): ?>
  arrows: false,
  <?php endif; ?>
  <?php if( $simg_car_imgnum < 2 ): ?>
  fade: true,
  <?php endif; ?>
  slidesToScroll: <?php if( $simg_car_imgnum > 1 ): ?>3<?php else: ?>1<?php endif; ?>,
  <?php if($simg_car_auto): ?>
  autoplay: true,
  autoplaySpeed: 3000,
<?php endif; ?>
  <?php if( $simg_car_imgnum > 1 ): ?>
    responsive: [
    {
      breakpoint: 960,
      settings: {
        slidesToShow: 2,
        slidesToScroll: 2
      }
    },
    {
      breakpoint: 480,
      settings: {
        slidesToShow: 1,
        slidesToScroll: 1
      }
    }

  ]<?php endif; ?>
});
<?php
}
add_action('gbox_simple_img_carousel','gbox_simple_img_carousel_script');

// Masonary active script
function gbox_masonry_loadmore_active_script($id){
if($gb_image = get_option('img_style')){
  $gb_image = get_option('img_style');
}
$default_img_load_button = isset( $gb_image['img_load_button'] ) ? $gb_image['img_load_button'] :'enable';
$img_item_number = isset( $gb_image['img_item_number'] ) ? $gb_image['img_item_number'] :10;

//meta loadmore
$simg_main = get_post_meta( get_the_ID(), 'simg_main', 1 );
$gbox_simg_loadmore =  !empty( $simg_main[0]['simg_loadmore'])  ? $simg_main[0]['simg_loadmore'] : 'default';
$simple_imgs = get_post_meta($id, 'simple_imgs', true);
// count for Load more button 
  $total_simgaes_cunt = count($simple_imgs);


//load more button
        if($gbox_simg_loadmore !=='default'){ 
            $img_load_button = $gbox_simg_loadmore;
        }else{ 
            $img_load_button = $default_img_load_button;
        }

  //javascript code so the function need to call in script tag
?>
   if ( $('.g-box<?php echo esc_attr($id); ?>').length > 0 )
    {
        var $gbox_smasonry<?php echo esc_attr($id); ?> = $('.g-box<?php echo esc_attr($id); ?>').isotope({
            itemSelector : '.gb-masonry<?php echo esc_attr($id); ?>',
            //masonry: {
                //columnWidth: '.grid-sizer'
            //}
        });

        $gbox_smasonry<?php echo esc_attr($id); ?>.imagesLoaded().progress( function() {
            $gbox_smasonry<?php echo esc_attr($id); ?>.isotope('layout');
            
        });
<?php if( $img_load_button == 'enable'  && ($img_item_number < $total_simgaes_cunt) ): ?>
  // Isotope Load more button
  var initShow = <?php echo esc_attr($img_item_number); ?>; 
  var counter = initShow; 
  var iso = $gbox_smasonry<?php echo esc_attr($id); ?>.data('isotope'); 
  loadMore(initShow); 

  function loadMore(toShow) {
    $gbox_smasonry<?php echo esc_attr($id); ?>.find(".hidden").removeClass("hidden");

    var hiddenElems = iso.filteredItems.slice(toShow, iso.filteredItems.length).map(function(item) {
      return item.element;
    });
    $(hiddenElems).addClass('hidden');
    $gbox_smasonry<?php echo esc_attr($id); ?>.isotope('layout');

    //when no more to load, hide show more button
    if (hiddenElems.length == 0) {
      jQuery("#load-more<?php echo esc_attr($id); ?>").hide();
    } else {
      jQuery("#load-more<?php echo esc_attr($id); ?>").show();
    };

  }
  //append load more button
  $gbox_smasonry<?php echo esc_attr($id); ?>.after('<div id="gbload-btn<?php echo esc_attr($id); ?>"><button id="load-more<?php echo esc_attr($id); ?>"  class="gbox-loadmore"><?php esc_html_e('Load More','gbox'); ?> </button></div>');
  //when load more button clicked
  $("#load-more<?php echo esc_attr($id); ?>").click(function() {
    if ($('#filters').data('clicked')) {
      //when filter button clicked, set initial value for counter
      counter = initShow;
      $('#filters').data('clicked', false);
    } else {
      counter = counter;
    };
    counter = counter + initShow;
    loadMore(counter);
  });
  //when filter button clicked
  $("#filters").click(function() {
    $(this).data('clicked', true);
    loadMore(initShow);
  });
<?php endif; ?>

    }//check image not empty
<?php
}
add_action('gbox_masonry_loadmore_active','gbox_masonry_loadmore_active_script');