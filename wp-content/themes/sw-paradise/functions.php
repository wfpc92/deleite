<?php 
$language = ( get_locale() == 'en_US' ) ? '' : get_locale();
if ( !defined('SW_PARADISETHEME') ){
	define( 'SW_PARADISETHEME', 'paradise_theme' . $language );
}

/**
 * Variables
 */
require_once (get_template_directory().'/lib/defines.php');
/**
 * Roots includes
 */
require_once (get_template_directory().'/lib/classes.php');		// Utility functions
require_once (get_template_directory().'/lib/utils.php');			// Utility functions
require_once (get_template_directory().'/lib/init.php');			// Initial theme setup and constants
require_once (get_template_directory().'/lib/cleanup.php');		// Cleanup
require_once (get_template_directory().'/lib/nav.php');			// Custom nav modifications
require_once (get_template_directory().'/lib/widgets.php');		// Sidebars and widgets
require_once (get_template_directory().'/lib/scripts.php');		// Scripts and stylesheets
require_once (get_template_directory().'/lib/customizer.php');	// Custom functions
require_once (get_template_directory().'/lib/plugin-requirement.php');			// Custom functions
require_once (get_template_directory().'/lib/metabox.php');	// Custom functions

if( class_exists( 'Woocommerce' ) ){
	require_once (get_template_directory().'/lib/plugins/currency-converter/currency-converter.php'); // currency converter
	require_once (get_template_directory().'/lib/woocommerce-hook.php');	// Utility functions
}
