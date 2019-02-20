<?php  
extract($instance);

$default = array(
	'order' => $order,
	'number' => $number,
	'status' => 'approve', 
	'post_status' => 'publish'
);

$list = get_comments($default);
if (count($list) > 0){
?>
<div class="sw-paradise-comments">
	<?php foreach ($list as $comment){ ?>
	<div class="sw-paradise-comment">
		<div class="sw-content"><?php echo self::sw_paradise_trim_words($comment->comment_content, $length)?></div>
		<div class="comment-author">
			<span><?php esc_html_e('Written by', 'sw-paradise');?></span>: <a href="<?php echo get_comment_author_link( $comment->comment_ID ); ?>"><?php echo get_comment_author( $comment->comment_ID );?></a>
		</div>
	</div>
	<?php } ?>
</div>
<?php }?>