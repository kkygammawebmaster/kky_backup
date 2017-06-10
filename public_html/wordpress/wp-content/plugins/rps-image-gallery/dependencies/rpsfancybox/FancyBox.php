<?php
	
namespace rpsfancybox;

require_once('Slideshow.php');

/**
* Class modeling a FancyBox object, utilized to output the HTML and Javascript code needed to display a gallery
* 
* @author		Pablo S. Gallastegui
* @copyright	2015 Red Pixel Studios
* @version		1.0.0
* @package		rpsfancybox
*/

class FancyBox {
    /** @var  Slideshow					$_slideshow					the slideshow being output. */
    private $_slideshow;
    /** @var  FancyBoxContainerType		$_container_type			the type of container for the gallery. */
    private $_container_type; 
    /** @var  HTMLFormat				$_format					the format used for the output. */
    private $_format;
    
    /**
	* <CTOR>
	*
	* @param 	Slideshow	$slideshow		the slideshow being output
	*/
	function __construct() {
		$this->setSlideshow( new Slideshow() );
	}
	
    /**
	* Set the value for the slideshow
	*
	* @param	Slideshow	$id	the value for the slideshow
	*/
	private function setSlideshow($slideshow) {
		$this->_slideshow = $slideshow;
	}
    
    /**
	* Set the type of container for the gallery
	*
	* @param	FancyBoxContainerType	$container_type	the type of container for the gallery
	*/
	function setContainerType($container_type) {
		$this->_container_type = $container_type;
	}
    /**
	* Get the type of container for the gallery
	*
	* @return	FancyBoxContainerType		the type of container for the gallery
	*/
	function getContainerType() {
		return $this->_container_type;
	}
    
    /**
	* Set the format used for the output
	*
	* @param	HTMLFormat	$transition_in	the format used for the output
	*/
	function setFormat($format) {
		$this->_format = $format;
	}
    /**
	* Get the format used for the output
	*
	* @return	HTMLFormat		the format used for the output
	*/
	function getFormat() {
		return $this->_format;
	}
}

?>