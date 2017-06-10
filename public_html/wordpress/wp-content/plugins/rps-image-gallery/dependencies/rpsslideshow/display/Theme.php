<?php
    
namespace rpsslideshow\display;

/**
* Enumeration of the different theme options for formats.
*
* @author		Pablo S. Gallastegui
* @author 		Eric Kyle
* @copyright	2015 Red Pixel Studios
* @version 		1.0.0
* @package		rpsfancybox\display
*/
final class Theme {
	const DefaultTheme = 'default';
	const None = 'none';
	
	/**
	* Return a constant value from a string value
	*
	* @param	$value						the value to be evaluated and matched to a constant
	* 
	* @throws	UnexpectedValueException	when the value passed as a parameter does not match a valid constant
	*/
	static function fromValue( $value ) {
		switch( strtolower( $value ) ) {
			case self::DefaultTheme:
				return self::DefaultTheme;
			case self::None:
				return self::None;
			
			throw new \UnexpectedValueException("Action value is not valid");
		}
	}
}

?>