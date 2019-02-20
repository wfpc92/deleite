<?php get_header(); ?>

<!-- Breadcrumb title -->
<?php sw_paradise_breadcrumb_title() ?>

<div class="container">
	<div class="row">
		<div id="contents" role="main" class="col-lg-12 col-md-12 col-sm-12">
			<?php
			get_template_part('templates/content', 'page')
			?>
		</div>
	</div>
	
</div>
<?php get_footer(); ?>

