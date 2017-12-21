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
final class ContainerTag {
	const Div = 'div';
	const Span = 'span';
	
	/**
	* Return a constant value from a string value
	*
	* @param	$value						the value to be evaluated and matched to a constant
	* 
	* @throws	UnexpectedValueException	when the value passed as a parameter does not match a valid constant
	*/
	static function fromValue( $value ) {
		switch( strtolower( $value ) ) {
			case self::Div:
				return self::Div;
			case self::Span:
				return self::Span;
			
			throw new \UnexpectedValueException("Container tag for gallery value is not valid");
		}
	}
}

?>