<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.4.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>

<?php
	/**
	 * woocommerce_before_single_product hook
	 *
	 * @hooked woocommerce_show_messages - 10
	 */
	 do_action( 'woocommerce_before_single_product' );
	global $product;
?>
<div itemscope itemtype="http://schema.org/Product" id="product-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="product_detail row">
		<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 clear_xs">							
			<div class="slider_img_productd">
				<!-- woocommerce_show_product_images -->
				<?php
					/**
					 * woocommerce_show_product_images hook
					 *
					 * @hooked woocommerce_show_product_sale_flash - 10
					 * @hooked woocommerce_show_product_images - 20
					 */
					do_action( 'woocommerce_before_single_product_summary' );
				?>
			</div>							
		</div>
		<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 clear_xs">
			<div class="content_product_detail">
				<!-- woocommerce_template_single_title - 5 -->
				<!-- woocommerce_template_single_rating - 10 -->
				<!-- woocommerce_template_single_price - 20 -->
				<!-- woocommerce_template_single_excerpt - 30 -->
				<!-- woocommerce_template_single_add_to_cart 40 -->
				<?php
					/**
					 * woocommerce_single_product_summary hook
					 *
					 * @hooked woocommerce_template_single_title - 5
					 * @hooked woocommerce_template_single_price - 10
					 * @hooked woocommerce_template_single_excerpt - 20
					 * @hooked woocommerce_template_single_add_to_cart - 30
					 * @hooked woocommerce_template_single_meta - 40
					 * @hooked woocommerce_template_single_sharing - 50
					 */
					do_action( 'woocommerce_single_product_summary' );
				?>				
			</div>
		</div>
	</div>
</div>		
<div class="tabs clearfix">
	<?php
		/**
		 * woocommerce_after_single_product_summary hook
		 *
		 * @hooked woocommerce_output_product_data_tabs - 10
		 * @hooked woocommerce_output_related_products - 20
		 */
		do_action( 'woocommerce_after_single_product_summary' );
	?>
</div>

<?php if (is_active_sidebar_SW_PARADISE('bottom-detail-product')) { ?>
	<div class="bottom-single-product theme-clearfix">
		<?php dynamic_sidebar('bottom-detail-product'); ?>
	</div>
<?php } ?>
	
<?php do_action( 'woocommerce_after_single_product' ); ?>