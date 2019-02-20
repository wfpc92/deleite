<?php if (!have_posts()) : ?>
	<?php get_template_part('templates/no-results'); ?>
<?php endif; ?>
<div class="blog-content blog-full-list">
	<?php 
	while (have_posts()) : the_post(); 
	$post_format = get_post_format();
	?>
	<div id="post-<?php the_ID();?>" <?php post_class( 'theme-clearfix' ); ?>>
		<div class="entry">
			<?php if (get_the_post_thumbnail()){?>
			<div class="entry-thumb">
				<a class="entry-hover" href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">			
					<?php the_post_thumbnail('sw_paradise_detail_thumb')?>
				</a>
			</div>
			<?php }?>
			<div class="entry-content">
				<div class="title-blog">
					<h3>
						<a href="<?php the_permalink(); ?>"><?php the_title(); ?> </a>
					</h3>
				</div>
				<div class="entry-meta">
					<span class="entry-date">
						<i class="fa fa-clock-o"></i><?php echo ( get_the_title() ) ? date( 'l, F j, Y',strtotime($post->post_date)) : '<a href="'.get_the_permalink().'">'.date( 'l, F j, Y',strtotime($post->post_date)).'</a>'; ?>
					</span>
					<span class="category-blog"><i class="fa fa-folder-open"></i><?php the_category(', '); ?></span>
				</div>
				<div class="entry-comment">
					<span class="comment"><?php echo esc_html( $post->comment_count ) .'<span>'. esc_html__(' comment(s)', 'sw-paradise').'</span>'; ?></span>		
					<span class="author"><?php esc_html_e('By', 'sw-paradise'); ?> <?php the_author_posts_link(); ?></span>	
				</div>
				<div class="entry-description">
					<?php 
					the_content('...');						
					?>
					<?php the_tags( '<div class="entry-meta-tag"><span class="fa fa-tag"></span>', ', ', '</div>' ); ?>
					<?php wp_link_pages( array( 'before' => '<div class="page-links"><span class="page-links-title">' . esc_html__( 'Pages:', 'sw-paradise' ).'</span>', 'after' => '</div>' , 'link_before' => '<span>', 'link_after'  => '</span>' ) ); ?>
					<div class="bl_read_more"><a href="<?php the_permalink(); ?>"><?php esc_html_e('Read more','sw-paradise')?></a></div>
				</div>					
			</div>
		</div>
	</div>
<?php endwhile; ?>
</div>
<div class="clearfix"></div>
