<?php
/**
 * yatheme initial setup and constants
 */
function sw_paradise_setup() {
	// Make theme available for translation
	load_theme_textdomain('sw-paradise', get_template_directory() . '/lang');

	// Register wp_nav_menu() menus (http://codex.wordpress.org/Function_Reference/register_nav_menus)
	register_nav_menus(array(
		'primary_menu' => esc_html__('Primary Menu', 'sw-paradise'),
		'vertical_menu' => esc_html__('Vertical Menu', 'sw-paradise'),
	));
	
	/* Add automatic feed link */
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'sw_theme' );
	
	add_theme_support( 'wc-product-gallery-lightbox' );
	if( sw_paradise_options()->getCpanelValue( 'product_zoom' ) ) :
		add_theme_support( 'wc-product-gallery-zoom' );
	endif;
	
	/* Add support title tag */
	add_theme_support( "title-tag" );
	
	add_theme_support('bootstrap-gallery');
	// Add post thumbnails (http://codex.wordpress.org/Post_Thumbnails)
	add_theme_support('post-thumbnails');

	// Add post formats (http://codex.wordpress.org/Post_Formats)
	add_theme_support('post-formats', array('aside', 'gallery', 'link', 'image', 'quote', 'status', 'video', 'audio', 'chat'));

	// Custom image header
	$sw_paradise_header_arr = array(
		'default-image' => get_template_directory_uri().'/assets/img/logo-default.png',
		'uploads'       => true
	);
	add_theme_support( 'custom-header', $sw_paradise_header_arr );
	
	// Custom Background 
	$sw_paradise_bgarr = array(
		'default-color' => 'ffffff',
		'default-image' => '',
	);
	add_theme_support( 'custom-background', $sw_paradise_bgarr );

	// Add images size
	add_image_size('paradise-blogpost-thumb', 870, 400, true);
	// Add images 
	add_image_size('paradise-product-thumb', 600, 711, true);
	
	// Tell the TinyMCE editor to use a custom stylesheet
	add_editor_style('/assets/css/editor-style.css');
	
	new SW_PARADISE_Menu();
}
add_action('after_setup_theme', 'sw_paradise_setup');

