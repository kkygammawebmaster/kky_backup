<?php

/**
 * Automatically load required classes.
 *
 * @since 				1.0.0
 */
spl_autoload_register( 'autoload_rpsslideshow' );

function autoload_rpsslideshow ( $class ) {

	// Make sure that the class being loaded is in the right namespace
	$namespace = 'rpsslideshow\\';
	
	$class_parts = explode( '\\', $class );
	array_shift( $class_parts );
	
	if ( substr( $class, 0, strlen( $namespace ) ) !== $namespace ) {
		return;
	}
	
	// Locate and load the file that contains the class
	$path = dirname( __FILE__ ) . DIRECTORY_SEPARATOR . implode( DIRECTORY_SEPARATOR, $class_parts ) . '.php';
		
	if ( file_exists( $path ) ) {
		require( $path );
	} else {
		throw ( new Exception( 'Class ' . $class . ' not found at ' . $path ) );
	}

}	
	
?>