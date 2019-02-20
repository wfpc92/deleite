<?php
/**
 * The Template for displaying all single products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product.php.
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
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
$sidebar = sw_paradise_options() -> getCpanelValue('sidebar_product');
?>
<?php get_template_part('header'); ?>

<?php if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>
	
<!-- Breadcrumb title -->
<?php sw_paradise_breadcrumb_title() ?>

<?php endif; ?>

<div class="container">
	<div class="row">

	<?php if ( is_active_sidebar_SW_PARADISE('left-product') && $sidebar == 'left' ):
		$left_span_class = 'col-lg-'.sw_paradise_options()->getCpanelValue('sidebar_left_expand');
		$left_span_class .= ' col-md-'.sw_paradise_options()->getCpanelValue('sidebar_left_expand_md');
		$left_span_class .= ' col-sm-'.sw_paradise_options()->getCpanelValue('sidebar_left_expand_sm');
	?>
		<aside id="left" class="sidebar <?php echo esc_attr($left_span_class); ?>">
			<?php dynamic_sidebar('left-product'); ?>
		</aside>
		<?php endif; ?>

		<div id="contents-detail" <?php sw_paradise_content_product(); ?> role="main">
			<?php
				/**
				 * woocommerce_before_main_content hook
				 *
				 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
				 * @hooked woocommerce_breadcrumb - 20
				 */
				do_action('woocommerce_before_main_content');
			?>
			<div class="single-product clearfix">
			
				<?php while ( have_posts() ) : the_post(); ?>

					<?php wc_get_template_part( 'content', 'single-product' ); ?>

				<?php endwhile; // end of the loop. ?>
			
			</div>
			
			<?php
				/**
				 * woocommerce_after_main_content hook
				 *
				 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
				 */
				do_action('woocommerce_after_main_content');
			?>
		</div>

		<?php if ( is_active_sidebar_SW_PARADISE('right-product') && $sidebar == 'right' ):
			$right_span_class = 'col-lg-'.sw_paradise_options()->getCpanelValue('sidebar_right_expand');
			$right_span_class .= ' col-md-'.sw_paradise_options()->getCpanelValue('sidebar_right_expand_md');
			$right_span_class .= ' col-sm-'.sw_paradise_options()->getCpanelValue('sidebar_right_expand_sm');
		?>
		<aside id="right" class="sidebar <?php echo esc_attr($right_span_class); ?>">
			<?php dynamic_sidebar('right-product'); ?>
		</aside>
		<?php endif; ?>
		
	</div>
</div>

<?php get_template_part('footer'); ?>
