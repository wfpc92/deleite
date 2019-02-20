<?php 
if (!isset($instance['category'])){
	$instance['category'] = 0;
}
extract($instance);

$default = array(
	'category' => $category,
	'orderby' => $orderby,
	'order' => $order,
	'include' => $include,
	'exclude' => $exclude,
	'post_status' => 'publish',
	'numberposts' => $numberposts
	);

$list = get_posts($default);
if (count($list)>0){
	?>
	<div class="widget-the-blog">
		<ul>
			<?php foreach ($list as $key => $post){?>
			<li class="widget-post item-<?php echo $key;?>">
				<div class="widget-post-info">		
					<a href="<?php echo get_permalink($post->ID)?>" title="<?php echo esc_attr( $post->post_title );?>"><?php echo get_the_post_thumbnail($post->ID, 'thumbnail');?></a>
					<div class="item-title">
						<h4><a href="<?php echo get_permalink($post->ID)?>" title="<?php echo esc_attr( $post->post_title );?>"><?php echo esc_html( $post->post_title );?></a></h4>
						<p class="post_my"><?php echo get_the_time('M, d, Y', $post->ID ); ?></p>
					</div>
				</div>
			</li>
			<?php }?>
		</ul>
	</div>
	<?php }?>