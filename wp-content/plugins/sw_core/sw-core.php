<?php
/**
 * Plugin Name: SW Core
 * Plugin URI: http://www.smartaddons.com
 * Description: A plugin developed for many shortcode in theme
 * Version: 1.0.1
 * Author: SmartAddons
 * Author URI: http://www.smartaddons.com
 * This Widget help you to show images of product as a beauty reponsive slider
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; 
}

if( !function_exists( 'is_plugin_active' ) ){
	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
}
/* define plugin path */
if ( ! defined( 'SWPATH' ) ) {
	define( 'SWPATH', plugin_dir_path( __FILE__ ) );
}
/* define plugin URL */
if ( ! defined( 'SWURL' ) ) {
	define( 'SWURL', plugins_url(). '/sw_core' );
}

function sw_core_construct(){
	/*
	** Require file
	*/
	require_once( SWPATH . 'sw_plugins/sw-plugins.php' );
	require_once( SWPATH . 'skills.php' );
	require_once( SWPATH . 'gallery.php' );
	require_once( SWPATH . 'countdown.php' );

	if( is_plugin_active( 'js_composer/js_composer.php' ) ){
		require_once ( SWPATH . '/visual-map.php' );
	}
	
	/*
	** Load text domain
	*/
	load_plugin_textdomain( 'sw_core', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' ); 
	
	/*
	** Call action and filter
	*/
	add_filter('style_loader_tag', 'sw_clean_style_tag');
	add_filter('widget_text', 'do_shortcode');
	add_action( 'wp_enqueue_scripts', 'Sw_AddScript', 20 );
}

add_action( 'plugins_loaded', 'sw_core_construct', 20 );

/**
 * Clean up output of stylesheet <link> tags
 */
function sw_clean_style_tag($input) {
	preg_match_all("!<link rel='stylesheet'\s?(id='[^']+')?\s+href='(.*)' type='text/css' media='(.*)' />!", $input, $matches);
	// Only display media if it's print
	$media = $matches[3][0] === 'print' ? ' media="print"' : '';
	return '<link rel="stylesheet" href="' . esc_url( $matches[2][0] ) . '"' . $media . '>' . "\n";
}

function Sw_AddScript(){
	wp_register_style('ya_photobox_css', SWURL . '/css/photobox.css', array(), null);	
	wp_register_style('flexslider_css', SWURL . '/css/flexslider.css', array(), null);
	wp_register_style('fancybox_css', SWURL . '/css/jquery.fancybox.css', array(), null);
	wp_register_style('shortcode_css', SWURL . '/css/shortcodes.css', array(), null);
	wp_register_script('flexslider_js', SWURL . '/js/jquery.flexslider-min.js', array(), null, true);
	wp_register_script('photobox_js', SWURL . '/js/photobox.js', array(), null, true);
	wp_register_script('fancybox_js', SWURL . '/js/jquery.fancybox.pack.js', array(	), null, true);
	wp_register_script('countdown', SWURL . '/js/jquery.classycountdown.min.js', array(), null, true);

	wp_enqueue_style( 'lightbox_css' );
	wp_enqueue_style( 'ya_photobox_css' );
	wp_enqueue_style( 'shortcode_css' );
	wp_enqueue_script( 'lightbox_js' );	
	wp_enqueue_script( 'flexslider_js' );
	wp_enqueue_script( 'photobox_js' );
	wp_enqueue_script( 'countdown' );		
}

class YA_Shortcodes{
	protected $supports = array();

	protected $tags = array( 'icon', 'button', 'alert', 'bloginfo', 'colorset', 'slideshow', 'googlemaps', 'columns', 'row', 'col', 'code', 'breadcrumb', 'pricing','tooltip','modal','gallery_image', 'headings', 'ya_tooltip', 'ya_modal', 'soundcloud_audio', 'videos', 'divider', 'get_url', 'social', 'Title', 'quotes', 'tables' );

	public function __construct(){
		$this->add_shortcodes();
	}
	
	public function add_shortcodes(){
		if ( is_array($this->tags) && count($this->tags) ){
			foreach ( $this->tags as $tag ){
				add_shortcode($tag, array($this, $tag));
			}
		}
	}
	
	function code($attr, $content) {
		$html = '';
		$html .= '<pre>';
		$html .= $content;
		$html .= '</pre>';
		
		return $html;
	}
	
	function icon( $atts ) {
		
		// Attributes
		extract( shortcode_atts(
			array(
				'tag' => 'span',
				'name' => '*',
				'class' => '',
				'border'=>'',
				'bg'    =>'',
				'color' => ''
				), $atts )
		);
		$attributes = array();
		
		$classes = preg_split('/[\s,]+/', $class, -1, PREG_SPLIT_NO_EMPTY);
		
		if ( !preg_match('/fa-/', $name) ){
			$name = 'fa-'.$name;
		}
		array_unshift($classes, $name);
		
		$classes = array_unique($classes);
		
		$attributes[] = 'class="fa '.implode(' ', $classes).'"';
		if(!empty($color)&&!empty($bg)&&!empty($border)){
			$attributes[] = 'style="color: '.$color.';background:'.$bg.';border:1px solid '.$border.'"';
		}
		if ( !empty($color) ){
			$attributes[] = 'style="color: '.$color.'"';
		}
		
		// Code
		return "<$tag ".implode(' ', $attributes)."></$tag>";
	}
	
	public function button( $atts, $content = null ){
		// Attributes
		extract( shortcode_atts(
			array(
				'id' => '',
				'tag' => 'span',
				'class' => 'btn',
				'target' => '',
				'type' => 'default',
				'border' =>'',
				'color' =>'',
				'size'	=> '',
				'icon' => '',
				'href' => '#'
				), $atts )
		);
		$attributes = array();
		
		$classes = $class;
		if ( $type != '' ){
			$type = ' btn-'.$type;
		}
		if( $size != '' ){
			$size = 'btn-'.$size;
		}
		$classes .= $type.' '.$size;
		$attributes[] = 'class="'.$classes.'"';
		if ( !empty($id) ){
			$attributes[] = 'id="'.esc_attr($id).'"';
		}
		if ( !empty($target) ){
			if ( 'a' == $tag ){
				$attributes[] = 'target="'.esc_attr($target).'"';
			} else {
				// html5
				$attributes[] = 'data-target="'.esc_attr($target).'"';
			}
		}
		
		if ( 'a' == $tag ){
			$attributes[] = 'href="'.esc_attr($href).'"';
		}
		if( $icon != '' ){
			$icon = '<i class="'.$icon.'"></i>';
		}
		return "<$tag ".implode(' ', $attributes).">".$icon."".do_shortcode($content)."</$tag>";
	}
	
/**
* Alert
* */
public function alert($atts, $content = null ){

	extract(shortcode_atts(array(
		'tag' => 'div',
		'class' => 'block',
		'dismiss' => 'true',
		'icon'  => '',
		'color'	=> '',
		'border' => '',
		'type' => ''
		), $atts)
	);

	$attributes = array();
	$attributes[] = $tag;
	$classes = array();
	$classes = preg_split('/[\s,]+/', $class, -1, PREG_SPLIT_NO_EMPTY);

	if ( !preg_match('/alert-/', $type) ){
		$type = 'alert-'.$type;
	}
	if( $color != '' || $border != '' ){
		$attributes[] .= 'style="color: '.$color.'; border-color:'.$border.'"';
	}
	array_unshift($classes, 'alert', $type);
	$classes = array_unique($classes);
	$attributes[] = 'class="'.implode(' ', $classes).'"';

	$html = '';
	$html .= '<'.implode(' ', $attributes).'>';
	if( $icon != '' ){
		$html .= '<i class="'.$icon.'"></i>';
	}
	if ($dismiss == 'true') {
		$html .= '<button type="button" class="close" data-dismiss="alert">&times;</button>';
	}
	$html .= do_shortcode($content);
	$html .= '</'.$tag.'>';
	return $html;
}


/**
 * Bloginfo
 * */
function bloginfo( $atts){
	extract( shortcode_atts(array(
		'show' => 'wpurl',
		'filter' => 'raw'
		), $atts)
	);
	$html = '';
	$html .= get_bloginfo($show, $filter);

	return $html;
}

function colorset($atts){
	if( function_exists( 'sw_paradise_options' ) ) :
		$value = sw_paradise_options()->getCpanelValue('scheme'); 
	return $value;
	endif;
}

/**
 * Google Maps
 */
function googlemaps($atts, $content = null) {
	extract(shortcode_atts(array(
		"title" => '',
		"location" => '',
		"width" => '', //leave width blank for responsive designs
		"height" => '300',
		"zoom" => 10,
		"align" => '',
		), $atts));

		// load scripts
	wp_enqueue_script('ya_googlemap',  plugins_url( '/js/ya_googlemap.js', __FILE__ ), array('jquery'), '', true);
	wp_enqueue_script('ya_googlemap_api', 'https://maps.googleapis.com/maps/api/js?sensor=false', array('jquery'), null, true);

	$output = '<div id="map_canvas_'.rand(1, 100).'" class="googlemap" style="height:'.$height.'px;width:'.$width.'">';
	$output .= (!empty($title)) ? '<input class="title" type="hidden" value="'.esc_attr( $title ).'" />' : '';
	$output .= '<input class="location" type="hidden" value="'.esc_attr( $location ).'" />';
	$output .= '<input class="zoom" type="hidden" value="'.esc_attr( $zoom ).'" />';
	$output .= '<div class="map_canvas"></div>';
	$output .= '</div>';

	return $output;
}


/**
 * Column
 * */
public function row( $atts, $content = null ){
	extract( shortcode_atts( array(
		'class' => '',
		'tag'   => 'div',
		'type'  => ''
		), $atts) );
	$row_class = 'row';

	$classes = array();
	$classes = preg_split('/[\s,]+/', $class, -1, PREG_SPLIT_NO_EMPTY);

	array_unshift($classes, $row_class);
	$classes = array_unique($classes);
	$classes = ' class="'. implode(' ', $classes).'"';
	return "<$tag ". $classes . ">" . do_shortcode($content) . "</$tag>";
}

public function col( $atts, $content = null ){
	extract( shortcode_atts( array(
		'class' 	=> '',
		'tag'   	=> 'div',
		'large'  	=> '12',
		'medium'	=> '12',
		'small'		=> '12',
		'xsmall'	=> '12'
		), $atts) );
	$col_class  = !empty($large)  ? "col-lg-$large"   : 'col-lg-12';
	$col_class .= !empty($medium) ? " col-md-$medium" : ' col-md-12';
	$col_class .= !empty($small)  ? " col-sm-$small"  : ' col-sm-12';
	$col_class .= !empty($xsmall) ? " col-xs-$xsmall" : ' col-xs-12';
	$classes = array();
	$classes = preg_split('/[\s,]+/', $class, -1, PREG_SPLIT_NO_EMPTY);
	array_unshift($classes, $col_class);
	$classes = array_unique($classes);
	$classes = ' class="'. implode(' ', $classes).'"';
	return "<$tag ". $classes . ">" . do_shortcode($content) . "</$tag>";
}

public function breadcrumb ($atts){

	extract(shortcode_atts(array(
		'class' => 'breadcumbs',
		'tag'  => 'div'
		), $atts));

	$classes = preg_split('/[\s,]+/', $class, -1, PREG_SPLIT_NO_EMPTY);
	$classes = ' class="' . implode(' ', $classes) . '" ';

	$before = '<' . $tag . $classes . '>';
	$after  = '</' . $tag . '>';

	$ya_breadcrumb = new YA_Breadcrumbs;
	return $ya_breadcrumb->breadcrumb( $before, $after, false );
}
	/*
	 * Heading tag
	 */
	function headings($atts,$content = null){
		extract( shortcode_atts( array (
			'heading' => '',
			'type'=>'',
			'color'=>'',
			'icon'=>'',
			'class'=>'', 		
			'bg'=>''
			), $atts ) );
		if( $icon != ''||$color !=''||$bg !=''||$class !=''){
			return '<span class="'.$class.'" style="background:'.$bg.';color:'.$color.'"><i class="fa '.esc_attr( $icon ).'"></i>'.do_shortcode($content);
		}
		if($heading !=''){
			return '<'.$heading.' style="font-weight:'.esc_attr( $type ).'">'.do_shortcode($content).'</'.$heading.'>';
		}
	}
	/*
	* Tooltip
	* @since v1.0
	*
	*/
	function ya_tooltip($atts, $content = null) {
		extract(shortcode_atts(array(
			'info' =>'',
			'title'=>'',
			'style'=>'',
			'position'=>''
			),$atts));
		if($title !=''){
			$title = '<strong>'.$title.'</strong>';
		}
		$html ='<a class="tooltips " href="#">';
		$html .='<span class="'.$position.' tooltip-'.$style.'">'.$title.$info.'<b></b></span>';
		$html .=do_shortcode($content);
		$html .='</a>';
		return $html;
	}
	/*
	 * Modal
	 * @since v1.0
	 *
	 */
	
	function ya_modal($attr, $content = null) {
		ob_start();
		$tag_id = 'myModal_'.rand().time();
		?>
		<a href="#<?php echo esc_attr( $tag_id ); ?>" role="button" class="btn btn-default" data-toggle="modal"><?php echo trim($attr['label']) ?></a>

		<!-- Modal -->
		<div id="<?php echo esc_attr( $tag_id ); ?>" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
						<h3 id="myModalLabel"><?php echo esc_html( trim($attr['header']) ) ?></h3>
					</div>
					<div class="modal-body">
						<?php echo $content; ?>
					</div>
					<div class="modal-footer">
						<button class="btn btn-default" data-dismiss="modal" aria-hidden="true"><?php echo esc_html( trim($attr['close']) ) ?></button>
						<button class="btn btn-primary"><?php echo esc_html( trim($attr['save']) ) ?></button>
					</div>
				</div>
			</div>
		</div>
		
		<?php
		$output_string = ob_get_contents();
		ob_end_clean();
		return $output_string;
	}
	/*
	 * Videos shortcode
	 *
	 */
	
	// register the shortcode to wrap html around the content
	function videos($atts, $content=null) {
		extract(
			shortcode_atts(array(
				'site' => '',
				'id' => '',
				'w' => '',
				'h' => ''
				), $atts)
			);
		if ( $site == "youtube" ) { $src = 'http://www.youtube-nocookie.com/embed/'.esc_attr( $id ); }
		else if ( $site == "vimeo" ) { $src = 'http://player.vimeo.com/video/'.esc_attr( $id ); }
		else if ( $site == "dailymotion" ) { $src = 'http://www.dailymotion.com/embed/video/'.esc_attr( $id ); }
		else if ( $site == "yahoo" ) { $src = 'http://d.yimg.com/nl/vyc/site/player.html#vid='.esc_attr( $id ); }
		else if ( $site == "bliptv" ) { $src = 'http://a.blip.tv/scripts/shoggplayer.html#file=http://blip.tv/rss/flash/'.esc_attr( $id ); }
		else if ( $site == "veoh" ) { $src = 'http://www.veoh.com/static/swf/veoh/SPL.swf?videoAutoPlay=0&permalinkId='.esc_attr( $id ); }
		else if ( $site == "viddler" ) { $src = 'http://www.viddler.com/simple/'.esc_attr( $id )
			; }
		if ( $id != '' ) {
			return '<iframe width="'.esc_attr( $w ).'" height="'.esc_attr( $h ).'" src="'.esc_attr( $src ).'" class="vid iframe-'.esc_attr( $site ).'"></iframe>';
		}
	}
	/*
	* Divider
	*
	*/
	function divider($atts){
		extract(shortcode_atts(array(
			'position' =>'top',
			'title'=>'',
			'style'=>'',
			'type'=>'',
			'width' =>'auto',
			'widthbd'=>'1px',
			'color' =>'#d1d1d1'
			),$atts));
		if($position !=''&&$type !='LR'){
			return '<h4 style="text-align: center;">'.$title.'</h4><hr style ="border-'.$position.':'.$widthbd.' '.$style.' '.$color.';width:'.$width.';margin-top:10px">';
		}
		if($type == 'LR'){
			return'<div class="rpl-title-wrapper"><h4>'.$title.'</h4></div><hr style ="border-'.$position.':'.$widthbd.' '.$style.' '.$color.';width:'.$width.';margin-top:-20px">';
		}
	}
	/*
	 * Audios shortcode
	 *
	 */
	// register the shortcode to wrap html around the content
	function soundcloud_audio( $atts ) {
		extract( shortcode_atts( array (
			'identifier' => ''
			), $atts ) );
		return '<div class="yt-audio-container"><iframe width="100%" height="166" frameborder="no" scrolling="no" src="https://w.soundcloud.com/player/?url=' . esc_attr( $identifier ) . '"></iframe></div>';
	}
	/*
	* Get URL shortcode
	*/
	function get_url($atts) {
		if(is_front_page()){
			$frontpage_ID = get_option('page_on_front');
			$link =  get_site_url().'/?page_id='.$frontpage_ID ;
			return $link;
		}
		elseif(is_page()){
			$pageid = get_the_ID();
			$link = get_site_url().'/?page_id='.$pageid ;
			return $link;
		}
		else{
			$link = $_SERVER['REQUEST_URI'];
			return $link;
		}
	}
	/*
	* Social
	*
	*/
	function socials($atts){
		extract(shortcode_atts(array(
			'style'=>'',
			'background'=>'',
			'icon'=>'',
			'link'=>'',
			'title'=>''
			),$atts));
		$bg='';
		if($background !=''){
			$bg = 'style="background:'.$background.'"';
		}
		return '<div id="socials" class="socials-'.$style.'" '.$bg.'><a href="'.$link.'" title="'.$title.'"><i class="fa '.$icon.'"></i></a></div>';
	}
	
	/**  Nav Title Style **/
	function Title($atts,$content = null){
		extract(shortcode_atts(array(
			'style'=>'',
			'color'=>'',
			'tag'=>'h2',
			'icon'=>'',
			'font-color'=>''
			),$atts));
		if( $icon != '' ){
			$icon = '<i class="'.$icon.'"></i>';
		}
		return '<section class="block-title '.$style.'">
		<'.$tag.'><span>'.$icon.do_shortcode($content).'</span></'.$tag.'>
	</section>';
}
	/*
	* Block quotes
	*
	*/
	function quotes( $atts,$content = null ) {
		extract( shortcode_atts( array(
			'style'=>''
			), $atts ) );
		return '<div class="quote-'.$style.'">'.do_shortcode($content).'</div>';
	}
	/*
	* Tables
	*
	*/
	function tables( $atts ) {
		extract( shortcode_atts( array(
			'cols' => 'none',
			'data' => 'none',
			'class'=>'',
			'style'=>''
			), $atts ) );
		$cols = explode(',',$cols);
		$data = explode(',',$data);
		$total = count($cols);
		$output = '<table class="table-'.$style.' '.$class.'"><tr class="th">';
		foreach($cols as $col):
			$output .= '<td>'.$col.'</td>';
		endforeach;
		$output .= '</tr><tr>';
		$counter = 1;
		foreach($data as $datum):
			$output .= '<td>'.$datum.'</td>';
		if($counter%$total==0):
			$output .= '</tr>';
		endif;
		$counter++;
		endforeach;
		$output .= '</table>';
		return $output;
	}
}
new YA_Shortcodes();

class YA_Breadcrumbs {

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
		$options = array('breadcrumbs-home' => 'Home', 'breadcrumbs-blog-remove' => false, 'post_types-post-maintax' => '0');
		
		global $wp_query, $post;

		$on_front  = get_option( 'show_on_front' );
		$blog_page = get_option( 'page_for_posts' );

		$links = array(
			array(
				'url'  => get_home_url(),
				'text' => ( isset( $options['breadcrumbs-home'] ) && $options['breadcrumbs-home'] != '' ) ? $options['breadcrumbs-home'] : __( 'Home', 'sw_core' )
				)
			);

		if ( ( $on_front == "page" && is_front_page() ) || ( $on_front == "posts" && is_home() ) ) {

		} else if ( $on_front == "page" && is_home() ) {
			$links[] = array( 'id' => $blog_page );
		} else if ( is_singular() ) {
			if ( get_post_type_archive_link( $post->post_type ) ) {
				$links[] = array( 'ptarchive' => $post->post_type );
			}
			
			if ( 0 == $post->post_parent ) {
				if ( isset( $options['post_types-post-maintax'] ) && $options['post_types-post-maintax'] != '0' ) {
					$main_tax = $options['post_types-post-maintax'];
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
				$bc = __( 'Archives for', 'sw_core' );
				
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
				$bc = __( 'Archives for', 'sw_core' );
				$user    = $wp_query->get_queried_object();
				$links[] = array( 'text' => $bc . " " . esc_html( $user->display_name ) );
			} elseif ( is_search() ) {
				$bc = __( 'You searched for', 'sw_core' );
				$links[] = array( 'text' => $bc . ' "' . esc_html( get_search_query() ) . '"' );
			} elseif ( is_404() ) {
				$crumb404 = __( 'Error 404: Page not found', 'sw_core' );
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
				$link_output = '<a href="' . esc_url( $link['url'] ) . '" >' . esc_html( $link['text'] ) . '</a>';
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
$yabreadcrumb = new YA_Breadcrumbs();

if ( !function_exists( 'ya_breadcrumb' ) ) {
	/**
	 * Template tag for breadcrumbs.
	 *
	 * @param string $before  What to show before the breadcrumb.
	 * @param string $after   What to show after the breadcrumb.
	 * @param bool   $display Whether to display the breadcrumb (true) or return it (false).
	 * @return string
	 */
	function ya_breadcrumb( $before = '', $after = '', $display = true ) {
		global $yabreadcrumb;
		return $yabreadcrumb->breadcrumb( $before, $after, $display );
	}
}

/*
 * Pricing Table
 * @since v1.0
 *
 */

/*main*/
if( !function_exists('pricing_table_shortcode') ) {
	function pricing_table_shortcode( $atts, $content = null  ) {
		extract( shortcode_atts( array(
			'style' => 'style1',
			), $atts ) );
		
		return '<div class="pricing-table clearfix '.$style.'">' . do_shortcode($content) . '</div></div>';
	}
	add_shortcode( 'pricing_table', 'pricing_table_shortcode' );
}

/*section*/
if( !function_exists('pricing_shortcode') ) {
	function pricing_shortcode( $atts, $content = null, $style_table) {
		
		extract( shortcode_atts( array(
			'style' =>'style1',
			'size' => 'one-five',
			'featured' => 'no',
			'description'=>'',
			'plan' => '',
			'cost' => '$20',
			'currency'=>'',
			'per' => 'month',
			'button_url' => '',
			'button_text' => 'Purchase',
			'button_target' => 'self',
			'button_rel' => 'nofollow'
			), $atts ) );
		
		//set variables
		$featured_pricing = ( $featured == 'yes' ) ? 'most-popular' : NULL;
		
		//start content1  
		$pricing_content1 ='';
		$pricing_content1 .= '<div class="pricing pricing-'. $size .' '. $featured_pricing . '">';
		$pricing_content1 .= '<div class="header">'. esc_html( $plan ). '</div>';
		$pricing_content1 .= '<div class="price">'. esc_html( $cost ) .'/'. esc_html( $per ) .'</div>';
		$pricing_content1 .= '<div class="pricing-content">';
		$pricing_content1 .= ''. $content. '';
		$pricing_content1 .= '</div>';
		if( $button_url ) {
			$pricing_content1 .= '<a href="'. esc_url( $button_url ) .'" class="signup" target="_'. esc_attr( $button_target ).'" rel="'. esc_attr( $button_rel ) .'" '.'>'. esc_html( $button_text ) .'</a>';
		}
		$pricing_content1 .= '</div>';
		
		//start content2  
		$pricing_content2 ='';
		$pricing_content2 .= '<div class="pricing pricing-'. $size .' '. $featured_pricing . '">';
		$pricing_content2 .= '<div class="header"><h3>'. esc_html( $plan ). '</h3><span>'.esc_html( $description ).'</span></div>';
		
		$pricing_content2 .= '<div class="pricing-content">';
		$pricing_content2 .= ''. $content. '';
		$pricing_content2 .= '</div>';
		$pricing_content2 .= '<div class="price"><span class="span-1"><p>'.$currency.'</p>'. esc_html( $cost ) .'</span><span class="span-2">'. esc_html( $per ) .'</span></div>';
		if( $button_url ) {
			$pricing_content2 .= '<div class="plan"><a href="'. esc_url( $button_url ) .'" class="signup" target="_'. esc_attr( $button_target ) .'" rel="'. esc_attr( $button_rel ) .'" '.'>'. esc_html( $button_text ) .'</a></div>';
		}
		$pricing_content2 .= '</div>';
		//start basic
		$pricing_content4 ='';
		$pricing_content4 .= '<div class="pricing pricing-'. $size .' '. $featured_pricing . '">';
		$pricing_content4 .= '<div class="price"><span class="span-1">'. esc_html( $cost ) .'<p>'.$currency.'</p></span><span class="span-2">'. esc_html( $per ) .'</span></div>';
		if( $button_url ) {
			$pricing_content4 .= '<div class="plan"><a href="'. esc_url( $button_url ) .'" class="signup" target="_'. esc_attr( $button_target ) .'" rel="'. esc_attr( $button_rel ) .'" '.'>'. esc_html( $button_text ) .'</a></div>';
		}
		$pricing_content4 .= '</div>';
			//start content5  
		$pricing_content5 ='';
		$pricing_content5 .= '<div class="pricing pricing-'. $size .' '. $featured_pricing . '">';
		$pricing_content5 .= '<div class="header">'. esc_html( $plan ). '</div>';
		$pricing_content5 .= '<div class="price"><p class="currency">'.$currency.'</p><p class="cost">'. esc_html( $cost ) .'</p>/'. esc_html( $per ) .'</div>';
		$pricing_content5 .='<div class="description"><span>'.esc_html( $description ).'</span></div>';
		$pricing_content5 .= '<div class="pricing-content">';
		$pricing_content5 .= ''. $content. '';
		$pricing_content5 .= '</div>';
		
		$pricing_content5 .= '<div class="footer">'. esc_html( $button_text ).'</div>';

		$pricing_content5 .= '</div>';
		if($style == 'style1'||$style == 'style3'){
			return $pricing_content1;
		}
		if($style == 'style2') {
			return $pricing_content2;
		}
		if($style == 'basic'){
			return $pricing_content4;
		}
		if($style == 'vprice'){
			return $pricing_content5;
		}
	}
	
	add_shortcode( 'pricing', 'pricing_shortcode' );
}
	/*
	* Lightbox image
	*
	*/
	function yt_lightbox_shortcode($atts){
		extract( shortcode_atts( array (
			'id' => '',
			'style'=>'',
			'size'=>'thumbnail',
			'class'=>'',
			'title'=>''
			), $atts ) );
		add_action('wp_footer', 'add_script_lightbox', 50);
		return '<div class="lightbox '.esc_attr( $class ).' lightbox-'.esc_attr( $style ).'" ><a id="single_image" href="' . wp_get_attachment_url($id) . '">'.wp_get_attachment_image($id,$size).'</a><div class="caption"><h4>'.$title.'</h4></div></div>';
	}
	add_shortcode ('lightbox', 'yt_lightbox_shortcode' );
	function add_script_lightbox(){
		$script = '';
		$script .= '<script type="text/javascript">
		jQuery(document).ready(function($) {
			"use strict";
			$("a#single_image").fancybox();
		});
</script>';
echo $script;
}
	/*
	 * Counter box
	 *
	 */
	function yt_counter_box($atts){
		extract( shortcode_atts( array(
			'style'=>'',
			'icon'=>'',
			'number'=>'',
			'type'=>''
			), $atts ) );
		add_action('wp_footer', 'add_script_counterbox', 50);
		wp_enqueue_script('ya_waypoints_api', 'http://cdnjs.cloudflare.com/ajax/libs/waypoints/2.0.3/waypoints.min.js', array('jquery'), null, true);
		if($icon !=''){
			$icon= '<i class="'.$icon.'"></i>';
		}
		return'<div class="counter-'.$style.'"><ul><li class="counterbox-number">'.$icon.''.$number.'</li><li class="type">'.$type.'</li></ul></div>';
	}
	add_shortcode('counters','yt_counter_box');
	function add_script_counterbox(){
		$script = '';
		$script .='<script type="text/javascript">';
		$script .= 'jQuery(document).ready(function( $ ) {
			$(".counterbox-number").counterUp({
				delay: 10,
				time: 1000
			});
});';
$script .='</script>';
echo $script;
}





/*
 * Vertical mega menu
 *
 */
function yt_vertical_megamenu_shortcode($atts){
	extract( shortcode_atts( array(
		'menu_locate' =>'',
		'title'  =>'',
		'el_class' => ''
		), $atts ) );
	$output = '<div class="vc_wp_custommenu wpb_content_element ' . $el_class . '">';
	if($title != ''){
		$output.='<div class="mega-left-title">
		<strong>'.$title.'</strong>
	</div>';
}
$output.='<div class="wrapper_vertical_menu vertical_megamenu">';
ob_start();
$output .= wp_nav_menu( array( 'menu' => $menu_locate, 'theme_location' => 'vertical_menu' , 'menu_class' => 'nav vertical-megamenu' ) );
$output .= ob_get_clean();
$output .= '</div></div>';
return $output;
}
add_shortcode('ya_mega_menu','yt_vertical_megamenu_shortcode');


/**
 * Clean up gallery_shortcode()
 *
 * Re-create the [gallery] shortcode and use thumbnails styling from Bootstrap
 *
 * @link http://twitter.github.com/bootstrap/components.html#thumbnails
 */
function ya_gallery($attr) {
	$post = get_post();

	static $instance = 0;
	$instance++;

	if (!empty($attr['ids'])) {
		if (empty($attr['orderby'])) {
			$attr['orderby'] = 'post__in';
		}
		$attr['include'] = $attr['ids'];
	}

	$output = apply_filters('post_gallery', '', $attr);

	if ($output != '') {
		return $output;
	}

	if (isset($attr['orderby'])) {
		$attr['orderby'] = sanitize_sql_orderby($attr['orderby']);
		if (!$attr['orderby']) {
			unset($attr['orderby']);
		}
	}

	extract(shortcode_atts(array(
		'order'      => 'ASC',
		'orderby'    => 'menu_order ID',
		'id'         => $post->ID,
		'itemtag'    => '',
		'icontag'    => '',
		'captiontag' => '',
		'columns'    => 3,
		'size'       => 'medium',
		'include'    => '',
		'exclude'    => ''
		), $attr)
	);

	$id = intval($id);

	if ($order === 'RAND') {
		$orderby = 'none';
	}

	if (!empty($include)) {
		$_attachments = get_posts(array('include' => $include, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby));

		$attachments = array();
		foreach ($_attachments as $key => $val) {
			$attachments[$val->ID] = $_attachments[$key];
		}
	} elseif (!empty($exclude)) {
		$attachments = get_children(array('post_parent' => $id, 'exclude' => $exclude, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby));
	} else {
		$attachments = get_children(array('post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby));
	}

	if (empty($attachments)) {
		return '';
	}

	if (is_feed()) {
		$output = "\n";
		foreach ($attachments as $att_id => $attachment) {
			$output .= wp_get_attachment_link($att_id, $size, true) . "\n";
		}
		return $output;
	}
	
	if (!wp_style_is('ya_photobox_css')){
		wp_enqueue_style('ya_photobox_css');
	}
	
	if (!wp_enqueue_script('photobox_js')){
		wp_enqueue_script('photobox_js');
	}
	
	$output = '<ul id="photobox-gallery-' . esc_attr( $instance ). '" class="thumbnails photobox-gallery gallery gallery-columns-'.esc_attr( $columns ).'">';

	$i = 0;
	$width = 100/$columns - 1;
	foreach ($attachments as $id => $attachment) {
		//$link = isset($attr['link']) && 'file' == $attr['link'] ? wp_get_attachment_link($id, $size, false, false) : wp_get_attachment_link($id, $size, true, false);
		$link = '<a class="thumbnail" href="' .esc_url( wp_get_attachment_url($id) ) . '">';
		$link .= wp_get_attachment_image($attachment->ID, $size);
		$link .= '</a>';
		
		$output .= '<li style="width: '.esc_attr( $width ).'%;">' . $link;
		$output .= '</li>';
	}

	$output .= '</ul>';
	
	add_action('wp_footer', 'ya_add_script_gallery', 50);
	
	return $output;
}
add_action( 'after_setup_theme', 'ya_setup_gallery', 20 );
function ya_setup_gallery(){
	if ( current_theme_supports('bootstrap-gallery') ) {
		remove_shortcode('gallery');
		add_shortcode('gallery', 'ya_gallery');
	}
}

function ya_add_script_gallery() {
	$script = '';
	$script .= '<script type="text/javascript">
	jQuery(document).ready(function($) {
		try{
						// photobox
			$(".photobox-gallery").each(function(){
				$("#" + this.id).photobox("li a");
							// or with a fancier selector and some settings, and a callback:
				$("#" + this.id).photobox("li:first a", { thumbs:false, time:0 }, imageLoaded);
				function imageLoaded(){
					console.log("image has been loaded...");
				}
			})
} catch(e){
	console.log( e );
}
});
</script>';

echo $script;
}

function ya_popup_subscribe( $atts ){
	extract(shortcode_atts(array(
		'title' => '',
		'description' => '',
		'background' => '',
		'facebook_link' => '',
		'twitter_link' => '',
		'googleplus_link' => '',
		'pinterest_link' => '',
		'linkedin_link' => '',
		), $atts));
	ob_start();
	?>
	<div id="subscribe_popup" class="subscribe-popup" <?php echo ( $background != '' ) ? 'style="background: url('. esc_attr( $background ) .') no-repeat"' : '' ?>>
		<div class="subscribe-popup-container">
			<h2 class="title"><?php echo esc_html( $title ); ?></h2>
			<p class="description"><?php echo esc_html( $description ); ?></p>
			<div class="subscribe-form">
				<?php
				if( is_plugin_active( 'mailchimp-for-wp/mailchimp-for-wp.php' ) ){
					echo do_shortcode( '[mc4wp_form]' );  
				}else{
					?>
					<div class="alert alert-warning alert-dismissible" role="alert">
						<a class="close" data-dismiss="alert">&times;</a>
						<p><?php esc_html_e('Please active mailchimp plugin first!', 'sw_core'); ?></p>
					</div>
					<?php
				}
				?>
			</div>
			<div class="subscribe-checkbox">
				<input id="popup_check" name="popup_check" type="checkbox" />
				<label><?php esc_html_e( "Don't show this popup again!", 'sw_core' ); ?></label>
			</div>
			<div class="social-share">
				<?php echo ( $facebook_link != '' ) ? '<a href="'. esc_url( $facebook_link ) .'" class="social-fb"><i class="fa fa-facebook"></i></a>' : ''; ?>
				<?php echo ( $twitter_link != '' ) ? '<a href="'. esc_url( $twitter_link ) .'" class="social-tw"><i class="fa fa-twitter"></i></a>' : ''; ?>
				<?php echo ( $googleplus_link != '' ) ? '<a href="'. esc_url( $googleplus_link ) .'" class="social-gplus"><i class="fa fa-google-plus"></i></a>' : ''; ?>
				<?php echo ( $pinterest_link != '' ) ? '<a href="'. esc_url( $pinterest_link ) .'" class="social-pin"><i class="fa fa-pinterest"></i></a>' : ''; ?>
			</div>
		</div>
	</div>
	<?php	
	$output = ob_get_clean();
	return $output;
	
}
add_shortcode('ya_popup_subscribe','ya_popup_subscribe');

/**
 * Clean up gallery_shortcode()
 *
 * Re-create the [gallery] shortcode and use thumbnails styling from Bootstrap
 *
 * @link http://twitter.github.com/bootstrap/components.html#thumbnails
 */
function flytheme_gallery($attr) {
	$post = get_post();

	static $instance = 0;
	$instance++;

	if (!empty($attr['ids'])) {
		if (empty($attr['orderby'])) {
			$attr['orderby'] = 'post__in';
		}
		$attr['include'] = $attr['ids'];
	}

	$output = apply_filters('post_gallery', '', $attr);

	if ($output != '') {
		return $output;
	}

	if (isset($attr['orderby'])) {
		$attr['orderby'] = sanitize_sql_orderby($attr['orderby']);
		if (!$attr['orderby']) {
			unset($attr['orderby']);
		}
	}

	extract(shortcode_atts(array(
		'order'      => 'ASC',
		'orderby'    => 'menu_order ID',
		'id'         => $post->ID,
		'itemtag'    => '',
		'icontag'    => '',
		'captiontag' => '',
		'columns'    => 3,
		'size'       => 'thumbnail',
		'include'    => '',
		'exclude'    => ''
		), $attr)
	);

	$id = intval($id);

	if ($order === 'RAND') {
		$orderby = 'none';
	}

	if (!empty($include)) {
		$_attachments = get_posts(array('include' => $include, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby));

		$attachments = array();
		foreach ($_attachments as $key => $val) {
			$attachments[$val->ID] = $_attachments[$key];
		}
	} elseif (!empty($exclude)) {
		$attachments = get_children(array('post_parent' => $id, 'exclude' => $exclude, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby));
	} else {
		$attachments = get_children(array('post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby));
	}

	if (empty($attachments)) {
		return '';
	}

	if (is_feed()) {
		$output = "\n";
		foreach ($attachments as $att_id => $attachment) {
			$output .= wp_get_attachment_link($att_id, $size, true) . "\n";
		}
		return $output;
	}
	
	if (!wp_style_is('flytheme_photobox_css')){
		wp_enqueue_style('flytheme_photobox_css');
	}
	
	if (!wp_enqueue_script('photobox_js')){
		wp_enqueue_script('photobox_js');
	}
	
	$output = '<ul id="photobox-gallery-' . esc_attr( $instance ). '" class="thumbnails photobox-gallery gallery gallery-columns-'.esc_attr( $columns ).'">';

	$i = 0;
	$width = 100/$columns - 1;
	foreach ($attachments as $id => $attachment) {
		$link = '<a class="thumbnail" href="' .esc_url( wp_get_attachment_url($id) ) . '">';
		$link .= wp_get_attachment_image($id);
		$link .= '</a>';
		
		$output .= '<li style="width: '.esc_attr( $width ).'%;">' . $link;
		$output .= '</li>';
	}

	$output .= '</ul>';
	
	add_action('wp_footer', 'add_script_gallery', 50);
	
	return $output;
}
if ( current_theme_supports('bootstrap-gallery') ) {
	remove_shortcode('gallery');
	add_shortcode('gallery', 'flytheme_gallery');
}
