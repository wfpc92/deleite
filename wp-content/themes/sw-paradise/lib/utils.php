<?php 
/**
 * Theme wrapper
 *
 * @link http://scribu.net/wordpress/theme-wrappers.html
 */


/* Style Background */
function sw_paradise_style_bg(){ 
	
	$img =  sw_paradise_options()->getCpanelValue('bg_img');
	$color = sw_paradise_options()->getCpanelValue('bg_color');
	$repeat = sw_paradise_options()->getCpanelValue('bg_repeat');
	$layout = sw_paradise_options()->getCpanelValue('layout');
	$bg_image = sw_paradise_options()->getCpanelValue('bg_box_img');
	$img = isset($img) ? $img : '';
	$color = isset($color) ? $color : '';
	$repeat = isset($repeat) ? 'repeat' : 'no-repeat';
	
	if ( !empty($img) && strpos($img, 'bg-demo') === false ) {
		
	} elseif ( !empty($img) && strpos($img, 'bg-demo') == 0 ) {
		$img = get_template_directory_uri() . '/assets/img/' . $img . '.png';
	}
	
	if (strpos($color, '#') != 0) {
		$color = '#' . $color;
	} 
	if( $img != '' || $layout == 'boxed' ){
	?>

	<style>
		body{
			background-image: url('<?php echo esc_attr( $img ); ?>');
			background-color: <?php echo esc_html( $color ); ?>;
			background-repeat: <?php echo esc_html( $repeat ); ?>;
			<?php if( $layout == 'boxed' ){ ?>
				background-image: url('<?php echo esc_attr( $bg_image ); ?>');
				background-position: top center; 
				background-attachment: fixed;					
			<?php }	?>
		}
	</style>
	
	<?php 
	}
	return '';
}
add_filter('wp_head', 'sw_paradise_style_bg');


/**
 * Page titles
 */
function sw_paradise_title() {
	if (is_home()) {
		if (get_option('page_for_posts', true)) {
			echo get_the_title(get_option('page_for_posts', true));
		} else {
			esc_html_e('Latest Posts', 'sw-paradise');
		}
	} elseif (is_archive()) {
		$term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
		if ($term) {
			echo $term->name;
		} elseif (is_post_type_archive()) {
			echo get_queried_object()->labels->name;
		} elseif (is_day()) {
			printf(__('Daily Archives: %s', 'sw-paradise'), get_the_date());
		} elseif (is_month()) {
			printf(__('Monthly Archives: %s', 'sw-paradise'), get_the_date('F Y'));
		} elseif (is_year()) {
			printf(__('Yearly Archives: %s', 'sw-paradise'), get_the_date('Y'));
		} elseif (is_author()) {
			printf(__('Author Archives: %s', 'sw-paradise'), get_the_author());
		} else {
			single_cat_title();
		}
	} elseif (is_search()) {
		printf(__('Search Results for <small>%s</small>', 'sw-paradise'), get_search_query());
	} elseif (is_404()) {
		esc_html_e('Not Found', 'sw-paradise');
	} else {
		the_title();
	}
}

/*
** Get content page by ID
*/
function sw_get_the_content_by_id( $post_id ) {
  $page_data = get_page( $post_id );
  if ($page_data) {
    $content = apply_filters( 'the_content', $page_data->post_content );
		return $content;
  }
  else return false;
}

/**
 * Return WordPress subdirectory if applicable
 */
function wp_base_dir() {
	preg_match('!(https?://[^/|"]+)([^"]+)?!', home_url(), $matches);
	if (count($matches) === 3) {
		return end($matches);
	} else {
		return '';
	}
}

/**
 * Opposite of built in WP functions for trailing slashes
 */
function leadingslashit($string) {
	return '/' . unleadingslashit($string);
}

function unleadingslashit($string) {
	return ltrim($string, '/');
}

function add_filters($tags, $function) {
	foreach($tags as $tag) {
		add_filter($tag, $function);
	}
}

function is_element_empty($element) {
	$element = trim($element);
	return empty($element) ? false : true;
}

function is_customize(){
	return isset($_POST['customized']) && ( isset($_POST['customize_messenger_chanel']) || isset($_POST['wp_customize']) );
}

/**
 * Create HTML list checkbox of nav menu items.
 */

class SW_PARADISE_Menu_Checkbox extends Walker_Nav_Menu{
	
	private $menu_slug;
	
	public function __construct( $menu_slug = '') {
		$this->menu_slug = $menu_slug;
	}
	
	public function init($items, $args = array()) {
		$args = array( $items, 0, $args );
		
		return call_user_func_array( array($this, 'walk'), $args );
	}
	
	function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
		$class_names = $value = '';

		$classes = empty( $item->classes ) ? array() : (array) $item->classes;
		$classes[] = 'menu-item-' . $item->ID;

		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

		$id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args );
		$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

		$output .= $indent . '<li' . $id . $value . $class_names .'>';
		
		$item_output = '<label for="' . $this->menu_slug . '-' . $item->post_name . '-' . $item->ID . '">';
		$item_output .= '<input type="checkbox" name="' . $this->menu_slug . '_'  . $item->post_name .  '_' . $item->ID . '" ' . $this->menu_slug.$item->post_name.$item->ID . ' id="' . $this->menu_slug . '-'  . $item->post_name . '-' . $item->ID . '" /> ' . $item->title;
		$item_output .= '</label>';

		$output .= $item_output;
	}
	
	public function is_menu_item_active($menu_id, $item_ids) {
		global $wp_query;

		$queried_object = $wp_query->get_queried_object();
		$queried_object_id = (int) $wp_query->queried_object_id;
	
		$items = wp_get_nav_menu_items($menu_id);
		$items_current = array();
		$possible_object_parents = array();
		$home_page_id = (int) get_option( 'page_for_posts' );
		
		if ( $wp_query->is_singular && ! empty( $queried_object->post_type ) && ! is_post_type_hierarchical( $queried_object->post_type ) ) {
			foreach ( (array) get_object_taxonomies( $queried_object->post_type ) as $taxonomy ) {
				if ( is_taxonomy_hierarchical( $taxonomy ) ) {
					$terms = wp_get_object_terms( $queried_object_id, $taxonomy, array( 'fields' => 'ids' ) );
					if ( is_array( $terms ) ) {
						$possible_object_parents = array_merge( $possible_object_parents, $terms );
					}
				}
			}
		}
		
		foreach ($items as $item) {
			
			if (key_exists($item->ID, $item_ids)) {
				$items_current[] = $item;
			}
		}
		
		foreach ($items_current as $item) {
			
			if ( ($item->object_id == $queried_object_id) && (
						( ! empty( $home_page_id ) && 'post_type' == $item->type && $wp_query->is_home && $home_page_id == $item->object_id ) ||
						( 'post_type' == $item->type && $wp_query->is_singular ) ||
						( 'taxonomy' == $item->type && ( $wp_query->is_category || $wp_query->is_tag || $wp_query->is_tax ) && $queried_object->taxonomy == $item->object )
					)
				)
				return true;
			elseif ( $wp_query->is_singular &&
					'taxonomy' == $item->type &&
					in_array( $item->object_id, $possible_object_parents ) ) {
				return true;
			}
		}
		
		return false;
	}
}
/**
 * Check widget display
 * */
function check_wdisplay ($widget_display){
	$widget_display = json_decode(json_encode($widget_display), true);
	$SW_PARADISE_Menu_Checkbox = new SW_PARADISE_Menu_Checkbox;
	if ( isset($widget_display['display_select']) && $widget_display['display_select'] == 'all' ) {
		return true;
	}else{
	if ( in_array( 'sitepress-multilingual-cms/sitepress.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) { 
		if(  isset($widget_display['display_language']) && strcmp($widget_display['display_language'], ICL_LANGUAGE_CODE) != 0  ){
			return false;
		}
	}
	if ( isset($widget_display['display_select']) && $widget_display['display_select'] == 'if_selected' ) {
		
		if (isset($widget_display['checkbox'])) {
			
			if (isset($widget_display['checkbox']['users'])) {
				global $user_ID;
				
				foreach ($widget_display['checkbox']['users'] as $key => $value) {
					
					if ( ($key == 'login' && $user_ID) || ($key == 'logout' && !$user_ID) ){
						
						if (isset($widget_display['checkbox']['general'])) {
							foreach ($widget_display['checkbox']['general'] as $key => $value) {
								$is = 'is_'.$key;
								if ( $is() === true ) return true;
							}
						}
						
						if (isset($widget_display['taxonomy-slugs'])) {
							
							$taxonomy_slugs = preg_split('/[\s,]/', $widget_display['taxonomy-slugs']);
							foreach ($taxonomy_slugs as $slug) {is_post_type_archive('product_cat');
								if (!empty($slug) && is_tax($slug) === true) {
									return true;
								}
							}
						
						}
						
						if (isset($widget_display['post-type'])) {
							$post_type = preg_split('/[\s,]/', $widget_display['post-type']);
							
							foreach ($post_type as $type) {
								if(is_archive()){
									if (!empty($type) && is_post_type_archive($type) === true) {
										return true;
									}
								}
								
								if($type!=SW_PARADISE_PRODUCT_TYPE)
								{
									if(!empty($type) && $type==SW_PARADISE_PRODUCT_DETAIL_TYPE && is_single() && get_post_type() != 'post'){
										return true;
									}else if (!empty($type) && is_singular($type) === true) {
										return true;
									}
									
								}	
							}
						}
						
						if (isset($widget_display['catid'])) {
							$catid = preg_split('/[\s,]/', $widget_display['catid']);
							foreach ($catid as $id) {
								if (!empty($id) && is_category($id) === true) {
									return true;
								}
							}
								
						}
						
						if (isset($widget_display['postid'])) {
							$postid = preg_split('/[\s,]/', $widget_display['postid']);
							foreach ($postid as $id) {
								if (!empty($id) && (is_page($id) === true || is_single($id) === true) ) {
									return true;
								}
							}
						
						}
						
						if (isset($widget_display['checkbox']['menus'])) {
							
							foreach ($widget_display['checkbox']['menus'] as $menu_id => $item_ids) {
								
								if ( $SW_PARADISE_Menu_Checkbox->is_menu_item_active($menu_id, $item_ids) ) return true;
							}
						}
					}
				}
			}
			
			return false;
			
		} else return false ;
		
	} elseif ( isset($widget_display['display_select']) && $widget_display['display_select'] == 'if_no_selected' ) {
		
		if (isset($widget_display['checkbox'])) {
			
			if (isset($widget_display['checkbox']['users'])) {
				global $user_ID;
				
				foreach ($widget_display['checkbox']['users'] as $key => $value) {
					if ( ($key == 'login' && $user_ID) || ($key == 'logout' && !$user_ID) ) return false;
				}
			}
			
			if (isset($widget_display['checkbox']['general'])) {
				foreach ($widget_display['checkbox']['general'] as $key => $value) {
					$is = 'is_'.$key;
					if ( $is() === true ) return false;
				}
			}

			if (isset($widget_display['taxonomy-slugs'])) {
				$taxonomy_slugs = preg_split('/[\s,]/', $widget_display['taxonomy-slugs']);
				foreach ($taxonomy_slugs as $slug) {
					if (!empty($slug) && is_tax($slug) === true) {
						return false;
					}
				}
			
			}
			
			if (isset($widget_display['post-type'])) {
				$post_type = preg_split('/[\s,]/', $widget_display['post-type']);
				
				foreach ($post_type as $type) {
					if(is_archive()){
						if (!empty($type) && is_post_type_archive($type) === true) {
							return true;
						}
					}
					
					if($type!=SW_PARADISE_PRODUCT_TYPE)
					{
						if(!empty($type) && $type==SW_PARADISE_PRODUCT_DETAIL_TYPE && is_single() && get_post_type() != 'post'){
							return true;
						}else if (!empty($type) && is_singular($type) === true) {
							return true;
						}
						
					}	
				}
			}
			
			
			
			if (isset($widget_display['catid'])) {
				$catid = preg_split('/[\s,]/', $widget_display['catid']);
				foreach ($catid as $id) {
					if (!empty($id) && is_category($id) === true) {
						return false;
					}
				}
					
			}
			
			if (isset($widget_display['postid'])) {
				$postid = preg_split('/[\s,]/', $widget_display['postid']);
				foreach ($postid as $id) {
					if (!empty($id) && (is_page($id) === true || is_single($id) === true)) {
						return false;
					}
				}
			
			}
			
			if (isset($widget_display['checkbox']['menus'])) {
							
				foreach ($widget_display['checkbox']['menus'] as $menu_id => $item_ids) {
					
					if ( $SW_PARADISE_Menu_Checkbox->is_menu_item_active($menu_id, $item_ids) ) return false;
				}
			}			
		} else return false ;
	}
	}
	return true ;
}


/**
 *  Is active sidebar
 * */
function is_active_sidebar_SW_PARADISE($index) {
	global $wp_registered_widgets;
	
	$index = ( is_int($index) ) ? "sidebar-$index" : sanitize_title($index);
	$sidebars_widgets = wp_get_sidebars_widgets();
	if (!empty($sidebars_widgets[$index])) {
		foreach ($sidebars_widgets[$index] as $i => $id) {
			$id_base = preg_replace( '/-[0-9]+$/', '', $id );
			
			if ( isset($wp_registered_widgets[$id]) ) {
				$widget = new WP_Widget($id_base, $wp_registered_widgets[$id]['name']);

				if ( preg_match( '/' . $id_base . '-([0-9]+)$/', $id, $matches ) )
					$number = $matches[1];
					
				$instances = get_option($widget->option_name);
				
				if ( isset($instances) && isset($number) ) {
					$instance = $instances[$number];
					
					if ( isset($instance['widget_display']) && check_wdisplay($instance['widget_display']) == false ) {
						unset($sidebars_widgets[$index][$i]);
					}
				}
			}
		}
		
		if ( empty($sidebars_widgets[$index]) ) return false;
		
	} else return false;
	
	return true;
}	
	
/**
 * Get Social share
 * */
    function get_social() {
	global $post;
	
	$social['social-share'] = sw_paradise_options()->getCpanelValue('social-share');
	$social['social-share-fb'] = sw_paradise_options()->getCpanelValue('social-share-fb');
	$social['social-share-tw'] = sw_paradise_options()->getCpanelValue('social-share-tw');
	$social['social-share-in'] = sw_paradise_options()->getCpanelValue('social-share-in');
	$social['social-share-go'] = sw_paradise_options()->getCpanelValue('social-share-go');
	
	if (!$social['social-share']) return false;
	
	$permalinked = urlencode(get_permalink($post->ID));
	$spermalink = get_permalink($post->ID);
	$title = urlencode($post->post_title);
	$stitle = $post->post_title;
	
	$data = '<div class="social-share">';
	$data .= '<style type="text/css">
				.social-share{
					display: table;
				    margin: 5px;
				    width: 100%;
				}
				.social-share-item{
					float: left;
				}
				.social-share-fb{
					margin-right: 25px;
                }
			</style>';
	
	if ($social['social-share-fb']) {
		$data .='<div class="social-share-fb social-share-item" >';
		$data .= '<div id="fb-root"></div>
					<script>(function(d, s, id) {
					  var js, fjs = d.getElementsByTagName(s)[0];
					  if (d.getElementById(id)) return;
					  js = d.createElement(s); js.id = id;
					  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
					  fjs.parentNode.insertBefore(js, fjs);
					}(document, \'script\', \'facebook-jssdk\'));</script>';
		$data .= '<div class="fb-like" data-href="'.esc_attr( $spermalink ).'" data-send="true" data-layout="button_count" data-width="200" data-show-faces="false"></div>';
		$data .= '</div> <!--Facebook Button-->';
	}
		
	if ($social['social-share-tw']) {
		$data .='<div class="social-share-twitter social-share-item" >
					esc_url<a href="'.esc_url( 'https://twitter.com/share' ).'" class="twitter-share-button" data-url="'. esc_attr( $spermalink ) .'" data-text="'.$stitle.'" data-count="horizontal">Tweet</a><script type="text/javascript" src="//platform.twitter.com/widgets.js"></script>
				</div> <!--Twitter Button-->';
	}
	
	if ($social['social-share-go']) {
		$data .= '<div class="social-share-google-plus social-share-item">
					<!-- Place this tag where you want the +1 button to render -->
					<div class="g-plusone" data-size="medium" data-href="'. esc_url( $permalinked ) .'"></div>
		
					<!-- Place this render call where appropriate -->
					<script type="text/javascript">
					  (function() {
						var po = document.createElement("script"); po.type = "text/javascript"; po.async = true;
						po.src = "'.esc_url( 'https://apis.google.com/js/plusone.js ' ).'";
						var s = document.getElementsByTagName("script")[0]; s.parentNode.insertBefore(po, s);
					  })();
					</script>
				</div> <!--google plus Button-->';
	}
	
	if ($social['social-share-in']) {
		$data .= '<div class="social-share-linkedin social-share-item">
					<script src="'.esc_url( 'http://platform.linkedin.com/in.js' ) .'" type="text/javascript"></script>
					<script type="IN/Share" data-url="'. esc_url( $permalinked ) .'" data-counter="right"></script>
				</div> <!--linkedin Button-->';
	}
	$data .= '</div>';
	echo $data;

}


/**
 * Use Bootstrap's media object for listing comments
 *
 * @link http://twitter.github.com/bootstrap/components.html#media
 */

function sw_paradise_get_avatar($avatar) {
	$avatar = str_replace("class='avatar", "class='avatar pull-left media-object", $avatar);
	return $avatar;
}
add_filter('get_avatar', 'sw_paradise_get_avatar');

function sw_paradise_custom_direction(){
	global $wp_locale;
	$opt_direction = sw_paradise_options()->getCpanelValue('text_direction');
	$opt_direction = strtolower($opt_direction);
	if ( in_array($opt_direction, array('ltr', 'rtl')) ){
		$wp_locale->text_direction = $opt_direction;
	} 
}
add_filter( 'wp', 'sw_paradise_custom_direction' );

function sw_paradise_navbar_class(){
	$classes = array( 'navbar' );

	if ( 'static' != sw_paradise_options()->getCpanelValue('navbar_position') )
		$classes[]	=	sw_paradise_options()->getCpanelValue('navbar_position');

	if ( sw_paradise_options()->getCpanelValue('navbar_inverse') )
		$classes[]	=	'navbar-inverse';

	apply_filters( 'sw_paradise_navbar_classes', $classes );

	echo 'class="' . join( ' ', $classes ) . '"';
}

function sw_paradise_content_product(){
	    $left_span_class 		= sw_paradise_options()->getCpanelValue('sidebar_left_expand');
	    $left_span_md_class 	= sw_paradise_options()->getCpanelValue('sidebar_left_expand_md');
	    $left_span_sm_class 	= sw_paradise_options()->getCpanelValue('sidebar_left_expand_sm');
		$right_span_class 		= sw_paradise_options()->getCpanelValue('sidebar_right_expand');
	    $right_span_md_class 	= sw_paradise_options()->getCpanelValue('sidebar_right_expand_md');
	    $right_span_sm_class 	= sw_paradise_options()->getCpanelValue('sidebar_right_expand_sm');
		$sidebar 				= sw_paradise_options()->getCpanelValue('sidebar_product');
    if( is_active_sidebar_SW_PARADISE('left-product') && is_active_sidebar_SW_PARADISE('right-product') && $sidebar =='lr' ){
		$content_span_class 	= 12 - ( $left_span_class + $right_span_class );
		$content_span_md_class 	= 12 - ( $left_span_md_class +  $right_span_md_class );
		$content_span_sm_class 	= 12 - ( $left_span_sm_class + $right_span_sm_class );
	} elseif( is_active_sidebar_SW_PARADISE('left-product') && $sidebar =='left' ) {
		$content_span_class 	= ($left_span_class >= 12) ? 12 : 12 - $left_span_class ;
		$content_span_md_class 	= ($left_span_md_class >= 12) ? 12 : 12 - $left_span_md_class ;
		$content_span_sm_class 	= ($left_span_sm_class >= 12) ? 12 : 12 - $left_span_sm_class ;
	}elseif( is_active_sidebar_SW_PARADISE('right-product') && $sidebar =='right' ) {
		$content_span_class 	= ($right_span_class >= 12) ? 12 : 12 - $right_span_class;
		$content_span_md_class 	= ($right_span_md_class >= 12) ? 12 : 12 - $right_span_md_class ;
		$content_span_sm_class 	= ($right_span_sm_class >= 12) ? 12 : 12 - $right_span_sm_class ;
	}else {
		$content_span_class 	= 12;
		$content_span_md_class 	= 12;
		$content_span_sm_class 	= 12;
	}
	$classes = array( 'content' );
	
		$classes[] = 'col-lg-'.$content_span_class.' col-md-'.$content_span_md_class .' col-sm-'.$content_span_sm_class;
	
	echo 'class="' . join( ' ', $classes ) . '"';
}
function sw_paradise_content_blog(){
	    $left_span_class 		= sw_paradise_options()->getCpanelValue('sidebar_left_expand');
	    $left_span_md_class 	= sw_paradise_options()->getCpanelValue('sidebar_left_expand_md');
	    $left_span_sm_class 	= sw_paradise_options()->getCpanelValue('sidebar_left_expand_sm');
		$right_span_class 		= sw_paradise_options()->getCpanelValue('sidebar_right_expand');
	    $right_span_md_class 	= sw_paradise_options()->getCpanelValue('sidebar_right_expand_md');
	    $right_span_sm_class 	= sw_paradise_options()->getCpanelValue('sidebar_right_expand_sm');
		$sidebar_template 		= sw_paradise_options() -> getCpanelValue('sidebar_blog');
    if( is_active_sidebar_SW_PARADISE('left-blog') && is_active_sidebar_SW_PARADISE('right-blog') && $sidebar_template == 'lr_sidebar' ){
		$content_span_class 	= 12 - ($left_span_class + $right_span_class);
		$content_span_md_class 	= 12 - ( $left_span_md_class +  $right_span_md_class );
		$content_span_sm_class 	= 12 - ($left_span_sm_class + $right_span_sm_class);
	} elseif( is_active_sidebar_SW_PARADISE('left-blog') && $sidebar_template == 'left_sidebar' ) {
		$content_span_class 	= ($left_span_class >= 12) ? 12 : 12 - $left_span_class ;
		$content_span_md_class 	= ($left_span_md_class >= 12) ? 12 : 12 - $left_span_md_class ;
		$content_span_sm_class 	= ($left_span_sm_class >= 12) ? 12 : 12 - $left_span_sm_class ;
	} elseif( is_active_sidebar_SW_PARADISE('right-blog') && $sidebar_template == 'right_sidebar' ) {
		$content_span_class 	= ($right_span_class >= 12) ? 12 : 12 - $right_span_class;
		$content_span_md_class 	= ($right_span_md_class >= 12) ? 12 : 12 - $right_span_md_class ;
		$content_span_sm_class 	= ($right_span_sm_class >= 12) ? 12 : 12 - $right_span_sm_class ;
	} else {
		$content_span_class 	= 12;
		$content_span_md_class 	= 12;
		$content_span_sm_class 	= 12;
	}
	$classes = array( '' );
	
		$classes[] = 'col-lg-'.$content_span_class.' col-md-'.$content_span_md_class .' col-sm-'.$content_span_sm_class . ' col-xs-12';
	
	echo  join( ' ', $classes ) ;
}

function sw_paradise_typography_css(){
	$styles = '';
	if ( sw_paradise_options()->getCpanelValue('google_webfonts') ):
		
		$webfonts_assign = sw_paradise_options()->getCpanelValue('webfonts_assign');
		$styles = '<style>';
		if ( $webfonts_assign == 'headers' ){
			$styles .= 'h1, h2, h3, h4, h5, h6 {';
		} else if ( $webfonts_assign == 'custom' ){
			$custom_assign = sw_paradise_options()->getCpanelValue('webfonts_custom');
			$custom_assign = trim($custom_assign);
			if (!$custom_assign) return '';
			$styles .= $custom_assign . ' {';
		} else {
			$styles .= 'body, input, button, select, textarea, .search-query {';
		}
		$styles .= 'font-family: ' . sw_paradise_options()->getCpanelValue('google_webfonts') . ' !important;}</style>';
	endif;
	return $styles;
}

function sw_paradise_typography_css_cache(){
	$data = get_transient( 'sw_paradise_typography_css' );
	$data = sw_paradise_typography_css();
	set_transient( 'sw_paradise_typography_css', $data, 3600 * 24 );
	echo $data;
}
add_action( 'wp_head', 'sw_paradise_typography_css_cache', 12, 0 );

function sw_paradise_typography_css_cache_reset(){
	delete_transient( 'sw_paradise_typography_css' );
	sw_paradise_typography_css_cache();
}


function sw_paradise_typography_webfonts(){
	if ( sw_paradise_options()->getCpanelValue('google_webfonts') ):
		$font_url = '';
		$webfont_weight = array();
		$webfont				= sw_paradise_options()->getCpanelValue('google_webfonts');
		$webfont_weight			= sw_paradise_options()->getCpanelValue('webfonts_weight');
		$font_weight = '';
		if( empty($webfont_weight) ){
			$font_weight = '400';
		}
		else{
			foreach( $webfont_weight as $i => $wf_weight ){
				( $i < 1 )?	$font_weight .= '' : $font_weight .= ',';
				$font_weight .= $wf_weight;
			}
		}
		$f = strlen($webfont);
		if ($f > 3){
			$webfontname = str_replace( ',', '|', $webfont );
			if ( 'off' !== _x( 'on', 'Google font: on or off', 'sw-paradise' ) ) {
				$font_url = add_query_arg( 'family', urlencode( $webfontname . ':' . $font_weight ), "//fonts.googleapis.com/css" );
			}
		}
		return $font_url;
	endif;
}
function sw_paradise_googlefonts_script() {
    wp_enqueue_style( 'sw-paradise-googlefonts', sw_paradise_typography_webfonts(), array(), '1.0.0' );
}
add_action( 'wp_enqueue_scripts', 'sw_paradise_googlefonts_script' );
function sw_paradise_custom_header_scripts() {
	if ( sw_paradise_options()->getCpanelValue('advanced_head') ){
		echo sw_paradise_options()->getCpanelValue('advanced_head');
	}
}
add_action( 'wp_head', 'sw_paradise_custom_header_scripts', 200 );
/* Favicon */
if ( ! function_exists( 'wp_site_icon' ) ) {
	function sw_paradise_add_favicon(){
		if ( sw_paradise_options()->getCpanelValue('favicon') ){
			echo '<link rel="shortcut icon" href="' . esc_url( sw_paradise_options()->getCpanelValue('favicon') ). '" />';
		}
	}
	add_action('wp_head', 'sw_paradise_add_favicon');
}
/* Get video or iframe from content */
function get_entry_content_asset( $post_id ){
	global $post;
	$post = get_post( $post_id );
	
	$content = apply_filters ("the_content", $post->post_content);
	
	$value=preg_match('/<iframe.*src=\"(.*)\".*><\/iframe>/isU',$content,$results);
	if($value){
		return $results[0];
	}else{
		return '';
	}
}
function excerpt($limit) {
  $excerpt = explode(' ', get_the_content(), $limit);
  if (count($excerpt)>=$limit) {
    array_pop($excerpt);
    $excerpt = implode(" ",$excerpt).'...';
  } else {
    $excerpt = implode(" ",$excerpt);
  }
  $excerpt = preg_replace('`[[^]]*]`','',$excerpt);
  return $excerpt;
}
/*Product Meta*/
add_action("admin_init", "post_init");
add_action( 'save_post', 'sw_paradise_product_save_meta', 10, 1 );
function post_init(){
	add_meta_box("sw_paradise_product_meta", "Product Meta", "sw_paradise_product_meta", "product", "normal", "low");
}	
function sw_paradise_product_meta(){
	global $post;
	$value = get_post_meta( $post->ID, 'new_product', true );
	$recommend_product = get_post_meta( $post->ID, 'recommend_product', true );
?>
	<p><label><b>Recommend Product:</b></label> &nbsp;&nbsp;
	<input type="checkbox" name="recommend_product" value="yes" <?php if(esc_attr($recommend_product) == 'yes'){ echo "CHECKED"; }?> /></p>
<?php }
function sw_paradise_product_save_meta(){
	global $post;
	if( isset( $_POST['recommend_product'] ) && $_POST['recommend_product'] != '' ){
		update_post_meta($post->ID, 'recommend_product', $_POST['recommend_product']);
	}else{
		return;
	}
}
/*end product meta*/
remove_action( 'get_product_search_form', 'get_product_search_form', 10);
add_action('get_product_search_form', 'sw_paradise_search_product_form', 10);
function sw_paradise_search_product_form( ){
	$search_form_template = locate_template( 'product-searchform.php' );
	if ( '' != $search_form_template  ) {
		require $search_form_template;
		return;
	}

	$form = '<form role="search" method="get" id="searchform" action="' . esc_url( home_url( '/'  ) ) . '">
		<div class="product-search">
			<div class="product-search-inner">
				<input type="text" class="search-text" value="' . get_search_query() . '" name="s" id="s" placeholder="' . esc_attr__( 'Search for products', 'sw-paradise' ) . '" />
				<input type="submit" class="search-submit" id="searchsubmit" value="'. esc_attr__( 'Go', 'sw-paradise' ) .'" />
				<input type="hidden" name="post_type" value="product" />
			</div>
		</div>
	</form>';

	return apply_filters( 'sw_paradise_search_product_form', $form );
}

add_filter( 'widget_tag_cloud_args', 'sw_paradise_tag_clound' );
function sw_paradise_tag_clound($args){
	$args['largest'] = 8;
	return $args;
}

/*********************** Change direction RTL *************************************/
if( !is_admin() ){
	add_filter( 'language_attributes', 'sw_paradise_direction', 20 );
	function sw_paradise_direction( $doctype = 'html' ){
		$sw_paradise_direction = sw_paradise_options()->getCpanelValue( 'direction' );
		if ( ( function_exists( 'is_rtl' ) && is_rtl() ) || $sw_paradise_direction == 'rtl' )
			$sw_paradise_attribute[] = 'dir="rtl"';
		( $sw_paradise_direction === 'rtl' ) ? $lang = 'ar' : $lang = get_bloginfo('language');
		if ( $lang ) {
		if ( get_option('html_type') == 'text/html' || $doctype == 'html' )
			$sw_paradise_attribute[] = "lang=\"$lang\"";

		if ( get_option('html_type') != 'text/html' || $doctype == 'xhtml' )
			$sw_paradise_attribute[] = "xml:lang=\"$lang\"";
		}
		$sw_paradise_output = implode(' ', $sw_paradise_attribute);
		return $sw_paradise_output;
	}
}

/*
 ** Search Filter
*/
if( !is_admin() ){
	function Sw_paradise_SearchFilter( $query ) {
		if ( $query->is_search ) {
			$query->set( 'post_type', array( 'post', 'product' ) );
		}
		return $query;
	}
	add_filter('pre_get_posts','Sw_paradise_SearchFilter');
}

/**
 * This class handles the Breadcrumbs generation and display
 */
class Sw_paradise_Breadcrumbs {

	/**
	 * Wrapper function for the breadcrumb so it can be output for the supported themes.
	 */
	function breadcrumb_output() {
		$this->breadcrumb( '<div class="breadcumbs">', '</div>' );
	}

	/**
	 * Get a term's parents.
	 *
	 * @param object $term Term to get the parents for
	 * @return array
	 */
	function get_term_parents( $term ) {
		$tax     = $term->taxonomy;
		$parents = array();
		while ( $term->parent != 0 ) {
			$term      = get_term( $term->parent, $tax );
			$parents[] = $term;
		}
		return array_reverse( $parents );
	}

	/**
	 * Display or return the full breadcrumb path.
	 *
	 * @param string $before  The prefix for the breadcrumb, usually something like "You're here".
	 * @param string $after   The suffix for the breadcrumb.
	 * @param bool   $display When true, echo the breadcrumb, if not, return it as a string.
	 * @return string
	 */
	function breadcrumb( $before = '', $after = '', $display = true ) {
		$options = array('breadcrumbs-home' => esc_html__( 'Home', 'sw-paradise' ), 'breadcrumbs-blog-remove' => false, 'post_types-post-maintax' => '0');
		
		global $wp_query, $post;	
		$on_front  = get_option( 'show_on_front' );
		$blog_page = get_option( 'page_for_posts' );

		$links = array(
			array(
				'url'  => get_home_url(),
				'text' => ( isset( $options['breadcrumbs-home'] ) && $options['breadcrumbs-home'] != '' ) ? $options['breadcrumbs-home'] : esc_html__( 'Home', 'sw-paradise' )
			)
		);

		if ( ( $on_front == "page" && is_front_page() ) || ( $on_front == "posts" && is_home() ) ) {

		} else if ( $on_front == "page" && is_home() ) {
			$links[] = array( 'id' => $blog_page );
		} else if ( is_singular() ) {		
			$tax = get_object_taxonomies( $post->post_type );
			if ( 0 == $post->post_parent ) {
				if ( isset( $tax ) && count( $tax ) > 0 ) {
					$main_tax = $tax[0];
					if( $post->post_type == 'product' ){
						$main_tax = 'product_cat';
					}					
					$terms    = wp_get_object_terms( $post->ID, $main_tax );
					
					if ( count( $terms ) > 0 ) {
						// Let's find the deepest term in this array, by looping through and then unsetting every term that is used as a parent by another one in the array.
						$terms_by_id = array();
						foreach ( $terms as $term ) {
							$terms_by_id[$term->term_id] = $term;
						}
						foreach ( $terms as $term ) {
							unset( $terms_by_id[$term->parent] );
						}

						// As we could still have two subcategories, from different parent categories, let's pick the first.
						reset( $terms_by_id );
						$deepest_term = current( $terms_by_id );

						if ( is_taxonomy_hierarchical( $main_tax ) && $deepest_term->parent != 0 ) {
							foreach ( $this->get_term_parents( $deepest_term ) as $parent_term ) {
								$links[] = array( 'term' => $parent_term );
							}
						}
						$links[] = array( 'term' => $deepest_term );
					}

				}
			} else {
				if ( isset( $post->ancestors ) ) {
					if ( is_array( $post->ancestors ) )
						$ancestors = array_values( $post->ancestors );
					else
						$ancestors = array( $post->ancestors );
				} else {
					$ancestors = array( $post->post_parent );
				}

				// Reverse the order so it's oldest to newest
				$ancestors = array_reverse( $ancestors );

				foreach ( $ancestors as $ancestor ) {
					$links[] = array( 'id' => $ancestor );
				}
			}
			$links[] = array( 'id' => $post->ID );
		} else {
			if ( is_post_type_archive() ) {
				$links[] = array( 'ptarchive' => get_post_type() );
			} else if ( is_tax() || is_tag() || is_category() ) {
				$term = $wp_query->get_queried_object();

				if ( is_taxonomy_hierarchical( $term->taxonomy ) && $term->parent != 0 ) {
					foreach ( $this->get_term_parents( $term ) as $parent_term ) {
						$links[] = array( 'term' => $parent_term );
					}
				}

				$links[] = array( 'term' => $term );
			} else if ( is_date() ) {
				$bc = esc_html__( 'Archives for', 'sw-paradise' );
				
				if ( is_day() ) {
					global $wp_locale;
					$links[] = array(
						'url'  => get_month_link( get_query_var( 'year' ), get_query_var( 'monthnum' ) ),
						'text' => $wp_locale->get_month( get_query_var( 'monthnum' ) ) . ' ' . get_query_var( 'year' )
					);
					$links[] = array( 'text' => $bc . " " . get_the_date() );
				} else if ( is_month() ) {
					$links[] = array( 'text' => $bc . " " . single_month_title( ' ', false ) );
				} else if ( is_year() ) {
					$links[] = array( 'text' => $bc . " " . get_query_var( 'year' ) );
				}
			} elseif ( is_author() ) {
				$bc = esc_html__( 'Archives for', 'sw-paradise' );
				$user    = $wp_query->get_queried_object();
				$links[] = array( 'text' => $bc . " " . esc_html( $user->display_name ) );
			} elseif ( is_search() ) {
				$bc = esc_html__( 'You searched for', 'sw-paradise' );
				$links[] = array( 'text' => $bc . ' "' . esc_html( get_search_query() ) . '"' );
			} elseif ( is_404() ) {
				$crumb404 = esc_html__( 'Error 404: Page not found', 'sw-paradise' );
				$links[] = array( 'text' => $crumb404 );
			}
		}
		
		$output = $this->create_breadcrumbs_string( $links );

		if ( $display ) {
			echo $before . $output . $after;
			return true;
		} else {
			return $before . $output . $after;
		}
	}

	/**
	 * Take the links array and return a full breadcrumb string.
	 *
	 * Each element of the links array can either have one of these keys:
	 *       "id"            for post types;
	 *    "ptarchive"  for a post type archive;
	 *    "term"         for a taxonomy term.
	 * If either of these 3 are set, the url and text are retrieved. If not, url and text have to be set.
	 *
	 * @link http://support.google.com/webmasters/bin/answer.py?hl=en&answer=185417 Google documentation on RDFA
	 *
	 * @param array  $links   The links that should be contained in the breadcrumb.
	 * @param string $wrapper The wrapping element for the entire breadcrumb path.
	 * @param string $element The wrapping element for each individual link.
	 * @return string
	 */
	function create_breadcrumbs_string( $links, $wrapper = 'ul', $element = 'li' ) {
		global $paged;
		
		$output = '';

		foreach ( $links as $i => $link ) {

			if ( isset( $link['id'] ) ) {
				$link['url']  = get_permalink( $link['id'] );
				$link['text'] = strip_tags( get_the_title( $link['id'] ) );
			}

			if ( isset( $link['term'] ) ) {
				$link['url']  = get_term_link( $link['term'] );
				$link['text'] = $link['term']->name;
			}

			if ( isset( $link['ptarchive'] ) ) {
				$post_type_obj = get_post_type_object( $link['ptarchive'] );
				$archive_title = $post_type_obj->labels->menu_name;
				$link['url']  = get_post_type_archive_link( $link['ptarchive'] );
				$link['text'] = $archive_title;
			}
			
			$link_class = '';
			if ( isset( $link['url'] ) && ( $i < ( count( $links ) - 1 ) || $paged ) ) {
				$link_output = '<a href="' . esc_url( $link['url'] ) . '" >' . esc_html( $link['text'] ) . '</a><span class="go-page"></span>';
			} else {
				$link_class = ' class="active" ';
				$link_output = '<span>' . esc_html( $link['text'] ) . '</span>';
			}
			
			$element = esc_attr(  $element );
			$element_output = '<' . $element . $link_class . '>' . $link_output . '</' . $element . '>';
			
			$output .=  $element_output;
			
			$class = ' class="breadcrumb" ';
		}

		return '<' . $wrapper . $class . '>' . $output . '</' . $wrapper . '>';
	}

}

global $yabreadcrumb;
$yabreadcrumb = new Sw_paradise_Breadcrumbs();

if ( !function_exists( 'sw_paradise_breadcrumb' ) ) {
	/**
	 * Template tag for breadcrumbs.
	 *
	 * @param string $before  What to show before the breadcrumb.
	 * @param string $after   What to show after the breadcrumb.
	 * @param bool   $display Whether to display the breadcrumb (true) or return it (false).
	 * @return string
	 */
	function sw_paradise_breadcrumb( $before = '', $after = '', $display = true ) {
		global $yabreadcrumb;
		return $yabreadcrumb->breadcrumb( $before, $after, $display );
	}
}

/*
** Search form to footer
*/
	add_action( 'wp_footer', 'sw_paradise_search_form' );
	function sw_paradise_search_form(){
?>
	<div class="modal fade" id="search_form" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog block-popup-search-form">
			<form role="search" method="get" class="form-search searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
				<label class="hide"></label>
				<input type="text" value="<?php if (is_search()) { echo get_search_query(); } ?>" name="s" class="search-query" placeholder="Enter your keyword...">
				<button type="submit" class=" fa fa-search button-search-pro form-button"></button>
				<a href="javascript:void(0)" title="<?php esc_attr_e( 'Close', 'sw-paradise' ) ?>" class="close close-search" data-dismiss="modal"><i class="fa fa-search"></i></a>
			</form>
		</div>
	</div>
<?php } 

/* Login Form */
if( class_exists( 'Woocommerce' ) ){
	add_action( 'wp_footer', 'sw_paradise_login_form' );
	function sw_paradise_login_form(){
?>
	<div class="modal fade" id="login_form" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog block-popup-login">
			<a href="javascript:void(0)" title="<?php esc_attr_e( 'Close', 'sw-paradise' ) ?>" class="close close-login" data-dismiss="modal"><?php esc_html_e( 'Close', 'sw-paradise' ) ?></a>
		<div class="tt_popup_login"><strong><?php esc_html_e('Sign in Or Register', 'sw-paradise'); ?></strong></div>
		<?php get_template_part('woocommerce/myaccount/login-form'); ?>
		</div>
	</div>
<?php 
	} 
}
/*
** Breadcrumb Custom with title 
*/
function sw_paradise_breadcrumb_title(){
	$maintaince_attr = ( sw_paradise_options()->getCpanelValue( 'bg_breadcrumb' ) != '' ) ? 'style="background: url( '. esc_url( sw_paradise_options()->getCpanelValue( 'bg_breadcrumb' ) ) .' )"' : '';
?>
	<div class="sw_paradise_breadcrumbs">
		<div class="container">
			<div class="breadcrumbs-page" <?php echo $maintaince_attr; ?>>						
			</div>
			<?php
				if (!is_front_page() ) {
					if (function_exists('sw_paradise_breadcrumb')){
						sw_paradise_breadcrumb('<div class="breadcrumbs custom-font theme-clearfix">', '</div>');
					} 
				} 
			?>
		</div>
	</div>
<?php 
}
