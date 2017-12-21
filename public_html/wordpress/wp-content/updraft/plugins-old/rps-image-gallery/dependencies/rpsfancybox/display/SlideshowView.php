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

abstract class SlideshowView {
    /** @var  Slideshow											$_slideshow						the slideshow code to be displayed. */
    private $_slideshow;
    
    /**
	* <CTOR>
	*
	* @param	Slideshow		$slideshow		the slideshow code to be displayed
	*/
	function __construct( $slideshow ) {
		$this->setSlideshow( $slideshow );
	}
    
    /**
	* Set the slideshow code to be displayed
	*
	* @param	Slideshow	$slideshow	the slideshow code to be displayed
	*/
	function setSlideshow( $slideshow ) {
		$this->_slideshow = $slideshow;
	}
    /**
	* Get the slideshow code to be displayed
	*
	* @return	Slideshow		the slideshow code to be displayed
	*/
	function getSlideshow() {
		return $this->_slideshow;
	}
	
    /**
    * Display a piece of javascript code responsible for handling the slideshow output to screen
    *
    * @param	bool	$display_progress_bar		boolean value specifying if a progress bar should be displayed
    *
    */
    function display( $display_progress_bar = true ) {
		$output = '';
		
		$output .= $this->display_fancybox();
		
		return $output;
	}
	
	/**
	* Display the code necessary for the fancybox library to display it
	*/
	abstract protected function display_fancybox();
}

?>