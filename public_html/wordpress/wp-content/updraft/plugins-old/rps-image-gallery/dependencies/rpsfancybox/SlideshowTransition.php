<?php
    
namespace rpsfancybox;

/**
* Enumeration of the different transition types
*
* @author		Pablo S. Gallastegui
* @copyright	2015 Red Pixel Studios
* @version 		1.0.0
* @package		rpsfancybox
*/
final class SlideshowTransition {
	const Elastic = 'elastic';
	const Fade = 'fade';
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
			case self::Elastic:
				return self::Elastic;
			case self::Fade:
				return self::Fade;
			case self::None:
				return self::None;
			
			throw new \UnexpectedValueException("Transition value is not valid");
		}
	}
}

?>