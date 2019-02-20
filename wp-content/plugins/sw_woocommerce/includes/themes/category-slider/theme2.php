<?php 

$widget_id = isset( $widget_id ) ? $widget_id : 'category_slide_'.rand().time();
if( $category == '' ){
	return '<div class="alert alert-warning alert-dismissible" role="alert">
	<a class="close" data-dismiss="alert">&times;</a>
	<p>'. esc_html__( 'Please select a category for SW Woocommerce Category Slider. Layout ', 'sw_woocommerce' ) . $layout .'</p>
</div>';
}
?>
<div id="<?php echo 'slider_' . $widget_id; ?>" class="responsive-slider sw-category-slider-theme2 loading"  data-append=".resp-slider-container" data-lg="<?php echo esc_attr( $columns ); ?>" data-md="<?php echo esc_attr( $columns1 ); ?>" data-sm="<?php echo esc_attr( $columns2 ); ?>" data-xs="<?php echo esc_attr( $columns3 ); ?>" data-mobile="<?php echo esc_attr( $columns4 ); ?>" data-speed="<?php echo esc_attr( $speed ); ?>" data-scroll="<?php echo esc_attr( $scroll ); ?>" data-interval="<?php echo esc_attr( $interval ); ?>"  data-autoplay="<?php echo esc_attr( $autoplay ); ?>">
	<?php	if( $title1 != '' ){ ?>
	<div class="block-title">
		<h2><span><?php echo $title1; ?></span></h2>
		<?php echo ( $description != '' ) ? '<div class="slider-description">'. $description .'</div>' : ''; ?>
	</div>
	<?php } ?>
	<div class="resp-slider-container">
		<div class="slider responsive">
			<?php
			if( !is_array( $category ) ){
				$category = explode( ',', $category );
			}
			foreach( $category as $j => $cat ){
				$term = get_term_by('slug', $cat, 'product_cat');							
				$thumbnail_id  = get_woocommerce_term_meta( $term->term_id, 'thumbnail_id', true );
				$thumb = wp_get_attachment_image( $thumbnail_id, 'full' );
				?>
				<?php if( $j % $item_row == 0 ){ ?>
				<div class="item item-product-cat">	
				<?php } ?>
				<div class="item-wrap">
						<div class="item-image list-image-static">
							<a href="<?php echo get_term_link( $term->term_id, 'product_cat' ); ?>">
								<?php echo $thumb; ?>
							</a>
						</div>
						<div class="item-content">
							<h3>
								<?php 
								$variable= esc_html( $term->name );
								?>
								<a href="<?php echo get_term_link( $term->term_id, 'product_cat' ); ?>">
									<span><?php	echo $variable; ?></span>
								</a>
							</h3>
							<div class="product-count"><?php echo $term->count; ?><?php echo esc_html__(' Products ','sw-woocomerce'); ?></div>
						</div>
					</div>
					<?php if( ( $j+1 ) % $item_row == 0 ){?> </div><?php } ?>
				<?php } ?>
			</div>
		</div>
	</div>		