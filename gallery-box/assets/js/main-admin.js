(function ($) {
	"use strict";

    $(document).ready(function(){
        $('#m_gallery').addClass('gbox-tabs');
	 	$('.cmb2-id-simple-imgs').show();
	 	$('#simg_main_repeat').hide();

		$('.cmb2-id-simg-type .cmb2-radio-list li:nth-child(2)').on('click', function(){
	        $('#simg_main_repeat').show();
	        $('.cmb2-id-simple-imgs').hide();

	    });
	    $('.cmb2-id-simg-type .cmb2-radio-list li:nth-child(1)').on('click', function(){
	        $('#simg_main_repeat').hide();

	    });

    //advance images
    $('#gbox_adimg').show();
    $('#settings_maintab').hide();

    $('.cmb2-id-adimg-type .cmb2-radio-list li:nth-child(2)').on('click', function(){
        $('#settings_maintab').show();
        $('#gbox_adimg').hide();

    });
    $('.cmb2-id-adimg-type .cmb2-radio-list li:nth-child(1)').on('click', function(){
        $('#settings_maintab').hide();
        $('#gbox_adimg').show();

    });
    //portfolio gallery
    $('#gbox_portfolio').show();
 	$('#port_settings').hide();

	$('.cmb2-id-portfolio-type .cmb2-radio-list li:nth-child(2)').on('click', function(){
        $('#port_settings').show();
        $('#gbox_portfolio').hide();

    });
    $('.cmb2-id-portfolio-type .cmb2-radio-list li:nth-child(1)').on('click', function(){
        $('#port_settings').hide();
        $('#gbox_portfolio').show();

    });
    //Youtube gallery
    $('#youtube_maintab').show();
 	$('#you_settings').hide();

	$('.cmb2-id-youtube-type .cmb2-radio-list li:nth-child(2)').on('click', function(){
        $('#you_settings').show();
        $('#youtube_maintab').hide();

    });
    $('.cmb2-id-youtube-type .cmb2-radio-list li:nth-child(1)').on('click', function(){
        $('#you_settings').hide();
        $('#youtube_maintab').show();

    });

    //Vimeo gallery
    $('#vimeo_maintab').show();
    $('#vimeo_settings').hide();

    $('.cmb2-id-vimeo-type .cmb2-radio-list li:nth-child(2)').on('click', function(){
        $('#vimeo_settings').show();
        $('#vimeo_maintab').hide();

    });
    $('.cmb2-id-vimeo-type .cmb2-radio-list li:nth-child(1)').on('click', function(){
        $('#vimeo_settings').hide();
        $('#vimeo_maintab').show();

    });
    
    //iframe gallery
    $('#iframe_maintab').show();
    $('#iframe_settings').hide();

    $('.cmb2-id-iframe-type .cmb2-radio-list li:nth-child(2)').on('click', function(){
        $('#iframe_settings').show();
        $('#iframe_maintab').hide();

    });
    $('.cmb2-id-iframe-type .cmb2-radio-list li:nth-child(1)').on('click', function(){
        $('#iframe_settings').hide();
        $('#iframe_maintab').show();

    });
    
   


    });
}(jQuery));	