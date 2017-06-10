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

class Slideshow1View extends SlideshowView {
	
    function display( $display_progress_bar = true ) {
		$output = parent::display( $display_progress_bar );
		
		if ( $this->getSlideshow()->autoplays() ) {
			if ( $display_progress_bar ) {
				$output .= self::displayProgressBar();
			}
			
			$output .= $this->display_autoplay();
		}
		
		return $output;
	}
	
	/**
	* Add the progress bar code. It will output it only the first time it is called
	*
	* @throws	\LogicException	when it attempts to output the progress bar multiple times
	*/
	static function displayProgressBar() {
		static $already_displayed = false;
		
		if ( $already_displayed ) {
			throw new \LogicException( 'Code for progress bar already output' );
		} else {
			$already_displayed = true;
			
			$output = '';
			
			$output .= 'var progress_div = false;';
			$output .= 'var play_stop_button = document.createElement( \'DIV\' );';
			$output .= 'play_stop_button.setAttribute( \'id\' , \'fancybox-play-stop\' );';
			$output .= 'var play_icon = document.createElement( \'DIV\' );';
			$output .= 'play_icon.setAttribute( \'id\' , \'fancybox-play-icon\' );';
			$output .= 'play_stop_button.appendChild(play_icon);';
			$output .= 'var stop_icon = document.createElement( \'DIV\' );';
			$output .= 'stop_icon.setAttribute( \'id\' , \'fancybox-stop-icon\' );';
			$output .= 'play_stop_button.appendChild(stop_icon);';
			
			return $output;
		}
	}
	
	/**
	* Display the code necessary for the slideshow to autoplay
	*/
	private function display_autoplay() {
		$output = '';
		
		$timer_name = $this->get_autoplay_timer_name();
		$timer_interval = $this->getSlideshow()->getAutoplayTime() * 1000;
		$progress_interval = 25;
		$progress_increment = 1.2 * 100 / ( $timer_interval / $progress_interval );
		
		$output .= 'var ' . $timer_name . ' = false;';
		$output .= 'var ' . $timer_name . '_interval = ' . $timer_interval . ';';
		$output .= 'var progress_' . $timer_name . ' = false;';
		$output .= 'var progress_' . $timer_name . '_interval = ' . $progress_interval . ';';
		$output .= 'var ' . $timer_name . '_progress = 0;';
		$output .= 'var ' . $timer_name . '_progress_increment = ' . $progress_increment . ';';
		
		$output .= 'function start_' . $timer_name . '() {';
		$output .= 'if (' . $timer_name . ' == false) {';
		$output .= 'if (progress_div == false) {';
		$output .= 'progress_div = document.createElement( \'DIV\' );';
		$output .= 'progress_div.setAttribute( \'id\' , \'fancybox-progress\' );';
		$output .= 'progress_div.setAttribute( \'style\' , \'position: absolute; bottom: 0px; height: 1px; width: 0%; background-color: black; z-index:9999; left:0; right: 0; margin: auto; opacity:.50; -moz-opacity:.50; filter:alpha(opacity=50); \' );';
		$output .= 'document.getElementById( \'fancybox-outer\' ).appendChild( progress_div );';
		$output .= '}';
		$output .= 'document.getElementById( \'fancybox-outer\' ).appendChild( play_stop_button );';
		$output .= 'play_stop_button.removeEventListener(\'click\', start_' . $timer_name . ');';
		$output .= 'play_stop_button.addEventListener(\'click\', stop_' . $timer_name . ');';
		$output .= 'play_icon.style.display = \'none\';';
		$output .= 'stop_icon.style.display = \'block\';';
		$output .= $timer_name . '_progress = 0;';
		$output .= $timer_name . ' = setInterval( ' . $timer_name . '_tick, ' . $timer_name . '_interval );';
		$output .= 'progress_' . $timer_name . ' = setInterval( progress_' . $timer_name . '_tick, progress_' . $timer_name . '_interval );';
		$output .= '}';
		$output .= '}';
		
		$output .= 'function progress_' . $timer_name . '_tick() {';
		$output .= 'if ( ' . $timer_name . '_progress < 100 ) {';
		$output .= $timer_name . '_progress += ' . $timer_name . '_progress_increment;';
		$output .= 'progress_div.style.width = ' . $timer_name . '_progress + \'%\';';
		$output .= '}';
		$output .= '}';
		
		$output .= 'function ' . $timer_name . '_tick() {';
		$output .= 'console.log(' . $timer_name . '_progress);';
		$output .= $timer_name . '_progress = 0;';
		$output .= '$.fancybox.next();';
		$output .= '}';
		
		$output .= 'function stop_' . $timer_name . '() {';
		$output .= 'clearInterval( ' . $timer_name . ' );';
		$output .= $timer_name . ' = false;';
		$output .= 'clearInterval( progress_' . $timer_name . ' );';
		$output .= 'progress_' . $timer_name . ' = false;';
		$output .= 'play_stop_button.removeEventListener(\'click\', stop_' . $timer_name . ');';
		$output .= 'play_stop_button.addEventListener(\'click\', start_' . $timer_name . ');';
		$output .= $timer_name . '_progress = 0;';
		$output .= 'progress_div.style.width = ' . $timer_name . '_progress + \'%\';';
		$output .= 'play_icon.style.display = \'block\';';
		$output .= 'stop_icon.style.display = \'none\';';
		$output .= '}';
		
		return $output;
	}
	
	private function get_autoplay_timer_name() {
		return 'timer_' . preg_replace( '/[^0-9a-z_]/i', "_", $this->getSlideshow()->getId() ) ;
	}
	
	
	/**
	* Display the code necessary for the fancybox library to display it
	*/
	protected function display_fancybox() {
		$output = '';
		
		$output .= '$(\'a[rel="' . $this->getSlideshow()->getId() . '"]\').fancybox({';
		$output .= '\'transitionIn\' : \'' . $this->getSlideshow()->getTransitionIn() . '\',';
		$output .= '\'transitionOut\' : \'' . $this->getSlideshow()->getTransitionOut() . '\',';
		$output .= '\'titlePosition\' : \'' . $this->getSlideshow()->getTitlePosition() . '\',';
		$output .= '\'speedIn\' : ' . $this->getSlideshow()->getSpeedIn() . ',';
		$output .= '\'speedOut\' : ' . $this->getSlideshow()->getSpeedOut() . ',';
		$output .= '\'showCloseButton\' : ' . ( ( $this->getSlideshow()->showsCloseButton() ) ? 'true' : 'false' ) . ',';
		$output .= '\'cyclic\' : ' . ( ( $this->getSlideshow()->cycles() ) ? 'true' : 'false' ) . ',';
		$output .= '\'centerOnScroll\' : ' . ( ( $this->getSlideshow()->centersOnScroll() ) ? 'true' : 'false' ) . ',';
		
		if ( $this->getSlideshow()->showsTitle() || $this->getSlideshow()->showsImageCountInTitle() ) {
			$output .= '\'titleShow\' : true,';
			
			$output .= '\'titleFormat\' : function(title, currentArray, currentIndex, currentOpts) { return \'<div style="text-align: ' . $this->getSlideshow()->getTitleAlignment() . '" id="fancybox-title-' . $this->getSlideshow()->getTitlePosition() . '">\' + ';
			if ( $this->getSlideshow()->showsImageCountInTitle() ) {
				$output .= '\'Image \' + (currentIndex + 1) + \'/\' + currentArray.length + \' &nbsp; \' + ';
			}
			
			if ( $this->getSlideshow()->showsTitle() ) {
				$output .= '(title.length ? \' \' + $(\'<div/>\').html(title).text() : \'\') + ';
			}
			
			$output .= '\'</div>\'; },';
			
		} else {
			$output .= '\'titleShow\' : false,';
		}
		
		$output .= '\'padding\' : ' . $this->getSlideshow()->getPadding() . ',';
		$output .= '\'margin\' : ' . $this->getSlideshow()->getMargin() . ',';
		$output .= '\'overlayOpacity\' : ' . $this->getSlideshow()->getOverlayOpacity() . ',';
		$output .= '\'overlayColor\' : \'' . $this->getSlideshow()->getOverlayColor() . '\',';
		
		if ( $this->getSlideshow()->autoplays() ) {
			$timer_name = $this->get_autoplay_timer_name();
			$output .= '\'onStart\' : function(){ start_' . $timer_name . '(); },';
			$output .= '\'onClosed\' : function(){ stop_' . $timer_name . '(); },';
		}
		
		$output .= '\'showNavArrows\' : ' . ( ( $this->getSlideshow()->showsNavigationArrows() ) ? 'true' : 'false' );
		
		$output .= '});';
		
		return $output;
	}
}

?>