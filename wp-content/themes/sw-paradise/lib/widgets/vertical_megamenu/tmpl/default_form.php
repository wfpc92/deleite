<?php

$menu_locate      = isset( $instance['menu_locate'] )       ? strip_tags($instance['menu_locate']) : '';
$menu_locations = wp_get_nav_menus();	
?>

<p>
	<label for="<?php echo esc_attr( $this->get_field_id('menu_locate') ); ?>"><?php esc_html_e('Menu Name', 'sw-paradise')?></label>
	<br />
	<select class="widefat"
		id="<?php echo esc_attr( $this->get_field_id('menu_locate') ); ?>"
		name="<?php echo esc_attr( $this->get_field_name('menu_locate') ); ?>">
		<option value=""><?php esc_html_e( 'Select Menu Name', 'sw-paradise' ); ?></option>
		<?php foreach( $menu_locations as $menu_location ){?>
			<option value="<?php echo esc_attr( $menu_location -> name ); ?>" <?php if ( $menu_locate == ( $menu_location -> name ) ){?> selected="selected"
			<?php } ?>>
				<?php echo $menu_location -> name; ?>
			</option>
		<?php } ?>
	</select>
</p>