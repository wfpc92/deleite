<?php
$lib_dir = trailingslashit( str_replace( '\\', '/', get_template_directory() . '/lib/' ) );

if( !defined('SW_PARADISE_DIR') ){
	define( 'SW_PARADISE_DIR', $lib_dir );
}

if( !defined('SW_PARADISE_URL') ){
	define( 'SW_PARADISE_URL', trailingslashit( get_template_directory_uri() ) . '/lib/' );
}

defined('SW_PARADISETHEME') or die;

if (!isset($content_width)) { $content_width = 940; }

define("SW_PARADISE_PRODUCT_TYPE","product");
define("SW_PARADISE_PRODUCT_DETAIL_TYPE","product_detail");

require_once( get_template_directory().'/lib/options.php' );
function Sw_paradise_Options_Setup(){
	global $sw_paradise_options, $options, $options_args;

	$options = array();
	$options[] = array(
		'title' => esc_html__('General', 'sw-paradise'),
		'desc' => wp_kses( __('<p class="description">The theme allows to build your own styles right out of the backend without any coding knowledge. Start your own color scheme by selecting one of 4 predefined schemes. Upload new logo and favicon or get their URL.</p>', 'sw-paradise'), array( 'p' => array( 'class' => array() ) ) ),
			//all the glyphicons are included in the options folder, so you can hook into them, or link to your own custom ones.
			//You dont have to though, leave it blank for default.
		'icon' => SW_PARADISE_URL.'/options/img/glyphicons/glyphicons_019_cogwheel.png',
			//Lets leave this as a blank section, no options just some intro text set above.
		'fields' => array(
			array(
				'id' => 'scheme',
				'type' => 'radio_img',
				'title' => esc_html__('Color Scheme', 'sw-paradise'),
				'sub_desc' => esc_html__( 'Select one of 7 predefined schemes', 'sw-paradise' ),
				'desc' => '',
				'options' => array(
					'default' => array('title' => 'Default', 'img' => get_template_directory_uri().'/assets/img/default.png'),
					'lime' => array('title' => 'Lime', 'img' => get_template_directory_uri().'/assets/img/lime.png'),
					'green' => array('title' => 'Green', 'img' => get_template_directory_uri().'/assets/img/green.png'),
					'red' => array('title' => 'Red', 'img' => get_template_directory_uri().'/assets/img/red.png'),
					'pink' => array('title' => 'Pink', 'img' => get_template_directory_uri().'/assets/img/pink.png'),
					'light_pink' => array('title' => 'Light Pink', 'img' => get_template_directory_uri().'/assets/img/light-pink.png'),
					'violet' => array('title' => 'Violet', 'img' => get_template_directory_uri().'/assets/img/violet.png'),
					'orange' => array('title' => 'Orange', 'img' => get_template_directory_uri().'/assets/img/orange.png'),
					'cyan' => array('title' => 'Cyan', 'img' => get_template_directory_uri().'/assets/img/cyan.png')
											),//Must provide key => value(array:title|img) pairs for radio options
				'std' => 'default'
				),

		array(
			'id' => 'favicon',
			'type' => 'upload',
			'title' => esc_html__('Favicon Icon', 'sw-paradise'),
			'sub_desc' => esc_html__( 'Use the Upload button to upload the new favicon and get URL of the favicon. To config Favicon in WordPress 4.3 upward, please go to Appearance -> Customize', 'sw-paradise' ),
			'std' => get_template_directory_uri().'/assets/img/favicon.ico'
			),

		array(
			'id' => 'sitelogo',
			'type' => 'upload',
			'title' => esc_html__('Logo Image', 'sw-paradise'),
			'sub_desc' => esc_html__( 'Use the Upload button to upload the new logo and get URL of the logo', 'sw-paradise' ),
			'std' => get_template_directory_uri().'/assets/img/logo-default.png'
			),

		array(
			'id' => 'sitelogo_transparent',
			'type' => 'upload',
			'title' => esc_html__('Logo Image Transparent', 'sw-paradise'),
			'sub_desc' => esc_html__( 'Use to upload new logo for header style 2', 'sw-paradise' ),
			'std' => get_template_directory_uri().'/assets/img/logo-default2.png'
			),
		array(
				'id' => 'bg_breadcrumb',
				'type' => 'upload',
				'title' => esc_html__('Breadcrumb Background', 'sw-paradise'),
				'sub_desc' => esc_html__( 'Use upload button to upload custom background for breadcrumb.', 'sw-paradise' ),
				'std' => ''
			),
		)
		);

$options[] = array(
	'title' => esc_html__('Layout', 'sw-paradise'),
	'desc' => wp_kses( __('<p class="description">Sw Paradise Framework comes with a layout setting that allows you to build any number of stunning layouts and apply theme to your entries.</p>', 'sw-paradise'), array( 'p' => array( 'class' => array() ) ) ),
			//all the glyphicons are included in the options folder, so you can hook into them, or link to your own custom ones.
			//You dont have to though, leave it blank for default.
	'icon' => SW_PARADISE_URL.'/options/img/glyphicons/glyphicons_319_sort.png',
			//Lets leave this as a blank section, no options just some intro text set above.
	'fields' => array(
		array(
			'id' => 'layout',
			'type' => 'select',
			'title' => esc_html__('Box Layout', 'sw-paradise'),
			'sub_desc' => esc_html__( 'Select Layout Box or Wide', 'sw-paradise' ),
			'options' => array(
				'full' => esc_html__( 'Wide', 'sw-paradise' ),
				'boxed' =>  esc_html__( 'Boxed', 'sw-paradise' )
				),
			),
		array(
			'id' => 'bg_box_img',
			'type' => 'upload',
			'title' => esc_html__('Background Box Image', 'sw-paradise'),
			'sub_desc' => '',
			'std' => ''
			),
		array(
			'id' => 'sidebar_left_expand',
			'type' => 'select',
			'title' => esc_html__('Left Sidebar Expand', 'sw-paradise'),
			'options' => array(
				'2' => '2/12',
				'3' => '3/12',
				'4' => '4/12',
				'5' => '5/12', 
				'6' => '6/12',
				'7' => '7/12',
				'8' => '8/12',
				'9' => '9/12',
				'10' => '10/12',
				'11' => '11/12',
				'12' => '12/12'
				),
			'std' => '3',
			'sub_desc' => esc_html__( 'Select width of left sidebar.', 'sw-paradise' ),
			),

		array(
			'id' => 'sidebar_right_expand',
			'type' => 'select',
			'title' => esc_html__('Right Sidebar Expand', 'sw-paradise'),
			'options' => array(
				'2' => '2/12',
				'3' => '3/12',
				'4' => '4/12',
				'5' => '5/12',
				'6' => '6/12',
				'7' => '7/12',
				'8' => '8/12',
				'9' => '9/12',
				'10' => '10/12',
				'11' => '11/12',
				'12' => '12/12'
				),
			'std' => '3',
			'sub_desc' => esc_html__( 'Select width of right sidebar medium desktop.', 'sw-paradise' ),
			),
		array(
			'id' => 'sidebar_left_expand_md',
			'type' => 'select',
			'title' => esc_html__('Left Sidebar Medium Desktop Expand', 'sw-paradise'),
			'options' => array(
				'2' => '2/12',
				'3' => '3/12',
				'4' => '4/12',
				'5' => '5/12',
				'6' => '6/12',
				'7' => '7/12',
				'8' => '8/12',
				'9' => '9/12',
				'10' => '10/12',
				'11' => '11/12',
				'12' => '12/12'
				),
			'std' => '4',
			'sub_desc' => esc_html__( 'Select width of left sidebar medium desktop.', 'sw-paradise' ),
			),
		array(
			'id' => 'sidebar_right_expand_md',
			'type' => 'select',
			'title' => esc_html__('Right Sidebar Medium Desktop Expand', 'sw-paradise'),
			'options' => array(
				'2' => '2/12',
				'3' => '3/12',
				'4' => '4/12',
				'5' => '5/12',
				'6' => '6/12',
				'7' => '7/12',
				'8' => '8/12',
				'9' => '9/12',
				'10' => '10/12',
				'11' => '11/12',
				'12' => '12/12'
				),
			'std' => '4',
			'sub_desc' => esc_html__( 'Select width of right sidebar.', 'sw-paradise' ),
			),
		array(
			'id' => 'sidebar_left_expand_sm',
			'type' => 'select',
			'title' => esc_html__('Left Sidebar Tablet Expand', 'sw-paradise'),
			'options' => array(
				'2' => '2/12',
				'3' => '3/12',
				'4' => '4/12',
				'5' => '5/12',
				'6' => '6/12',
				'7' => '7/12',
				'8' => '8/12',
				'9' => '9/12',
				'10' => '10/12',
				'11' => '11/12',
				'12' => '12/12'
				),
			'std' => '4',
			'sub_desc' => esc_html__( 'Select width of left sidebar tablet.', 'sw-paradise' ),
			),
		array(
			'id' => 'sidebar_right_expand_sm',
			'type' => 'select',
			'title' => esc_html__('Right Sidebar Tablet Expand', 'sw-paradise'),
			'options' => array(
				'2' => '2/12',
				'3' => '3/12',
				'4' => '4/12',
				'5' => '5/12',
				'6' => '6/12',
				'7' => '7/12',
				'8' => '8/12',
				'9' => '9/12',
				'10' => '10/12',
				'11' => '11/12',
				'12' => '12/12'
				),
			'std' => '4',
			'sub_desc' => esc_html__( 'Select width of right sidebar tablet.', 'sw-paradise' ),
			),				
		)
);
$options[] = array(
	'title' => esc_html__('Header & Footer', 'sw-paradise'),
	'desc' => wp_kses( __('<p class="description">Sw Paradise Framework comes with a header and footer setting that allows you to build style header.</p>', 'sw-paradise'), array( 'p' => array( 'class' => array() ) ) ),
			//all the glyphicons are included in the options folder, so you can hook into them, or link to your own custom ones.
			//You dont have to though, leave it blank for default.
	'icon' => SW_PARADISE_URL.'/options/img/glyphicons/glyphicons_336_read_it_later.png',
			//Lets leave this as a blank section, no options just some intro text set above.
	'fields' => array(
		array(
			'id' => 'header_style',
			'type' => 'select',
			'title' => esc_html__('Header Style', 'sw-paradise'),
			'sub_desc' => esc_html__('Select Header style', 'sw-paradise' ),
			'options' => array(
				'default'  => esc_html__( 'Default', 'sw-paradise'),
				'style1'  => esc_html__( 'Style 1', 'sw-paradise'),
				'style2'  => esc_html__( 'Style 2', 'sw-paradise'),
				'style3'  => esc_html__( 'Style 3', 'sw-paradise'),
				'style4'  => esc_html__( 'Style 4', 'sw-paradise'),
				'style5'  => esc_html__( 'Style 5', 'sw-paradise'),
				'style6'  => esc_html__( 'Style 6', 'sw-paradise'),
				'style7'  => esc_html__( 'Style 7', 'sw-paradise') 
				),
			'std' => 'default'
			),
		array(
			'id' => 'phone_number',
			'type' => 'text',
			'sub_desc' => esc_html__( 'Use this param for header style default', 'sw-paradise' ),
			'title' => esc_html__( 'My Phone.', 'sw-paradise' ),
			'std' => ''
			),
		array(
			'id' => 'my_delivery',
			'type' => 'text',
			'sub_desc' => esc_html__( 'Enter my delivery.', 'sw-paradise' ),
			'title' => esc_html__( 'My Delivery.', 'sw-paradise' ),
			'std' => ''
			),
		array(
			'id' => 'my_address',
			'type' => 'pages_select',
			'title' => esc_html__('Select Page Find Our Address', 'sw-paradise'),
			'sub_desc' => esc_html__( 'Use this param for header style default', 'sw-paradise' ),
			'std' => ''
			),
		array(
			'id' => 'my_store',
			'type' => 'text',
			'sub_desc' => esc_html__( 'Use this param for all header styles except header style default', 'sw-paradise' ),
			'title' => esc_html__( 'My Store.', 'sw-paradise' ),
			'std' => ''
			),
		array(
			'id' => 'my_store2',
			'type' => 'text',
			'sub_desc' => esc_html__( 'Use this param for all header styles except header style default', 'sw-paradise' ),
			'title' => esc_html__( 'My Store 2.', 'sw-paradise' ),
			'std' => ''
			),
		array(
			'id' => 'my_sale',
			'type' => 'text',
			'sub_desc' => esc_html__( 'Use this param for header style default', 'sw-paradise' ),
			'title' => esc_html__( 'My Sale.', 'sw-paradise' ),
			'std' => ''
			),
		array(
			'id' => 'page_footer',
			'type' => 'pages_select',
			'title' => esc_html__('Select Page On Footer', 'sw-paradise'),
			'sub_desc' => esc_html__( 'Select a page show on footer', 'sw-paradise' ),
			'std' => ''
			),
		)
);
$options[] = array(
	'title' => esc_html__('Navbar Options', 'sw-paradise'),
	'desc' => wp_kses( __('<p class="description">If you got a big site with a lot of sub menus we recommend using a mega menu. Just select the dropbox to display a menu as mega menu or dropdown menu.</p>', 'sw-paradise'), array( 'p' => array( 'class' => array() ) ) ),
			//all the glyphicons are included in the options folder, so you can hook into them, or link to your own custom ones.
			//You dont have to though, leave it blank for default.
	'icon' => SW_PARADISE_URL.'/options/img/glyphicons/glyphicons_157_show_lines.png',
			//Lets leave this as a blank section, no options just some intro text set above.
	'fields' => array(
		array(
			'id' => 'menu_type',
			'type' => 'select',
			'title' => esc_html__('Menu Type', 'sw-paradise'),
			'options' => array( 'dropdown' => esc_html__( 'Dropdown Menu', 'sw-paradise' ), 'mega' => esc_html__( 'Mega Menu', 'sw-paradise' ) ),
			'std' => 'mega'
			),
		array(
			'id' => 'menu_location',
			'type' => 'menu_location_multi_select',
			'title' => esc_html__('Theme Location', 'sw-paradise'),
			'sub_desc' => esc_html__( 'Select theme location to active mega menu and menu responsive.', 'sw-paradise' ),
			'std' => 'primary_menu'
			),
		
		array(
			'id' => 'sticky_menu',
			'type' => 'checkbox',
			'title' => esc_html__('Active sticky menu', 'sw-paradise'),
			'sub_desc' => '',
			'desc' => '',
						'std' => '0'// 1 = on | 0 = off
						),	
		)
	);
$options[] = array(
	'title' => esc_html__('Blog Options', 'sw-paradise'),
	'desc' => wp_kses( __('<p class="description">Select layout in blog listing page.</p>', 'sw-paradise'), array( 'p' => array( 'class' => array() ) ) ),
		//all the glyphicons are included in the options folder, so you can hook into them, or link to your own custom ones.
		//You dont have to though, leave it blank for default.
	'icon' => SW_PARADISE_URL.'/options/img/glyphicons/glyphicons_319_sort.png',
		//Lets leave this as a blank section, no options just some intro text set above.
	'fields' => array(
		array(
			'id' => 'sidebar_blog',
			'type' => 'select',
			'title' => esc_html__( 'Sidebar Blog Layout', 'sw-paradise' ),
			'options' => array(
				'full' => esc_html__( 'Full Layout', 'sw-paradise' ),		
				'left_sidebar'	=> esc_html__( 'Left Sidebar', 'sw-paradise' ),
				'right_sidebar' => esc_html__( 'Right Sidebar', 'sw-paradise' ),
				),
			'std' => 'left_sidebar',
			'sub_desc' => esc_html__( 'Select style sidebar blog', 'sw-paradise' ),
			),
		array(
			'id' => 'blog_layout',
			'type' => 'select',
			'title' => esc_html__('Layout blog', 'sw-paradise'),
			'options' => array(
				'list'	=>  esc_html__( 'List Layout', 'sw-paradise' ),
				'grid' =>  esc_html__( 'Grid Layout', 'sw-paradise' )								
				),
			'std' => 'list',
			'sub_desc' => esc_html__( 'Select style layout blog', 'sw-paradise' ),
			),
		array(
			'id' => 'blog_column',
			'type' => 'select',
			'title' => esc_html__('Blog column', 'sw-paradise'),
			'options' => array(								
				'2' => '2 columns',
				'3' => '3 columns',
				'4' => '4 columns'								
				),
			'std' => '2',
			'sub_desc' => esc_html__( 'Select style number column blog', 'sw-paradise' ),
			),
		)
);	
$options[] = array(
	'title' => esc_html__('Product Options', 'sw-paradise'),
	'desc' => wp_kses( __('<p class="description">Select layout in product listing page.</p>', 'sw-paradise'), array( 'p' => array( 'class' => array() ) ) ),
		//all the glyphicons are included in the options folder, so you can hook into them, or link to your own custom ones.
		//You dont have to though, leave it blank for default.
	'icon' => SW_PARADISE_URL.'/options/img/glyphicons/glyphicons_319_sort.png',
		//Lets leave this as a blank section, no options just some intro text set above.
	'fields' => array(
		array(
			'id' => 'product_col_large',
			'type' => 'select',
			'title' => esc_html__('Product Listing column Desktop', 'sw-paradise'),
			'options' => array(
				'2' => '2',
				'3' => '3',
				'4' => '4',							
				),
			'std' => '4',
			'sub_desc' => esc_html__( 'Select number of column on Desktop Screen', 'sw-paradise' ),
			),
		array(
			'id' => 'product_col_medium',
			'type' => 'select',
			'title' => esc_html__('Product Listing column Medium Desktop', 'sw-paradise'),
			'options' => array(
				'2' => '2',
				'3' => '3',
				'4' => '4',							
				),
			'std' => '3',
			'sub_desc' => esc_html__( 'Select number of column on Medium Desktop Screen', 'sw-paradise' ),
			),
		array(
			'id' => 'product_col_sm',
			'type' => 'select',
			'title' => esc_html__('Product Listing column Tablet', 'sw-paradise'),
			'options' => array(
				'2' => '2',
				'3' => '3',
				'4' => '4',							
				),
			'std' => '2',
			'sub_desc' => esc_html__( 'Select number of column on Tablet Screen', 'sw-paradise' ),
			),
		array(
			'id' => 'sidebar_product',
			'type' => 'select',
			'title' => esc_html__( 'Sidebar Product Layout', 'sw-paradise' ),
			'options' => array(
				'left'	=> esc_html__( 'Left Sidebar', 'sw-paradise' ),
				'full' =>  esc_html__( 'Full Layout', 'sw-paradise' ),		
				'right' => esc_html__( 'Right Sidebar', 'sw-paradise' ),				
				),
			'std' => 'left',
			'sub_desc' => esc_html__( 'Select style sidebar product', 'sw-paradise' ),
			),
		array(
			'id' => 'product_quickview',
			'title' => esc_html__( 'Quickview', 'sw-paradise' ),
			'type' => 'checkbox',
			'sub_desc' => '',
			'desc' => esc_html__( 'Turn On/Off Product Quickview', 'sw-paradise' ),
			'std' => '1'
			),
		array(
				'id' => 'product_zoom',
				'title' => esc_html__( 'Product Zoom', 'sw-paradise' ),
				'type' => 'checkbox',
				'sub_desc' => '',
				'desc' => esc_html__( 'Turn On/Off image zoom when hover on single product', 'sw-paradise' ),
				'std' => '1'
			),
			
		)
);		
$options[] = array(
	'title' => esc_html__('Typography', 'sw-paradise'),
	'desc' => wp_kses( __('<p class="description">Change the font style of your blog, custom with Google Font.</p>', 'sw-paradise'), array( 'p' => array( 'class' => array() ) ) ),
			//all the glyphicons are included in the options folder, so you can hook into them, or link to your own custom ones.
			//You dont have to though, leave it blank for default.
	'icon' => SW_PARADISE_URL.'/options/img/glyphicons/glyphicons_151_edit.png',
			//Lets leave this as a blank section, no options just some intro text set above.
	'fields' => array(
		array(
			'id' => 'google_webfonts',
			'type' => 'text',
			'title' => esc_html__('Use Google Webfont', 'sw-paradise'),
			'sub_desc' => esc_html__( 'Insert font style that you actually need on your webpage.', 'sw-paradise' ), 
			'std' => ''
			),
		array(
			'id' => 'webfonts_weight',
			'type' => 'multi_select',
			'sub_desc' => esc_html__( 'For weight, see Google Fonts to custom for each font style.', 'sw-paradise' ),
			'title' => esc_html__('Webfont Weight', 'sw-paradise'),
			'options' => array(
				'100' => '100',
				'200' => '200',
				'300' => '300',
				'400' => '400',
				'500' => '500',
				'600' => '600',
				'700' => '700',
				'800' => '800',
				'900' => '900'
				),
			'std' => ''
			),
		array(
			'id' => 'webfonts_assign',
			'type' => 'select',
			'title' => esc_html__( 'Webfont Assign to', 'sw-paradise' ),
			'sub_desc' => esc_html__( 'Select the place will apply the font style headers, every where or custom.', 'sw-paradise' ),
			'options' => array(
				'headers' => esc_html__( 'Headers',    'sw-paradise' ),
				'all'     => esc_html__( 'Everywhere', 'sw-paradise' ),
				'custom'  => esc_html__( 'Custom',     'sw-paradise' )
				)
			),
		array(
			'id' => 'webfonts_custom',
			'type' => 'text',
			'sub_desc' => esc_html__( 'Insert the places will be custom here, after selected custom Webfont assign.', 'sw-paradise' ),
			'title' => esc_html__( 'Webfont Custom Selector', 'sw-paradise' )
			),
		)
);

$options[] = array(
	'title' => esc_html__('Social share', 'sw-paradise'),
	'desc' => wp_kses( __('<p class="description">Social sharing is ready to use and built in. You can share your pages with just a click and your post can go to their wall and you can gain vistitors from Social Networks. Check Social Networks that you want to use.</p>', 'sw-paradise'), array( 'p' => array( 'class' => array() ) ) ),
			//all the glyphicons are included in the options folder, so you can hook into them, or link to your own custom ones.
			//You dont have to though, leave it blank for default.
	'icon' => SW_PARADISE_URL.'/options/img/glyphicons/glyphicons_222_share.png',
			//Lets leave this as a blank section, no options just some intro text set above.
	'fields' => array(
		array(
			'id' => 'social-share',
			'title' => esc_html__( 'Social share', 'sw-paradise' ),
			'type' => 'checkbox',
			'sub_desc' => '',
			'desc' => '',
			'std' => '0'
			),
		array(
			'id' => 'social-share-fb',
			'title' => esc_html__( 'Facebook', 'sw-paradise' ),
			'type' => 'checkbox',
			'sub_desc' => '',
			'desc' => '',
			'std' => '1',
			),
		array(
			'id' => 'social-share-tw',
			'title' => esc_html__( 'Twitter', 'sw-paradise' ),
			'type' => 'checkbox',
			'sub_desc' => '',
			'desc' => '',
			'std' => '1',
			),
		array(
			'id' => 'social-share-in',
			'title' => esc_html__( 'Linked_in', 'sw-paradise' ),
			'type' => 'checkbox',
			'sub_desc' => '',
			'desc' => '',
			'std' => '1',
			),
		array(
			'id' => 'social-share-go',
			'title' => esc_html__( 'Google+', 'sw-paradise' ),
			'type' => 'checkbox',
			'sub_desc' => '',
			'desc' => '',
			'std' => '1',
			),
		)
	);
$options[] = array(
	'title' => esc_html__('Advanced', 'sw-paradise'),
	'desc' => wp_kses( __('<p class="description">Custom advanced with Cpanel, Widget advanced, Developer mode </p>', 'sw-paradise'), array( 'p' => array( 'class' => array() ) ) ),
			//all the glyphicons are included in the options folder, so you can hook into them, or link to your own custom ones.
			//You dont have to though, leave it blank for default.
	'icon' => SW_PARADISE_URL.'/options/img/glyphicons/glyphicons_083_random.png',
			//Lets leave this as a blank section, no options just some intro text set above.
	'fields' => array(
		array(
			'id' => 'show_cpanel',
			'title' => esc_html__( 'Show cPanel', 'sw-paradise' ),
			'type' => 'checkbox',
			'sub_desc' => esc_html__( 'Turn on/off Cpanel', 'sw-paradise' ),
			'desc' => '',
			'std' => ''
			),
		array(
			'id' => 'widget-advanced',
			'title' => esc_html__('Widget Advanced', 'sw-paradise'),
			'type' => 'checkbox',
			'sub_desc' => esc_html__( 'Turn on/off Widget Advanced', 'sw-paradise' ),
			'desc' => '',
			'std' => '1'
			),
		array(
			'id' => 'developer_mode',
			'title' => esc_html__( 'Developer Mode', 'sw-paradise' ),
			'type' => 'checkbox',
			'sub_desc' => esc_html__( 'Turn on/off preset', 'sw-paradise' ),
			'desc' => '',
			'std' => '0'
			),
		array(
			'id' => 'back_active',
			'type' => 'checkbox',
			'title' => esc_html__('Back to top', 'sw-paradise'),
			'sub_desc' => '',
			'desc' => '',
							'std' => '1'// 1 = on | 0 = off
							),							
		array(
			'id' => 'direction',
			'type' => 'select',
			'title' => esc_html__('Direction', 'sw-paradise'),
			'options' => array( 'ltr' => esc_html__( 'Left to Right', 'sw-paradise' ), 'rtl' => esc_html__( 'Right to Left', 'sw-paradise' ) ),
			'std' => 'ltr'
			),
		array(
			'id' => 'popup_active',
			'type' => 'checkbox',
			'title' => esc_html__('Active Popup Subscribe', 'sw-paradise'),
			'sub_desc' => esc_html__( 'Check to active popup subscribe', 'sw-paradise' ),
			'desc' => '',
							'std' => '0'// 1 = on | 0 = off
							),	
		array(
			'id' => 'popup_shortcode',
			'type' => 'textarea',
			'sub_desc' => esc_html__( 'Insert the popup shortcode here', 'sw-paradise' ),
			'title' => esc_html__( 'Popup Shortcode', 'sw-paradise' )
			),
		array(
			'id' => 'advanced_head',
			'type' => 'textarea',
			'sub_desc' => esc_html__( 'Insert your own CSS into this block. This overrides all default styles located throughout the theme', 'sw-paradise' ),
			'title' => esc_html__( 'Custom CSS/JS', 'sw-paradise' )
			)
		)
);

$options_args = array();

	//Setup custom links in the footer for share icons
$options_args['share_icons']['facebook'] = array(
	'link' => esc_url( 'https://www.facebook.com/smartaddons' ),
	'title' => esc_html__( 'Facebook', 'sw-paradise' ),
	'img' =>  esc_url( SW_PARADISE_URL.'/options/img/glyphicons/glyphicons_320_facebook.png' )
	);
$options_args['share_icons']['twitter'] = array(
	'link' =>  esc_url( 'https://twitter.com/smartaddons' ),
	'title' => esc_html__( 'Folow me on Twitter', 'sw-paradise' ),
	'img' => SW_PARADISE_URL.'/options/img/glyphicons/glyphicons_322_twitter.png'
	);
$options_args['share_icons']['linked_in'] = array(
	'link' =>  esc_url( 'https://www.linkedin.com/in/smartaddons' ),
	'title' => esc_html__( 'Find me on LinkedIn', 'sw-paradise' ),
	'img' =>  esc_url( SW_PARADISE_URL.'/options/img/glyphicons/glyphicons_337_linked_in.png' )
	);


	//Choose a custom option name for your theme options, the default is the theme name in lowercase with spaces replaced by underscores
$options_args['opt_name'] = SW_PARADISETHEME;

	$options_args['google_api_key'] = '';//must be defined for use with google webfonts field type

	//Custom menu title for options page - default is "Options"
	$options_args['menu_title'] = esc_html__('Theme Options', 'sw-paradise');

	//Custom Page Title for options page - default is "Options"
	$options_args['page_title'] = esc_html__('SW PARADISE Options :: ', 'sw-paradise') . wp_get_theme()->get('Name');

	//Custom page slug for options page (wp-admin/themes.php?page=***) - default is "sw_paradise_theme_options"
	$options_args['page_slug'] = 'sw_paradise_theme_options';

	//page type - "menu" (adds a top menu section) or "submenu" (adds a submenu) - default is set to "menu"
	$options_args['page_type'] = 'submenu';

	//custom page location - default 100 - must be unique or will override other items
	$options_args['page_position'] = 27;
	$sw_paradise_options = new SW_PARADISE_Options($options, $options_args);
}
add_action( 'admin_init', 'Sw_paradise_Options_Setup', 0 );
Sw_paradise_Options_Setup();

function sw_paradise_widget_setup_args(){
	$sw_paradise_widget_areas = array(
		
		array(
			'name' => esc_html__('Sidebar Left Blog', 'sw-paradise'),
			'id'   => 'left-blog',
			'before_widget' => '<div id="%1$s" class="widget %1$s %2$s"><div class="widget-inner">',
			'after_widget' => '</div></div>',
			'before_title' => '<div class="block-title-widget"><h2><span>',
			'after_title' => '</span></h2></div>'
			),
		array(
			'name' => esc_html__('Sidebar Right Blog', 'sw-paradise'),
			'id'   => 'right-blog',
			'before_widget' => '<div id="%1$s" class="widget %1$s %2$s"><div class="widget-inner">',
			'after_widget' => '</div></div>',
			'before_title' => '<div class="block-title-widget"><h2><span>',
			'after_title' => '</span></h2></div>'
			),
		array(
			'name' => esc_html__('Top', 'sw-paradise'),
			'id'   => 'top',
			'before_widget' => '<div id="%1$s" class="widget %1$s %2$s"><div class="widget-inner">',
			'after_widget'  => '</div></div>',
			'before_title'  => '<h3>',
			'after_title'   => '</h3>'
			),
		array(
			'name' => esc_html__('Top Currency', 'sw-paradise'),
			'id'   => 'top-currency',
			'before_widget' => '<div id="%1$s" class="widget %1$s %2$s"><div class="widget-inner">',
			'after_widget'  => '</div></div>',
			'before_title'  => '<h3>',
			'after_title'   => '</h3>'
			),
		array(
			'name' => esc_html__('Top Right', 'sw-paradise'),
			'id'   => 'top-right',
			'before_widget' => '<div id="%1$s" class="widget %1$s %2$s"><div class="widget-inner">',
			'after_widget'  => '</div></div>',
			'before_title'  => '<h3>',
			'after_title'   => '</h3>'
			),
		array(
			'name' => esc_html__('Sidebar Bottom Detail Product', 'sw-paradise'),
			'id'   => 'bottom-detail-product',
			'before_widget' => '<div class="widget %1$s %2$s" data-scroll-reveal="enter bottom move 20px wait 0.2s"><div class="widget-inner">',
			'after_widget'  => '</div></div>',
			'before_title'  => '<h3>',
			'after_title'   => '</h3>'
			),		
		array(
			'name' => esc_html__('Sidebar Left Product', 'sw-paradise'),
			'id'   => 'left-product',
			'before_widget' => '<div id="%1$s" class="widget %1$s %2$s"><div class="widget-inner">',
			'after_widget' => '</div></div>',
			'before_title' => '<div class="block-title-widget"><h2><span>',
			'after_title' => '</span></h2></div>'
			),
		array(
			'name' => esc_html__('Sidebar Right Product', 'sw-paradise'),
			'id'   => 'right-product',
			'before_widget' => '<div id="%1$s" class="widget %1$s %2$s"><div class="widget-inner">',
			'after_widget' => '</div></div>',
			'before_title' => '<div class="block-title-widget"><h2><span>',
			'after_title' => '</span></h2></div>'
			),
		array(
			'name' => esc_html__('Slider Home 5', 'sw-paradise'),
			'id'   => 'slider-home-end',
			'before_widget' => '<div id="%1$s" class="widget %1$s %2$s"><div class="widget-inner">',
			'after_widget' => '</div></div>',
			'before_title' => '<div class="block-title-widget"><h2><span>',
			'after_title' => '</span></h2></div>'
			)
		);
return $sw_paradise_widget_areas;
}
