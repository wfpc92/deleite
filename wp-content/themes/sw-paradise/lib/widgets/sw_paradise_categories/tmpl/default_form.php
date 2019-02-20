<?php
$categoriesid = isset( $instance['categories'] )    ? $instance['categories'] : array();
$orderby    = isset( $instance['orderby'] )     ? strip_tags($instance['orderby']) : 'ID';
$order      = isset( $instance['order'] )       ? strip_tags($instance['order']) : 'ASC';
$number     = isset( $instance['number'] ) ? intval($instance['number']) : 5;
$exclude    = isset( $instance['exclude'] ) ? strip_tags($instance['exclude']) : 0;

?>

<p>
	<label for="<?php echo esc_attr( $this->get_field_id('categories') ); ?>"><?php esc_html_e('Categories Name', 'sw-paradise')?></label>
	<br />
	<?php echo $this->category_select('categories', array('allow_select_all' => true, 'multiple' => true), $categoriesid); ?>
</p>

<p>
	<label for="<?php echo esc_attr( $this->get_field_id('orderby') ); ?>"><?php esc_html_e('Orderby', 'sw-paradise')?></label>
	<br />
	<?php $allowed_keys = array('name' => 'Name', 'author' => 'Author', 'date' => 'Date', 'title' => 'Title', 'modified' => 'Modified', 'parent' => 'Parent', 'ID' => 'ID', 'rand' =>'Rand', 'comment_count' => 'Comment Count'); ?>
	<select class="widefat"
		id="<?php echo esc_attr( $this->get_field_id('orderby') ); ?>"
		name="<?php echo esc_attr( $this->get_field_name('orderby') ); ?>">
		<?php
		$option ='';
		foreach ($allowed_keys as $value => $key) :
			$option .= '<option value="' . esc_attr( $value ) . '" ';
			if ($value == $orderby){
				$option .= 'selected="selected"';
			}
			$option .=  '>'.$key.'</option>';
		endforeach;
		echo $option;
		?>
	</select>
</p>

<p>
	<label for="<?php echo esc_attr( $this->get_field_id('order') ); ?>"><?php esc_html_e('Order', 'sw-paradise')?></label>
	<br />
	<select class="widefat"
		id="<?php echo esc_attr( $this->get_field_id('order') ); ?>"
		name="<?php echo esc_attr( $this->get_field_name('order') ); ?>">
		<option value="DESC" <?php if ($order=='DESC'){?> selected="selected"
		<?php } ?>>
			<?php esc_html_e('Descending', 'sw-paradise')?>
		</option>
		<option value="ASC" <?php if ($order=='ASC'){?> selected="selected"
		<?php } ?>>
			<?php esc_html_e('Ascending', 'sw-paradise')?>
		</option>
	</select>
</p>

<p>
	<label for="<?php echo esc_attr( $this->get_field_id('number') ); ?>"><?php esc_html_e('Number of Categories', 'sw-paradise')?></label>
	<br />
	<input class="widefat"
		id="<?php echo esc_attr( $this->get_field_id('number') ); ?>"
		name="<?php echo esc_attr( $this->get_field_name('number') ); ?>" type="text"
		value="<?php echo esc_attr($number); ?>" />
</p>

<p>
	<label for="<?php echo esc_attr( $this->get_field_id('exclude') ); ?>"><?php esc_html_e('Exclude', 'sw-paradise')?></label>
	<br />
	<input class="widefat"
		id="<?php echo esc_attr( $this->get_field_id('exclude') ); ?>"
		name="<?php echo esc_attr( $this->get_field_name('exclude') ); ?>" type="text"
		value="<?php echo esc_attr($exclude); ?>" />
</p>