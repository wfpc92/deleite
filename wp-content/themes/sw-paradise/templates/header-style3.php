<?php 
$sw_paradise_colorset      = sw_paradise_options()->getCpanelValue('scheme');
$sw_paradise_header_style  = sw_paradise_options()->getCpanelValue('header_style');
$sw_paradise_my_delivery   = sw_paradise_options()->getCpanelValue('my_delivery');
$sw_paradise_my_store      = sw_paradise_options()->getCpanelValue('my_store');
?>
<header id="header" class="header">
	<div class="top-header">
		<div class="sw-paradise-header container">
			<?php if($sw_paradise_my_store != '') { ?>
			<div class="sw_paradise_store col-md-4 col-sm-4 col-xs-12">
				<div class="main-store">
					<span><?php echo esc_html($sw_paradise_my_store ); ?></span>
				</div>
			</div>
			<?php } ?>

			<?php if($sw_paradise_my_delivery != '') { ?>
			<div class="sw_paradise_delivery col-md-4 col-sm-4 col-xs-12">
				<div class="main-delivery">
					<span><?php echo esc_html($sw_paradise_my_delivery ); ?></span>
				</div>
			</div>
			<?php } ?>
			
			<?php if( class_exists( 'WooCommerce' ) ) : ?>
			<div class="sw-paradise-account col-md-4 col-sm-4 col-xs-12 pull-right">
				<div class="sw_paradise_cart pull-right">
					<?php get_template_part( 'woocommerce/minicart-ajax' ); ?>
				</div>
				
				<?php if( function_exists( 'YITH_WCWL' ) ){ ?>
				<div class="sw-paradise-wishlist pull-right">
					<?php 					
						$wishlist_page_id  = yith_wcwl_object_id( get_option( 'yith_wcwl_wishlist_page_id' ) );
						$wishlist_permalink = trailingslashit( get_the_permalink( $wishlist_page_id ) );
					?>
					<a href="<?php echo esc_url( $wishlist_permalink ) ?>" title="<?php esc_html_e( 'View my Wishlist', 'sw-paradise' ); ?>"><i class="fa fa-heart" aria-hidden="true"></i></a>
				</div>
				<?php } ?>
				
				<?php if (is_active_sidebar_SW_PARADISE('top-right')) {?>
				<div class="sw-paradise-account-info pull-right">
					<i class="fa fa-user" aria-hidden="true"></i>					
					<div id="sidebar-top-right" class="sidebar-top-right">
						<?php dynamic_sidebar('top-right'); ?>
					</div>					
				</div>
				<?php }?>
			</div>
			<?php endif; ?>			
		</div>
		<div class="sw-paradise-center container">
			<div class="row">
				<div class="sw-paradise-lang col-md-4 col-sm-4 col-xs-12">
					<?php if (is_active_sidebar_SW_PARADISE('top')) {?>
					<div id="sidebar-top" class="sidebar-top">
						<?php dynamic_sidebar('top'); ?>
					</div>
					<?php }?>
				</div>
				<div class="sw-paradise-logo col-md-4 col-sm-4 col-xs-12">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>">
						<?php if(sw_paradise_options()->getCpanelValue('sitelogo')){ ?>
						<img src="<?php echo esc_attr( sw_paradise_options()->getCpanelValue('sitelogo') ); ?>" alt="<?php bloginfo('name'); ?>"/>
						<?php }else{
							if ($sw_paradise_colorset){$logo = get_template_directory_uri().'/assets/img/logo-'.$sw_paradise_colorset.'.png';}
							else $logo = get_template_directory_uri().'/assets/img/logo-default.png';
							?>
							<img src="<?php echo esc_attr( $logo ); ?>" alt="<?php bloginfo('name'); ?>"/>
							<?php } ?>
						</a>
					</div>
					<div id="sidebar-search" class="header-right-search col-md-4 col-sm-4 col-xs-12">
						<?php get_template_part( 'widgets/sw_paradise_top/search' ); ?>
					</div>
				</div>
			</div>
			<div class="header-bottom">
				<div class="container">
					<?php if ( has_nav_menu('primary_menu') ) { ?>
					<!-- Primary navbar -->
					<div id="main-menu" class="main-menu clearfix">
						<nav id="primary-menu" class="primary-menu">
							<div class="mid-header clearfix">
								<a href="#" class="phone-icon-menu"></a>
								<div class="navbar-inner navbar-inverse">
									<?php
									$sw_paradise_menu_class = 'nav nav-pills';
									if ( 'mega' == sw_paradise_options()->getCpanelValue('menu_type') ){
										$sw_paradise_menu_class .= ' nav-mega';
									} else $sw_paradise_menu_class .= ' nav-css';
									?>
									<?php wp_nav_menu(array('theme_location' => 'primary_menu', 'menu_class' => $sw_paradise_menu_class)); ?>
								</div>
							</div>
						</nav>
					</div>
				</div>
			</div>
			<!-- /Primary navbar -->
			<?php } ?>
		</div>
	</header>
