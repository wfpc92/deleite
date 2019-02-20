<?php get_header() ?>
<div class="flytheme_breadcrumbs">
	<div class="container">
		<div class="breadcrumbs-page">						
		</div>
		<?php
		if (!is_front_page() ) {
			if (function_exists('flytheme_breadcrumb')){
				flytheme_breadcrumb('<div class="breadcrumbs custom-font theme-clearfix">', '</div>');
			} 
		} 
		?>
	</div>
</div>
<div class="container">
	<div id="main" class="main">
		<?php 
		while (have_posts()) : the_post(); 
		global $post;
		$client 	= get_post_meta( $post->ID, 'client', true );	
		$date 		= get_post_meta( $post->ID, 'date', true );
		$terms 		= get_the_terms( $post->ID, 'portfolio_cat' );
		$term_str 	= '';
		foreach( $terms as $key => $term ){
			$str = ( $key == 0 ) ? '' : ', ';
			$term_str .= $str . '<a href="'. get_term_link( $term->term_id, 'portfolio_cat' ) .'">'. $term->name .'</a>';
		}
		?>
		<div <?php post_class(); ?>>
			<!-- Content Portfolio -->
			<div class="portfolio-top">
				<div class="portfolio-content clearfix">
					<?php if( has_post_thumbnail() ){ ?>
					<div class="single-thumbnail pull-left">
						<?php the_post_thumbnail( 'large' ); ?>
					</div>
					<?php } ?>
					<div class="single-portfolio-content">
						<h1 class="portfolio-title"><?php the_title(); ?></h1>
						<div class="single-description">
							<?php the_content(); ?>
						</div>
						<div class="portfolio-meta">
							<?php if( $client != '' ){ ?>
							<div class="pmeta-item">
								<?php echo '<span>'.__( 'Client', 'sw_core' ).'</span> '. esc_html( $client ); ?>
							</div>
							<?php } ?>
							<?php if( $date != '' ){ ?>
							<div class="pmeta-item">
								<?php echo '<span>'.__( 'Date', 'sw_core' ).'</span> '. esc_html( $date ); ?>
							</div>
							<?php } ?>
							<div class="pmeta-item">
								<?php echo '<span>'.__( 'Categories', 'sw_core' ).'</span> '. $term_str; ?>
							</div>
						</div>
						<!-- Social -->
						<div class="social-share">
							<span><?php esc_attr_e( 'Share', 'sw_core' ) ?></span>
							<a href="http://www.facebook.com/share.php?u=<?php the_permalink(); ?>&title=<?php the_title(); ?>" onclick="javascript:window.open(this.href,'', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;"><i class="fa fa-facebook"></i></a>
							<a href="https://plus.google.com/share?url=<?php the_permalink(); ?>" onclick="javascript:window.open(this.href,'', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;"><i class="fa fa-google-plus"></i></a>
							<a href="http://twitter.com/home?status=<?php the_title(); ?>+<?php the_permalink(); ?>" onclick="javascript:window.open(this.href,'', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;"><i class="fa fa-twitter"></i></a>
							<a href="http://pinterest.com/pin/create/bookmarklet/?media=<?php echo wp_get_attachment_url( get_post_thumbnail_id($post->ID) ); ?>&url=<?php the_permalink(); ?>&is_video=false&description=<?php the_title(); ?>" onclick="javascript:window.open(this.href,'', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;"><i class="fa fa-pinterest"></i></a>
						</div>
					</div>
				</div>
			</div>
			<!-- End Content Portfolio -->			
			<!-- Related Portfolio -->
			<?php 
			global $related_term;
			$categories = get_the_terms( $post->ID, 'portfolio_cat' );								
			$category_ids = array();
			foreach( $categories as $individual_category ) {$category_ids[] = $individual_category->term_id;}
			if ( $categories ) {
				$related = array(
					'post_type'	   => 'portfolio',
					'tax_query' => array(
						array(
							'taxonomy' => 'portfolio_cat',
							'field' => 'term_id',
							'terms' => $category_ids
							)
						),
					'post__not_in' => array( $post->ID ),
					'showposts'	   => 3,
					'orderby'	   => 'rand',	
					'ignore_sticky_posts'=> 1
					);				
				$query = new wp_query( $related );
				//var_dump( $query );
				if( $query -> have_posts() ){
					?>
					<div class="related-portfolio">
						<h2><?php esc_html_e( 'Related Portfolio' ); ?></h2>
						<!-- Relate Post -->			
						<div class="related-items clearfix">
							<?php while( $query -> have_posts() ) : $query -> the_post(); ?>
								<div class="related-item col-md-4 col-sm-4 col-xs-12 pull-left">
									<div class="item-img list-image-static">
										<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_post_thumbnail( 'large' ); ?></a>
									</div>
								</div>
							<?php endwhile; wp_reset_postdata(); ?>
						</div>			
					</div>
					<?php } } ?>
					<!-- End Related Portfolio -->
				</div>
			<?php endwhile; ?>
		</div>
	</div>
	<?php get_footer() ?>