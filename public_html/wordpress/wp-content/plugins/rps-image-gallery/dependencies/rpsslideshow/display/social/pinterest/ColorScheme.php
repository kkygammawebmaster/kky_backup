<?php
    
namespace rpsslideshow\display\social\pinterest;

/**
* Enumeration of the different color schemes allowed by Pinterest
*
* @author		Pablo S. Gallastegui
* @copyright	2015 Red Pixel Studios
* @version 		1.0.0
* @package		rpsslideshow\display\social\pinterest
*/
final class ColorScheme {
	const Red = 'red';
	const Gray = 'gray';
	const White = 'white';
	
	/**
	* Return a constant value from a string value
	*
	* @param	$value						the value to be evaluated and matched to a constant
	* 
	* @throws	UnexpectedValueException	when the value passed as a parameter does not match a valid constant
	*/
	static function fromValue( $value ) {
		switch( strtolower( $value ) ) {
			case self::Red:
				return self::Red;
			case self::Gray:
				return self::Gray;
			case self::White:
				return self::White;
			
			throw new \UnexpectedValueException("Color Scheme value is not valid");
		}
	}
}

?>