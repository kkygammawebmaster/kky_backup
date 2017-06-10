<?php
    
namespace rpsslideshow\display;

/**
* Enumeration of the different sizes for images
*
* @author		Pablo S. Gallastegui
* @copyright	2015 Red Pixel Studios
* @version 		1.0.0
* @package		rpsfancybox\display
*/
final class HTMLFormat {
	const DefaultFormat = 'default';
	const Legacy = 'legacy';
	const HTML5 = 'html5';
	
	/**
	* Return a constant value from a string value
	*
	* @param	$value						the value to be evaluated and matched to a constant
	* 
	* @throws	UnexpectedValueException	when the value passed as a parameter does not match a valid constant
	*/
	static function fromValue( $value ) {
		switch( strtolower( $value ) ) {
			case self::DefaultFormat:
				return self::DefaultFormat;
			case self::HTML5:
				return self::HTML5;
			case self::Legacy:
				return self::Legacy;
			
			throw new \UnexpectedValueException("Action value is not valid");
		}
	}
}

?>