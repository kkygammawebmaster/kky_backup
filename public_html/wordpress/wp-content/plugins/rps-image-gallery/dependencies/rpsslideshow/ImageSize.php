<?php
    
namespace rpsslideshow;

/**
* Enumeration of the different sizes for images
*
* @author		Pablo S. Gallastegui
* @copyright	2015 Red Pixel Studios
* @version 		1.0.0
* @package		rpsslideshow
*/
final class ImageSize {
	const Thumbnail = 'thumbnail';
	const Medium = 'medium';
	const Large = 'large';
	const Original = 'full';
	
	/**
	* Return a constant value from a string value
	*
	* @param	$value						the value to be evaluated and matched to a constant
	* 
	* @throws	UnexpectedValueException	when the value passed as a parameter does not match a valid constant
	*/
	static function fromValue( $value ) {
		switch( strtolower( $value ) ) {
			case self::Thumbnail:
				return self::Thumbnail;
			case self::Medium:
				return self::Medium;
			case self::Large:
				return self::Large;
			case self::Original:
				return self::Original;
			
			throw new \UnexpectedValueException("Image Size is not valid");
		}
	}
}

?>