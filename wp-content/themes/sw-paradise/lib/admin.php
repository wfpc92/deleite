<?php

/**
 * Add Theme Options page.
 */
function sw_paradise_theme_admin_page(){
	add_theme_page(
		esc_html__('Theme Options', 'sw-paradise'),
		esc_html__('Theme Options', 'sw-paradise'),
		'manage_options',
		'sw_paradise_theme_options',
		'sw_paradise_theme_admin_page_content'
	);
}
add_action('admin_menu', 'sw_paradise_theme_admin_page', 49);

function sw_paradise_theme_admin_page_content(){ ?>
	<div class="wrap">
		<h2><?php esc_html_e( 'SW PARADISE Advanced Options Page', 'sw-paradise' ); ?></h2>
		<?php do_action( 'sw_paradise_theme_admin_content' ); ?>
	</div>
<?php
}