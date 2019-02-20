<?php if ( is_active_sidebar_SW_PARADISE('left') ):
	$left_span_class = 'col-lg-'.sw_paradise_options()->getCpanelValue('sidebar_left_expand');
	$left_span_class .= ' col-md-'.sw_paradise_options()->getCpanelValue('sidebar_left_expand_md');
	$left_span_class .= ' col-sm-'.sw_paradise_options()->getCpanelValue('sidebar_left_expand_sm');
?>
<aside id="left" class="sidebar <?php echo esc_attr($left_span_class); ?>">
	<?php dynamic_sidebar('left'); ?>
</aside>
<?php endif; ?>