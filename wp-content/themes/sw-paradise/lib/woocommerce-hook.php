<?php
add_theme_support( 'woocommerce' );

/*
** WooCommerce Compare Version
*/
if( !function_exists( 'sw_woocommerce_version_check' ) ) :
	function sw_woocommerce_version_check( $version = '3.0' ) {
		global $woocommerce;
		if( version_compare( $woocommerce->version, $version, ">=" ) ) {
			return true;
		}else{
			return false;
		}
	}
endif;

add_filter( 'wp_list_categories', 'sw_paradise_list_categories' );
function sw_paradise_list_categories( $output ){
	$output = preg_replace('~\((\d+)\)(?=\s*+<)~', '<span class="number-count">$1</span>', $output);
	return $output;
}

/*minicart via Ajax*/
$filter = sw_woocommerce_version_check( $version = '3.0.3' ) ? 'woocommerce_add_to_cart_fragments' : 'add_to_cart_fragments';
add_filter($filter, 'sw_paradise_add_to_cart_fragment', 100);
function sw_paradise_add_to_cart_fragment( $fragments ) {
	ob_start();
	?>
	<?php get_template_part( 'woocommerce/minicart-ajax' ); ?>
	<?php
	$fragments['.sw-paradise-minicart'] = ob_get_clean();
	return $fragments;
}

/*remove woo breadcrumb*/
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0 );

/*add second thumbnail loop product*/
remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
add_action( 'woocommerce_before_shop_loop_item_title', 'sw_paradise_woocommerce_template_loop_product_thumbnail', 10 );
function sw_paradise_product_thumbnail( $size = 'shop_single', $placeholder_width = 0, $placeholder_height = 0  ) {

	global $product, $post;
	$html = '';
	$gallery = get_post_meta($post->ID, '_product_image_gallery', true);
	$attachment_image = '';
	if(!empty($gallery)) {
		$gallery = explode(',', $gallery);
		$first_image_id = $gallery[0];
		$attachment_image = wp_get_attachment_image($first_image_id , $size, false, array('class' => 'hover-image back'));
	}
	$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), '' );
	$html .= '<a href="'.get_permalink( $post->ID ).'">';
	if ( has_post_thumbnail( $post->ID ) ){
		if( $attachment_image ){
			$html .= '<a href="'.get_permalink( $post->ID ).'">';
			$html .= '<div class="product-thumb-hover">';
			$html .= (get_the_post_thumbnail( $post->ID, $size )) ? get_the_post_thumbnail( $post->ID, $size ): '<img src="'.get_template_directory_uri().'/assets/img/placeholder/'.$size.'.png" alt="No thumb">';
			$html .= $attachment_image;
			$html .= '</div>';		
		}else{
			$html .= (get_the_post_thumbnail( $post->ID, $size )) ? get_the_post_thumbnail( $post->ID, $size ): '<img src="'.get_template_directory_uri().'/assets/img/placeholder/'.$size.'.png" alt="No thumb">';
		}			
	}else{
		$html .= '<img src="'.get_template_directory_uri().'/assets/img/placeholder/'.$size.'.png" alt="No thumb">';		
	}
	$html .= '</a>';		

	return $html;
}

function sw_paradise_woocommerce_template_loop_product_thumbnail(){
	echo sw_paradise_product_thumbnail();
}

/*filter order*/
function sw_paradise_addURLParameter($url, $paramName, $paramValue) {
	$url_data = parse_url($url);
	if(!isset($url_data["query"]))
		$url_data["query"]="";

	$params = array();
	parse_str($url_data['query'], $params);
	$params[$paramName] = $paramValue;
	$url_data['query'] = http_build_query($params);
	return sw_paradise_build_url($url_data);
}


function sw_paradise_build_url($url_data) {
	$url="";
	if(isset($url_data['host']))
	{
		$url .= $url_data['scheme'] . '://';
		if (isset($url_data['user'])) {
			$url .= $url_data['user'];
			if (isset($url_data['pass'])) {
				$url .= ':' . $url_data['pass'];
			}
			$url .= '@';
		}
		$url .= $url_data['host'];
		if (isset($url_data['port'])) {
			$url .= ':' . $url_data['port'];
		}
	}
	if (isset($url_data['path'])) {
		$url .= $url_data['path'];
	}
	if (isset($url_data['query'])) {
		$url .= '?' . $url_data['query'];
	}
	if (isset($url_data['fragment'])) {
		$url .= '#' . $url_data['fragment'];
	}
	return $url;
}

remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
remove_action( 'woocommerce_after_shop_loop', 'woocommerce_pagination', 10 );
remove_filter( 'woocommerce_product_loop_start', 'woocommerce_maybe_show_product_subcategories' );

add_filter( 'paradise_custom_category', 'woocommerce_maybe_show_product_subcategories' );
add_action( 'woocommerce_before_shop_loop', 'sw_paradise_viewmode_wrapper_start', 5 );
add_action( 'woocommerce_before_shop_loop', 'sw_paradise_viewmode_wrapper_end', 50 );
add_action( 'woocommerce_before_shop_loop', 'sw_paradise_woocommerce_pagination', 35 );
add_action( 'woocommerce_before_shop_loop','sw_paradise_woommerce_view_mode_wrap',15 );
add_action( 'woocommerce_after_shop_loop', 'sw_paradise_viewmode_wrapper_start', 5 );
add_action( 'woocommerce_after_shop_loop', 'sw_paradise_viewmode_wrapper_end', 50 );
add_action( 'woocommerce_after_shop_loop', 'sw_paradise_woocommerce_pagination', 7 );
add_action( 'woocommerce_after_shop_loop', 'sw_paradise_woommerce_view_mode_wrap', 6 );
remove_action( 'woocommerce_before_shop_loop', 'wc_print_notices', 10 );
add_action('woocommerce_message','wc_print_notices', 10);
function sw_paradise_viewmode_wrapper_start(){
	echo '<div class="products-nav">';
}
function sw_paradise_viewmode_wrapper_end(){
	echo '</div>';
}
function sw_paradise_woommerce_view_mode_wrap () {
	global $wp_query, $data;
	parse_str($_SERVER['QUERY_STRING'], $params);
	$query_string = '?'.$_SERVER['QUERY_STRING'];

	$pob = !empty( $params['orderby'] ) ? $params['orderby'] : get_option( 'woocommerce_default_catalog_orderby' );
	$po  = !empty($params['product_order'])  ? $params['product_order'] : 'asc';

	$html = '';
	// replace it with theme option
	if($data['woo_items']) {
		$per_page = $data['woo_items'];
	} else {
		$per_page = 12;
	}
	$pc  = !empty($params['product_count']) ? $params['product_count'] : $per_page;

	$html = '<div class="view-top col-lg-6 col-md-6 col-sm-6 col-xs-12">';
	$html .= '<div class="view-mode-wrap pull-left">
	<div class="view-mode">
		<a href="javascript:void(0)" class="grid-view active" title="'. esc_attr__('Grid view', 'sw-paradise').'"><span>'. esc_html__('Grid view', 'sw-paradise').'</span></a>
		<a href="javascript:void(0)" class="list-view" title="'. esc_attr__('List view', 'sw-paradise') .'"><span>'.esc_html__('List view', 'sw-paradise').'</span></a>
	</div>	
</div>';
$html .= '<div class="view-catelog pull-right">';
$html .= '<div class="orderby-order-container">';
$html .= '<ul class="orderby order-dropdown">';
$html .= '<li>';
$html .= '<span class="current-li"><a>'.esc_html__('Positions', 'sw-paradise').'</a></span>';
$html .= '<ul>';
$html .= '<li class="'.(($pob == 'menu_order') ? 'current': '').'"><a href="'.sw_paradise_addURLParameter($query_string, 'orderby', 'menu_order').'">'.esc_html__('Default', 'sw-paradise').'</a></li>';
$html .= '<li class="'.(($pob == 'popularity') ? 'current': '').'"><a href="'.sw_paradise_addURLParameter($query_string, 'orderby', 'popularity').'">'.esc_html__('Popularity', 'sw-paradise').'</a></li>';
$html .= '<li class="'.(($pob == 'rating') ? 'current': '').'"><a href="'.sw_paradise_addURLParameter($query_string, 'orderby', 'rating').'">'.esc_html__('Rating', 'sw-paradise').'</a></li>';
$html .= '<li class="'.(($pob == 'date') ? 'current': '').'"><a href="'.sw_paradise_addURLParameter($query_string, 'orderby', 'date').'">'.esc_html__('Date', 'sw-paradise').'</a></li>';
$html .= '<li class="'.(($pob == 'price') ? 'current': '').'"><a href="'.sw_paradise_addURLParameter($query_string, 'orderby', 'price').'">'.esc_html__('Price', 'sw-paradise').'</a></li>';
$html .= '</ul>';
$html .= '</li>';
$html .= '</ul>';	
$html .= '</div>';
$html .= '</div>';	
$html .= '</div>';
echo $html;
}

function sw_paradise_woocommerce_pagination() {
	global $wp_query, $data;
	parse_str($_SERVER['QUERY_STRING'], $params);
	$query_string = '?'.$_SERVER['QUERY_STRING'];

	$pob = !empty( $params['orderby'] ) ? $params['orderby'] : get_option( 'woocommerce_default_catalog_orderby' );
	$po  = !empty($params['product_order'])  ? $params['product_order'] : 'asc';

	$html = '';
	// replace it with theme option
	if($data['woo_items']) {
		$per_page = $data['woo_items'];
	} else {
		$per_page = 12;
	}
	$pc  = !empty($params['product_count']) ? $params['product_count'] : $per_page;

	?>
	<div class="view-bottom col-lg-6 col-md-6 col-sm-6 col-xs-12">
		<?php 

		$html .= '<div class="view-catelog pull-left">';
		$html .= '<div class="orderby-order-container">';
		$html .= '<span class="show-product"> '.esc_html__('Show :', 'sw-paradise').' </span>';
		$html .= '<ul class="sort-count order-dropdown">';
		$html .= '<li>';
		$html .= '<span class="current-li"><a>'.esc_html__('12', 'sw-paradise').'</a></span>';
		$html .= '<ul>';
		$html .= '<li class="'.(($pc == $per_page) ? 'current': '').'"><a href="'.sw_paradise_addURLParameter($query_string, 'product_count', $per_page).'">'.$per_page.'</a></li>';
		$html .= '<li class="'.(($pc == $per_page*2) ? 'current': '').'"><a href="'.sw_paradise_addURLParameter($query_string, 'product_count', $per_page*2).'">'.($per_page*2).'</a></li>';
		$html .= '<li class="'.(($pc == $per_page*3) ? 'current': '').'"><a href="'.sw_paradise_addURLParameter($query_string, 'product_count', $per_page*3).'">'.($per_page*3).'</a></li>';
		$html .= '</ul>';
		$html .= '</li>';
		$html .= '</ul>';
		$html .= '</div>';
		$html .= '</div>';

		echo $html;
		$term 		= get_queried_object();
		$parent_id 	= empty( $term->term_id ) ? 0 : $term->term_id;
		$product_categories = get_categories( apply_filters( 'woocommerce_product_subcategories_args', array(
			'parent'       => $parent_id,
			'menu_order'   => 'ASC',
			'hide_empty'   => 0,
			'hierarchical' => 1,
			'taxonomy'     => 'product_cat',
			'pad_counts'   => 1
			) ) );
		if ( $product_categories ) {
			if ( is_product_category() ) {
				$display_type = get_woocommerce_term_meta( $term->term_id, 'display_type', true );

				switch ( $display_type ) {
					case 'subcategories' :
					$wp_query->post_count    = 0;
					$wp_query->max_num_pages = 0;
					break;
					case '' :
					if ( get_option( 'woocommerce_category_archive_display' ) == 'subcategories' ) {
						$wp_query->post_count    = 0;
						$wp_query->max_num_pages = 0;
					}
					break;
				}
			}

			if ( is_shop() && get_option( 'woocommerce_shop_page_display' ) == 'subcategories' ) {
				$wp_query->post_count    = 0;
				$wp_query->max_num_pages = 0;
			}
		}
		wc_get_template( 'loop/pagination.php' );
		echo '</div>';
	}


	add_filter('loop_shop_per_page', 'sw_paradise_loop_shop_per_page');
	function sw_paradise_loop_shop_per_page()
	{
		global $data;

		parse_str($_SERVER['QUERY_STRING'], $params);

		if($data['woo_items']) {
			$per_page = $data['woo_items'];
		} else {
			$per_page = 12;
		}

		$pc = !empty($params['product_count']) ? $params['product_count'] : $per_page;

		return $pc;
	}
	/*********QUICK VIEW PRODUCT**********/

	add_action("wp_ajax_sw_paradise_quickviewproduct", "sw_paradise_quickviewproduct");
	add_action("wp_ajax_nopriv_sw_paradise_quickviewproduct", "sw_paradise_quickviewproduct");
	function sw_paradise_quickviewproduct(){

		$productid = (isset($_REQUEST["post_id"]) && $_REQUEST["post_id"]>0) ? $_REQUEST["post_id"] : 0;

		$query_args = array(
			'post_type'	=> 'product',
			'p'			=> $productid
			);
		$outputraw = $output = '';
		$r = new WP_Query($query_args);
		if($r->have_posts()){ 

			while ($r->have_posts()){ $r->the_post(); setup_postdata($r->post);
				global $product;
				ob_start();
				wc_get_template_part( 'content', 'quickview-product' );
				$outputraw = ob_get_contents();
				ob_end_clean();
			}
		}
		$output = preg_replace(array('/\s{2,}/', '/[\t\n]/'), ' ', $outputraw);
		echo $output;exit();
	}
	/* Product loop content */
	remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );
	remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );
	add_action( 'woocommerce_shop_loop_item_title', 'sw_paradise_loop_product_title', 10 );
	add_action( 'woocommerce_after_shop_loop_item_title', 'sw_paradise_product_description', 11 );
	add_action( 'woocommerce_after_shop_loop_item', 'sw_paradise_product_addcart_start', 1 );
	add_action( 'woocommerce_after_shop_loop_item', 'sw_paradise_product_addcart_mid', 20 );
	add_action( 'woocommerce_after_shop_loop_item', 'sw_paradise_product_addcart_end', 99 );
	function sw_paradise_loop_product_title(){
		?>
		<h4><a href="<?php the_permalink() ?>" title="<?php the_title_attribute(); ?>"><?php the_title() ?></a></h4>
		<?php
	}
	function sw_paradise_product_description(){
		global $post;
		if ( ! $post->post_excerpt ) return;

		echo '<div class="item-description">'.wp_trim_words( $post->post_excerpt, 20 ).'</div>';
	}
	function sw_paradise_product_addcart_start(){
		echo '<div class="item-bottom clearfix">';
	}
	function sw_paradise_product_addcart_end(){
		echo '</div>';
	}
	function sw_paradise_product_addcart_mid(){
		global $product, $post;
		$quickview = sw_paradise_options()->getCpanelValue( 'product_quickview' );

		$html ='';
		/* compare & wishlist */
		if( class_exists( 'YITH_WCWL' ) ){
			$html .= do_shortcode( "[yith_wcwl_add_to_wishlist]" );
		}
		if( class_exists( 'YITH_WOOCOMPARE' ) ){
			$html .= do_shortcode( "[yith_compare_button]" );	
		}	
		/* quickview */
		if( $quickview ) : 
			$nonce = wp_create_nonce("sw_paradise_quickviewproduct_nonce");
		$link = admin_url('admin-ajax.php?ajax=true&amp;action=sw_paradise_quickviewproduct&amp;post_id='.$post->ID.'&amp;nonce='.$nonce);
		$html .= '<a href="'. $link .'" data-fancybox-type="ajax" class="group fancybox fancybox.ajax">'.apply_filters( 'out_of_stock_add_to_cart_text', esc_html__( 'Quick View ', 'sw-paradise' ) ).'</a>';	
		endif;
		echo $html;
	}
	add_filter( 'product_cat_class', 'sw_paradise_product_category_class', 2 );
	function sw_paradise_product_category_class( $classes, $category = null ){
		$sw_paradise_product_sidebar = sw_paradise_options()->getCpanelValue('sidebar_product');
		if( $sw_paradise_product_sidebar == 'left' || $sw_paradise_product_sidebar == 'right' ){
			$classes[] = 'col-lg-4 col-md-4 col-sm-6 col-xs-6 col-mb-12';
		}else if( $sw_paradise_product_sidebar == 'lr' ){
			$classes[] = 'col-lg-6 col-md-6 col-sm-6 col-xs-6 col-mb-12';
		}else if( $sw_paradise_product_sidebar == 'full' ){
			$classes[] = 'col-lg-3 col-md-4 col-sm-6 col-xs-6 col-mb-12';
		}
		return $classes;
	}

	/* Add Product Tag To Tabs */
	add_filter( 'woocommerce_product_tabs', 'sw_paradise_tab_tag' );
	function sw_paradise_tab_tag($tabs){
		global $post, $product;
		$tag_count = sizeof( get_the_terms( $post->ID, 'product_tag' ) );
		if ( count( $tag_count ) > 1 ) {
			$tabs['product_tag'] = array(
				'title'    => esc_html__( 'Tags', 'sw-paradise' ),
				'priority' => 11,
				'callback' => 'sw_paradise_single_product_tab_tag'
				);
		}
		return $tabs;
	}
	function sw_paradise_single_product_tab_tag(){
		global $post, $product;
		echo $product->get_tags( ', ', '<span class="tagged_as">' . _n( 'Tag:', 'Tags:', $tag_count, 'sw-paradise' ) . ' ', '</span>' );
	}
	function sw_paradise_cpwl_init(){
		if( class_exists( 'YITH_WOOCOMPARE' ) ){
			update_option( 'yith_woocompare_compare_button_in_product_page', 'no' );
			update_option( 'yith_woocompare_compare_button_in_products_list', 'no' );
		}
		if( class_exists( 'YITH_WCWL' ) ){
			update_option( 'yith_wcwl_button_position', 'shortcode' );
		}
	}
	add_action('admin_init','sw_paradise_cpwl_init');
	remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );

/* ==========================================================================================
	** Single Product
   ========================================================================================== */
remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10 );
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
add_action( 'woocommerce_single_product_summary', 'flytheme_product_excerpt', 20 );
function flytheme_product_excerpt(){
	global $post;
	
	if ( ! $post->post_excerpt ) {
		return;
	}
	$html = '';
	$html .= '<div class="product-description" itemprop="description">';
	$html .= '<h2 class="quick-overview">'. esc_html__( 'Quick Overview', 'sw-paradise' ) .'</h2>';
	$html .= apply_filters( 'woocommerce_short_description', $post->post_excerpt );
	$html .= '</div>';
	echo $html;
}
add_action( 'woocommerce_single_product_summary', 'flytheme_single_addcart_wrapper_start', 25 );
add_action( 'woocommerce_after_add_to_cart_button', 'flytheme_single_addcart', 10 );
add_action( 'woocommerce_single_product_summary', 'flytheme_single_addcart_wrapper_end', 32 );
add_action( 'woocommerce_single_product_summary', 'flytheme_woocommerce_sharing', 20 );
function flytheme_woocommerce_sharing(){
	global $product, $post;
?>
	<div class="social-share">
	     <span class="share-title custom-font"><?php echo esc_html_e('Share This ','sw-paradise')?></span>
	     <a href="http://www.facebook.com/share.php?u=<?php the_permalink(); ?>&title=<?php the_title(); ?>" onclick="javascript:window.open(this.href,'', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;"><i class="fa fa-facebook"></i></a>
		 <a href="http://twitter.com/home?status=<?php the_title(); ?>+<?php the_permalink(); ?>" onclick="javascript:window.open(this.href,'', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;"><i class="fa fa-twitter"></i></a>
	     <a href="https://plus.google.com/share?url=<?php the_permalink(); ?>" onclick="javascript:window.open(this.href,'', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;"><i class="fa fa-google-plus"></i></a>
		 <a href="http://pinterest.com/pin/create/bookmarklet/?media=<?php echo wp_get_attachment_url( get_post_thumbnail_id($post->ID) ); ?>&url=<?php the_permalink(); ?>&is_video=false&description=<?php the_title(); ?>" onclick="javascript:window.open(this.href,'', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;"><i class="fa fa-pinterest"></i></a>
	</div>
<?php
}
function flytheme_single_addcart_wrapper_start(){
	echo '<div class="product-summary-bottom clearfix">';
}
function flytheme_single_addcart_wrapper_end(){
	echo "</div>";
}
function flytheme_single_addcart(){
	/* compare & wishlist */
	global $product, $post;
	$product_id = $post->ID;
	$html = '';
	$product_type = ( sw_woocommerce_version_check( '3.0' ) ) ? $product->get_type() : $product->product_type;
		if( $product_type != 'variable' ) :
		$html .= '<div class="single-product-addcart">';
		if( class_exists( 'YITH_WCWL' ) ){
			$html .= do_shortcode( "[yith_wcwl_add_to_wishlist]" );
		}
		if( class_exists( 'YITH_WOOCOMPARE' ) ){
			$html .= '<div class="woocommerce product compare-button"><a href="javascript:void(0)" class="compare button" data-product_id="'. $product_id .'" rel="nofollow">'. esc_html__( 'Compare', 'sw-paradise' ) .'</a></div>';
		}
		$html .= '</div>';
	echo $html;
	endif;
}

/*
**Hook into review for rick snippet
*/
add_action( 'woocommerce_review_before_comment_meta', 'paradise_title_ricksnippet', 10 ) ;
function paradise_title_ricksnippet(){
	global $post;
	echo '<span class="hidden" itemprop="itemReviewed" itemscope itemtype="http://schema.org/Thing">
    <span itemprop="name">'. $post->post_title .'</span>
  </span>';
}
	?>