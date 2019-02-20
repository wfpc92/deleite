<?php 
add_action( 'vc_before_init', 'SW_shortcodeVC' );
vc_add_shortcode_param( 'date', 'flytheme_date_vc_setting' );

function flytheme_date_vc_setting( $settings, $value ) {
	return '<div class="vc_date_block">'
		 .'<input name="' . esc_attr( $settings['param_name'] ) . '" class="wpb_vc_param_value wpb-textinput ' .
		 esc_attr( $settings['param_name'] ) . ' ' .
		 esc_attr( $settings['type'] ) . '_field" type="date" value="' . esc_attr( $value ) . '" placeholder="dd-mm-yyyy"/>' .
		'</div>'; 
}
function SW_shortcodeVC(){
$target_arr = array(
	__( 'Same window', 'sw_woocommerce' ) => '_self',
	__( 'New window', 'sw_woocommerce' ) => "_blank"
);	
$args = array(
			'type' => 'post',
			'child_of' => 0,
			'parent' => 0,
			'orderby' => 'name',
			'order' => 'ASC',
			'hide_empty' => false,
			'hierarchical' => 1,
			'exclude' => '',
			'include' => '',
			'number' => '',
			'taxonomy' => 'product_cat',
			'pad_counts' => false,

		);
		$product_categories_dropdown = array( __( 'All Categories Products', 'sw_woocommerce' ) => '' );
		$categories = get_categories( $args );
		foreach($categories as $category){
			$product_categories_dropdown[$category->name] = $category -> slug;
		}
$menu_locations_array = array( __( 'All Categories', 'sw_woocommerce' ) => '' );
$menu_locations = wp_get_nav_menus();	
foreach ($menu_locations as $menu_location){
	$menu_locations_array[$menu_location->name] = $menu_location -> slug;
}


$terms = get_terms( 'product_cat', array( 'parent' => '', 'hide_emty' => false ) );
if( count( $terms ) == 0 ){
	return ;
}
$term = array();
foreach( $terms as $cat ){
	$term[$cat->name] = $cat -> slug;
}

vc_map( array(
	  "name" => __( "Sw Listing Table Product", 'sw_woocommerce' ),
	  "base" => "product_tab",
	  "icon" => "icon-wpb-ytc",
	  "class" => "",
	  "category" => __( "SW Shortcodes", 'sw_woocommerce' ),
	  "params" => array(
	  	array(
			'type' => 'textfield',
			'heading' => __( 'Title', 'sw_woocommerce' ),
			'param_name' => 'title',
			'description' => __( 'What text use as a widget title. Leave blank to use default widget title.', 'sw_woocommerce' )
		),
		 array(
				"type" => "checkbox",
				"holder" => "div",
				"class" => "",
				"heading" => __( "Category", 'sw_woocommerce' ),
				"param_name" => "category",
				"value" => $term,
				"description" => __( "Select Categories", 'sw_woocommerce' )
		 ),
		 array(
			"type" => "dropdown",
			"holder" => "div",
			"class" => "",
			"heading" => __( "Order By", 'sw_woocommerce' ),
			"param_name" => "orderby",
			"value" => array('Name' => 'name', 'Author' => 'author', 'Date' => 'date', 'Modified' => 'modified', 'Parent' => 'parent', 'ID' => 'ID', 'Random' =>'rand', 'Comment Count' => 'comment_count'),
			"description" => __( "Order By", 'sw_woocommerce' )
		 ),
		 array(
			"type" => "dropdown",
			"holder" => "div",
			"class" => "",
			"heading" => __( "Order", 'sw_woocommerce' ),
			"param_name" => "order",
			"value" => array('Descending' => 'DESC', 'Ascending' => 'ASC'),
			"description" => __( "Order", 'sw_woocommerce' )
		 )
	  )
   ) );
  /////////////////// best sale /////////////////////
vc_map( array(
	'name' => __( 'SW Best Sale', 'sw_woocommerce' ),
	'base' => 'BestSale',
	'icon' => 'icon-wpb-ytc',
	'category' => __( 'SW Shortcodes', 'sw_woocommerce' ),
	'class' => 'wpb_vc_wp_widget',
	'weight' => - 50,
	'description' => __( 'Display bestseller', 'sw_woocommerce' ),
	'params' => array(
		array(
			'type' => 'textfield',
			'heading' => __( 'Widget title', 'sw_woocommerce' ),
			'param_name' => 'title',
			'description' => __( 'What text use as a widget title. Leave blank to use default widget title.', 'sw_woocommerce' )
		),
		array(
			"type" => "textfield",
			"holder" => "div",
			"class" => "",
			"heading" => __( "Product Title Length", 'sw_woocommerce' ),
			"param_name" => "title_length",
			"value" => 0,
			"description" => __( "Choose Product Title Length if you want to trim word, leave 0 to not trim word", 'sw_woocommerce' )
		),
			array(
			"type" => "dropdown",
			"holder" => "div",
			"class" => "",
			"heading" => __( "Category", 'sw_woocommerce' ),
			"param_name" => "category",
			"value" => $product_categories_dropdown,
			"description" => __( "Select Categories", 'sw_woocommerce' )
		 ),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Template', 'sw_woocommerce' ),
			'param_name' => 'template',
			'value' => array(
				'Select type',
				__( 'Default', 'sw_woocommerce' ) => 'default',
				__( 'Slide', 'sw_woocommerce' ) => 'slide',
			),
			'description' => sprintf( __( 'Select different style best sale.', 'sw_woocommerce' ) )
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Number of products to slide', 'sw_woocommerce' ),
			'param_name' => 'item_slide',
			'admin_label' => true,
			'dependency' => array(
					'element' => 'template',
					'value' => array( 'slide' ),
				)
		),
		
		array(
			'type' => 'textfield',
			'heading' => __( 'Number of posts to show', 'sw_woocommerce' ),
			'param_name' => 'number',
			'admin_label' => true
		),
		
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', 'sw_woocommerce' ),
			'param_name' => 'el_class',
			'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'sw_woocommerce' )
		),	
	)
) );

///////////////////Latest Product/////////////////////
vc_map( array(
	'name' => __( 'SW Latest Product', 'sw_woocommerce' ),
	'base' => 'Latest',
	'icon' => 'icon-wpb-ytc',
	'category' => __( 'SW Shortcodes', 'sw_woocommerce' ),
	'class' => 'wpb_vc_wp_widget',
	'weight' => - 50,
	'description' => __( 'Display Latest Products', 'sw_woocommerce' ),
	'params' => array(
		array(
			'type' => 'textfield',
			'heading' => __( 'Widget title', 'sw_woocommerce' ),
			'param_name' => 'title',
			'description' => __( 'What text use as a widget title. Leave blank to use default widget title.', 'sw_woocommerce' )
		),
		array(
			"type" => "textfield",
			"holder" => "div",
			"class" => "",
			"heading" => __( "Product Title Length", 'sw_woocommerce' ),
			"param_name" => "title_length",
			"admin_label" => true,
			"value" => 0,
			"description" => __( "Choose Product Title Length if you want to trim word, leave 0 to not trim word", 'sw_woocommerce' )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Template', 'sw_woocommerce' ),
			'param_name' => 'template',
			'value' => array(
				'Select type',
				__( 'Default', 'sw_woocommerce' ) => 'default',
				__( 'Slide', 'sw_woocommerce' ) => 'slide'
			),
			'description' => sprintf( __( 'Select different style best sale.', 'sw_woocommerce' ) )
		),
		array(
			"type" => "dropdown",
			"holder" => "div",
			"class" => "",
			"heading" => __( "Category", 'sw_woocommerce' ),
			"param_name" => "category",
			"value" => $product_categories_dropdown,
			"description" => __( "Select Categories", 'sw_woocommerce' )
		 ),
		array(
			'type' => 'textfield',
			'heading' => __( 'Number of products to slide', 'sw_woocommerce' ),
			'param_name' => 'item_slide',
			'admin_label' => true,
			'dependency' => array(
					'element' => 'template',
					'value' => array( 'slide' ),
				)
		),
		
		array(
			'type' => 'textfield',
			'heading' => __( 'Number of posts to show', 'sw_woocommerce' ),
			'param_name' => 'number',
			'admin_label' => true
		),
		
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', 'sw_woocommerce' ),
			'param_name' => 'el_class',
			'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'sw_woocommerce' )
		),	
	)
) );
	/////////////////// Featured product /////////////////////
vc_map( array(
	'name' => __( 'SW Featured Product', 'sw_woocommerce' ),
	'base' => 'Featured',
	'icon' => 'icon-wpb-ytc',
	'category' => __( 'SW Shortcodes', 'sw_woocommerce' ),
	'class' => 'wpb_vc_wp_widget',
	'weight' => - 50,
	'description' => __( 'Display Featured', 'sw_woocommerce' ),
	'params' => array(
		array(
			'type' => 'textfield',
			'heading' => __( 'Widget title', 'sw_woocommerce' ),
			'param_name' => 'title',
			'description' => __( 'What text use as a widget title. Leave blank to use default widget title.', 'sw_woocommerce' )
		),
		array(
			"type" => "textfield",
			"holder" => "div",
			"class" => "",
			"heading" => __( "Product Title Length", 'sw_woocommerce' ),
			"param_name" => "title_length",
			"admin_label" => true,
			"value" => 0,
			"description" => __( "Choose Product Title Length if you want to trim word, leave 0 to not trim word", 'sw_woocommerce' )
		),
		array(
			"type" => "dropdown",
			"holder" => "div",
			"class" => "",
			"heading" => __( "Category", 'sw_woocommerce' ),
			"param_name" => "category",
			"value" => $product_categories_dropdown,
			"description" => __( "Select Categories", 'sw_woocommerce' )
		 ),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Template', 'sw_woocommerce' ),
			'param_name' => 'template',
			'value' => array(
				'Select type',
				__( 'Default', 'sw_woocommerce' ) => 'default',
				__( 'Slide', 'sw_woocommerce' ) => 'slide',
			),
			'description' => sprintf( __( 'Select different style best sale.', 'sw_woocommerce' ) )
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Number of products to slide', 'sw_woocommerce' ),
			'param_name' => 'item_slide',
			'admin_label' => true,
			'dependency' => array(
					'element' => 'template',
					'value' => array( 'slide' ),
				)
		),
		
		array(
			'type' => 'textfield',
			'heading' => __( 'Number of posts to show', 'sw_woocommerce' ),
			'param_name' => 'number',
			'admin_label' => true
		),
		
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', 'sw_woocommerce' ),
			'param_name' => 'el_class',
			'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'sw_woocommerce' )
		),	
	)
) );
}
?>