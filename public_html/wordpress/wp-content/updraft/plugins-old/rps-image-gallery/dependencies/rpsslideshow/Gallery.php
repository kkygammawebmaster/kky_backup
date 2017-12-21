<?php
	
namespace rpsslideshow;

/**
* Class modeling a Gallery of images
* 
* @author		Pablo S. Gallastegui
* @copyright	2015 Red Pixel Studios
* @version		1.0.0
* @package		rpsslideshow
*/

class Gallery {
    /** @var  Image[]				$_images						an array of the images in the gallery. */
    private $_images;
    /** @var  int					$_column_count					number of columns in the gallery. */
    private $_column_count;
    /** @var  bool					$_column_count_lock				whether the number of columns in the gallery is static. */
    private $_column_count_lock;
    /** @var  ImageSize				$_image_size					size of the images in the gallery. */
    private $_image_size;
    /** @var  Alignment				$_image_alignment				the alignment of the images in the gallery. */
    private $_image_alignment;
    /** @var  Slideshow				$_slideshow						object specifying the slideshow setup for the gallery. If null, no slideshow is displayed. */
    private $_slideshow;
    /** @var  Classes				$_classes						string of custom css classes for the gallery. */
    private $_classes;
    
    /**
	* <CTOR>
	*/
	function __construct() {
		$this->setImages( array() );
	}
    
    /**
	* Set an array of the images in the gallery
	*
	* @param	int	$images	an array of the images in the gallery
	*/
	function setImages($images) {
		$this->_images = $images;
	}
    /**
	* Get an array of the images in the gallery
	*
	* @return	int		an array of the images in the gallery
	*/
	function getImages() {
		return $this->_images;
	}   
    
    /**
	* Set the number of columns in the gallery
	*
	* @param	int	$column_count	the number of columns in the gallery
	*/
	function setColumnCount($column_count) {
		$this->_column_count = $column_count;
	}
    /**
	* Get the number of columns in the gallery
	*
	* @return	int		the number of columns in the gallery
	*/
	function getColumnCount() {
		return $this->_column_count;
	}
    
    /**
	* Set the custom css classes for the gallery
	*
	* @param	string	$class	the custom css classes for the gallery
	*/
	function setClasses($classes) {
		$this->_classes = $classes;
	}
    /**
	* Get the custom css classes for the gallery
	*
	* @return	string		the custom css classes for the gallery
	*/
	function getClasses() {
		return $this->_classes;
	}
    
    /**
	* Set the column count lock setting.
	*
	* @param	bool	$column_count	whether the number of columns in the gallery is static
	*/
	function setColumnCountLock($column_count_lock) {
		$this->_column_count_lock = $column_count_lock;
	}
    /**
	* Get the column count lock setting.
	*
	* @return	bool 	whether the number of columns in the gallery is static
	*/
	function getColumnCountLock() {
		return $this->_column_count_lock;
	}
    
    /**
	* Set the size of the images in the gallery
	*
	* @param	ImageSize	$image_size	the size of the images in the gallery
	*/
	function setImageSize($image_size) {
		$this->_image_size = $image_size;
	}
    /**
	* Get the size of the images in the gallery
	*
	* @return	ImageSize		the size of the images in the gallery
	*/
	function getImageSize() {
		return $this->_image_size;
	}
    
    /**
	* Set the alignment of the images in the gallery
	*
	* @param	Alignment	$image_alignment	the alignment of the images in the gallery
	*/
	function setImageAlignment($image_alignment) {
		$this->_image_alignment = $image_alignment;
	}
    /**
	* Get the alignment of the images in the gallery
	*
	* @return	Alignment		the alignment of the images in the gallery
	*/
	function getImageAlignment() {
		return $this->_image_alignment;
	}
    
    /**
	* Set the object specifying the slideshow setup for the gallery
	*
	* @param	Slideshow	$slideshow	the object specifying the slideshow setup for the gallery
	*/
	function setSlideshow($slideshow) {
		$this->_slideshow = $slideshow;
	}
    /**
	* Get the object specifying the slideshow setup for the gallery
	*
	* @return	Slideshow		the object specifying the slideshow setup for the gallery
	*/
	function getSlideshow() {
		return $this->_slideshow;
	}
	
	/**
	* Add an image to the gallery
	*
	* @param	Image	$image	the image to be added
	*/
	function addImage( $image ) {
		$this->_images[] = $image;
	}
	
}

?>