<?php
	
namespace rpsfancybox\display;

/**
* Class utilized to display the code necessary for a fancybox slideshow on an HTML page
* 
* @author		Pablo S. Gallastegui
* @copyright	2015 Red Pixel Studios
* @version		1.0.0
* @package		rpsfancybox\display
*/

class Slideshow2View extends SlideshowView {
	/**
	* Display the code necessary for the fancybox library to display it
	*/
	protected function display_fancybox() {
		$output = '';
		
		$output .= '$(\'a[rel="' . $this->getSlideshow()->getId() . '"]\').fancybox({';
		
		$output .= '\'openEffect\' : \'' . $this->getSlideshow()->getTransitionIn() . '\',';
		$output .= '\'closeEffect\' : \'' . $this->getSlideshow()->getTransitionOut() . '\',';
		$output .= '\'openSpeed\' : ' . $this->getSlideshow()->getSpeedIn() . ',';
		$output .= '\'closeSpeed\' : ' . $this->getSlideshow()->getSpeedOut() . ',';
		$output .= '\'closeBtn\' : ' . ( ( $this->getSlideshow()->showsCloseButton() ) ? 'true' : 'false' ) . ',';
		$output .= '\'loop\' : ' . ( ( $this->getSlideshow()->cycles() ) ? 'true' : 'false' ) . ',';
		
		if ( $this->getSlideshow()->autoplays() ) {
			$output .= '\'autoPlay\' : true,';
			$output .= '\'playSpeed\' : ' . $this->getSlideshow()->getAutoplayTime() * 1000 . ',';
		}
		
		$output .= 'helpers : {';
		
		if ( $this->getSlideshow()->showsTitle() ) {
			$output .= 'title: { position: \'bottom\',';
			$output .= 'type: \'' . $this->getSlideshow()->getTitlePosition() . '\',';
			$output .= '},';
		} else {
			$output .= 'title: null,';
		}
		
		if ( $this->getSlideshow()->showsHelperThumbs() ) {
			$output .= 'thumbs: { width: ' . $this->getSlideshow()->getHelperThumbsWidth() . ', height: ' . $this->getSlideshow()->getHelperThumbsHeight() . ' },';
		}
		
		$overlayColor = $this->getSlideshow()->getOverlayColor();
		$overlayRed = hexdec( substr( $overlayColor, 1, 2) );
		$overlayGreen = hexdec( substr( $overlayColor, 3, 2) );
		$overlayBlue = hexdec( substr( $overlayColor, 5, 2) );
		
		$output .= 'overlay : {';
		$output .= 'css : {';
		$output .= '\'background\' : \'rgba(' . $overlayRed . ', ' . $overlayGreen . ', ' . $overlayBlue . ', ' . $this->getSlideshow()->getOverlayOpacity() . ')\'';
		$output .= '}';
		$output .= '}'; //end overlay
		
		$output .= '},'; //end helpers
		
		
		if ( $this->getSlideshow()->showsTitle() ) {
			
			$output .= 'beforeShow : function() {';
			$output .= 'this.title = $(\'<div/>\').html(this.title).text();';
			$output .= 'var elem = document.createElement(\'textarea\');';
			$output .= 'elem.innerHTML = this.title;';
			$output .= 'var imageTitle = elem.value;';
			
			if ( $this->getSlideshow()->getTitleAlignment() != 'none' ) {
				$output .= 'this.title = \'<span style="text-align: ' . $this->getSlideshow()->getTitleAlignment() . '" id="fancybox-title-' . $this->getSlideshow()->getTitlePosition() . '">\' + ';
				
				$output .= 'imageTitle + ';
				
				$output .= '\'</span>\';';
			}
			
			if ( $this->getSlideshow()->showsImageCountInTitle() ) {
				$output .= 'this.title = \' Image \' + (this.index + 1) + \'/\' + this.group.length + \' &nbsp; \' + (this.title ? \'\' + this.title + \'\' : \'\');';
			}
			
			$output .= '},';
			
		}
		
		if ( $this->getSlideshow()->showsDownloadLink() ) {
			$output .= 'afterLoad : function() {';
			$output .= 'this.skin.append( \'<a title="Download" href="\' + this.href + \'" class="rps-fancybox-item rps-fancybox-download"></a> \' )';
			$output .= '},';
		}

		$output .= '\'padding\' : ' . $this->getSlideshow()->getPadding() . ',';
		$output .= '\'margin\' : ' . $this->getSlideshow()->getMargin() . ',';
		
		$output .= '\'arrows\' : ' . ( ( $this->getSlideshow()->showsNavigationArrows() ) ? 'true' : 'false' ) . ',';
		
		$output .= '});';
		
		return $output;
	}
}

?>