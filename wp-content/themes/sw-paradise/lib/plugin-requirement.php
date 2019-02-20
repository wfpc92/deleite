<?php
/***** Active Plugin ********/
require_once( get_template_directory().'/lib/class-tgm-plugin-activation.php' );

add_action( 'tgmpa_register', 'sw_paradise_register_required_plugins' );
function sw_paradise_register_required_plugins() {
    $plugins = array(
        array(
            'name'               => esc_html__( 'WooCommerce', 'sw-paradise' ), 
            'slug'               => 'woocommerce', 
            'required'           => true, 
			'version'            => '3.4.2'
        ),

         array(
            'name'               => esc_html__( 'Revslider', 'sw-paradise' ), 
            'slug'               => 'revslider', 
            'source'             => esc_url( 'http://demo.wpthemego.com/themes/sw_paradise/plugins/revslider.zip' ), 
            'required'           => true, 
        ),
        
        array(
            'name'               => esc_html__( 'SW Core', 'sw-paradise' ),
            'slug'               => 'sw_core',
			'source'         	 => esc_url( 'http://demo.wpthemego.com/themes/sw_paradise/plugins/sw_core.zip' ), 
            'required'           => true,
			'version'			 => '1.0.1'
            ),
        
            array(
            'name'               => esc_html__( 'SW WooCommerce', 'sw-paradise' ),
            'slug'               => 'sw_woocommerce',
			'source'         	 => esc_url( 'http://demo.wpthemego.com/themes/sw_paradise/plugins/sw_woocommerce.zip' ), 
            'required'           => true,
			'version'            => '1.3.1'
        ),
		
		 array(
            'name'               => esc_html__( 'Sw Woocommerce Swatches', 'sw-paradise' ),
            'slug'               => 'sw_wooswatches',
			'source'         	 => esc_url( 'http://demo.wpthemego.com/themes/sw_paradise/plugins/sw_wooswatches.zip' ), 
            'required'           => true,
			'version'            => '1.0.4'
        ),
        
         array(
            'name'               => esc_html__( 'SW Responsive Post Slider', 'sw-paradise' ), 
            'slug'               => 'sw-responsive-post-slider', 
            'source'             => esc_url( 'http://demo.wpthemego.com/themes/sw_paradise/plugins/sw-responsive-post-slider.zip' ), 
            'required'           => true, 
        ),
        
        array(
            'name'               => esc_html__( 'One Click Install', 'sw-paradise' ), 
            'slug'               => 'one-click-install', 
            'source'             => esc_url( 'http://demo.wpthemego.com/themes/sw_paradise/plugins/one-click-install.zip' ), 
            'required'           => true, 
        ),
        array(
            'name'               => esc_html__( 'Visual Composer', 'sw-paradise' ), 
            'slug'               => 'js_composer', 
            'source'             => esc_url( 'http://demo.wpthemego.com/themes/sw_paradise/plugins/js_composer.zip' ), 
            'required'           => true, 
        ),      
        array(
            'name'               => esc_html__( 'WordPress Importer', 'sw-paradise' ),
            'slug'               => 'wordpress-importer',
            'required'           => true,
        ), 
        array(
            'name'               => esc_html__( 'MailChimp for WordPress Lite', 'sw-paradise' ),
            'slug'               => 'mailchimp-for-wp',
            'required'           => false,
        ),
        array(
            'name'               => esc_html__( 'Contact Form 7', 'sw-paradise' ),
            'slug'               => 'contact-form-7',
            'required'           => false,
        ),      
         array(
            'name'               => esc_html__( 'YITH Woocommerce Compare', 'sw-paradise' ),
            'slug'               => 'yith-woocommerce-compare',
            'required'           => false
        ),
         array(
            'name'               => esc_html__( 'YITH Woocommerce Wishlist', 'sw-paradise' ),
            'slug'               => 'yith-woocommerce-wishlist',
            'required'           => false
        ), 
        array(
            'name'               => esc_html__( 'WordPress Seo', 'sw-paradise' ),
            'slug'               => 'wordpress-seo',
            'required'           => false,
        ),

    );
    $config = array();

    tgmpa( $plugins, $config );

}
add_action( 'vc_before_init', 'sw_paradise_vcSetAsTheme' );
function sw_paradise_vcSetAsTheme() {
    vc_set_as_theme();
}