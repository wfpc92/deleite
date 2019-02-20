<?php 
$sw_paradise_header_style = sw_paradise_options()->getCpanelValue('header_style');
if($sw_paradise_header_style !='style5') { ?>
<?php do_action( 'before' ); ?>
<?php if ( class_exists( 'WooCommerce' ) ) { ?>
<?php global $woocommerce; ?>
<div class="top-login">
	<?php if ( ! is_user_logged_in() ) {  ?>
		<ul>
			<li>
			<?php echo ' <a href="javascript:void(0);" data-toggle="modal" data-target="#login_form"><span>'.esc_html__('Login', 'sw-paradise').'</span></a> '; ?>
				
			</li>
		</ul>
	<?php } else{?>
		<div class="div-logined">
			<ul>
				<li>
					<?php 
						$user_id = get_current_user_id();
						$user_info = get_userdata( $user_id );	
					?>
					<a href="<?php echo wp_logout_url( home_url('/') ); ?>" title="<?php esc_attr_e( 'Logout', 'sw-paradise' ) ?>"><?php esc_html_e('Logout', 'sw-paradise'); ?></a>
				</li>
			</ul>
		</div>
	<?php } ?>
</div>
<?php }  } ?>
