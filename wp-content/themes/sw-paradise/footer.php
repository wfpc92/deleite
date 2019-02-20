<?php
	$sw_paradise_page_footer   	 = ( get_post_meta( get_the_ID(), 'page_footer_style', true ) != '' ) ? get_post_meta( get_the_ID(), 'page_footer_style', true ) : sw_paradise_options()->getCpanelValue( 'page_footer' );
	$footer_style   	 =  get_post_meta( get_the_ID(), 'footer_style', true );
	?>
<footer id="footer" class="footer default <?php echo esc_attr( $footer_style); ?> theme-clearfix" >
	<div class="container">
		<?php 
			if( $sw_paradise_page_footer != '' ) :
				echo sw_get_the_content_by_id( $sw_paradise_page_footer ); 
			endif;
		?>
	</div>
</footer>
</div>
<?php if(sw_paradise_options()->getCpanelValue('back_active') == '1') { ?>
<a id="sw-paradise-totop" href="#" ></a>
<?php }?>
<?php wp_footer(); ?>
</body>
</html>




