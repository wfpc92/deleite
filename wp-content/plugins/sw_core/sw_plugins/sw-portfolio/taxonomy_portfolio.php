<?php
add_action("admin_init", "portfolio_init");
function portfolio_init(){
	add_meta_box("Portfolio Meta", "Portfolio Meta", "portfolio_detail", "portfolio", "normal", "low");
	}
function portfolio_detail(){
	global $post;
	$client = get_post_meta( $post->ID, 'client', true );	
	$date = get_post_meta( $post->ID, 'date', true );
	$arr_size = array( 'Default' => 'default', 'Double Width' => 'p-double-width', 'Double Width & Height' => 'p-double-wh' );
?>	
	<p><label><b><?php _e('Client', 'sw_core'); ?>:</b></label><br/>
		<input type ="text" name = "client" value ="<?php echo esc_attr( $client );?>" size="50%" />
	</p>
	<p><label><b><?php _e('Date', 'sw_core'); ?>:</b></label><br/>
		<input type ="date" name = "date" value ="<?php echo esc_attr( $date );?>" size="50%" />
	</p>	
<?php }
add_action( 'save_post', 'portfolio_save_meta' );
function portfolio_save_meta(){
	global $post;
	$list_meta = array('client', 'date');
	foreach( $list_meta as $meta ){
		if( isset( $_POST[$meta] ) && $_POST[$meta] !=  NULL ){
			update_post_meta($post->ID, $meta, $_POST[$meta]);
		}		
	}
}
?>
