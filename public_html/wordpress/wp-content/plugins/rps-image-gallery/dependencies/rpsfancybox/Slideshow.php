<?php
	
namespace rpsfancybox;

require_once( dirname( dirname( __FILE__ ) ) . '/rpsslideshow/Slideshow.php' );

/**
* Class modeling a Slideshow object. A slideshow is composed of a gallery of thumbnails and an individual viewer for the larger version of each image
* 
* @author		Pablo S. Gallastegui
* @copyright	2015 Red Pixel Studios
* @version		1.0.0
* @package		rpsfancybox
*/

class Slideshow extends \rpsslideshow\Slideshow {	
    /** @var  SlideshowTransition		$_transition_in					the transition type for images appearing in the slideshow. */
    private $_transition_in;    
    /** @var  SlideshowTransition		$_transition_out				the transition type for images disappearing in the slideshow. */
    private $_transition_out;  
    /** @var  SlideshowTitlePosition	$_title_position				the position of the title in the slideshow. */
    private $_title_position;
    /** @var  rpsslideshow\Alignment	$_title_alignment				the alignment of the title in the slideshow. */
    private $_title_alignment;
    /** @var  boolean					$_show_title					boolean value specifying if the slideshow should show the title. */
    private $_show_title;
    /** @var  int						$_speed_in						the amount of milliseconds it takes for an image to appear. */
    private $_speed_in;
    /** @var  int						$_speed_out						the amount of milliseconds it takes for an image to disappear. */
    private $_speed_out;
    /** @var  boolean					$_show_close_button				boolean value specifying if the slideshow should show a close button. */
    private $_show_close_button;
    /** @var  boolean					$_show_navigation_arrows		boolean value specifying if the slideshow should show navigation arrows to switch images. */
    private $_show_navigation_arrows;
    /** @var  boolean					$_show_image_count_in_title		boolean value specifying if the title should show an image count. */
    private $_show_image_count_in_title;
    /** @var  boolean					$_show_download_link			boolean value specifying if the download link should show. */
    private $_show_download_link;
    /** @var  boolean					$_cycle							boolean value specifying if the images should cycle. */
    private $_cycle;
    /** @var  boolean					$_center_on_scroll				boolean value specifying if the slideshow should remain centered on screen while the user scrolls. */
    private $_center_on_scroll;
    /** @var  int						$_padding						amount of pixels of padding. */
    private $_padding;
    /** @var  int						$_margin						amount of pixels of margin. */
    private $_margin;
    /** @var  float						$_overlay_opacity				value in between 0 and 1 defining the opacity of the overlay. */
    private $_overlay_opacity;
    /** @var  string					$_overlay_color					hex value of the overlay color. */
    private $_overlay_color;
    /** @var  boolean					$_autoplay						boolean value specifying if the slideshow should autoplay. */
    private $_autoplay;
    /** @var  int						$_autoplay_time					the amount of seconds an image remains visible before transitioning to the next one. */
    private $_autoplay_time;
    /** @var  boolean					$_show_helper_thumbs			boolean value specifying if the helper thumbnails should show. */
    private $_show_helper_thumbs;
    /** @var  int						$_helper_thumbs_width			the width of the helper thumbs in pixels. */
    private $_helper_thumbs_width;
    /** @var  int						$_helper_thumbs_height			the height of the helper thumbs in pixels. */
    private $_helper_thumbs_height;
    
    /**
	* <CTOR>
	*
	* @param	Gallery		$gallery		the gallery to be displayed
	*/
	function __construct() {
		$this->setPadding( 10 );
		$this->setMargin( 20 );
		$this->setShowNagigationArrows( true );
		$this->setOverlayOpacity( .3 );
		$this->setOverlayColor( '#66666' );
		$this->setAutoplayTime( 6 );
	}
	
    /**
	* Set the transition type for images appearing in the slideshow
	*
	* @param	SlideshowTransition	$transition_in	the transition type for images appearing in the slideshow
	*/
	function setTransitionIn($transition_in) {
		$this->_transition_in = $transition_in;
	}
    /**
	* Get the transition type for images appearing in the slideshow
	*
	* @return	SlideshowTransition		the transition type for images appearing in the slideshow
	*/
	function getTransitionIn() {
		return $this->_transition_in;
	}
    
    /**
	* Set the transition type for images disappearing in the slideshow
	*
	* @param	SlideshowTransition	$transition_out	the transition type for images disappearing in the slideshow
	*/
	function setTransitionOut($transition_out) {
		$this->_transition_out = $transition_out;
	}
    /**
	* Get the transition type for images disappearing in the slideshow
	*
	* @return	SlideshowTransition		the transition type for images disappearing in the slideshow
	*/
	function getTransitionOut() {
		return $this->_transition_out;
	}
    
    /**
	* Set the position of the title in the slideshow
	*
	* @param	SlideshowTitlePosition	$title_position	the position of the title in the slideshow
	*/
	function setTitlePosition($title_position) {
		$this->_title_position = $title_position;
	}
    /**
	* Get the position of the title in the slideshow
	*
	* @return	SlideshowTitlePosition		the position of the title in the slideshow
	*/
	function getTitlePosition() {
		return $this->_title_position;
	}
    
    /**
	* Set the alignment of the title in the slideshow
	*
	* @param	rpsslideshow\Alignment	$title_position	the alignment of the title in the slideshow
	*/
	function setTitleAlignment($title_alignment) {
		$this->_title_alignment = $title_alignment;
	}
    /**
	* Get the position of the title in the slideshow
	*
	* @return	rpsslideshow\Alignment		the alignment of the title in the slideshow
	*/
	function getTitleAlignment() {
		return $this->_title_alignment;
	}
    
    /**
	* Set the boolean value specifying if the slideshow should show the title
	*
	* @param	boolean	$show_navigation_arrows	the boolean value specifying if the slideshow should show the title
	*/
	function setShowTitle($show_title) {
		$this->_show_title = $show_title;
	}
    /**
	* Get the boolean value specifying if the slideshow should show the title
	*
	* @return	boolean		the boolean value specifying if the slideshow should show the title
	*/
	function showsTitle() {
		return $this->_show_title;
	}
    
    /**
	* Set the amount of milliseconds it takes for an image to appear
	*
	* @param	int	$speed_in	the amount of milliseconds it takes for an image to appear. Range: 0-1000
	*/
	function setSpeedIn($speed_in) {
		if ( ( $speed_in < 0 ) || ( $speed_in > 1000 ) ) {
			throw new \RangeException( "Transition Speed In must be in between 0 and 1000 milliseconds" );
		}
		
		$this->_speed_in = $speed_in;
	}
    /**
	* Get the amount of milliseconds it takes for an image to appear
	*
	* @return	int		the amount of milliseconds it takes for an image to appear
	*/
	function getSpeedIn() {
		return $this->_speed_in;
	}
    
    /**
	* Set the amount of milliseconds it takes for an image to disappear
	*
	* @param	int	$speed_out	the amount of milliseconds it takes for an image to disappear
	*/
	function setSpeedOut($speed_out) {
		if ( ( $speed_out < 0 ) || ( $speed_out > 1000 ) ) {
			throw new \RangeException( "Transition Speed Out must be in between 0 and 1000 milliseconds" );
		}
		
		$this->_speed_out = $speed_out;
	}
    /**
	* Get the amount of milliseconds it takes for an image to disappear
	*
	* @return	int		the amount of milliseconds it takes for an image to disappear
	*/
	function getSpeedOut() {
		return $this->_speed_out;
	}
    
    /**
	* Set the boolean value specifying if the slideshow should show a close button
	*
	* @param	boolean	$show_close_button	the boolean value specifying if the slideshow should show a close button
	*/
	function setShowCloseButton($show_close_button) {
		$this->_show_close_button = $show_close_button;
	}
    /**
	* Get the boolean value specifying if the slideshow should show a close button
	*
	* @return	boolean		the boolean value specifying if the slideshow should show a close button
	*/
	function showsCloseButton() {
		return $this->_show_close_button;
	}
    
    /**
	* Set the boolean value specifying if the slideshow should show navigation arrows to switch images
	*
	* @param	boolean	$show_navigation_arrows	the boolean value specifying if the slideshow should show navigation arrows to switch images
	*/
	function setShowNagigationArrows($show_navigation_arrows) {
		$this->_show_navigation_arrows = $show_navigation_arrows;
	}
    /**
	* Get the boolean value specifying if the slideshow should show navigation arrows to switch images
	*
	* @return	boolean		the boolean value specifying if the slideshow should show navigation arrows to switch images
	*/
	function showsNavigationArrows() {
		return $this->_show_navigation_arrows;
	}
    
    /**
	* Set the boolean value specifying if the title should show an image count
	*
	* @param	boolean	$show_image_count_in_title	the boolean value specifying if the title should show an image count
	*/
	function setShowImageCountInTitle($show_image_count_in_title) {
		$this->_show_image_count_in_title = $show_image_count_in_title;
	}
    /**
	* Get the boolean value specifying if the title should show an image count
	*
	* @return	boolean		the boolean value specifying if the title should show an image count
	*/
	function showsImageCountInTitle() {
		return $this->_show_image_count_in_title;
	}
    
    /**
	* Set the boolean value specifying if the download link should show
	*
	* @param	boolean	$show_image_count_in_title	the boolean value specifying if the download link should show
	*/
	function setShowDownloadLink($show_download_link) {
		$this->_show_download_link = $show_download_link;
	}
    /**
	* Get the boolean value specifying if the download link should show
	*
	* @return	boolean		the boolean value specifying if the download link should show
	*/
	function showsDownloadLink() {
		return $this->_show_download_link;
	}
    
    /**
	* Set the boolean value specifying if the images should cycle
	*
	* @param	boolean	$cycle	the boolean value specifying if the images should cycle
	*/
	function setCycle($cycle) {
		$this->_cycle = $cycle;
	}
    /**
	* Get the boolean value specifying if the images should cycle
	*
	* @return	boolean		the boolean value specifying if the images should cycle
	*/
	function cycles() {
		return $this->_cycle;
	}
    
    /**
	* Set the boolean value specifying if the slideshow should remain centered on screen while the user scrolls
	*
	* @param	boolean	$center_on_scroll	the boolean value specifying if the slideshow should remain centered on screen while the user scrolls
	*/
	function setCenterOnScroll($center_on_scroll) {
		$this->_center_on_scroll = $center_on_scroll;
	}
    /**
	* Get the boolean value specifying if the slideshow should remain centered on screen while the user scrolls
	*
	* @return	boolean		the boolean value specifying if the slideshow should remain centered on screen while the user scrolls
	*/
	function centersOnScroll() {
		return $this->_center_on_scroll;
	}
    
    /**
	* Set the amount of pixels of padding
	*
	* @param	int	$padding	the amount of pixels of padding
	*/
	function setPadding($padding) {
		$this->_padding = $padding;
	}
    /**
	* Get the amount of pixels of padding
	*
	* @return	int		the amount of pixels of padding
	*/
	function getPadding() {
		return $this->_padding;
	}
    
    /**
	* Set the amount of pixels of margin
	*
	* @param	int	$margin	the amount of pixels of margin
	*/
	function setMargin($margin) {
		$this->_margin = $margin;
	}
    /**
	* Get the amount of pixels of margin
	*
	* @return	int		the amount of pixels of margin
	*/
	function getMargin() {
		return $this->_margin;
	}
    
    /**
	* Set a value in between 0 and 1 defining the opacity of the overlay
	*
	* @param	float						$overlay_opacity	a value in between 0 and 1 defining the opacity of the overlay
	*
	* @throws	\InvalidArgumentException						a value in between 0 and 1 defining the opacity of the overlay
	*/
	function setOverlayOpacity($overlay_opacity) {		
		if (($overlay_opacity < 0) || ( $overlay_opacity > 1) ) {
            throw new \RangeException('Opacity is a floating point number in between 0 and 1');
		} else {
			$this->_overlay_opacity = $overlay_opacity;
		}
	}
    /**
	* Get a value in between 0 and 1 defining the opacity of the overlay
	*
	* @return	float		a value in between 0 and 1 defining the opacity of the overlay
	*/
	function getOverlayOpacity() {
		return $this->_overlay_opacity;
	}
    
    /**
	* Set the hex value of the overlay color
	*
	* @param	string	$overlay_color	the hex value of the overlay color
	*/
	function setOverlayColor($overlay_color) {
		$this->_overlay_color = $overlay_color;
	}
    /**
	* Get the hex value of the overlay color
	*
	* @return	string		the hex value of the overlay color
	*/
	function getOverlayColor() {
		return $this->_overlay_color;
	}
    
    /**
	* Set the boolean value specifying if the slideshow should autoplay
	*
	* @param	boolean	$autoplay	the boolean value specifying if the slideshow should autoplay
	*/
	function setAutoplay($autoplay) {
		$this->_autoplay = $autoplay;
	}
    /**
	* Get the boolean value specifying if the slideshow should autoplay
	*
	* @return	boolean		the boolean value specifying if the slideshow should autoplay
	*/
	function autoplays() {
		return $this->_autoplay;
	}
    
    /**
	* Set the amount of seconds an image remains visible before transitioning to the next one
	*
	* @param	int	$autoplay_time	the amount of seconds an image remains visible before transitioning to the next one
	*/
	function setAutoplayTime($autoplay_time) {
		if ( ( $autoplay_time < 1 ) || ( $autoplay_time > 30 ) ) {
			throw new \RangeException( "Autoplay time must be in between 1 and 30 seconds" );
		}
		
		$this->_autoplay_time = $autoplay_time;
	}
    /**
	* Get the amount of seconds an image remains visible before transitioning to the next one
	*
	* @return	int		the amount of seconds an image remains visible before transitioning to the next one
	*/
	function getAutoplayTime() {
		return $this->_autoplay_time;
	}
	
    /**
	* Set the boolean value specifying if the helper thumbnails should show
	*
	* @param	boolean	$show_navigation_arrows	the boolean value specifying if the helper thumbnails should show
	*/
	function setShowHelperThumbs($show_helper_thumbs) {
		$this->_show_helper_thumbs = $show_helper_thumbs;
	}
    /**
	* Get the boolean value specifying if the helper thumbnails should show
	*
	* @return	boolean		the boolean value specifying if the helper thumbnails should show
	*/
	function showsHelperThumbs() {
		return $this->_show_helper_thumbs;
	}
	
    /**
	* Set the width of the helper thumbs in pixels
	*
	* @param	int		$helper_thumbs_width	the width of the helper thumbs in pixels
	*/
	function setHelperThumbsWidth($helper_thumbs_width) {
		$this->_helper_thumbs_width = $helper_thumbs_width;
	}
    /**
	* Get the width of the helper thumbs in pixels
	*
	* @return	int		the width of the helper thumbs in pixels
	*/
	function getHelperThumbsWidth() {
		return $this->_helper_thumbs_width;
	}

    /**
	* Set the height of the helper thumbs in pixels
	*
	* @param	int		$helper_thumbs_height	the height of the helper thumbs in pixels
	*/
	function setHelperThumbsHeight($helper_thumbs_height) {
		$this->_helper_thumbs_height = $helper_thumbs_height;
	}
    /**
	* Get the height of the helper thumbs in pixels
	*
	* @return	int		the height of the helper thumbs in pixels
	*/
	function getHelperThumbsHeight() {
		return $this->_helper_thumbs_height;
	}

    /**
	* Validate for conflicts on the settings
	*
	* @return	boolean								true if validation is successful

	* @throws	\UnexpectedValueException			a value is not what should be expected
	*/
	function validate() {
		if ($this->autoplays() && $this->showsNavigationArrows()) {
			throw new \UnexpectedValueException("Slideshow is set to autoplay and show navigation arrows");
		}
		
		return true;
	}
}

?>