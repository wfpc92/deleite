<?php if(have_posts()):
		while (have_posts()) : the_post(); ?>
			<div <?php post_class(); ?>>
			    <div class="entry-content">
						<div class="entry-summary">
							<?php the_content(); ?>
						</div>
						<div class="clearfix"></div>
			      <?php wp_link_pages( array( 'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'sw-paradise' ), 'after' => '</div>' ) ); ?>
			    </div>
					<div class="clearfix"></div>
			    <?php comments_template('/templates/comments.php'); ?>
			</div>
			<?php
		endwhile;
	else:
    	get_template_part('templates/no-results');
	endif;
?>