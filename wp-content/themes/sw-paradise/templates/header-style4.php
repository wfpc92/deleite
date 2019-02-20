<?php 
$sw_paradise_colorset      = sw_paradise_options()->getCpanelValue('scheme');
$sw_paradise_header_style  = sw_paradise_options()->getCpanelValue('header_style');
$sw_paradise_my_delivery   = sw_paradise_options()->getCpanelValue('my_delivery');
?>
<header id="header" class="header">
	<?php if (is_active_sidebar_SW_PARADISE('slider-home-end')){ ?>
	<div class="home-slider5">
		<?php dynamic_sidebar('slider-home-end'); ?>
	</div>
	<?php } ?>
	
	<div class="top-header">
		<div class="sw-paradise-header clearfix">
			<div class="container">
				<?php if (is_active_sidebar_SW_PARADISE('top')) {?>
				<div class="sw-paradise-lang col-md-4 col-sm-4 col-xs-12">					
					<div id="sidebar-top" class="sidebar-top">
						<?php dynamic_sidebar('top'); ?>
					</div>					
				</div>
				<?php }?>

				<?php if($sw_paradise_my_delivery != '') { ?>
				<div class="sw_paradise_delivery col-md-4 col-sm-4 col-xs-12">
					<div class="main-delivery">
						<span><?php echo esc_html($sw_paradise_my_delivery ); ?></span>
					</div>
				</div>
				<?php } ?>
				
				<?php if( class_exists( 'WooCommerce' ) ) : ?>
				<div class="sw-paradise-account col-md-4 col-sm-4 col-xs-12 pull-right">
				
					<?php if (is_active_sidebar_SW_PARADISE('top-right')) {?>
					<div class="sw-paradise-account-info pull-right">
						<i class="fa fa-user" aria-hidden="true"></i>						
						<div id="sidebar-top-right" class="sidebar-top-right">
							<?php dynamic_sidebar('top-right'); ?>
						</div>						
					</div>
					<?php }?>

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
				</div> 
				<?php endif; ?>
			</div>
		</div>
		<div class="header-bottom clearfix">
			<div class="container">
				<div class="row">
					<div class="sw-paradise-logo col-md-3 col-sm-3 col-xs-12">
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

						<?php if ( has_nav_menu('primary_menu') ) { ?>
						<div id="main-menu" class="main-menu col-md-9 col-sm-9 col-xs-12">
							<div class="container">
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
								<div id="sidebar-search" class="header-right-search">
									<div class="icon-search">
										<i class="fa fa-search"></i>
									</div>
									<?php get_template_part( 'widgets/sw_paradise_top/search' ); ?>
								</div>
							</div>
						</div>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>
	</header>



