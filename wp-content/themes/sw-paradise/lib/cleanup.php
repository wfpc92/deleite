<?php
/**
 * Clean up wp_head()
 *
 * Remove unnecessary <link>'s
 * Remove inline CSS used by Recent Comments widget
 * Remove inline CSS used by posts with galleries
 * Remove self-closing tag and change ''s to "'s on rel_canonical()
 */

define('SW_PARADISE_POST_EXCERPT_LENGTH', 40);

/**
 * Add and remove body_class() classes
 */
function sw_paradise_body_class($classes) {
	$sw_paradise_direction  = sw_paradise_options()->getCpanelValue('direction');
	$sw_paradise_box_layout 	= sw_paradise_options()->getCpanelValue('layout');
	$sw_paradise_header_style = sw_paradise_options()->getCpanelValue('header_style');
	if( $sw_paradise_direction == 'rtl' ){
		$classes[] = 'rtl';
	}
	if( $sw_paradise_box_layout == 'boxed' ){
		$classes[] = 'box-layout';
	}
	if( $sw_paradise_header_style == 'style2' ){
		$classes[] = 'content-style2';
	}

	// Add post/page slug
	if (is_single() || is_page() && !is_front_page()) {
		$classes[] = basename(get_permalink());
	}
	
	// Remove unnecessary classes
	$home_id_class = 'page-id-' . get_option('page_on_front');
	$remove_classes = array(
			'page-template-default',
			$home_id_class
	);
	$classes = array_diff($classes, $remove_classes);
	return $classes;
}
add_filter('body_class', 'sw_paradise_body_class');


/**
 * Wrap embedded media as suggested by Readability
 *
 * @link https://gist.github.com/965956
 * @link http://www.readability.com/publishers/guidelines#publisher
 */
function sw_paradise_embed_wrap($cache, $url, $attr = '', $post_ID = '') {
	$cache = preg_replace('/width="(.*?)?"/', 'width="100%"', $cache);
	return '<div class="entry-content-asset">' . $cache . '</div>';
}
add_filter('embed_oembed_html', 'sw_paradise_embed_wrap', 10, 4);
add_filter('embed_googlevideo', 'sw_paradise_embed_wrap', 10, 2);

/**
 * Add class="thumbnail" to attachment items
 */
function sw_paradise_attachment_link_class($html) {
	$postid = get_the_ID();
	$html = str_replace('<a', '<a class="thumbnail"', $html);
	return $html;
}
add_filter('wp_get_attachment_link', 'sw_paradise_attachment_link_class', 10, 1);

/**
 * Add Bootstrap thumbnail styling to images with captions
 * Use <figure> and <figcaption>
 *
 * @link http://justintadlock.com/archives/2011/07/01/captions-in-wordpress
 */
function sw_paradise_caption($output, $attr, $content) {
	if (is_feed()) {
		return $output;
	}

	$defaults = array(
			'id'      => '',
			'align'   => 'alignnone',
			'width'   => '',
			'caption' => ''
	);

	$attr = shortcode_atts($defaults, $attr);

	// If the width is less than 1 or there is no caption, return the content wrapped between the [caption] tags
	if ($attr['width'] < 1 || empty($attr['caption'])) {
		return $content;
	}

	// Set up the attributes for the caption <figure>
	$attributes  = (!empty($attr['id']) ? ' id="' . esc_attr($attr['id']) . '"' : '' );
	$attributes .= ' class="thumbnail wp-caption ' . esc_attr($attr['align']) . '"';
	$attributes .= ' style="width: ' . esc_attr($attr['width']) . 'px"';

	$output  = '<figure' . $attributes .'>';
	$output .= do_shortcode($content);
	$output .= '<figcaption class="caption wp-caption-text">' . $attr['caption'] . '</figcaption>';
	$output .= '</figure>';

	return $output;
}
add_filter('img_caption_shortcode', 'sw_paradise_caption', 10, 3);


/**
 * Clean up the_excerpt()
 */
function sw_paradise_excerpt_length($length) {
	return SW_PARADISE_POST_EXCERPT_LENGTH;
}

function sw_paradise_excerpt_more($more) {
	//return;
	return ' &hellip; <a href="' . get_permalink() . '">' . esc_html__('Readmore', 'sw-paradise') . '</a>';
}
add_filter('excerpt_length', 'sw_paradise_excerpt_length');
add_filter('excerpt_more',   'sw_paradise_excerpt_more');

/**
 * Remove unnecessary self-closing tags
 */
function sw_paradise_remove_self_closing_tags($input) {
  return str_replace(' />', '>', $input);
}
add_filter('get_avatar',          'sw_paradise_remove_self_closing_tags'); // <img />
add_filter('comment_id_fields',   'sw_paradise_remove_self_closing_tags'); // <input />
add_filter('post_thumbnail_html', 'sw_paradise_remove_self_closing_tags'); // <img />


/**
 * Allow more tags in TinyMCE including <iframe> and <script>
 */
function sw_paradise_change_mce_options($options) {
	$ext = 'pre[id|name|class|style],iframe[align|longdesc|name|width|height|frameborder|scrolling|marginheight|marginwidth|src],script[charset|defer|language|src|type]';

	if (isset($initArray['extended_valid_elements'])) {
		$options['extended_valid_elements'] .= ',' . $ext;
	} else {
		$options['extended_valid_elements'] = $ext;
	}

	return $options;
}
add_filter('tiny_mce_before_init', 'sw_paradise_change_mce_options');

/**
 * Add additional classes onto widgets
 *
 * @link http://wordpress.org/support/topic/how-to-first-and-last-css-classes-for-sidebar-widgets
 */
function sw_paradise_widget_first_last_classes($params) {
	global $my_widget_num;

	$this_id = $params[0]['id'];
	$arr_registered_widgets = wp_get_sidebars_widgets();

	if (!$my_widget_num) {
		$my_widget_num = array();
	}

	if (!isset($arr_registered_widgets[$this_id]) || !is_array($arr_registered_widgets[$this_id])) {
		return $params;
	}

	if (isset($my_widget_num[$this_id])) {
		$my_widget_num[$this_id] ++;
	} else {
		$my_widget_num[$this_id] = 1;
	}

	$class = 'class="widget-' . esc_attr( $my_widget_num[$this_id] ) . ' ';

	if ($my_widget_num[$this_id] == 1) {
		$class .= 'widget-first ';
	} elseif ($my_widget_num[$this_id] == count($arr_registered_widgets[$this_id])) {
		$class .= 'widget-last ';
	}

	$params[0]['before_widget'] = preg_replace('/class=\"/', "$class", $params[0]['before_widget'], 1);

	return $params;
}
add_filter('dynamic_sidebar_params', 'sw_paradise_widget_first_last_classes');

/**
 * Redirects search results from /?s=query to /search/query/, converts %20 to +
 *
 * @link http://txfx.net/wordpress-plugins/nice-search/
 */
function sw_paradise_nice_search_redirect() {
	global $sw_paradise_rewrite;
	if (!isset($sw_paradise_rewrite) || !is_object($sw_paradise_rewrite) || !$sw_paradise_rewrite->using_permalinks()) {
		return;
	}

	$search_base = $sw_paradise_rewrite->search_base;
	if (is_search() && !is_admin() && strpos($_SERVER['REQUEST_URI'], "/{$search_base}/") === false) {
		wp_redirect(home_url("/{$search_base}/" . urlencode(get_query_var('s'))));
		exit();
	}
}
if (current_theme_supports('nice-search')) {
	add_action('template_redirect', 'sw_paradise_nice_search_redirect');
}

/**
 * Fix for empty search queries redirecting to home page
 *
 * @link http://wordpress.org/support/topic/blank-search-sends-you-to-the-homepage#post-1772565
 * @link http://core.trac.wordpress.org/ticket/11330
 */
function sw_paradise_request_filter($query_vars) {
  if (isset($_GET['s']) && empty($_GET['s'])) {
    $query_vars['s'] = ' ';
  }

  return $query_vars;
}
add_filter('request', 'sw_paradise_request_filter');



function sw_paradise_wp_title( $title, $sep ) {
	global $paged, $page;

	if ( is_feed() )
		return $title;

	// Add the site name.
	$title .= get_bloginfo( 'name' );

	// Add the site description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		$title = "$title $sep $site_description";

	// Add a page number if necessary.
	if ( $paged >= 2 || $page >= 2 )
		$title = "$title $sep " . sprintf( __( 'Page %s', 'sw-paradise' ), max( $paged, $page ) );

	return $title;
}
add_filter( 'wp_title', 'sw_paradise_wp_title', 10, 2 );



add_filter('wp_link_pages_args','add_next_and_number');

function add_next_and_number($args){
    if($args['next_or_number'] == 'next_and_number'){
        global $page, $numpages, $multipage, $more, $pagenow;
        $args['next_or_number'] = 'number';
        $prev = '';
        $next = '';
        if ( $multipage ) {
            if ( $more ) {
                $i = $page - 1;
                if ( $i && $more ) {
					$prev .='<p>';
                    $prev .= _wp_link_page($i);
                    $prev .= $args['link_before'].$args['previouspagelink'] . $args['link_after'] . '</a></p>';
                }
                $i = $page + 1;
                if ( $i <= $numpages && $more ) {
					$next .='<p>';
                    $next .= _wp_link_page($i);
                    $next .= $args['link_before']. $args['nextpagelink'] . $args['link_after'] . '</a></p>';
                }
            }
        }
        $args['before'] = $args['before'].$prev;
        $args['after'] = $next.$args['after'];    
    }
    return $args;
}
/* Menu Sticky */
add_action( 'wp_footer', 'sw_paradise_sticky_menu', 100 );
function sw_paradise_sticky_menu(){
	$sticky_menu 		= sw_paradise_options()->getCpanelValue( 'sticky_menu' );
	$sw_paradise_header_style 	= sw_paradise_options()->getCpanelValue('header_style');
	$output = '';
	if( $sticky_menu ){
		$output .= '<script type="text/javascript">';
		$output .= '(function($) {';
		$output .= 'var sticky_navigation_offset_top = $("#main-menu").offset().top;';
		$output .= 'var sticky_navigation = function(){';
		$output .= 'var scroll_top = $(window).scrollTop();';
		$output .= 'if (scroll_top > sticky_navigation_offset_top) {';
		$output .= '$("#header").addClass("sticky-menu");';
		$output .= '} else {';
		$output .= '$("#header").removeClass("sticky-menu");';
		$output .= '}';
		$output .= '};';
		$output .= 'sticky_navigation();';
		$output .= '$(window).scroll(function() {';
		$output .= 'sticky_navigation();';
		$output .= '});';
		$output .= '}(jQuery));';
		$output .= '</script>';
		echo $output;
	} 
}
/* Popup Newsletter */
add_action( 'wp_footer', 'sw_paradise_popup', 101 );
function sw_paradise_popup(){
	$sw_paradise_popup	 		= sw_paradise_options()->getCpanelValue( 'popup_active' );
	$sw_paradise_popup_content 	= sw_paradise_options()->getCpanelValue('popup_shortcode');
	if( $sw_paradise_popup ){
		echo stripslashes( do_shortcode( $sw_paradise_popup_content ) );
?>
		<script>
			(function($) {
				$(document).ready(function() {
					var check_cookie = $.cookie('subscribe_popup');
					if(check_cookie == null || check_cookie == 'shown') {
						 popupNewsletter();
					 }
					$('#subscribe_popup input#popup_check').on('click', function(){
						if($(this).parent().find('input:checked').length){        
							var check_cookie = $.cookie('subscribe_popup');
						   if(check_cookie == null || check_cookie == 'shown') {
								$.cookie('subscribe_popup','dontshowitagain');            
							}
							else
							{
								$.cookie('subscribe_popup','shown');
								popupNewsletter();
							}
						} else {
							$.cookie('subscribe_popup','shown');
						}
					}); 
				});

				function popupNewsletter() {
					jQuery.fancybox({
						href: '#subscribe_popup',
						autoResize: true
					});
					jQuery('#subscribe_popup').trigger('click');
					jQuery('#subscribe_popup').parents('.fancybox-overlay').addClass('popup-fancy');
				};
			}(jQuery));
		</script>
<?php
	}
}
