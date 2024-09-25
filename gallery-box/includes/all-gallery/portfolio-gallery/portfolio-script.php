<?php 
/*
 * @link              https://wpthemespace.com
 * @since             1.5.6
 * @package           Gallery box wordpress plugin    
 * description        Advance imaeg gallery dynamic script 
 *
 * @ Gallery box
 */

function gbox_portfolio_gallery_script($id){



?>
<script type="text/javascript">

(function ($) {
	"use strict";
    $(document).ready(function(){
    <?php
        do_action( 'gbox_lightbox_active', $id ); 

        do_action( 'gbox_portfolio_masonry_loadmore_active', $id );
  

     ?>


    });
}(jQuery));	

</script>
<?php
}
add_action('gbox_portfoli_script', 'gbox_portfolio_gallery_script');


// Masonary active script
function gbox_portfolio_masonry_loadmore_active_script($id){
if($gb_image = get_option('img_style')){
  $gb_image = get_option('img_style');
}
$default_img_load_button = isset( $gb_image['img_load_button'] ) ? $gb_image['img_load_button'] :'disable';
$img_item_number = isset( $gb_image['img_item_number'] ) ? $gb_image['img_item_number'] :10;

//meta loadmore
$port_settings = get_post_meta($id, 'port_settings', true);
$gbox_uniqe_loadmore =  !empty( $port_settings[0]['uniqe_loadmore'])  ? $port_settings[0]['uniqe_loadmore'] : 'default';
$portfo_main = get_post_meta($id, 'portfo_main', true);
// count for Load more button 
  $total_imgaes_cunt = count($portfo_main);



//load more button
        if($gbox_uniqe_loadmore !=='default'){ 
            $img_load_button = $gbox_uniqe_loadmore;
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
<?php if( $img_load_button == 'enable' && ($img_item_number < $total_imgaes_cunt) ): ?>
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
  $gbox_smasonry<?php echo esc_attr($id); ?>.after('<div id="gbload-btn<?php echo esc_attr($id); ?>"><button id="load-more<?php echo esc_attr($id); ?>" class="gbox-loadmore"><?php esc_html_e('Load More','gbox'); ?></button></div>');
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
add_action('gbox_portfolio_masonry_loadmore_active','gbox_portfolio_masonry_loadmore_active_script');