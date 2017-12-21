jQuery(function($){
	var galleries = $('.rps-image-gallery-no-masonry');
	
	var swapImages = function( gallery, finalPage, fade ) {
		var fadeDuration = 0;
		
		if ( fade ) {
			fadeDuration = 400;
		}
		
		var pageSize = parseInt( gallery.attr( 'pageSize' ) );
		var galleryContainer = gallery.find('ul,div');
		galleryContainer.fadeOut(fadeDuration, function(){
			var images = gallery.find('.gallery-item');
				
			var minimumIndex = finalPage * pageSize;
			var maximumIndex = ( finalPage + 1 ) * pageSize;
			
			if (minimumIndex == 0) {
				gallery.find('.navigation .prev').addClass( 'disabled' );
			} else {
				gallery.find('.navigation .prev').removeClass( 'disabled' );
			}
			
			if (maximumIndex >= gallery.find('.gallery-item').length) {
				gallery.find('.navigation .next').addClass( 'disabled' );
			} else {
				gallery.find('.navigation .next').removeClass( 'disabled' );
			}
			
			for ( var j = 0; j < images.length; j++ ) {
				var image = $(images[j]);
				var display = 'inline-block';
				
				if ( ( j < minimumIndex ) || ( j >= maximumIndex ) ) {
					display = 'none';
				}
				
				image.css( 'display', display );
			}
			
			gallery.find( '.page-numbers' ).removeClass( 'current' );
			gallery.find( '.page-' + ( finalPage + 1 ) ).addClass( 'current' );
			
			galleryContainer.fadeIn(fadeDuration);
			gallery.attr( 'currentPage', finalPage );
		});
	};
	
	for ( var i = 0; i < galleries.length; i++ ) {
		var gallery = $(galleries[i]);
		var pageSize = parseInt( gallery.attr( 'pageSize' ) );
		
		if ( pageSize > 0 ) {
			swapImages( gallery, 0, false );
			var prev = gallery.find('.navigation .prev');
			var next = gallery.find('.navigation .next');
			var jump = gallery.find('.navigation .jump-to-page');
			
			prev.click(function() {
				var prevGallery = $(this.parentNode.parentNode.parentNode);
				var currentPage = parseInt( prevGallery.attr( 'currentPage' ) );
				
				if ( currentPage > 0 ) {
					currentPage--;
					swapImages( prevGallery, currentPage, true );
				}
			});
			
			next.click(function() {
				var nextGallery = $(this.parentNode.parentNode.parentNode);
				var currentPage = parseInt( nextGallery.attr( 'currentPage' ) );
				var pageSize = parseInt( nextGallery.attr( 'pageSize' ) );
				var lastImageShown = (currentPage + 1) * pageSize;
				
				if ( lastImageShown < nextGallery.find('.gallery-item').length ) {
					currentPage++;
					swapImages( nextGallery, currentPage, true );
				}
			});
			
			jump.click(function() {
				var jumpGallery = $(this.parentNode.parentNode.parentNode);
				var currentPage = parseInt( jumpGallery.attr( 'currentPage' ) );
				var jumpPage = parseInt( this.text ) - 1;
				
				if ( currentPage != jumpPage ) {
					swapImages( jumpGallery, jumpPage, true );
				}
			});
		}
	}
});