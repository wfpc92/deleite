<?php 

/**
	* Layout Theme Default
	* @version     1.0.0
**/
global $product, $post;
	$gallery = get_post_meta($post->ID, '_product_image_gallery', true);
	$attachment_image = '';
	if(!empty($gallery)) {
		$gallery = explode(',', $gallery);
		$first_image_id = $gallery[0];
		$attachment_image = wp_get_attachment_image($first_image_id ,'paradise-product-thumb', false, array('class' => 'hover-image back'));
	}
	$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), '' );
?>
<div class="item-wrap2">
	<div class="item-detail">										
		<div class="item-img products-thumb">
			<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
			<?php
					$attachments = array();
					$attachments = ( sw_woocommerce_version_check( '3.0' ) ) ? $product->get_gallery_image_ids() : $product->get_gallery_attachment_ids();
					$first_image_id = $attachments[0];
					$attachment_image = wp_get_attachment_image($first_image_id ,'paradise-product-thumb', false, array('class' => 'hover-image back'));
				?>
			<?php	if ( has_post_thumbnail( ) ){
				if( $attachment_image ){ ?>
					<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
					<div class="product-thumb-hover">
						<?php the_post_thumbnail( 'paradise-product-thumb'); ?> <?php  echo $attachment_image; ?>
					</div>
					</a>	
				<?php	}else{ ?>
						<?php the_post_thumbnail( 'paradise-product-thumb' ); ?>
				<?php	} }else{ ?>
					<img src="'<?php echo get_template_directory_uri().'/assets/img/placeholder/shop_single.png' ?>" alt="No thumb">';
				<?php } ?>
			</a>
		</div>										
		<div class="item-content">
			<h4><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute();?>"><?php the_title(); ?></a></h4>				
			<?php 
				$rating_count = $product->get_rating_count();
				$review_count = $product->get_review_count();
				$average      = $product->get_average_rating();
			?>
			<div class="reviews-content">
				<div class="star"><?php echo ( $average > 0 ) ?'<span style="width:'. ( $average*13 ).'px"></span>' : ''; ?></div>
			</div>
			<?php if ( $price_html = $product->get_price_html() ){?>
				<div class="item-price">
					<span>
						<?php echo $price_html; ?>
					</span>
				</div>
			<?php } ?>	
			
			<!-- add to cart, wishlist, compare -->
			<?php do_action( 'woocommerce_after_shop_loop_item' ); ?>
		</div>								
	</div>
</div>