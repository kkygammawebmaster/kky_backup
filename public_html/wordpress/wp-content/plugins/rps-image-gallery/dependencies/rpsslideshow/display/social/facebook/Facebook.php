<?php
	
namespace rpsslideshow\display\social\facebook;

/**
* Class utilized to setup the configuration of the Facebook integration with the gallery
* 
* @author		Pablo S. Gallastegui
* @copyright	2015 Red Pixel Studios
* @version		1.0.0
* @package		rpsslideshow\display\social\facebook
*/

class Facebook {
    /** @var  Action				$_action						the action to be performed by the Facebook button. */
    private $_action;
    /** @var  ColorScheme			$_color_scheme					the color scheme of the Facebook button. */
    private $_color_scheme;
    /** @var  ColorScheme			$_layout						the layout of the Facebook button. */
    private $_layout;
    /** @var  boolean				$_kid_directed_site				boolean value specifying if site or a portion of it is directed to children under 13 years of age. */
    private $_kid_directed_site;
    /** @var  boolean				$_sharing_enabled				boolean value specifying if a share button beside the Like button should be included. */
    private $_sharing_enabled;
    
    
    /**
	* Set the action to be performed by the Facebook button
	*
	* @param	Action	$action	the action to be performed by the Facebook button
	*/
	function setAction( $action ) {
		$this->_action = $action;
	}
    /**
	* Get the action to be performed by the Facebook button
	*
	* @return	Action		the action to be performed by the Facebook button
	*/
	function getAction() {
		return $this->_action;
	}
    
    /**
	* Set the color scheme of the Facebook button
	*
	* @param	ColorScheme	$color_scheme	the color scheme of the Facebook button
	*/
	function setColorScheme( $color_scheme ) {
		$this->_color_scheme = $color_scheme;
	}
    /**
	* Get the color scheme of the Facebook button
	*
	* @return	ColorScheme		the color scheme of the Facebook button
	*/
	function getColorScheme() {
		return $this->_color_scheme;
	}
    
    /**
	* Set the layout of the Facebook button
	*
	* @param	Layout	$layout	the layout of the Facebook button
	*/
	function setLayout( $layout ) {
		$this->_layout = $layout;
	}
    /**
	* Get the layout of the Facebook button
	*
	* @return	Layout		the layout of the Facebook button
	*/
	function getLayout() {
		return $this->_layout;
	}
    
    /**
	* Set the boolean value specifying if site or a portion of it is directed to children under 13 years of age
	*
	* @param	boolean	$kid_directed_site	the boolean value specifying if site or a portion of it is directed to children under 13 years of age
	*/
	function setKidDirectedSite($kid_directed_site) {
		$this->_kid_directed_site = $kid_directed_site;
	}
    /**
	* Get the boolean value specifying if site or a portion of it is directed to children under 13 years of age
	*
	* @return	boolean		the boolean value specifying if site or a portion of it is directed to children under 13 years of age
	*/
	function isKidDirectedSite() {
		return $this->_kid_directed_site;
	}
    
    /**
	* Set the boolean value specifying if a share button beside the Like button should be included
	*
	* @param	boolean	$sharing_enabled	the boolean value specifying if a share button beside the Like button should be included
	*/
	function setSharingEnabled($sharing_enabled) {
		$this->_sharing_enabled = $sharing_enabled;
	}
    /**
	* Get the boolean value specifying if a share button beside the Like button should be included
	*
	* @return	boolean		the boolean value specifying if a share button beside the Like button should be included
	*/
	function sharingIsEnabled() {
		return $this->_sharing_enabled;
	}
	
	/**
	* Get the HTML content reuquired to output the Facebook functionality for a URL
	*
	* @param	string	$url	the URL of the content to be liked/shared
	*
	* @return	string			the HTML needed to display the the Facebook functionality to the visitor
	*/
	function display( $url ) {
		$arguments = array();
		$arguments_processed = array();
		
		$arguments = array(
			'data-action' => $this->getAction(),
			'data-colorscheme' => $this->getColorScheme(),
			'data-kid-directed-site' => $this->isKidDirectedSite(),
			'data-layout' => $this->getLayout(),
			'data-share' => $this->sharingIsEnabled(),
		);
		
		array_filter( $arguments );
		
		foreach ( $arguments as $key => $value ) {
			$arguments_processed[] = $key . '="' . $value . '"';
		}
		
		$data = implode( ' ', $arguments_processed );
		
		$output = <<<EX
<div class="fb-like" data-href="$url" $data></div>
EX;
		return $output;
	}
}