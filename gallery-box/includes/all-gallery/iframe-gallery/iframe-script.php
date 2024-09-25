<?php 
/*
 * @link              https://wpthemespace.com
 * @since             1.5.6
 * @package           Gallery box wordpress plugin    
 * description        iframe gallery dynamic script 
 *
 * @ Gallery box
 */

function gbox_iframe_gallery_script($id){
    
?>
<script type="text/javascript">

(function ($) {
	"use strict";
    $(document).ready(function(){
    <?php
        do_action( 'gbox_lightbox_active', $id ); 

        do_action( 'gbox_iframe_masonry_loadmore_active', $id );
  

     ?>


    });
}(jQuery));	

</script>
<?php
}
add_action('gbox_iframe_script', 'gbox_iframe_gallery_script');


// Masonary active script
function gbox_irame_masonry_loadmore_active_script($id){
  if( get_option('iframe_style') ){
     $giframe = get_option('iframe_style');
  }

//Youtube loadmore options
$iframe_load_button = isset( $giframe['iframe_load_button'] ) ? $giframe['iframe_load_button'] :'disable';
  $iframe_item_number = isset( $giframe['iframe_item_number'] ) ? $giframe['iframe_item_number'] :10;
//meta loadmore
$iframe_settings = get_post_meta(get_the_ID(), 'iframe_settings', true);
$iframe_main = get_post_meta(get_the_ID(), 'iframe_main', true);
$iframe_loadmore =  !empty( $iframe_settings[0]['iframe_loadmore'])  ? $iframe_settings[0]['iframe_loadmore'] : 'default';


//load more button
        if($iframe_loadmore !=='default'){ 
            $iframeload_button = $iframe_loadmore;
        }else{ 
            $iframeload_button = $iframe_load_button;
        }
// count for Load more button 
  $total_iframe_cunt = count($iframe_main);
       

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
<?php if( $iframeload_button == 'enable' && ($iframe_item_number < $total_iframe_cunt) ): ?>
  // Isotope Load more button
  var initShow = <?php echo esc_attr($iframe_item_number); ?>; 
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
  $gbox_smasonry<?php echo esc_attr($id); ?>.after('<div id="gbload-btn<?php echo esc_attr($id); ?>"><button id="load-more<?php echo esc_attr($id); ?>"  class="gbox-loadmore"><?php esc_html_e('Load More','gbox'); ?></button></div>');
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
add_action('gbox_iframe_masonry_loadmore_active','gbox_irame_masonry_loadmore_active_script');