<?php get_template_part('header'); ?>
<?php $sidebar_template = sw_paradise_options()->getCpanelValue('sidebar_blog') ;?>

<!-- Breadcrumb title -->
<?php sw_paradise_breadcrumb_title() ?>

<div class="container">
	<div class="row">
		<?php if ( is_active_sidebar_SW_PARADISE('left-blog') && $sidebar_template != 'right_sidebar' && $sidebar_template !='full' ):
			$left_span_class = 'col-lg-'.sw_paradise_options()->getCpanelValue('sidebar_left_expand');
			$left_span_class .= ' col-md-'.sw_paradise_options()->getCpanelValue('sidebar_left_expand_md');
			$left_span_class .= ' col-sm-'.sw_paradise_options()->getCpanelValue('sidebar_left_expand_sm');
		?>
		<aside id="left" class="sidebar <?php echo esc_attr($left_span_class); ?>">
			<?php dynamic_sidebar('left-blog'); ?>
		</aside>

		<?php endif; ?>

		<div class="category-contents <?php sw_paradise_content_blog(); ?>">
			<?php 
				$blog_styles = sw_paradise_options()->getCpanelValue('blog_layout');	
					get_template_part('templates/content', $blog_styles);
			?>
			<?php get_template_part('templates/pagination'); ?>
		</div>
		<?php if ( is_active_sidebar_SW_PARADISE('right-blog') && $sidebar_template !='left_sidebar' && $sidebar_template !='full' ):
			$right_span_class = 'col-lg-'.sw_paradise_options()->getCpanelValue('sidebar_right_expand');
			$right_span_class .= ' col-md-'.sw_paradise_options()->getCpanelValue('sidebar_right_expand_md');
			$right_span_class .= ' col-sm-'.sw_paradise_options()->getCpanelValue('sidebar_right_expand_sm');
		?>
		<aside id="right" class="sidebar <?php echo esc_attr($right_span_class); ?>">
			<?php dynamic_sidebar('right-blog'); ?>
		</aside>
		<?php endif; ?>
	</div>
</div>
<?php get_template_part('footer'); ?>
