<?php
	
namespace rpsslideshow;

/**
* Class modeling a Slideshow object
* 
* @author		Pablo S. Gallastegui
* @copyright	2015 Red Pixel Studios
* @version		1.0.0
* @package		rpsslideshow
*/

class Slideshow {	
    /** @var  string				$_id				a string containing the id of the slideshow. Corresponds to fancybox's group_name. */
    private $_id;
    /** @var  ImageSize				$_image_size		size of the images in the slideshow. */
    private $_image_size;
    /** @var  Gallery				$_gallery			a gallery of images in the slideshow. */
    private $_gallery;


    /**
	* Set the string containing the id of the slideshow
	*
	* @param	string	$image_size	the string containing the id of the slideshow
	*/
	function setId($id) {
		$this->_id = $id;
	}
    /**
	* Get the string containing the id of the slideshow
	*
	* @return	string		the string containing the id of the slideshow
	*/
	function getId() {
		return $this->_id;
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
	* Set a gallery of images in the slideshow
	*
	* @param	Gallery	$image_size	a gallery of images in the slideshow
	*/
	function setGallery($gallery) {
		$this->_gallery = $gallery;
	}
    /**
	* Get a gallery of images in the slideshow
	*
	* @return	Gallery		a gallery of images in the slideshow
	*/
	function getGallery() {
		return $this->_gallery;
	}
}

?>