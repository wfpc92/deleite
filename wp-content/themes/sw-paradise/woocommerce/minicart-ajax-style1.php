<?php 
if ( !in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) { 
	return false;
}
global $woocommerce; ?>
<div class="top-form top-form-minicart sw-paradise-minicart-style1 pull-right">
	<div class="top-minicart-icon pull-right">
		<span class="shopping-text"><?php esc_html_e('Shopping Cart','sw-paradise');?></span>
		<?php echo '<span class="minicart-number">'.$woocommerce->cart->cart_contents_count.'</span>';  esc_html_e('item(s)', 'sw-paradise');?> :  <?php echo $woocommerce->cart->get_cart_total(); ?>
	</div>
	<div class="wrapp-minicart">
		<div class="minicart-padding">
			<h2><?php echo esc_html_e('Recent Add Item(s)', 'sw-paradise');?></h2>
			<ul class="minicart-content">
			<?php 
					foreach($woocommerce->cart->cart_contents as $cart_item_key => $cart_item): 
					$_product  = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
					$product_name = ( sw_woocommerce_version_check( '3.0' ) ) ? $_product->get_name() : $_product->get_title();
				?>
				<li>
					<a href="<?php echo get_permalink($cart_item['product_id']); ?>" class="product-image">
						<?php echo $_product->get_image( 'thumbnail' ); ?>
					</a>
					<?php 	global $product, $post, $wpdb, $average;
			$count = $wpdb->get_var($wpdb->prepare("
				SELECT COUNT(meta_value) FROM $wpdb->commentmeta
				LEFT JOIN $wpdb->comments ON $wpdb->commentmeta.comment_id = $wpdb->comments.comment_ID
				WHERE meta_key = 'rating'
				AND comment_post_ID = %d
				AND comment_approved = '1'
				AND meta_value > 0
			",$cart_item['product_id']));

			$rating = $wpdb->get_var($wpdb->prepare("
				SELECT SUM(meta_value) FROM $wpdb->commentmeta
				LEFT JOIN $wpdb->comments ON $wpdb->commentmeta.comment_id = $wpdb->comments.comment_ID
				WHERE meta_key = 'rating'
				AND comment_post_ID = %d
				AND comment_approved = '1'
			",$cart_item['product_id']));?>		
						 
	<div class="detail-item">
		<div class="product-details"> 
				<a href="<?php echo get_permalink($cart_item['product_id']); ?>"><?php echo esc_html( $product_name ); ?></a>
			<div class="rating-container">
					<div class="ratings">
						 <?php
							if( $count > 0 ){
								$average = number_format($rating / $count, 1);
						?>
							<div class="star"><span style="width: <?php echo ($average*13).'px'; ?>"></span></div>
							
						<?php } else { ?>
						
							<div class="star"></div>
							
						<?php } ?>			      
					
					</div>
			</div>	  		
			<div class="product-price">
				 <span class="price"><?php echo $woocommerce->cart->get_product_subtotal($cart_item['data'], 1); ?></span>		        		        		    		
			</div>
			<div class="qty">
				<span class="qty-label"><?php echo esc_html_e('Qty:', 'sw-paradise');?></span>
				<?php echo '<span class="qty-number">'.esc_html( $cart_item['quantity'] ).'</span>'; ?>
			</div>
			<div class="product-action">
				<?php echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf( '<a href="%s" class="btn-remove" title="%s"><span></span></a>', esc_url( $woocommerce->cart->get_remove_url( $cart_item_key ) ), __( 'Remove this item', 'sw-paradise' ) ), $cart_item_key ); ?>           
				<a class="btn-edit" href="<?php echo esc_url( wc_get_cart_url() ); ?>" title="<?php esc_attr_e('View your shopping cart', 'sw-paradise'); ?>"><span></span></a>    
			</div>
		</div>	
	</div>
							
	</li>
<?php
endforeach;
?>
</ul>
			<div class="cart-checkout">
			    <div class="price-total">
				   <span class="label-price-total"><?php esc_html_e('Total:', 'sw-paradise'); ?></span>
				   <span class="price-total-w"><span class="price"><?php echo $woocommerce->cart->get_cart_total(); ?></span></span>
				   
				</div>
				<div class="cart-links">
					<div class="cart-link"><a href="<?php echo get_permalink(get_option('woocommerce_cart_page_id')); ?>" title="<?php esc_attr_e( 'Cart', 'sw-paradise' ) ?>"><?php esc_html_e('View Cart', 'sw-paradise'); ?></a></div>
					<div class="checkout-link"><a href="<?php echo get_permalink(get_option('woocommerce_checkout_page_id')); ?>" title="<?php esc_attr_e( 'Check Out', 'sw-paradise' ) ?>"><?php esc_html_e('Check Out', 'sw-paradise'); ?></a></div>
				</div>
			</div>
		</div>
	</div>
</div>