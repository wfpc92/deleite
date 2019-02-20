<?php 
add_action( 'vc_before_init', 'my_shortcodeVC' );
function my_shortcodeVC(){
$target_arr = array(
	__( 'Same window', 'sw_core' ) => '_self',
	__( 'New window', 'sw_core' ) => "_blank"
);
$ya_link_category = array( __( 'All Categories', 'sw_core' ) => '' );
$ya_link_cats     = get_categories();
if ( is_array( $ya_link_cats ) ) {
	foreach ( $ya_link_cats as $link_cat ) {
		$ya_link_category[ $link_cat->name ] = $link_cat->slug;
	}
}		

$menu_locations_array = array( __( 'All Categories', 'sw_core' ) => '' );
$menu_locations = wp_get_nav_menus();	
foreach ($menu_locations as $menu_location){
	$menu_locations_array[$menu_location->name] = $menu_location -> slug;
}


//// vertical mega menu
vc_map( array(
 'name' => __( 'Sw Vertical Megamenu', 'sw_core' ),
 'base' => 'ya_mega_menu',
 'icon' => 'icon-wpb-ytc',
 'category' => __( 'SW Core', 'sw_core' ),
 'class' => 'wpb_vc_wp_widget',
 'weight' => - 50,
 'description' => '',
 'params' => array(
     array(
   'type' => 'textfield',
   'heading' => __( 'Title', 'sw_core' ),
   'param_name' => 'title',
   'description' => __( 'Title', 'sw_core' )
  ),
     array(
   'param_name'    => 'menu_locate',
   'type'          => 'dropdown',
   'value'         => $menu_locations_array, 
   'heading'       => __('Category menu:', 'sw_core'),
   'description'   => '',
   'holder'        => 'div',
   'class'         => ''
  ),
  array(
   'type' => 'dropdown',
   'heading' => __( 'Theme shortcode want display', 'sw_core' ),
   'param_name' => 'widget_template',
   'value' => array(
    __( 'default', 'sw_core' ) => 'default',
   ),
   'description' => sprintf( __( 'Select different style menu.', 'sw_core' ) )
  ),
  array(
   'type' => 'textfield',
   'heading' => __( 'Extra class name', 'sw_core' ),
   'param_name' => 'el_class',
   'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'sw_core' )
  ),   
 )
));



}
?>