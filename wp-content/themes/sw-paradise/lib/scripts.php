<?php
/**
 * Enqueue scripts and stylesheets
 *
 */

function sw_paradise_scripts() {	
	$scheme = sw_paradise_options()->getCpanelValue('scheme');
	if ($scheme){
		$app_css = get_template_directory_uri() . '/css/app-'.$scheme.'.css';
	} else {
		$app_css = get_template_directory_uri() . '/css/app-default.css';
	}
	wp_register_style('bootstrap', get_template_directory_uri() . '/css/bootstrap.min.css', array(), null);
	wp_register_style('rtl_css', get_template_directory_uri() . '/css/rtl.css', array(), null);
	wp_register_style('yatheme_css', $app_css, array(), null);
	 wp_register_style('slick_slider_css', get_template_directory_uri() . '/css/slick.css', array(), null);
	wp_register_style('fancybox_css', get_template_directory_uri() . '/css/jquery.fancybox.css', array(), null);
	wp_register_style('yatheme_responsive_css', get_template_directory_uri() . '/css/app-responsive.css', array('yatheme_css'), null);
	/* register script */

	wp_register_script('modernizr', get_template_directory_uri() . '/js/modernizr-2.6.2.min.js', false, null, false);
	wp_register_script('bootstrap_js', get_template_directory_uri() . '/js/bootstrap.min.js', array('jquery'), null, true);
	wp_register_script('gallery_load_js', get_template_directory_uri() . '/js/load-image.min.js', array('bootstrap_js'), null, true);
	wp_register_script('bootstrap_gallery_js', get_template_directory_uri() . '/js/bootstrap-image-gallery.min.js', array('gallery_load_js'), null, true);
	wp_register_script('plugins_js', get_template_directory_uri() . '/js/plugins.js', array('jquery'), null, true);	
	wp_register_script('fancybox_js', get_template_directory_uri() . '/js/jquery.fancybox.pack.js', array('jquery'), null, true);
	wp_register_script('slick_slider',get_template_directory_uri().'/js/slick.min.js',array(),null,true);
	wp_register_script('isotope_script', get_template_directory_uri() . '/js/isotope.js', array(), null, true);
	wp_register_script('wc-quantity', get_template_directory_uri() . '/js/wc-quantity-increment.min.js', array('jquery'), null, true);
	wp_register_script('paradise_megamenu', get_template_directory_uri() . '/js/megamenu.js', array(), null, true);
	wp_register_script('sw_paradise_js', get_template_directory_uri() . '/js/main.js', array('bootstrap_js', 'plugins_js'), null, true);

	
	
	/* enqueue script & style */
	if ( !is_admin() ){			
		wp_dequeue_style('slick_slider_css');
		wp_dequeue_style('tabcontent_styles');
		wp_enqueue_style('bootstrap');	
		if( is_rtl() || $sw_paradise_direction = 'rtl' ){
			wp_enqueue_style('rtl_css');
		}
		
		wp_enqueue_script('fancybox_js');
		wp_enqueue_style('fancybox_css');
		wp_enqueue_style('yatheme_css');
		wp_enqueue_script('slick_slider');
		wp_enqueue_script('isotope_script');
		wp_enqueue_script('cloud-zoom');
		wp_enqueue_script('wc-quantity');
		
		wp_enqueue_style('yatheme_responsive_css');
	
		
		/* is_rtl() && wp_enqueue_style('bootstrap_rtl_css'); */
		/* Load style.css from child theme */
		if (is_child_theme()) {
			wp_enqueue_style('paradise_child_css', get_stylesheet_uri(), false, null);
		}
	}
	if (is_single() && comments_open() && get_option('thread_comments')) {
		wp_enqueue_script('comment-reply');
	}		
	
	$is_category = is_category() && !is_category('blog');
	if ( !is_admin() ){
		wp_enqueue_script('modernizr');
		wp_enqueue_script('sw_paradise_js');
	}
	
	if( sw_paradise_options()-> getCpanelValue( 'menu_type' ) == 'mega' ){
		wp_enqueue_script('paradise_megamenu');	
	}
}
add_action('wp_enqueue_scripts', 'sw_paradise_scripts', 100);
