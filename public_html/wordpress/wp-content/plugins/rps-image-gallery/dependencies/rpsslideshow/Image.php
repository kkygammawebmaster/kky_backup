<?php
	
namespace rpsslideshow;

/**
* Class modeling an Image object
* 
* @author		Pablo S. Gallastegui
* @copyright	2015 Red Pixel Studios
* @version		1.0.0
* @package		rpsslideshow
*/

class Image {
    /** @var  int					$_id				integer value specifying the attachment id. */
    private $_id;
    /** @var  string				$_heading			the heading of the image. */
    private $_heading;
    /** @var  string				$_title				the title of the image. */
    private $_title;
    /** @var  string				$_caption			the caption of the image. */
    private $_caption;
    /** @var  string				$_alternative_text	the alternative text to be displayed for the image. */
    private $_alternative_text;
    /** @var  int					$_small_height		pixel height of the small sized image. */
    private $_small_height;
    /** @var  int					$_small_width		pixel width of the small sized image. */
    private $_small_width;
    /** @var  string				$_small_url			the url of the small sized image. */
    private $_small_url;
    /** @var  string				$_large_url			the url of the large sized image. */
    private $_large_url;
    /** @var  string				$_source			the source of the image. It may be an image URL or a page containing information about it. */
    private $_source;
    /** @var  string				$_exif				the exif data of the image. */
    private $_exif;
    /** @var  string				$_target			the target of the image link. */
    private $_target;
    /** @var  boolean				$_display_title		boolean value specifying if the gallery should display the image title. */
    private $_display_title;
    /** @var  boolean				$_display_caption	boolean value specifying if the gallery should display the image caption. */
    private $_display_caption;
    /** @var  Alignment				$_title_alignment	the alignment of the title of the images in the gallery. */
    private $_title_alignment;
    /** @var  boolean				$_external_link		boolean value specifying if the source of the image is an external link. */
    private $_external_link;
    /** @var  string				$_orientation		string value specifying the image orientation. */
    private $_orientation;
    
    
    /**
	* Set the id of the image
	*
	* @param	int		$id	the id of the image
	*/
	function setId($id) {
		$this->_id = $id;
	}
    /**
	* Get the id of the image
	*
	* @return	int		the id of the image
	*/
	function getId() {
		return $this->_id;
	}
    
    /**
	* Set the heading of the image
	*
	* @param	string	$heading	the heading of the image
	*/
	function setHeading($heading) {
		$this->_heading = $heading;
	}
    /**
	* Get the heading of the image
	*
	* @return	string		the heading of the image
	*/
	function getHeading() {
		return $this->_heading;
	}
    
    /**
	* Set the title of the image
	*
	* @param	string	$title	the title of the image
	*/
	function setTitle($title) {
		$this->_title = $title;
	}
    /**
	* Get the title of the image
	*
	* @return	string		the title of the image
	*/
	function getTitle() {
		return $this->_title;
	}
    
    /**
	* Set the caption of the image
	*
	* @param	string	$caption	the caption of the image
	*/
	function setCaption($caption) {
		$this->_caption = $caption;
	}
    /**
	* Get the caption of the image
	*
	* @return	string		the caption of the image
	*/
	function getCaption() {
		return $this->_caption;
	}
    
    /**
	* Set the alternative text to be displayed for the image
	*
	* @param	string	$alternative_text	the alternative text to be displayed for the image
	*/
	function setAlternativeText($alternative_text) {
		$this->_alternative_text = $alternative_text;
	}
    /**
	* Get the alternative text to be displayed for the image
	*
	* @return	string		the alternative text to be displayed for the image
	*/
	function getAlternativeText() {
		return $this->_alternative_text;
	}
    
    /**
	* Set the height of the small sized image
	*
	* @param	int		$small_height	the height of the small sized image
	*/
	function setSmallHeight($small_height) {

		if ( ! is_int( $small_height ) ) {
			error_log( 'RPS Image Gallery: The height value provided is not an integer and has been converted' );
			$small_height = intval( $small_height );
		}
		
		$this->_small_height = $small_height;

	}
    /**
	* Get the height of the small sized image
	*
	* @return	int		the height of the small sized image
	*/
	function getSmallHeight() {
		return $this->_small_height;
	}
    
    /**
	* Set the width of the small sized image
	*
	* @param	int		$small_width	the width of the small sized image
	*/
	function setSmallWidth($small_width) {

		if ( ! is_int( $small_width ) ) {
			error_log( 'RPS Image Gallery: The width value provided is not an integer and has been converted.' );
			$small_width = intval( $small_width );
		}

		$this->_small_width = $small_width;

	}
    /**
	* Get the width of the small sized image
	*
	* @return	int		the width of the small sized image
	*/
	function getSmallWidth() {
		return $this->_small_width;
	}
    
    /**
	* Set the url of the small sized image
	*
	* @param	string	$small_url	the url of the small sized image
	*/
	function setSmallUrl($small_url) {
		$this->_small_url = $small_url;
	}
    /**
	* Get the url of the small sized image
	*
	* @return	string		the url of the small sized image
	*/
	function getSmallUrl() {
		return $this->_small_url;
	}
    
    /**
	* Set the url of the large sized image
	*
	* @param	string	$large_url	the url of the large sized image
	*/
	function setLargeUrl($large_url) {
		$this->_large_url = $large_url;
	}
    /**
	* Get the url of the large sized image
	*
	* @return	string		the url of the large sized image
	*/
	function getLargeUrl() {
		return $this->_large_url;
	}
    
    /**
	* Set the source of the image. It may be an image URL or a page containing information about it.
	*
	* @param	string	$large_url	the source of the image.
	*/
	function setSource($source) {
		$this->_source = $source;
	}
    /**
	* Get the source of the image. It may be an image URL or a page containing information about it.
	*
	* @return	string		the source of the image.
	*/
	function getSource() {
		return $this->_source;
	}
    
    /**
	* Set the exif data of the image
	*
	* @param	string	$exif	the exif data of the image
	*/
	function setExif($exif) {
		$this->_exif = $exif;
	}
    /**
	* Get the exif data of the image
	*
	* @return	string		the exif data of the image
	*/
	function getExif() {
		return $this->_exif;
	}
    
    /**
	* Set the target of the image link
	*
	* @param	string	$target	the target of the image link
	*/
	function setTarget($target) {
		$this->_target = $target;
	}
    /**
	* Get the the target of the image link
	*
	* @return	string		the target of the image link
	*/
	function getTarget() {
		return $this->_target;
	}
    
    /**
	* Set the boolean value specifying if the gallery should display the image title
	*
	* @param	boolean	$display_title	the boolean value specifying if the gallery should display the image title
	*/
	function setDisplayTitle($display_title) {
		$this->_display_title = $display_title;
	}
    /**
	* Get the boolean value specifying if the gallery should display the image title
	*
	* @return	boolean		the boolean value specifying if the gallery should display the image title
	*/
	function displaysTitle() {
		return $this->_display_title;
	}
    
    /**
	* Set the boolean value specifying if the gallery should display the image caption
	*
	* @param	boolean	$display_title	the boolean value specifying if the gallery should display the image caption
	*/
	function setDisplayCaption($display_caption) {
		$this->_display_caption = $display_caption;
	}
    /**
	* Get the boolean value specifying if the gallery should display the image caption
	*
	* @return	boolean		the boolean value specifying if the gallery should display the image caption
	*/
	function displaysCaption() {
		return $this->_display_caption;
	}
    
    /**
	* Set the alignment of the title of the images in the gallery
	*
	* @param	Alignment	$title_alignment	the alignment of the title of the images in the gallery
	*/
	function setTitleAlignment($title_alignment) {
		$this->_title_alignment = $title_alignment;
	}
    /**
	* Get the alignment of the title of the images in the gallery
	*
	* @return	Alignment		the alignment of the title of the images in the gallery
	*/
	function getTitleAlignment() {
		return $this->_title_alignment;
	}
    
    /**
	* Set the boolean value specifying if the source of the image is an external link
	*
	* @param	boolean		the boolean value specifying if the source of the image is an external link
	*/
	function setExternalLink($external_link) {
		$this->_external_link = $external_link;
	}
    /**
	* Get the boolean value specifying if the source of the image is an external link
	*
	* @return	boolean		boolean value specifying if the source of the image is an external link
	*/
	function hasExternalLink() {
		return $this->_external_link;
	}

    /**
	* Set the boolean value specifying the image orientation
	*
	* @param	string 		string value specifying the image orientation
	*/
	function setOrientation($orientation) {
		$this->_orientation = $orientation;
	}
    /**
	* Get the boolean value specifying the image orientation
	*
	* @return	string		string value specifying the image orientation
	*/
	function getOrientation() {
		return $this->_orientation;
	}
}

?>