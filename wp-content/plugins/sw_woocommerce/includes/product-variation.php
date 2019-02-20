<?php 
/*
** Product variation hook
*/
add_filter( 'woocommerce_dropdown_variation_attribute_options_html', 'sw_woocommerce_custom_variation', 10, 2 );
function sw_woocommerce_custom_variation( $html, $args ){
	$options   = $args['options'];
	$product   = $args['product'];
	$attribute = $args['attribute'];
	$name      = $args['name'] ? $args['name'] : 'attribute_' . sanitize_title( $attribute );
	$id        = $args['id'] ? $args['id'] : sanitize_title( $attribute );
	$class     = $args['class'];
	
	if ( empty( $options ) && ! empty( $product ) && ! empty( $attribute ) ) {
		$attributes = $product->get_variation_attributes();
		$options    = $attributes[ $attribute ];
	}
	$html = '';
	if ( ! empty( $options ) ) {
		$html .= '<div class="sw-custom-variation">';	
		if ( $product && taxonomy_exists( $attribute ) ) {
			// Get terms if this is a taxonomy - ordered. We need the names too.
			$terms = wc_get_product_terms( $product->get_id(), $attribute, array( 'fields' => 'all' ) );								
			foreach ( $terms as $key => $term ) {
				$color  		= get_term_meta( $term->term_id, 'sw_variation_color', true );
				$active 		= ( checked( sanitize_title( $args['selected'] ), $term->slug, false ) ) ? ' selected' : '';
				$attributes = ( preg_match( '/color|colors/', $attribute, $match ) && $color != '' ) ? 'class="variation-color" style="background: '. esc_attr( $color ) .'"' : '';
				
				if ( in_array( $term->slug, $options ) ) {
					$html .= '<label class="radio-label sw-radio-variation sw-radio-variation-'. esc_attr( $key .' '. $active ) .'" title="'. esc_attr( $term->slug )  .'" for="'. esc_attr( $term->slug . '_' . $key ) . '">';
					$html .= '<input type="radio" id="'.  esc_attr( $term->slug . '_' . $key ) .'" name="' . esc_attr( $name ) . '" data-attribute_name="attribute_' . esc_attr( sanitize_title( $attribute ) ) . '" value="' . esc_attr( $term->slug ) . '" '. checked( sanitize_title( $args['selected'] ), $term->slug, false ) .'/>';
					$html .= '<span '. $attributes .'>'. $term->name .'</span>';
					$html .= '</label>';
				}
			}			
		}else {
			foreach ( $options as $key => $option ) {
				// This handles < 2.4.0 bw compatibility where text attributes were not sanitized.
				$checked = sanitize_title( $args['selected'] ) === $args['selected'] ? checked( $args['selected'], sanitize_title( $option ), false ) : checked( $args['selected'], $option, false );
				$active = ( $checked ) ? 'selected' : '';

				$html .= '<label class="radio-label sw-radio-variation sw-radio-variation-'. esc_attr( $key .' '. $active ) .'"  title="'. esc_attr( $option )  .'"  for="'. esc_attr( $option . '_' . $key ) . '">';
				$html .= '<input type="radio" id="'.  esc_attr( $option . '_' . $key ) .'" name="' . esc_attr( $name ) . '" data-attribute_name="attribute_' . esc_attr( sanitize_title( $attribute ) ) . '" value="' . esc_attr( $option ) . '" '. $checked .'/>';
				$html .= '<span>'. $option .'</span>';
				$html .= '</label>';
			}
		}
		$html .= '</div>';
	}
	return $html;
}

/*
** Action hook to color
*/

$sw_attribute_taxonomies = wc_get_attribute_taxonomies();
if ( ! empty( $sw_attribute_taxonomies ) ) {
	foreach( $sw_attribute_taxonomies as $attr ){
		if( preg_match( '/color|colors/', $attr->attribute_name, $match ) ){
			add_action( 'pa_'. $attr->attribute_name .'_add_form_fields', 'sw_woocommerce_variation_fields', 200 );
			add_action( 'pa_'. $attr->attribute_name .'_edit_form_fields', 'sw_woocommerce_edit_variation_fields', 200 );
			add_action( 'created_term', 'sw_woocommerce_save_variation_fields', 10, 3 );
			add_action( 'edit_terms', 'sw_woocommerce_save_variation_fields', 10, 3 );
			
			/* Enqueue Admin js */
			add_action( 'admin_enqueue_scripts', 'sw_woocommerce_variation_color_script' );	
		}
	}
}

/*
** Create color
*/
function sw_woocommerce_variation_fields() {
	?>
	<div class="form-field custom-picker">
		<label for="sw_variation_color"><?php _e( 'Color', 'sw_woocommerce' ); ?></label>
		<input name="sw_variation_color" id="sw_variation_color" type="text" value="" size="40" class="category-colorpicker"/>
	</div>
	<?php
}

function sw_woocommerce_edit_variation_fields( $term ) {

	$sw_variation_color = get_term_meta( $term->term_id, 'sw_variation_color', true );

	?>
	<tr class="form-field custom-picker custom-picker-edit">
		<th scope="row" valign="top"><label for="sw_variation_color"><?php _e( 'Color', 'sw_woocommerce' ); ?></label></th>
		<td>
			<input name="sw_variation_color" id="sw_variation_color" type="text" value="<?php echo esc_attr( $sw_variation_color ) ?>" size="40" class="category-colorpicker"/>
		</td>
	</tr>
	<?php
}

/** Save Custom Field Of Category Form */
function sw_woocommerce_save_variation_fields( $term_id, $tt_id = '', $taxonomy = '', $prev_value = '' ) {
	if ( isset( $_POST['sw_variation_color'] ) ) {			
		$term_value = esc_attr( $_POST['sw_variation_color'] );
		update_term_meta( $term_id, 'sw_variation_color', $term_value, $prev_value );
	}
}

function sw_woocommerce_variation_color_script(){
	wp_enqueue_style( 'wp-color-picker' ); 
	wp_enqueue_script('category_color_picker_js', WCURL . '/js/admin/category_color_picker.js', array( 'wp-color-picker' ), false, true);
}