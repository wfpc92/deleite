<?php
/**
 * SW WooCommerce Shortcodes
 * @author 		flytheme
 * @version     1.0.0
 */

if( is_plugin_active( 'js_composer/js_composer.php' ) ){
	require_once ( WCPATH . '/includes/visual-map.php' );
}

/*
** Listing tab product
*/
function flytheme_listing_product_tab( $atts ){
	extract( shortcode_atts( array(
		'title'		=> 'Categories',
		'category'	=> '',
		'orderby' => 'date',
		'order' => 'DESC',
		'number'	=> 3,
		), $atts )
	);
	ob_start();		
	global $yith_wcwl,$product;
	$yith_compare = new YITH_Woocompare_Frontend();
	$rand_time = rand().time();
	$lf_id = 'listing_tab_'.rand().time();
	$categories_id = array();
	if( $categories_id  == '' ){
		return ;
	}
	if( $categories_id  != '' ){
		$categories_id = explode( ',', $category );
	}
	$attributes = '';
	$attributes .= 'tab-col-'.$number;
	?>
	<div id="<?php echo esc_attr( $lf_id ) ?>" class="listing-tab-shortcode">
	<div class="tabbable tabs"><ul id="myTabs" class="nav nav-tabs">
		<li class="title-cat custom-font"><span><?php echo $title ?></span></li>
	<?php 
		foreach( $categories_id as $key => $category_id ){
			$cat = get_term_by('slug', $category_id, 'product_cat');
			$active = ( $key == 0 ) ? 'active' : '';
			if( $cat != NULL ){
			
	?>
		<li class="<?php echo esc_attr( $active ) ?>" onclick="window.location='<?php echo get_term_link( $cat->term_id, 'product_cat' ) ?>'"><a href="#listing_category_<?php echo $category_id.'_'.$rand_time ?>" data-toggle="tab"><?php echo esc_html( $cat -> name ) ?></a></li>
	<?php } } ?>
	</ul>
	<div class="tab-content">
	<?php 
	foreach( $categories_id as $key => $category_id ){
		$active = ( $key == 0 ) ? 'active' : '';
	?>
		<div id="listing_category_<?php echo esc_attr( $category_id ).'_'.$rand_time ?>" class="tab-pane clearfix <?php echo esc_attr( $active ); ?>" data-lg="<?php echo esc_attr( $columns ); ?>" data-md="<?php echo esc_attr( $columns1 ); ?>" data-sm="<?php echo esc_attr( $columns2 ); ?>" data-xs="<?php echo esc_attr( $columns3 ); ?>" data-mobile="<?php echo esc_attr( $columns4 ); ?>">
	<?php 
		if( $category_id != '' ){
		$args = array(
			'post_type' => 'product',
			'tax_query'	=> array(
			array(
				'taxonomy'	=> 'product_cat',
				'field'		=> 'slug',
				'terms'		=> $category_id)),
			
			'orderby'		=> $orderby,
			'order'			=> $order,
			'post_status' 	=> 'publish',
			'showposts' 	=> $number
		);
		}else{
			$args = array(
				'post_type' => 'product',
				'orderby' => $orderby,
				'order' => $order,
				'post_status' => 'publish',
				'showposts' => $number
			);
		}
		$list = new WP_Query( $args );
		while($list->have_posts()): $list->the_post();
		global $product, $post;
	?>
			<div class="item-wrap">
				<div class="item-wrap">
					<div class="item-detail">										
						<div class="item-img products-thumb">			
							<?php do_action( 'woocommerce_before_shop_loop_item_title' ); ?>
						</div>										
						<div class="item-content">
							<h4><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute();?>"><?php the_title(); ?></a></h4>				
								
							<?php if ( $price_html = $product->get_price_html() ){?>
								<div class="item-price">
									<span>
										<?php echo $price_html; ?>
									</span>
								</div>
							<?php } ?>	
							
							<?php 
								$rating_count = $product->get_rating_count();
								$review_count = $product->get_review_count();
								$average      = $product->get_average_rating();
							?>
							<div class="reviews-content">
								<div class="star"><?php echo ( $average > 0 ) ?'<span style="width:'. ( $average*13 ).'px"></span>' : ''; ?></div>
							</div>
							
							<!-- add to cart, wishlist, compare -->
							<?php do_action( 'woocommerce_after_shop_loop_item' ); ?>
						</div>								
					</div>
				</div>
			</div>	
		<?php 
			endwhile; wp_reset_postdata();
			wp_reset_postdata();
		?>
			</div>
	<?php } ?>
		</div></div></div>
<?php 
	$output = ob_get_clean();
	return $output;
}
add_shortcode('product_tab','flytheme_listing_product_tab');

 /*
 * Best Sale product
 *
 */
 function flytheme_bestsale_shortcode($atts){
	 extract(shortcode_atts(array(
	 'number' => 5,
	 'title'=>'',
	 'category'=>'',
	 'el_class'=>'',
	 'item_slide'=>'',
	 'template'=>'',
    		'post_status' 	 => 'publish',
    		'post_type' 	 => 'product',
    		'meta_key' 		 => 'total_sales',
    		'orderby' 		 => 'meta_value_num',
    		'no_found_rows'  => 1
	 ),$atts));
	 ob_start();
	 global $woocommerce;
	 $i='';
	 $pf_id = 'bestsale-'.rand().time();
	 $query_args =array( 'posts_per_page'=> $number,'post_type' => 'product','meta_key' => 'total_sales','orderby' => 'meta_value_num','no_found_rows' => 1);
if( $category != '' ){
			$term = get_term_by( 'slug', $category, 'product_cat' );	
			$term_name = $term->name;
			$query_args['tax_query'] = array(
				array(
					'taxonomy'  => 'product_cat',
					'field'     => 'slug',
					'terms'     => $category )
			);	
		}	 
	 $query_args['meta_query'] = $woocommerce->query->get_meta_query();

    		$query_args['meta_query'][] = array(
			    'key'     => '_price',
			    'value'   => 0,
			    'compare' => '>',
			    'type'    => 'DECIMAL',
			);
    

		$r = new WP_Query($query_args);
		$numb_post = count( $r -> posts );
		if ( $r->have_posts() ) {
if($template== 'default'){
?>
	<div id="<?php echo $pf_id ?>" class="sw-best-seller-product vc_element">
		<?php 
			if( $title != '' ){ 
				$titles = strpos($title, ' ');
		?>
			<div class="box-slider-title"><h2><span><?php echo substr( $title, 0, $titles ) ?></span> <?php echo substr( $title, $titles + 1 ) ?></h2></div>
	<?php } 
		while ( $r -> have_posts() ) : $r -> the_post();
		global $product, $post;
	?>
		<div class="item">
			<div class="item-inner">
				<div class="item-img">
					<a href="<?php echo get_permalink($post->ID) ?>" title="<?php echo esc_attr( $post->post_title ) ?>">
					<?php if( has_post_thumbnail() ){  ?>
					<?php echo ( get_the_post_thumbnail( $r->post->ID, 'shop_thumbnail' ) ) ? get_the_post_thumbnail( $r->post->ID, 'shop_thumbnail' ):'<img src="'.get_template_directory_uri().'/assets/img/placeholder/shop_thumbnail.png" alt="No thumb"/>';
						}else{ ?>
						<img src="'.get_template_directory_uri().'/assets/img/placeholder/shop_thumbnail.png" alt="No thumb"/>
					<?php	} ?>
					</a>
				</div>
				<div class="item-content">
					<?php 
						$rating_count = $product->get_rating_count();
						$review_count = $product->get_review_count();
						$average      = $product->get_average_rating();
					?>
					<div class="star"><?php echo ( $average > 0 ) ?'<span style="width:'. ( $average*14 ).'px"></span>' : '' ?> </div>
					<h4><a href="<?php echo get_permalink($post->ID) ?>" title="<?php echo esc_attr( $post->post_title ) ?>"><?php echo  esc_html( $post->post_title ) ?></a></h4>	
					<p><?php echo $product->get_price_html() ?></p>			 
				</div>
			</div>
		</div>
	<?php 
		endwhile;
		wp_reset_postdata();
	?>
	</div>
<?php
	}elseif($template == 'slide'){ ?>
	<div id="<?php echo $pf_id ?>" class="sw-best-seller-product vc_element carousel slide <?php echo $el_class ?>" data-interval="0">
	<?php if( $title != '' ){
				$titles = strpos($title, ' ');
	?>
		<div class="box-slider-title"><h2><span><?php echo $title; ?></h2></div>
	<?php } ?>
		<div class="customNavigation nav-left-product">
			<a title="<?php echo esc_attr__( 'Previous', 'sw_woocommerce' ) ?>" class="btn-bs prev-bs fa fa-angle-left"  href="#<?php echo $pf_id ?>" role="button" data-slide="prev"></a>
			<a title="<?php echo esc_attr__( 'Next', 'sw_woocommerce' ) ?>" class="btn-bs next-bs fa fa-angle-right" href="#<?php echo $pf_id ?>" role="button" data-slide="next"></a>
		</div>
    <div class="carousel-inner">
		<?php 
			$i = 0;
			while ( $r -> have_posts() ) : $r -> the_post();
			global $product, $post;
				if( ( $i % $item_slide ) == 0 && ( $item_slide != 0 ) ){
				$active = ( $i == 0 ) ? 'active' : '';
		?>
			<div class="item <?php echo $active ?>" >
		<?php } ?>
				<div class="item-wrap">
					<div class="item-inner">
						<div class="item-img">
							<a href="<?php echo get_permalink($post->ID) ?>" title="<?php echo esc_attr( $post->post_title ) ?>">
							<?php if( has_post_thumbnail() ){ 							
									echo ( get_the_post_thumbnail( $r->post->ID, 'paradise-product-thumb' ) ) ? get_the_post_thumbnail( $r->post->ID, 'paradise-product-thumb' ):'<img src="'.get_template_directory_uri().'/assets/img/placeholder/shop_thumbnail.png" alt="No thumb"/>' ;
								}else{ 
									echo '<img src="'.get_template_directory_uri().'/assets/img/placeholder/shop_thumbnail.png" alt="No thumb"/>';
								}
							?>
							</a>
						</div>
						<div class="item-content">
							<?php 	
								$rating_count = $product->get_rating_count();
								$review_count = $product->get_review_count();
								$average      = $product->get_average_rating();
							?>
							<div class="star"><?php echo ( $average > 0 ) ?'<span style="width:'. ( $average*14 ).'px"></span>' : '' ?> </div>	
							<h4><a href="<?php echo get_permalink($post->ID) ?>" title="<?php echo esc_attr( $post->post_title ) ?>"><?php echo esc_html( $post->post_title ) ?></a></h4>
							<div class="item-price"><p><?php echo $product->get_price_html() ?></p></div>
						</div>
					</div>
				</div>
		<?php if( ( $i+1 ) % ($item_slide) == 0 || ( $i+1 ) == $numb_post ){ ?>
			</div>
		<?php }
			$i++;endwhile;
			wp_reset_postdata();
		?>
		</div>
	</div>
		<?php 
		}
	}
	$content = ob_get_clean();
	return $content;
 }
 add_shortcode('BestSale','flytheme_bestsale_shortcode');
  /*
 * Recommend product
 *
 */
 function flytheme_recommend_shortcode($atts){
	 extract(shortcode_atts(array(
	 'number' => '',
	 'title'=>'',
	 'el_class'=>'',
	 'template'=>'',
	 'item_slide'=>'',
    		'post_status' 	 => 'publish',
    		'post_type' 	 => 'product',
    		'meta_key' 		 => 'recommend_product',
			'meta_value'     => 'yes',
    		'orderby' 		 => 'ID',
    		'no_found_rows'  => 1
	 ),$atts));
		ob_start();
		global $woocommerce;
		$pf_id = 'sw_recommend_product-'.rand().time();
		$query_args =array( 'posts_per_page'=> $number,'post_type' => 'product','meta_key' => 'recommend_product','meta_value' => 'yes','orderby' => $orderby,'no_found_rows' => 1); 
		$query_args['meta_query'] = $woocommerce->query->get_meta_query();
		$r = new WP_Query($query_args);
		$numb_post = count( $r -> posts );
		if ( $r->have_posts() ) {
			if($template== 'default'){	
	?>
	<div id="<?php echo $pf_id ?>" class="sw-recommend-product vc_element">
	<?php 
		if( $title != '' ){
			$titles = strpos($title, ' ');
	?>
			<div class="box-slider-title"><h2><span><?php echo substr( $title, 0, $titles ) ?></span> <?php echo substr( $title, $titles + 1 ) ?></h2></div>
	<?php }
		while ( $r -> have_posts() ) : $r -> the_post();
		global $product, $post;
	?>
		<div class="item">
			<div class="item-inner">
				<div class="item-img">
					<a href="<?php echo get_permalink($post->ID) ?>" title="<?php echo esc_attr( $post->post_title ) ?>">
					<?php 
						if( has_post_thumbnail() ){  
							echo ( get_the_post_thumbnail( $r->post->ID, 'shop_thumbnail' ) ) ? get_the_post_thumbnail( $r->post->ID, 'shop_thumbnail' ):'<img src="'.get_template_directory_uri().'/assets/img/placeholder/shop_thumbnail.png" alt="No thumb"/>';
						}else{ 
							echo '<img src="'.get_template_directory_uri().'/assets/img/placeholder/shop_thumbnail.png" alt="No thumb"/>' ;
						}
					?>
					</a>
				</div>
				<div class="item-content">
					<h4><a href="<?php echo get_permalink($post->ID) ?>" title="<?php echo esc_attr( $post->post_title ) ?>"><?php echo esc_html( $post->post_title ) ?></a></h4>
					<?php 
						$rating_count = $product->get_rating_count();
						$review_count = $product->get_review_count();
						$average      = $product->get_average_rating();
					?>
					<div class="star"><?php echo ( $average > 0 ) ?'<span style="width:'. ( $average*14 ).'px"></span>' : '' ?></div>	
					<p><?php echo $product->get_price_html() ?></p>			 
				</div>
			</div>
		</div>
	<?php 
		endwhile;
		wp_reset_postdata();
	?>
	</div>
<?php 
	}elseif( $template == 'slide' ){
?>
	<div id="<?php echo $pf_id ?>" class="sw-recommend-product-slider carousel slide <?php echo $el_class ?>" data-interval="0">
	<?php if( $title != '' ){
		$titles = strpos($title, ' ');
	?>
		<div class="box-slider-title"><h2><span><?php echo substr( $title, 0, $titles ) ?></span><?php echo substr( $title, $titles + 1 ) ?></h2></div>
	<?php } ?>
		<div class="customNavigation nav-left-product">
			<a title="<?php echo esc_attr__( 'Previous', 'sw_woocommerce' ) ?>" class="btn-bs prev-bs fa  fa-arrow-left"  href="#<?php echo $pf_id ?>" role="button" data-slide="prev"></a>
			<a title="<?php echo esc_attr__( 'Next', 'sw_woocommerce' ) ?>" class="btn-bs next-bs fa  fa-arrow-right" href="#<?php echo $pf_id ?>" role="button" data-slide="next"></a>
		</div>
    <div class="carousel-inner">
		<?php 
			$i = 0;
			while ( $r -> have_posts() ) : $r -> the_post();
			global $product, $post;
			if( ( $i % $item_slide ) == 0 && ( $item_slide != 0 ) ){
				$active = ( $i == 0 ) ? 'active' : '';
		?>
			<div class="item <?php echo $active ?>" >
	<?php } ?>
				<div class="item-wrap">
					<div class="item-inner">
						<div class="item-img">
							<a href="<?php echo get_permalink($post->ID) ?>" title="<?php echo esc_attr( $post->post_title ) ?>">
							<?php 
								if( has_post_thumbnail() ){  
									echo ( get_the_post_thumbnail( $r->post->ID, 'shop_thumbnail' ) ) ? get_the_post_thumbnail( $r->post->ID, 'shop_thumbnail' ):'<img src="'.get_template_directory_uri().'/assets/img/placeholder/shop_thumbnail.png" alt="No thumb"/>' ;
								}else{ 
									echo '<img src="'.get_template_directory_uri().'/assets/img/placeholder/shop_thumbnail.png" alt="No thumb"/>';
								}
							?>
							</a>
						</div>
						<div class="item-content">
							<h4><a href="<?php echo get_permalink($post->ID) ?>" title="<?php echo esc_attr( $post->post_title ) ?>"><?php echo esc_html( $post->post_title ) ?></a></h4>
							<?php
								$rating_count = $product->get_rating_count();
								$review_count = $product->get_review_count();
								$average      = $product->get_average_rating();
							?>
							<div class="star"><?php echo ( $average > 0 ) ?'<span style="width:'. ( $average*14 ).'px"></span>' : '' ?></div>	
							<div class="item-price"><p><?php echo $product->get_price_html() ?></p></div>
						</div>
					</div>
				</div>
			<?php if( ( $i+1 ) % ($item_slide) == 0 || ( $i+1 ) == $numb_post ){ ?>
			</div>
			<?php }
				$i++;endwhile;
				wp_reset_postdata();
			?>
		</div>
	</div>
	<?php 
		}
	}
	$content = ob_get_clean();
	return $content;
 }
 add_shortcode('Recommend','flytheme_recommend_shortcode');
   /*
 * Recommend product
 *
 */
 function flytheme_latest_products_shortcode($atts){
	 extract(shortcode_atts(array(
			'number' => 5,
			'title'=>'',
			'category'=>'',
			'el_class'=>'',
			'template'=>'',
			'item_slide'=>'',
			'post_status' 	 => 'publish',
			'post_type' 	 => 'product',
			'orderby' 		 => 'date',
			'order'          => 'DESC',
			'no_found_rows'  => 1
		),$atts));
		ob_start();
		global $woocommerce;
		$pf_id = 'SW_latest_product-'.rand().time();
		$query_args =array( 'posts_per_page'=> $number, 'post_type' => 'product', 'orderby' => $orderby, 'order' => $order, 'no_found_rows' => 1); 
		if( $category != '' ){
			$term = get_term_by( 'slug', $category, 'product_cat' );	
			$term_name = $term->name;
			$query_args['tax_query'] = array(
				array(
					'taxonomy'  => 'product_cat',
					'field'     => 'slug',
					'terms'     => $category )
			);	
		}	
		$query_args['meta_query'] = $woocommerce->query->get_meta_query();
		$r = new WP_Query($query_args);
		$numb_post = count( $r -> posts );
		if ( $r->have_posts() ) {
			if($template== 'default'){
	?>
	<div id="<?php echo $pf_id ?>" class="sw-latest-product vc_element">
	<?php 
		if( $title != '' ){
			$titles = strpos($title, ' ');
	?>
			<div class="box-slider-title"><h2><span><?php echo substr( $title, 0, $titles ) ?></span><?php echo substr( $title, $titles + 1 ) ?></h2></div>
	<?php }
		while ( $r -> have_posts() ) : $r -> the_post();
		global $product, $post;
	?>
		<div class="item">
			<div class="item-inner">
				<div class="item-img">
					<a href="<?php echo get_permalink($post->ID) ?>" title="<?php echo esc_attr( $post->post_title ) ?>">
					<?php
						if( has_post_thumbnail() ){  
							echo ( get_the_post_thumbnail( $r->post->ID, 'shop_thumbnail' ) ) ? get_the_post_thumbnail( $r->post->ID, 'shop_thumbnail' ):'<img src="'.get_template_directory_uri().'/assets/img/placeholder/shop_thumbnail.png" alt="No thumb"/>' ;
						}else{ 
							echo '<img src="'.get_template_directory_uri().'/assets/img/placeholder/shop_thumbnail.png" alt="No thumb"/>' ;
						}
					?>
					</a>
				</div>
				<div class="item-content">
					<h4><a href="<?php echo get_permalink($post->ID) ?>" title="<?php echo esc_attr( $post->post_title ) ?>"><?php echo esc_html( $post->post_title ) ?></a></h4>
					<?php
						$rating_count = $product->get_rating_count();
						$review_count = $product->get_review_count();
						$average      = $product->get_average_rating();
					?>
					<div class="star"><?php echo ( $average > 0 ) ?'<span style="width:'. ( $average*14 ).'px"></span>' : '' ?></div>	
					<div class="item-price"><p><?php echo $product->get_price_html() ?></p></div>
				</div>
			</div>
		</div>
	<?php 
		endwhile;
		wp_reset_postdata();
	?>
	</div>
<?php 
	}elseif($template == 'slide'){ ?>
	<div id="<?php echo $pf_id ?>" class="sw-latest-product vc_element carousel slide <?php echo $el_class ?>" data-interval="0">
	<?php 
		if( $title != '' ){
			$titles = strpos($title, ' ');
	?>
			<div class="box-slider-title"><h2><span><?php echo substr( $title, 0, $titles ) ?></span><?php echo substr( $title, $titles + 1 ) ?></h2></div>
	<?php } ?>
		<div class="customNavigation nav-left-product">
			<a title="<?php echo esc_attr__( 'Previous', 'sw_woocommerce' ) ?>" class="btn-bs prev-bs fa fa-angle-left"  href="#<?php echo $pf_id ?>" role="button" data-slide="prev"></a>
			<a title="<?php echo esc_attr__( 'Next', 'sw_woocommerce' ) ?>" class="btn-bs next-bs fa fa-angle-right" href="#<?php echo $pf_id ?>" role="button" data-slide="next"></a>
		</div>
    <div class="carousel-inner">
		<?php 
			$i = 0;
			while ( $r -> have_posts() ) : $r -> the_post();
			global $product, $post;
			if( ( $i % $item_slide ) == 0 && ( $item_slide != 0 ) ){
				$active = ( $i == 0 ) ? 'active' : '';
		?>
			<div class="item <?php echo $active ?>" >
	<?php } ?>
				<div class="item-wrap">
					<div class="item-inner">
						<div class="item-img">
							<a href="<?php echo get_permalink($post->ID) ?>" title="<?php echo esc_attr( $post->post_title ) ?>">
							<?php
								if( has_post_thumbnail() ){  
									echo ( get_the_post_thumbnail( $r->post->ID, 'paradise-product-thumb' ) ) ? get_the_post_thumbnail( $r->post->ID, 'paradise-product-thumb' ):'<img src="'.get_template_directory_uri().'/assets/img/placeholder/shop_thumbnail.png" alt="No thumb"/>' ;
								}else{ 
									echo '<img src="'.get_template_directory_uri().'/assets/img/placeholder/shop_thumbnail.png" alt="No thumb"/>' ;
								}
							?>
							</a>
						</div>
						<div class="item-content">
							<?php
								$rating_count = $product->get_rating_count();
								$review_count = $product->get_review_count();
								$average      = $product->get_average_rating();
							?>
							<div class="star"><?php echo ( $average > 0 ) ?'<span style="width:'. ( $average*14 ).'px"></span>' : '' ?></div>	
							<h4><a href="<?php echo get_permalink($post->ID) ?>" title="<?php echo esc_attr( $post->post_title ) ?>"><?php echo esc_html( $post->post_title ) ?></a></h4>
							<div class="item-price"><p><?php echo $product->get_price_html() ?></p></div>
						</div>
					</div>
				</div>
			<?php if( ( $i+1 ) % ($item_slide) == 0 || ( $i+1 ) == $numb_post ){ ?>
			</div>
		<?php }
			$i++;endwhile;
			wp_reset_postdata();
		?>
		</div>
	</div>
<?php
		}
	}
	$content = ob_get_clean();
	return $content;
 }
 add_shortcode('Latest','flytheme_latest_products_shortcode');

/*
** Featured Shortcodes
*/
 function sw_featured_shortcode($atts){
	 extract(shortcode_atts(array(
		'number' => 5,
		'title'=>'',
		'title_length'  => 0,
		'el_class'=>'',
		'images' => '',
		'template'=>'',
		'category' => '',
		'item_slide'=>'',
		'post_type' 	 => 'product',
	 ),$atts));
	 ob_start();
	 global $woocommerce;
	 $i='';
	 $pf_id = 'bestsale-'.rand().time();
	 $query_args = array( 'posts_per_page'=> $number,'post_type' => 'product', 'orderby' => 'name', 'order' => 'ASC',);
	if( $category != '' ){
			$term = get_term_by( 'slug', $category, 'product_cat' );	
			$term_name = $term->name;
			$query_args['tax_query'] = array(
				array(
					'taxonomy'  => 'product_cat',
					'field'     => 'slug',
					'terms'     => $category )
			);	
		}	 
	if( sw_woocommerce_version_check( '3.0' ) ){	
		$query_args['tax_query'][] = array(						
			'taxonomy' => 'product_visibility',
			'field'    => 'name',
			'terms'    => 'featured',
			'operator' => 'IN',	
		);
	}else{
		$query_args['meta_query'] = array(
			array(
				'key' 		=> '_featured',
				'value' 	=> 'yes'
			)					
		);				
	}
    
		$r = new WP_Query($query_args);
		$numb_post = count( $r -> posts );
		if ( $r->have_posts() ) {
if($template== 'default'){
?>
	<div id="<?php echo $pf_id ?>" class="sw-featured-product vc_element">
		<?php 
			if( $title != '' ){ 
				$titles = strpos($title, ' ');
		?>
			<div class="box-title"><h3><span><?php echo substr( $title, 0, $titles ) ?></span> <?php echo substr( $title, $titles + 1 ) ?></h3></div>
	<?php } ?>
	<div class="wrap-content">
	<?php
		while ( $r -> have_posts() ) : $r -> the_post();
		global $product, $post;
	?>
	
		<div class="item">
			<div class="item-inner">
				<div class="item-img">
					<a href="<?php echo get_permalink($post->ID) ?>" title="<?php echo esc_attr( $post->post_title ) ?>">
					<?php if( has_post_thumbnail() ){  ?>
					<?php echo ( get_the_post_thumbnail( $r->post->ID, 'shop_thumbnail' ) ) ? get_the_post_thumbnail( $r->post->ID, 'shop_thumbnail' ):'<img src="'.get_template_directory_uri().'/assets/img/placeholder/shop_thumbnail.png" alt="No thumb"/>';
						}else{ ?>
						<img src="'.get_template_directory_uri().'/assets/img/placeholder/shop_thumbnail.png" alt="No thumb"/>
					<?php	} ?>
					</a>
				</div>
				<div class="item-content">
					<h4><a href="<?php echo get_permalink($post->ID) ?>" title="<?php echo esc_attr( $post->post_title ) ?>"><?php  echo esc_html( $post->post_title ); ?></a></h4>
					<div class="item-price"><?php echo $product->get_price_html() ?></div>			 
				</div>
			</div>
		</div>
	<?php 
		endwhile;
		wp_reset_postdata();
	?>
	</div>
	</div>
<?php
	}elseif($template == 'slide'){ ?>
	<div id="<?php echo $pf_id ?>" class="sw-featured-product-slider vc_element carousel carousel-fade slide <?php echo $el_class ?>" data-interval="0">
	<?php 
		if( $title != '' ){
			$titles = strpos($title, ' ');
	?>
			<div class="box-slider-title"><h2><span><?php echo substr( $title, 0, $titles ) ?></span><?php echo substr( $title, $titles + 1 ) ?></h2></div>
	<?php } ?>
		<div class="customNavigation nav-left-product">
			<a title="<?php echo esc_attr__( 'Previous', 'sw_woocommerce' ) ?>" class="btn-bs prev-bs fa fa-angle-left"  href="#<?php echo $pf_id ?>" role="button" data-slide="prev"></a>
			<a title="<?php echo esc_attr__( 'Next', 'sw_woocommerce' ) ?>" class="btn-bs next-bs fa fa-angle-right" href="#<?php echo $pf_id ?>" role="button" data-slide="next"></a>
		</div>
    <div class="carousel-inner">
		<?php 
			$i = 0;
			while ( $r -> have_posts() ) : $r -> the_post();
			global $product, $post;
			if( ( $i % $item_slide ) == 0 && ( $item_slide != 0 ) ){
				$active = ( $i == 0 ) ? 'active' : '';
		?>
			<div class="item <?php echo $active ?>" >
	<?php } ?>
				<div class="item-wrap">
					<div class="item-inner">
						<div class="item-img">
							<a href="<?php echo get_permalink($post->ID) ?>" title="<?php echo esc_attr( $post->post_title ) ?>">
							<?php 
								if( has_post_thumbnail() ){  
									echo ( get_the_post_thumbnail( $r->post->ID, 'paradise-product-thumb' ) ) ? get_the_post_thumbnail( $r->post->ID, 'paradise-product-thumb' ):'<img src="'.get_template_directory_uri().'/assets/img/placeholder/shop_thumbnail.png" alt="No thumb"/>' ;
								}else{ 
									echo '<img src="'.get_template_directory_uri().'/assets/img/placeholder/shop_thumbnail.png" alt="No thumb"/>';
								}
							?>
							</a>
						</div>
						<div class="item-content">
							<?php
								$rating_count = $product->get_rating_count();
								$review_count = $product->get_review_count();
								$average      = $product->get_average_rating();
							?>
							<div class="star"><?php echo ( $average > 0 ) ?'<span style="width:'. ( $average*14 ).'px"></span>' : '' ?></div>	
							<h4><a href="<?php echo get_permalink($post->ID) ?>" title="<?php echo esc_attr( $post->post_title ) ?>"><?php echo esc_html( $post->post_title ); ?></a></h4>
							<div class="item-price"><p><?php echo $product->get_price_html() ?></p></div>
						</div>
					</div>
				</div>
			<?php if( ( $i+1 ) % ($item_slide) == 0 || ( $i+1 ) == $numb_post ){ ?>
			</div>
			<?php }
				$i++;endwhile;
				wp_reset_postdata();
			?>
		</div>
	</div>
		<?php 
		}
	}
	$content = ob_get_clean();
	return $content;
 }
 add_shortcode('Featured','sw_featured_shortcode');

