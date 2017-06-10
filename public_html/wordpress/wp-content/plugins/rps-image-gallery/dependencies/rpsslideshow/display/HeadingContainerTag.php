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
final class HeadingContainerTag {
	const H1 = 'h1';
	const H2 = 'h2';
	const H3 = 'h3';
	const H4 = 'h4';
	const H5 = 'h5';
	const H6 = 'h6';
	
	/**
	* Return a constant value from a string value
	*
	* @param	$value						the value to be evaluated and matched to a constant
	* 
	* @throws	UnexpectedValueException	when the value passed as a parameter does not match a valid constant
	*/
	static function fromValue( $value ) {
		switch( strtolower( $value ) ) {
			case self::H1:
				return self::H1;
			case self::H2:
				return self::H2;
			case self::H3:
				return self::H3;
			case self::H4:
				return self::H4;
			case self::H5:
				return self::H5;
			case self::H6:
				return self::H6;
			
			throw new \UnexpectedValueException("Container tag for heading value is not valid");
		}
	}
}

?>