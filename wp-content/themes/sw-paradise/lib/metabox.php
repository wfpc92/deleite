<?php 
/*
	* Name: Metabox Page
	* Develope: Smartaddons
*/
/*
** Build array
*/
function sw_paradise_build_array( $case ){
	$build_arr = array();
	if( $case == 'page' ) :
		$build_arr = array( '' => esc_html__( 'Select Page', 'sw-paradise' ) );
		$pages = get_pages(); 
		foreach( $pages as $page ) {
			$build_arr[$page->ID] = $page->post_title;
		}
	elseif( $case == 'sidebar' ) :
		$wp_registered_sidebars = sw_paradise_widget_setup_args();
		$build_arr = array( '' => esc_html__( 'Select Sidebar', 'sw-paradise' ) );
		foreach( $wp_registered_sidebars as $sidebar ) {
			$build_arr[$sidebar['id']] = $sidebar['name'];
		}
	endif;
	return $build_arr;
}

/*
** Metabox array define
*/
function sw_paradise_metabox_init(){

	$sw_paradise_metabox_pages[] = array(
		'title' 	=> esc_html__( 'Footer', 'sw-paradise' ),
		'fields'	=> array(
			array(
				'type'	=> 'checkbox',
				'title'	=> esc_html__( 'Hide Footer', 'sw-paradise' ),
				'id'	=> 'page_footer_hide',
				'description' => esc_html__( 'Choose to show or hide the footer. ', 'sw-paradise' ),
				'std'	 => '0',
			),
			array(
				'type'	=> 'select',
				'title'	=> esc_html__( 'Footer Page Select', 'sw-paradise' ),
				'id'	=> 'page_footer_style',
				'description' => esc_html__( ' Chose to select footer page content for this page. ', 'sw-paradise' ),
				'std'	 => '',
				'values' => sw_paradise_build_array( 'page' )
			),
			array(
				'type'	=> 'select',
				'title'	=> esc_html__( 'Footer Style', 'sw-paradise' ),
				'id'	=> 'footer_style',
				'description' => esc_html__( 'Select home template', 'sw-paradise' ),
				'std'	 => '',
				'values' => array( '' => esc_html__( 'Default', 'sw-paradise' ), 'style1' => esc_html__( 'Style1', 'sw-paradise' ),  'style2' => esc_html__( 'Style2', 'sw-paradise' ),
				'style3' => esc_html__( 'Style3', 'sw-paradise' )),
			)
		)
	);
	
	
	return $sw_paradise_metabox_pages;
}
add_action( 'init', 'sw_paradise_metabox_init' );

add_action( 'admin_init', 'sw_paradise_page_init' );
function sw_paradise_page_init(){
	add_meta_box( 'sw_paradise_page_meta', esc_html__( 'Page Metabox', 'sw-paradise' ), 'sw_paradise_page_meta', array( 'page', 'post', 'product' ), 'normal', 'low' );
	add_action( 'save_post', 'sw_paradise_page_save_meta', 10, 1 );
}	

/*
** Metabox HTML
*/
function sw_paradise_page_meta(){
global $post;
	$sw_paradise_metabox_pages = sw_paradise_metabox_init();
	$except_args = array( 'General', 'Typography' );
	$current_screen =  get_current_screen()->post_type;
	wp_nonce_field( 'sw_paradise_page_save_meta', 'sw_paradise_metabox_plugin_nonce' );
	if( in_array( $current_screen, array( 'post', 'page', 'product' ) ) ) : 
		wp_enqueue_style( 'metabox_style', get_template_directory_uri() . '/lib/admin/css/metabox.css', array(), null );
		wp_enqueue_script( 'tab_script', get_template_directory_uri() . '/lib/admin/js/tab.js', array(), null, true );
		wp_enqueue_script( 'sw-paradise-opts-field-radio_img-js',	SW_PARADISE_URL.'/options/fields/radio_img/field_radio_img.js',	array('jquery'), time(), true	);
	endif; 
?>
	<div class="sw-paradise-metabox" id="sw_paradise_metabox">
		<div class="sw-paradise-metabox-content">
			<ul class="nav nav-tabs">
			<?php 
				$i = 0;
				foreach( $sw_paradise_metabox_pages as $metabox ){ 
					if( ( $current_screen == 'post' || $current_screen == 'product' ) && ( in_array( $metabox['title'], $except_args ) ) ){
						continue;
					}
					$active = ( $i == 0 ) ? 'active' : '';
					echo '<li class="' . esc_attr( $active ) . '"><a href="#sw_paradise_'. strtolower( $metabox['title'] ) .'" data-toggle="tab">' . $metabox['title'] . '</a></li>';
					$i ++;
				} 
			?>
			</ul>
			<div class="tab-content">
			<?php 
				$i = 0;
				foreach( $sw_paradise_metabox_pages as $metabox ){ 
					$active = ( $i == 0 ) ? 'active' : '';	
					if( ( $current_screen == 'post' || $current_screen == 'product' ) && ( in_array( $metabox['title'], $except_args ) ) ){
						continue;
					}
			?>
				<div class="tab-pane <?php echo esc_attr( $active ); ?>" id="sw_paradise_<?php echo strtolower( $metabox['title'] ) ; ?>">
					<?php if( isset( $metabox['fields'] ) && count( $metabox['fields'] ) > 0 ) {?>
						<?php 
							foreach( $metabox['fields'] as $meta_field ) { 
							$values = isset( $meta_field['values'] ) ? $meta_field['values'] : '';
						?>
							<div class="tab-inner clearfix">
								<div class="flytab-description pull-left">
								
									<!-- Title meta field -->
									<?php if( $meta_field['title'] != '' ) { ?>
									<div class="flytab-item-title">
										<?php echo $meta_field['title']; ?>
									</div>
									<?php } ?>
									
									<!-- Description -->
									<?php if( $meta_field['description'] != '' ) { ?>
									<div class="flytab-item-shortdes">
										<?php echo $meta_field['description']; ?>
									</div>
									<?php } ?>
								</div>
								<!-- Meta content -->
								<div class="flytab-content">
									<?php sw_paradise_render_html( $meta_field['id'], $meta_field['type'], $values, $meta_field['std'] ); ?>									
								</div>
							</div>
						<?php } ?>
					<?php } ?>
				</div>
			<?php $i ++; } ?>
			</div>
		</div>
	</div>
<?php 
}

/*
** Function Render HTML
*/
function sw_paradise_render_html( $id, $type, $values, $std ){
	global $post;
	$meta_value = '';
	if( get_post_meta( $post->ID, $id, true ) != '' ){
			$meta_value = get_post_meta( $post->ID, $id, true );
	}else if( isset( $std ) && $std != '' ){
		$meta_value = $std;
	}
	$html = '';
	switch( $type ) {
		case 'text' :
			$html .= '<input type="text" value="'. esc_attr( $meta_value ) .'" id="'. esc_attr( $id ) .'" name="'. esc_attr( $id ) .'"/>';
		break;
		
		case 'textarea' :
			$html .= '<texarea id="'. esc_attr( $id ) .'" name="'. esc_attr( $id ) .'"/>'. esc_attr( $meta_value ) .'</texarea>';
		break;
		
		case 'editor' :
			wp_editor( $meta_value, $id, array() );
		break;
		
		case 'select' :
			$html .= '<select id="'. esc_attr( $id ) .'" name="'. esc_attr( $id ) .'">';
				foreach( $values as $key => $value ) {
					$html .= '<option value="'. esc_attr( $key ) .'" '. selected( $meta_value, $key, false ) .'>'. $value .'</option>';
				}
			$html .= '</select>';
		break;
		
		case 'multiselect' :
			$multi_value = array();
			if( is_array( $meta_value ) ){
				$multi_value = $meta_value;
			}else{
				$multi_value[] = $meta_value;
			}
			$select_value = $multi_value;
			$html .= '<select id="'. esc_attr( $id ) .'" name="'. esc_attr( $id ) .'[]" multiple>';
				foreach( $values as $key => $value ) {
					$check = ( in_array( $key, $select_value ) ) ? 'selected="selected"' : '';
					$html .= '<option value="'. esc_attr( $key ) .'" '. $check .'>'. $value .'</option>';
				}
			$html .= '</select>';
		break;
		
		case 'checkbox' :
			$check = ( $meta_value == 'yes' ) ? 'checked' : '';
			$html .= '<input type="checkbox" name="'. esc_attr( $id ) .'" value="yes" '. esc_attr( $check ) .'/>';
		break;
		
		case 'radio_img' :
			$i = 0;
			$html .= '<div class="page-metabox-radio-img">';
			foreach( $values as $key => $value ) {
				$key_val = ( $key == 'none' ) ? esc_html__( 'No Select', 'sw-paradise' ) : $key; 
				$selected = ( checked( $meta_value, $key, false ) != '' ) ? ' sw-paradise-radio-img-selected' : '';
				$html .= '<label class="radio-label revo-radio-img'.$selected.' sw-paradise-radio-img-'. esc_attr( $id ) .'" for="'. esc_attr( $id ) .'_'. $i .'">';
				$html .= '<input type="radio" id="'. esc_attr( $id ) .'_'. $i .'" name="'. esc_attr( $id ) .'" value="'. esc_attr( $key ) .'" '.checked($meta_value, $key, false).'/>';
				$html .= '<div class="page-radio-color" style="background: '. esc_attr( $value ) .'" onclick="jQuery:sw_paradise_radio_img_select(\''. esc_attr( $id ) .'_'. $i .'\', \''. esc_attr( $id ) .'\');"></div>';
				$html .= '<br/><span>'. esc_attr( $key_val ) .'</span>';
				$html .= '</label>';
				$i ++;
			}
			$html .= '</div>';
		break;
		
		case 'radio' :
			$i = 0;
			$html .= '<div class="page-metabox-radio">';
			foreach( $values as $key => $value ) {
				$html .= '<label class="radio-label '. esc_attr( $id ) .'" for="'. esc_attr( $id ) .'_'. $i .'">';
				$html .= '<input type="radio" id="'. esc_attr( $id ) .'_'. $i .'" name="'. esc_attr( $id ) .'" value="'. esc_attr( $key ) .'" '.checked($meta_value, $key, false).'/>';
				$html .= '';
				$html .= '<br/><span>'. esc_attr( $value ) .'</span>';
				$html .= '</label>';
				$i ++;
			}
			$html .= '</div>';
		break;
		
		case 'multicheckbox' :
			$multi_value = array();
			if( is_array( $meta_value ) ){
				$multi_value = $meta_value;
			}else{
				$multi_value[] = $meta_value;
			}
			$checkbox_value = $multi_value;
			foreach( $values as $key => $value ) {
				$check = ( in_array( $key, $checkbox_value ) ) ? 'checked' : '';
				$html .= '<div class="metabox-multicheck pull-left"><input type="checkbox" name="'. esc_attr( $id ) .'[]" value="'. esc_attr( $key ) .'" '. $check .'/>';
				$html .= '<br/><label>'. $value .'</label></div>';
			}
		break;
		
		case 'upload' :
			$upload_img = wp_get_attachment_image_url( $meta_value, 'thumbnail' ) ? wp_get_attachment_image_url( intval($meta_value), 'thumbnail' ) : '';
			ob_start();
		?>
			<div class="upload-formfield">
				<div id="metabox_thumbnail" style="float: left; margin-right: 10px;"><img src="<?php echo esc_url( $upload_img ); ?>" alt="" width="30" height="30" /></div>
				<div class="metabox-thumbnail-wrapper">
					<input type="hidden" id="<?php echo esc_attr( $id ); ?>" name="<?php echo esc_attr( $id ); ?>" value="<?php echo esc_attr( $meta_value ) ?>"/>
					<button type="button" class="upload_image_button button"><?php echo esc_html__( 'Upload/Add image', 'sw-paradise' ) ?></button>
					<button type="button" class="remove_image_button button"><?php echo esc_html__( 'Remove image', 'sw-paradise' ) ?></button>
				</div>
				<script type="text/javascript">

					// Only show the "remove image" button when needed
					if ( ! jQuery( '#<?php echo esc_js( $id ); ?>' ).val() ) {
						jQuery( '.remove_image_button' ).hide();
					}

					// Uploading files
					var file_frame;

					jQuery( document ).on( 'click', '.upload_image_button', function( event ) {

						event.preventDefault();

						// If the media frame already exists, reopen it.
						if ( file_frame ) {
							file_frame.open();
							return;
						}

						// Create the media frame.
						file_frame = wp.media.frames.downloadable_file = wp.media({
							title: '<?php esc_html_e( "Choose an image", 'sw-paradise' ); ?>',
							button: {
								text: '<?php esc_html_e( "Use image", 'sw-paradise' ); ?>'
							},
							multiple: false
						});

						// When an image is selected, run a callback.
						file_frame.on( 'select', function() {
							var attachment = file_frame.state().get( 'selection' ).first().toJSON();
							
							jQuery( '#<?php echo esc_js( $id ); ?>' ).val( attachment.id );
							console.log('#<?php echo esc_js( $id ); ?>');
							jQuery( '#metabox_thumbnail > img' ).attr( 'src', attachment.sizes.thumbnail.url );
							jQuery( '.remove_image_button' ).show();
						});

						// Finally, open the modal.
						file_frame.open();
					});

					jQuery( document ).on( 'click', '.remove_image_button', function() {
						jQuery( '#metabox_thumbnail > img' ).attr( 'src', 'http://placehold.it/30x30' );
						jQuery( '#<?php echo esc_js( $id ); ?>' ).val( '' );
						jQuery( '.remove_image_button' ).hide();
						return false;
					});

				</script>
				<div class="clear"></div>
			</div>
	<?php
			$html .= ob_get_clean();
		break;
		
		case 'color' :
			$color_value = isset( $meta_value ) ? $meta_value : $std;			
			$html .= '<input type="text" id="'.esc_attr( $id ).'" name="'. esc_attr( $id ) .'" value="'.esc_attr( $color_value ).'" class="sw-paradise-popup-colorpicker" style="width:70px;"/>';
		break;
	}
	echo $html;
}

function sw_paradise_page_save_meta(){
	if( !is_admin() ){
		return;
	}
	global $post;
	$sw_paradise_metabox_pages = sw_paradise_metabox_init(); 
	$except_args = array( 'General', 'Typography' );
	$current_screen =  get_current_screen()->post_type;
	if ( ! isset( $_POST['sw_paradise_metabox_plugin_nonce'] ) ) {
		return;
	}
	if ( ! wp_verify_nonce( $_POST['sw_paradise_metabox_plugin_nonce'], 'sw_paradise_page_save_meta' ) ) {
		return;
	}
	foreach( $sw_paradise_metabox_pages as $key => $metabox ){ 
		if( ( $current_screen == 'post' || $current_screen == 'product' ) && ( in_array( $metabox['title'], $except_args ) ) ){
			continue;
		}
		foreach( $metabox['fields'] as $meta_field ) { 			
			if( isset( $_POST[$meta_field['id']] ) ){
				$data = $_POST[$meta_field['id']];
				switch( $meta_field['id'] ) {
					case 'text' :
						$data = sanitize_text_field( $_POST[$meta_field['id']] );
					break;
					
					case 'email' :
						$data = sanitize_email( $_POST[$meta_field['id']] );
					break;
					
					case 'number' :
						$data = intval( $_POST[$meta_field['id']] );
					break;
					
					case 'upload' :
						$data = intval( $_POST[$meta_field['id']] );
					break;
					
					case 'radio_img' :
						$data = $_POST[$meta_field['id']];
					break;

				}
				if( strlen( trim( $data ) ) > 0 ) :
					update_post_meta( $post->ID, $meta_field['id'], $data );
				else: 
					delete_post_meta( $post->ID, $meta_field['id'] );
				endif;
			}
			else{
				if( $meta_field['std'] != '' ){
					update_post_meta( $post->ID, $meta_field['id'], $meta_field['std'] );
				}else{
					delete_post_meta( $post->ID, $meta_field['id'] );
				}
			}
		}
	}
}

