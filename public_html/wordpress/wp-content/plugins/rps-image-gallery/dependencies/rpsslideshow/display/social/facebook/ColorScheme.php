<?php
    
namespace rpsslideshow\display\social\facebook;

/**
* Enumeration of the different color schemes allowed by Facebook
*
* @author		Pablo S. Gallastegui
* @copyright	2015 Red Pixel Studios
* @version 		1.0.0
* @package		rpsslideshow\display\social\facebook
*/
final class ColorScheme {
	const Light = 'light';
	const Dark = 'dark';
	
	/**
	* Return a constant value from a string value
	*
	* @param	$value						the value to be evaluated and matched to a constant
	* 
	* @throws	UnexpectedValueException	when the value passed as a parameter does not match a valid constant
	*/
	static function fromValue( $value ) {
		switch( strtolower( $value ) ) {
			case self::Light:
				return self::Light;
			case self::Dark:
				return self::Dark;
			
			throw new \UnexpectedValueException("Color Scheme value is not valid");
		}
	}
}

?>