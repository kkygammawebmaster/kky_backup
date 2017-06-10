<?php
    
namespace rpsfancybox;

/**
* Enumeration of the different positions the title of the slideshow can be in
*
* @author		Pablo S. Gallastegui
* @copyright	2015 Red Pixel Studios
* @version 		1.0.0
* @package		rpsfancybox
*/
final class SlideshowTitlePosition {
	const Outside = 'outside';
	const Inside = 'inside';
	const Over = 'over';
	
	/**
	* Return a constant value from a string value
	*
	* @param	$value						the value to be evaluated and matched to a constant
	* 
	* @throws	UnexpectedValueException	when the value passed as a parameter does not match a valid constant
	*/
	static function fromValue( $value ) {
		switch( strtolower( $value ) ) {
			case self::Outside:
				return self::Outside;
			case self::Inside:
				return self::Inside;
			case self::Over:
				return self::Over;
			
			throw new \UnexpectedValueException("Transition value is not valid");
		}
	}
}

?>