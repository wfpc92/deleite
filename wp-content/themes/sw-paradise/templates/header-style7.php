<?php 
$sw_paradise_colorset      = sw_paradise_options()->getCpanelValue('scheme');
$sw_paradise_header_style  = sw_paradise_options()->getCpanelValue('header_style');
$sw_paradise_my_store2   = sw_paradise_options()->getCpanelValue('my_store2');
$sw_paradise_my_phone  = sw_paradise_options()->getCpanelValue('phone_number');
?>
<header id="header" class="header">
	<div class="top-header">
		<div class="sw-paradise-header clearfix">
			<div class="container">
				
				<?php if($sw_paradise_my_store2 != '' || $sw_paradise_my_phone != '') { ?>
				<div class="sw_paradise_left col-md-6 col-sm-6 col-xs-6">
					<div class="main-item">
						<i class="fa fa-map-marker"></i><span><?php echo esc_html($sw_paradise_my_store2 ); ?></span>
					</div>
					<div class="main-item">
						<i class="fa fa-envelope"></i><span><?php echo esc_html($sw_paradise_my_store2 ); ?></span>
					</div>
					<div class="main-item">
						<i class="fa fa-phone"></i><span><?php echo esc_html($sw_paradise_my_phone ); ?></span>
					</div>
				</div>
				<?php } ?>
				
				<div class="sw_paradise_right col-md-6 col-sm-6 col-xs-6">
					
					<div class="sw-paradise-account pull-right">
						<?php if (is_active_sidebar_SW_PARADISE('top-right')) {?>
						<div class="sw-paradise-account-info pull-right">
							<span class="title-account"><?php echo esc_html__( 'My account','sw-paradise');?></span>						
							<div id="sidebar-top-right" class="sidebar-top-right">
								<?php dynamic_sidebar('top-right'); ?>
							</div>					
						</div>
						<?php }?>
					</div>
					
					<?php if (is_active_sidebar_SW_PARADISE('top')) {?>
						<div class="sw-paradise-lang pull-right">					
							<div id="sidebar-top" class="sidebar-top">
								<?php dynamic_sidebar('top'); ?>
							</div>					
						</div>
					<?php }?>
				
				</div>
			</div>
		</div>
		<div class="header-bottom clearfix">
			<div class="container">
				<div class="row">
					<div class="sw-paradise-logo col-md-3 col-sm-12 col-xs-12">
						<a href="<?php echo esc_url( home_url( '/' ) ); ?>">
							<?php if(sw_paradise_options()->getCpanelValue('sitelogo_transparent')){ ?>
							<img src="<?php echo esc_attr( sw_paradise_options()->getCpanelValue('sitelogo_transparent') ); ?>" alt="<?php bloginfo('name'); ?>"/>
							<?php }else{
								if ($sw_paradise_colorset){$logo = get_template_directory_uri().'/assets/img/logo-'.$sw_paradise_colorset.'.png';}
								else $logo = get_template_directory_uri().'/assets/img/logo-default2.png';
								?>
								<img src="<?php echo esc_url( $logo ); ?>" alt="<?php bloginfo('name'); ?>"/>
								<?php } ?>
							</a>
						</div>

						<?php if ( has_nav_menu('primary_menu') ) { ?>
						<div id="main-menu" class="main-menu col-md-7 col-sm-8 col-xs-6">
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
							</div>
						</div>
						<?php } ?>
						
						<?php if( class_exists( 'WooCommerce' ) ) : ?>
							<div class="sw-paradise-account  pull-right">
								<div class="sw_paradise_cart pull-right">
									<?php get_template_part( 'woocommerce/minicart-ajax' ); ?>
								</div>
							</div>
							<?php endif; ?>
							
							<div id="sidebar-search" class="header-right-search">
								<div class="icon-search">
									<i class="fa fa-search"></i>
								</div>
								<?php get_template_part( 'widgets/sw_paradise_top/search' ); ?>
							</div>
					</div>
				</div>
			</div>
		</div>
	</header>