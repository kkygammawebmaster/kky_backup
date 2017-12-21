<?php
    
namespace rpsslideshow;

/**
* Enumeration of the different alignments the title of the slideshow can be in
*
* @author		Pablo S. Gallastegui
* @copyright	2015 Red Pixel Studios
* @version 		1.0.0
* @package		rpsfancybox
*/
final class Alignment {
	const None = 'none';
	const Left = 'left';
	const Center = 'center';
	const Right = 'right';
	
	/**
	* Return a constant value from a string value
	*
	* @param	$value						the value to be evaluated and matched to a constant
	* 
	* @throws	UnexpectedValueException	when the value passed as a parameter does not match a valid constant
	*/
	static function fromValue( $value ) {
		switch( strtolower( $value ) ) {
			case self::None:
				return self::None;
			case self::Left:
				return self::Left;
			case self::Center:
				return self::Center;
			case self::Right:
				return self::Right;
			
			throw new \UnexpectedValueException("Alignment value is not valid");
		}
	}
}

?>