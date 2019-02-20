<?php if (!have_posts()) : ?>
	<?php get_template_part('templates/no-results'); ?>
<?php endif; ?>
<div class="blog-content blog-content-list">
	<?php 
	while (have_posts()) : the_post(); 
	global $post;
	$post_format = get_post_format();
	?>
	<div id="post-<?php the_ID();?>" <?php post_class( 'theme-clearfix' ); ?>>
		<div class="entry clearfix">
			<?php if ( get_the_post_thumbnail() ){ ?>
			<div class="entry-thumb">	
				<a class="entry-hover" href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
					<?php the_post_thumbnail('paradise-blogpost-thumb'); ?>			
				
					<div class="entry-meta">
						<span class="latest_post_date">
							<span class="post_day"><?php the_time('d'); ?></span>
							<span class="post_my"><?php the_time('M'); ?></span>
						</span>
					</div>
				</a>
			</div>			
			<?php } ?>			
			<div class="entry-content">				
				<div class="content-top">
					<div class="entry-title">
						<h4><a href="<?php echo get_permalink($post->ID)?>"><?php echo $post->post_title;?></a></h4>
					</div>
					<div class="entry-meta-content">
						<span class="entry-meta-link"><?php esc_html_e( "Post by ", 'sw-paradise' )?><?php the_author_posts_link(); ?></span>
						<span class="entry-meta-link category-blog"><?php esc_html_e( "In ", 'sw-paradise' )?><?php the_category(', '); ?></span>
						<a href="<?php echo esc_attr('#respond'); ?>">
							<span class="entry-commentt">
								<?php 
									$qty_comment = $post->comment_count;
									echo $qty_comment . ' ' .( ($qty_comment > 1 ) ? esc_html__(' Comments', 'sw-paradise') : esc_html__(' Comment', 'sw-paradise')  );							
								?>
							</span>
						</a>
					</div>						
					<div class="entry-summary">
						<?php 												
						if ( preg_match('/<!--more(.*?)?-->/', $post->post_content, $matches) ) {
							$content = explode($matches[0], $post->post_content, 2);
							$content = $content[0];
							$content = wp_trim_words($post->post_content, 60, '...');
							echo $content;	
						} else {
							the_content('...');
						}		
						?>	
					</div>
					<a class="readmore" href="<?php echo get_permalink($post->ID)?>"><?php esc_html_e('Read More', 'sw-paradise'); ?><i class="fa fa-angle-right"></i></a>
				</div>
			</div>
		</div>
	</div>
<?php endwhile; ?>
</div>
<div class="clearfix"></div>