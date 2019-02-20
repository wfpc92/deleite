<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 3.4.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $product, $woocommerce_loop, $post;
$col_lg = sw_paradise_options()->getCpanelValue('product_col_large');
$col_md = sw_paradise_options()->getCpanelValue('product_col_medium');
$col_sm = sw_paradise_options()->getCpanelValue('product_col_sm');
$col_large = 12 / $col_lg;
$column1 = 12 / $col_md;
$column2 = 12 / $col_sm;
$class_col= "item";

// Ensure visibility
if ( ! $product || ! $product->is_visible() ) {
 return;
}

$class_col .= ' col-lg-'.$col_large.' col-md-'.$column1.' col-sm-'.$column2;

if ( 0 == $woocommerce_loop['loop'] % $col_lg || 1 == $col_lg ) {
 $class_col .= ' clear_lg';
}
if ( 0 == $woocommerce_loop['loop'] % $col_md || 1 == $col_md ) {
 $class_col .= ' clear_md';
}
if ( 0 == $woocommerce_loop['loop'] % $col_sm || 1 == $col_sm ) {
 $class_col .= ' clear_sm';
}

?>
<li <?php post_class($class_col); ?> >
	<div class="products-entry item-wrap clearfix">
		<div class="item-detail">
			<div class="item-img products-thumb">
				<?php
					/**
					 * woocommerce_before_shop_loop_item_title hook
					 *
					 * @hooked woocommerce_show_product_loop_sale_flash - 10
					 * @hooked woocommerce_template_loop_product_thumbnail - 10
					 */
					do_action( 'woocommerce_before_shop_loop_item_title' );
					
				?>
			</div>
			<div class="item-content products-content">
			<?php
				/**
				 * woocommerce_shop_loop_item_title hook
				 *
				 * @hooked woocommerce_template_loop_product_title - 10
				 */
				do_action( 'woocommerce_shop_loop_item_title' );

				/**
				 * woocommerce_after_shop_loop_item_title hook
				 *
				 * @hooked woocommerce_template_loop_rating - 5
				 * @hooked woocommerce_template_loop_price - 10
				 */
				do_action( 'woocommerce_after_shop_loop_item_title' );
			?>
			<?php
				/**
				 * woocommerce_after_shop_loop_item hook
				 *
				 * @hooked woocommerce_template_loop_add_to_cart - 10
				 */
				do_action( 'woocommerce_after_shop_loop_item' );
			?>
			</div>
		</div>
	</div>
</li>