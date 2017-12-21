<?php

$purelinethemename = "Pure Line";

if ( STYLESHEETPATH == TEMPLATEPATH ) {
	define('PUR_URL', TEMPLATEPATH . '/library/functions/');
	define('PUR_DIRECTORY', get_template_directory_uri() . '/library/functions/');
} else {
	define('PUR_URL', STYLESHEETPATH . '/library/functions/');
	define('PUR_DIRECTORY', get_template_directory_uri() . '/library/functions/');
}

require_once( get_template_directory() . '/library/functions/options-framework.php' );
require_once( get_template_directory() . '/library/functions/basic-functions.php' );

?>