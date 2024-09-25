<?php 
/*
 * @link              https://wpthemespace.com
 * @since             1.5.6
 * @package           Gallery box wordpress plugin    
 * description        Youtube gallery dynamic scripts 
 *
 * @ Gallery box
 */

function gbox_youtube_gallery_script($id){
    
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
        do_action( 'gbox_lightbox_active', $id ); 

        do_action( 'gbox_youtube_masonry_loadmore_active', $id );
  

     ?>


    });
}(jQuery));	

</script>
<?php
}
add_action('gbox_youtube_script', 'gbox_youtube_gallery_script');


// Masonary active script
function gbox_youtube_masonry_loadmore_active_script($id){
	if($gbyoutube = get_option('youtube_style')){
		$gbyoutube = get_option('youtube_style');
		}

//Youtube loadmore options
    $you_load_button = isset( $gbyoutube['you_load_button'] ) ? $gbyoutube['you_load_button'] :'disable';
    $you_item_number = isset( $gbyoutube['you_item_number'] ) ? $gbyoutube['you_item_number'] :10;
//meta loadmore
$you_settings = get_post_meta($id, 'you_settings', true);
$you_loadmore =  !empty( $you_settings[0]['you_loadmore'])  ? $you_settings[0]['you_loadmore'] : 'default';
$youtube_main = get_post_meta($id, 'youtube_main', true);
// count for Load more button 
  $total_youtube_cunt = count($youtube_main);


//load more button
        if($you_loadmore !=='default'){ 
            $youtube_load_button = $you_loadmore;
        }else{ 
            $youtube_load_button = $you_load_button;
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
<?php if( $youtube_load_button == 'enable'  && ($you_item_number < $total_youtube_cunt) ): ?>
  // Isotope Load more button
  var initShow = <?php echo esc_attr($you_item_number); ?>; 
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
add_action('gbox_youtube_masonry_loadmore_active','gbox_youtube_masonry_loadmore_active_script');