<?php get_template_part('templates/head'); ?>
<div class="wrapper_404">
	<div class="container">
		<div class="row">
			<div class="page-404 col-lg-12 col-md-12">
				<div class="content_404">
					<div class="block-top">
						<h1><?php esc_html_e( 'page 404', 'sw-paradise' )?></h1>
						<p class="warning-code"><?php esc_html_e( 'The product not found', 'sw-paradise' ) ?></p>
					</div>
					<div class="block-bottom">
						<div class="custom-font"><?php esc_html_e( 'Please, try to find a way back home', 'sw-paradise' ) ?></div>
						<a class="btn-404 back2home" href="<?php echo esc_url( home_url('/') ); ?>" title="<?php esc_attr_e( 'Home page', 'sw-paradise' ) ?>"><?php esc_html_e( 'Home page', 'sw-paradise' )?></a>
					</div>					
				</div>
			</div>
		</div>
	</div>
</div>