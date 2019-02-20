<?php
class Sw_paradise_Selectmenu{
	function __construct(){
		add_filter( 'wp_nav_menu_args' , array( $this , 'Sw_paradise_SelectMenu_AdFilter' ), 100 ); 
		add_filter( 'wp_nav_menu_args' , array( $this , 'Sw_paradise_SelectMenu_Filter' ), 110 );	
		add_action( 'wp_footer', array( $this  , 'Sw_paradise_SelectMenu_AdScript' ), 110 );	
	}
	function Sw_paradise_SelectMenu_AdScript(){
		$sw_paradise_select_value  = '<script type="text/javascript">';
		$sw_paradise_select_value .= "jQuery(document).ready( function($){
			$( '.sw_paradise_selectmenu' ).change(function() {
				var loc = $(this).find( 'option:selected' ).val();
				if( loc != '' && loc != '#' ) window.location = loc;
			});
		});
		</script> ";
		echo $sw_paradise_select_value;
	}
	function Sw_paradise_SelectMenu_AdFilter( $args ){
		$args['container'] = false;
		$sw_paradise_theme_locates = sw_paradise_options()->getCpanelValue( 'menu_location' );
		foreach( $sw_paradise_theme_locates as $sw_paradise_theme_locate ){
			if ( ( strcmp( $sw_paradise_theme_locate, $args['theme_location'] ) == 0 ) ) {	
				if( isset( $args['sw_paradise_selectmenu'] ) && $args['sw_paradise_selectmenu'] == true ) {
					return $args;
				}		
				$selectNav = $this->selectNavMenu( $args );			
				$args['container_class'].= ($args['container_class'] == '' ? '' : ' ') . 'sw-paradise-selectmenu-container';	
				$args['menu_class'].= ($args['menu_class'] == '' ? '' : ' ') . 'sw-paradise-selectmenu';			
				$args['items_wrap']	= '<ul id="%1$s" class="%2$s">%3$s</ul>'.$selectNav;
			}
		}
		return $args;
	}
	function selectNavMenu( $args ){
		$args['sw_paradise_selectmenu'] = true;		
		$select = wp_nav_menu( $args );
		return $select;
	}
	function Sw_paradise_SelectMenu_Filter( $args ){
		$args['container'] = false;
		$sw_paradise_theme_locates = sw_paradise_options()->getCpanelValue( 'menu_location' );
		foreach( $sw_paradise_theme_locates as $sw_paradise_theme_locate ){
			if ( ( strcmp( $sw_paradise_theme_locate, $args['theme_location'] ) == 0 ) ) {	
				$args['menu_class'] = 'sw_paradise_selectmenu';
				$args['walker'] = new SW_PARADISE_Menu_Select();
				$args['items_wrap'] = '<button class="navbar-toggle" type="button" data-toggle="collapse" data-target="#ResMenu">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button><div id="ResMenu" class="collapse menu-responsive-wrapper"><select class="%2$s">%3$s</select></div>';
			}
		}
		return $args;
	}
}
class SW_PARADISE_Menu_Select extends Walker_Nav_Menu{
	
	function start_lvl( &$output, $depth = 0, $args = array() ) {
		$indent = str_repeat("\t", $depth);
	}
	
	function end_lvl(&$output, $depth = 0 , $args = array() ) {
		$indent = str_repeat("\t", $depth);
	}
	function start_el( &$output, $item, $depth = 0, $args = array(), $current_object_id = 0 ) {
		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
		$class_names = '';
		$dashes = ( $depth ) ? str_repeat( "-", $depth ) : '';
		$classes = empty( $item->classes ) ? array() : (array) $item->classes;
		$classes[] = 'menu-item-' . $item->ID;
		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';
		$match = preg_match('/active/', $class_names);		
		$item->url = urldecode( $item->url );
		$attributes = ' value="'   . esc_attr( $item->url ) .'"';
		if( $match ){
			$output = str_replace('selected="selected"', '', $output);
			$attributes.= ' selected="selected"';
		}
		$output .= $indent . '<option ' . $attributes . '>';
		$item_output = $args->before;
		$item_output .= $dashes . $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
		$item_output .= $args->after;

		$output.= str_replace( '%', '%%', $item_output );

		$output.= "</option>\n";
	}
	function end_el(&$output, $element, $depth=0, $args=array() ){
		return ;
	}
	public function getElement( $element, $children_elements, $max_depth, $depth = 0, $args ){
	
	}
}
new Sw_paradise_Selectmenu();