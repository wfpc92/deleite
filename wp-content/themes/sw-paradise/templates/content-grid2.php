<?php if (!have_posts()) : ?>
<?php get_template_part('templates/no-results'); ?>
<?php endif; ?>
<?php
	$blog_columns = sw_paradise_options()->getCpanelValue('blog_column');	
	$col = 'col-md-'.(12/$blog_columns).' col-sm-6 col-xs-12 theme-clearfix';
	global $instance;
?>
<div class="row blog-content blog-content-grid2">
<?php 
	while (have_posts()) : the_post(); 
	$format = get_post_format();
	global $post;
?>
	<div id="post-<?php the_ID();?>" <?php post_class($col); ?>>
		<div class="entry clearfix">
			<?php if( $format == '' || $format == 'image' ){ ?>
				<?php if ( get_the_post_thumbnail() ){ ?>
					<div class="entry-thumb">	
						<a class="entry-hover" href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
							<?php the_post_thumbnail('large');?>				
						</a>
						<div class="entry-meta">
							<span class="latest_post_date">
								<span class="post_day"><?php the_time('d'); ?></span>
								<span class="post_my"><?php the_time('M'); ?></span>
							</span>
						</div>
					</div>			
				<?php } ?>
				<div class="entry-content">				
					<div class="content-top">
						<div class="entry-title">
							<h4><a href="<?php echo get_permalink($post->ID)?>"><?php echo $post->post_title;?></a></h4>
						</div>
						<div class="entry-summary">
							<?php 												
								if ( preg_match('/<!--more(.*?)?-->/', $post->post_content, $matches) ) {
									$content = explode($matches[0], $post->post_content, 2);
									$content = $content[0];
									$content = wp_trim_words($post->post_content, 22, '...');
									echo $content;	
								} else {
									the_content('...');
								}		
							?>	
						</div>
						<a class="readmore" href="<?php echo get_permalink($post->ID)?>"><?php esc_html_e('Read More', 'sw-paradise'); ?> <i class="fa fa-angle-right"></i></a>
					</div>
				</div>
			<?php } elseif( !$format == ''){?>
			<div class="wp-entry-thumb">	
				<?php if( $format == 'video' || $format == 'audio' ){ ?>	
					<?php echo ( $format == 'video' ) ? '<div class="video-wrapper">'. get_entry_content_asset($post->ID) . '</div>' : get_entry_content_asset($post->ID); ?>										
				<?php } ?>
				
				<?php if( $format == 'gallery' ) { 
					if(preg_match_all('/\[gallery(.*?)?\]/', get_post($instance['post_id'])->post_content, $matches)){
						$attrs = array();
						if (count($matches[1])>0){
							foreach ($matches[1] as $m){
								$attrs[] = shortcode_parse_atts($m);
							}
						}
						if (count($attrs)> 0){
							foreach ($attrs as $attr){
								if (is_array($attr) && array_key_exists('ids', $attr)){
									$ids = $attr['ids'];
									break;
								}
							}
						}
					?>
						<div id="gallery_slider_<?php echo $post->ID; ?>" class="carousel slide gallery-slider" data-interval="0">	
							<div class="carousel-inner">
								<?php
									$ids = explode(',', $ids);						
									foreach ( $ids as $i => $id ){ ?>
										<div class="item<?php echo ( $i== 0 ) ? ' active' : '';  ?>">			
												<?php echo wp_get_attachment_image($id, 'full'); ?>
										</div>
									<?php }	?>
							</div>
							<a href="#gallery_slider_<?php echo $post->ID; ?>" class="left carousel-control" data-slide="prev"><?php esc_html_e( 'Prev', 'sw-paradise' ) ?></a>
							<a href="#gallery_slider_<?php echo $post->ID; ?>" class="right carousel-control" data-slide="next"><?php esc_html_e( 'Next', 'sw-paradise' ) ?></a>
						</div>
					<?php }	?>							
				<?php } ?>

				<?php if( $format == 'quote' ) { ?>
				
				<?php } ?>
			</div>
			<div class="entry-content">				
				<div class="content-top">
					<div class="entry-title">
						<h4><a href="<?php echo get_permalink($post->ID)?>"><?php echo $post->post_title;?></a></h4>
					</div>
					<div class="entry-summary">
						<?php 												
							if ( preg_match('/<!--more(.*?)?-->/', $post->post_content, $matches) ) {
								$content = explode($matches[0], $post->post_content, 2);
								$content = $content[0];
								$content = wp_trim_words($post->post_content, 22, '...');
								echo $content;	
							} else {
								the_content('...');
							}		
						?>	
					</div>
					<a class="readmore" href="<?php echo get_permalink($post->ID)?>"><?php esc_html_e('Read More', 'sw-paradise'); ?> <i class="fa fa-angle-right"></i></a>
				</div>
			</div>
			<?php } ?>
		</div>
	</div>
<?php endwhile; ?>
</div>
<div class="clearfix"></div>


