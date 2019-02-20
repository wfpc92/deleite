<?php while (have_posts()) : the_post();
	global $post;
	?>
	<?php $related_post_column = sw_paradise_options()->getCpanelValue('sidebar_blog'); ?>
	<?php 
	setPostViews(get_the_ID()); 
	?>
	<div <?php post_class(); ?>>
		<?php $pfm = get_post_format();?>
		<div class="entry-wrap">
			<?php if( $pfm == '' || $pfm == 'image' ){?>
			<?php if( has_post_thumbnail() ){ ?>
			<div class="entry-thumb single-thumb">
				<?php the_post_thumbnail('paradise-blogpost-thumb'); ?>
				<div class="entry-meta">
					<span class="entry-date">
						<span class="post_day"><?php the_time('d'); ?></span>
						<span class="post_my"><?php the_time('M'); ?></span>
					</span>
				</div>
			</div>
			<?php } }?>
			<div class="entry-content clearfix">
				<h1 class="entry-title clearfix"><?php the_title(); ?></h1>
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
				<div class="entry-summary single-content ">
					<?php the_content(); ?>
				</div>
				<div class="clearfix"></div>
				<!-- Social -->
				<div class="social-share">
					<a href="http://www.facebook.com/share.php?u=<?php the_permalink(); ?>&title=<?php the_title(); ?>" onclick="javascript:window.open(this.href,'', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;"><i class="fa fa-facebook"></i></a>
					<a href="https://plus.google.com/share?url=<?php the_permalink(); ?>" onclick="javascript:window.open(this.href,'', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;"><i class="fa fa-google-plus"></i></a>
					<a href="http://twitter.com/home?status=<?php the_title(); ?>+<?php the_permalink(); ?>" onclick="javascript:window.open(this.href,'', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;"><i class="fa fa-twitter"></i></a>
					<a href="http://pinterest.com/pin/create/bookmarklet/?media=<?php echo wp_get_attachment_url( get_post_thumbnail_id($post->ID) ); ?>&url=<?php the_permalink(); ?>&is_video=false&description=<?php the_title(); ?>" onclick="javascript:window.open(this.href,'', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;"><i class="fa fa-pinterest"></i></a>
				</div>
				<!-- Tag -->
				<?php if(get_the_tag_list()) { ?>
				<div class="entry-tag single-tag">
					<?php echo get_the_tag_list('<span class="custom-font title-tag">Tags: </span>',' , ','');  ?>
				</div>
				<?php } ?>
				<!-- link page -->
				<?php wp_link_pages( array( 'before' => '<div class="page-links"><span class="page-links-title">' . esc_html__( 'Pages:', 'sw-paradise' ).'</span>', 'after' => '</div>' , 'link_before' => '<span>', 'link_after'  => '</span>' ) ); ?>	
			</div>
		</div>
		<div class="clearfix"></div>
		
		<!-- Relate Post -->
		<?php 
		global $post;
		global $related_term;
		$class_col= "";
		$categories = get_the_category($post->ID);								
		$category_ids = array();
		foreach($categories as $individual_category) {$category_ids[] = $individual_category->term_id;}
		if ($categories) {
			if($related_post_column =='full'){
				$class_col .= 'col-lg-4 col-md-4 col-sm-4';
				$related = array(
					'category__in' => $category_ids,
					'post__not_in' => array($post->ID),
					'showposts'=>3,
					'orderby'	=> 'rand',	
					'ignore_sticky_posts'=>1
					);
			} else {
				$class_col .= 'col-lg-4 col-md-4 col-sm-4';
				$related = array(
					'category__in' => $category_ids,
					'post__not_in' => array($post->ID),
					'showposts'=>3,
					'orderby'	=> 'rand',	
					'ignore_sticky_posts'=>1
					);
				} ?>
				<div class="single-post-relate">
					<h4><?php esc_html_e('Related Posts', 'sw-paradise'); ?></h4>
					<div class="row">
						<?php
						$related_term = new WP_Query($related);
						while($related_term -> have_posts()):$related_term -> the_post();
						$format = get_post_format();
						?>
						<div <?php post_class($class_col); ?> >
							<?php if ( get_the_post_thumbnail() ) { ?>
							<div class="item-relate-img">
								<div class="entry-meta">
									<span class="latest_post_date">
										<span class="post_day"><?php the_time('d'); ?></span>
										<span class="post_my"><?php the_time('M'); ?></span>
									</span>
								</div>
								<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('sw_paradise_related_post'); ?></a>
							</div>
							<?php } ?>

							<div class="item-relate-content">
								<h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
								<p>
									<?php
									$text = strip_shortcodes( $post->post_content );
									$text = apply_filters('the_content', $text);
									$text = str_replace(']]>', ']]&gt;', $text);
									$content = wp_trim_words($text, 10,'...');
									echo esc_html($content);
									?>
								</p>
								<a class="readmore" href="<?php echo get_permalink($post->ID)?>"><?php esc_html_e('Read More', 'sw-paradise'); ?> <i class="fa fa-angle-right"></i></a>
							</div>
						</div>
						<?php
						endwhile;
						wp_reset_postdata();
						?>
					</div>
				</div>
				<?php } ?>
				
				<div class="clearfix"></div>
				<?php comments_template('/templates/comments.php'); ?>
			</div>
		<?php endwhile; ?>
