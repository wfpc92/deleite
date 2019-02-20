<?php get_header(); ?>

<!-- Breadcrumb title -->
<?php sw_paradise_breadcrumb_title() ?>

<div class="container">
	<?php
	$sw_paradise_post_type = isset( $_GET['search_posttype'] ) ? $_GET['search_posttype'] : '';
	if( ( $sw_paradise_post_type != '' ) &&  locate_template( 'templates/search-' . $sw_paradise_post_type . '.php' ) ){
		get_template_part( 'templates/search', $sw_paradise_post_type );
	}else{ 
		if( have_posts() ){
			?>
			<div class="blog-content content-search">
				<?php 
				while (have_posts()) : the_post(); 
				global $post;
				$post_format = get_post_format();
				?>
				<div id="post-<?php the_ID();?>" <?php post_class( 'theme-clearfix' ); ?>>
					<div class="entry clearfix">
						<?php if (get_the_post_thumbnail()){?>
						<div class="entry-thumb pull-left">
							<a class="entry-hover" href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">			
								<?php the_post_thumbnail("thumbnail")?>
							</a>
						</div>
						<?php }?>
						<div class="entry-content">
							<div class="title-blog">
								<h3>
									<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?> </a>
								</h3>
							</div>
							<div class="entry-meta">
								<span class="entry-author"><i class="fa fa-user"></i><?php the_author_posts_link(); ?></span>
								<span class="entry-date">
									<i class="fa fa-clock-o"></i><?php echo ( get_the_title() ) ? date( 'F j, Y',strtotime($post->post_date)) : '<a href="'.get_the_permalink().'">'.date( 'F j, Y',strtotime($post->post_date)).'</a>'; ?>
								</span>
							</div>
							<div class="entry-description">
								<?php 

								if ( preg_match('/<!--more(.*?)?-->/', $post->post_content, $matches) ) {
									$content = explode($matches[0], $post->post_content, 2);
									$content = $content[0];
									$content = wp_trim_words($post->post_content, 30, '...');
									echo $content;	
								} else {
									$content = wp_trim_words($post->post_content, 25, '...');
									echo $content;	
								}		
								?>
							</div>
							<div class="bl_read_more"><a href="<?php the_permalink(); ?>"><?php esc_html_e('Read more','sw-paradise')?><i class="fa fa-angle-double-right"></i></a></div>
							<?php wp_link_pages( array( 'before' => '<div class="page-links"><span class="page-links-title">' . esc_html__( 'Pages:', 'sw-paradise' ).'</span>', 'after' => '</div>' , 'link_before' => '<span>', 'link_after'  => '</span>' ) ); ?>
						</div>
					</div>
				</div>			
			<?php endwhile; ?>
			<?php get_template_part('templates/pagination'); ?>
		</div>
		<?php
	}else{
		get_template_part('templates/no-results');
	}
}
?>
</div>
<?php get_footer(); ?>