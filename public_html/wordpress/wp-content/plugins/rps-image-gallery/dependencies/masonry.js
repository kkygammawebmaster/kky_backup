/**
 * Controller for Masonry script.
 * 
 * @since 2.2.0
 * @package rps-image-gallery
 * @author Eric Kyle
 */
jQuery(function($) {

	if($().masonry() && $().imagesLoaded()) {
	   var container = $('.rps-image-gallery-format-default.rps-image-gallery-masonry > ul, .rps-image-gallery-masonry');
	   
	   container.css('opacity',0);
	   
	   imagesLoaded(container,function(){
	
			container.masonry({
				itemSelector: '.gallery-item',
				percentPosition: true,
			}).css('opacity',1);
			
		});
		
	}

});
