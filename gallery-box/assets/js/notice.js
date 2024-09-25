;(function($){
	$(document).ready(function(){
    	$('.gbox-dismiss').on('click',function(){
            var url = new URL(location.href);
            url.searchParams.append('ghide',1);
            location.href= url;
        });
    	$('.mgp-dismiss').on('click',function(){
            var url = new URL(location.href);
            url.searchParams.append('dismissed',1);
            location.href= url;
        });
	});
})(jQuery);