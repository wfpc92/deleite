<?php
/**
 * Single Product Price, including microdata for SEO
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/price.php.
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
 * @version 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product;

$wc_price = ( function_exists( 'wc_get_price_to_display' ) ) ? wc_get_price_to_display( $product ) : $product->get_display_price();
?>
<div class="price-content" itemprop="offers" itemscope itemtype="http://schema.org/Offer">

	<p class="price"><?php echo $product->get_price_html(); ?></p>

	<meta itemprop="price" content="<?php echo esc_attr( $wc_price ); ?>" />
	<meta itemprop="priceCurrency" content="<?php echo get_woocommerce_currency(); ?>" />
	<link itemprop="availability" href="http://schema.org/<?php echo $product->is_in_stock() ? 'InStock' : 'OutOfStock'; ?>" />

</div>
<?php $stock = ( $product->is_in_stock() )? 'in-stock' : 'out-stock' ; ?>
<div class="product_meta">
	<div class="product-stock <?php echo esc_attr( $stock ); ?>">
		<span><?php echo ( $product->is_in_stock() )? esc_html__( 'Available in stock', 'sw-paradise' ) : esc_html__( 'Out stock', 'sw-paradise' ); ?></span>
	</div>
	<?php if ( wc_product_sku_enabled() && ( $product->get_sku() || $product->is_type( 'variable' ) ) ) : ?>
		<span class="sku_wrapper custom-font"><?php esc_html_e( 'SKU:', 'sw-paradise' ); ?> <span class="sku" itemprop="sku"><?php echo ( $sku = $product->get_sku() ) ? $sku : esc_html__( 'N/A', 'sw-paradise' ); ?></span></span>
<?php endif; ?>
</div>