<?php
	
namespace rpsslideshow\display\social\pinterest;

/**
* Class utilized to setup the configuration of the Facebook integration with the gallery
* 
* @author		Pablo S. Gallastegui
* @copyright	2015 Red Pixel Studios
* @version		1.0.0
* @package		rpsslideshow\display\social\pinterest
*/

class Pinterest {
    /** @var  ColorScheme			$_color_scheme					the color scheme of the Pinterest button. */
    private $_color_scheme;
    
    
    /**
	* Set the color scheme of the Pinterest button
	*
	* @param	ColorScheme	$color_scheme	the color scheme of the Pinterest button
	*/
	function setColorScheme( $color_scheme ) {
		$this->_color_scheme = $color_scheme;
	}
    /**
	* Get the color scheme of the Pinterest button
	*
	* @return	ColorScheme		the color scheme of the Pinterest button
	*/
	function getColorScheme() {
		return $this->_color_scheme;
	}
	
	/**
	* Get the HTML content reuquired to output the Facebook functionality for a URL
	*
	* @param	string	$url				the URL of the content to be liked/shared
	* @param	string	$$large_image_src	the URL of the large image being shared
	* @param	string	$caption_value		the caption of the content being shared
	*
	* @return	string			the HTML needed to display the the Facebook functionality to the visitor
	*/
	function display( $url, $large_image_src, $caption_value ) {		
		$encoded_url = urlencode( $url );
		$media = urlencode( $large_image_src );
		$description = urlencode( $caption_value );
		$color = $this->getColorScheme();
		
		$output = <<<EX
<a href="//www.pinterest.com/pin/create/button/?url={$encoded_url}&media={$media}&description={$description}" data-pin-do="buttonPin" data-pin-config="none" data-pin-color="{$color}"><img src="//assets.pinterest.com/images/pidgets/pinit_fg_en_rect_{$color}_20.png" title="Pin it"></a>
EX;

		return $output;
	}
}