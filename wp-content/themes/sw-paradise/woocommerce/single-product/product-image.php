<?php
/**
 * Single Product Image
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/product-image.php.
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
 * @version 3.3.3
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
global $post, $woocommerce, $product;
$sw_paradise_direction 		= sw_paradise_options()->getCpanelValue( 'direction' );
$sidebar_product 	= sw_paradise_options()->getCpanelValue( 'sidebar_product' );
$attachments 		= array();
$post_thumbnail_id	 = get_post_thumbnail_id( $post->ID );

?>
<div id="product_img_<?php echo esc_attr( $post->ID ); ?>" class="woocommerce-product-gallery woocommerce-product-gallery--with-images images product-images loading" data-rtl="<?php echo ( is_rtl() || $sw_paradise_direction == 'rtl' )? 'true' : 'false';?>" data-vertical="<?php echo ( $sidebar_product == 'full' ) ? 'true' : 'false'; ?>">
	<figure class="woocommerce-product-gallery__wrapper">
	<div class="product-images-container clearfix <?php echo ( $sidebar_product == 'full' ) ? 'thumbnail-left' : 'thumbnail-bottom'; ?>">
		<?php 
		if( has_post_thumbnail() ){ 
			$attachments = ( sw_woocommerce_version_check( '3.0' ) ) ? $product->get_gallery_image_ids() : $product->get_gallery_attachment_ids();
				$image_id 	 = get_post_thumbnail_id();
				array_unshift( $attachments, $image_id );
				$atttachments = array_unique( $attachments );
		 ?>
		<?php 
			if( $sidebar_product == 'full' ){
				do_action('woocommerce_product_thumbnails');
			}
		?>
		<!-- Image Slider -->
		<div class="slider product-responsive">
			<?php foreach ( $attachments as $key => $attachment ) { 
				$full_size_image  = wp_get_attachment_image_src( $attachment, 'full' );
				$thumbnail_post   = get_post( $attachment );
				$image_title      = $thumbnail_post->post_content;

				$attributes = array(
					'class' => 'wp-post-image',
					'title'                   => get_post_field( 'post_title', $post_thumbnail_id ),
					'data-caption'            => get_post_field( 'post_excerpt', $post_thumbnail_id ),
					'data-src'                => $full_size_image[0],
					'data-large_image'        => $full_size_image[0],
					'data-large_image_width'  => $full_size_image[1],
					'data-large_image_height' => $full_size_image[2],
				);
			?>
			<div data-thumb="<?php echo wp_get_attachment_image_url( $attachment, 'shop_thumbnail' ) ?>" class="woocommerce-product-gallery__image">	
				<a href="<?php echo wp_get_attachment_url( $attachment ) ?>"><?php echo wp_get_attachment_image( $attachment, 'shop_single', false, $attributes ); ?></a>
			</div>
			<?php } ?>
		</div>
		<!-- Thumbnail Slider -->
		<?php 
			if( $sidebar_product != 'full' ){
				do_action('woocommerce_product_thumbnails'); 
			}
		?>
		<?php }else{ ?>
			<div class="single-img-product">
					<?php echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<img src="%s" alt="%s" />', wc_placeholder_img_src(), esc_html__( 'Placeholder', 'sw-paradise' ) ), $post->ID ); ?>
			</div>
		<?php } ?>
	</div>	
	</figure>
</div>