<?php 
$sw_paradise_colorset      = sw_paradise_options()->getCpanelValue('scheme');
$sw_paradise_header_style  = sw_paradise_options()->getCpanelValue('header_style');
$sw_paradise_sale      = sw_paradise_options()->getCpanelValue('my_sale');
$sw_paradise_phone     = sw_paradise_options()->getCpanelValue('phone_number');
?>
<header id="header" class="header">
	<div class="top-header">
		<div class="sw-paradise-header clearfix">
			<div class="hide-header"></div>
			<div class="container">
				<?php if($sw_paradise_sale != '') { ?>
					<div class="sw_paradise_sale">
						<div class="main-sale">
							<span><?php echo esc_html($sw_paradise_sale ); ?></span>
						</div>
					</div>
				<?php } ?>
			</div>
		</div>
		<div class="sw-paradise-center container">
			<div class="row">			
				<div class="sw-paradise-left col-md-4 col-sm-4 col-xs-12 pull-left">
					<div id="sidebar-search" class="header-right-search pull-left">
						<div class="icon-search">
							<i class="fa fa-search"></i>
						</div>
						<?php get_template_part( 'widgets/sw_paradise_top/search' ); ?>
					</div>				
					<?php if (is_active_sidebar_SW_PARADISE('top-right')) {?>
						<div class="sw-paradise-account-info pull-left">
							<i class="fa fa-user" aria-hidden="true"></i>						
							<div id="sidebar-top-right" class="sidebar-top-right">
								<?php dynamic_sidebar('top-right'); ?>
							</div>						
					</div>
					<?php }?>
					<?php if (is_active_sidebar_SW_PARADISE('top-currency')) {?>
						<div class="sw-paradise-lang pull-left">					
							<div id="sidebar-top" class="sidebar-top">
								<?php dynamic_sidebar('top-currency'); ?>
							</div>					
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
					
				<?php if( class_exists( 'WooCommerce' ) ) : ?>				
				<div class="sw-paradise-right col-md-4 col-sm-4 col-xs-12 pull-right">
					<div class="sw_paradise_cart pull-right">
						<?php get_template_part( 'woocommerce/minicart-ajax' ); ?>
					</div>					
					
					<?php if($sw_paradise_phone != '') { ?>
						<div class="sw_paradise_phone pull-right">
							<div class="main-phone">
								<i class="fa fa-mobile" aria-hidden="true"></i><span><?php echo esc_html($sw_paradise_phone ); ?></span>
							</div>
						</div>
					<?php } ?>
				</div> 
				<?php endif; ?>
				
				</div>
			</div>
			
			<div class="header-bottom">
				<?php if ( has_nav_menu('primary_menu') ) { ?>
				<!-- Primary navbar -->
				<div id="main-menu" class="main-menu clearfix">
					<div class="container">
						<div class="sw-paradise-center sticky-search pull-left">
							<div class="sw-paradise-left">
								<div  class="header-right-search">
									<div class="icon-search">
										<i class="fa fa-search"></i>
									</div>
									<?php get_template_part( 'widgets/sw_paradise_top/search' ); ?>
								</div>				
							</div>
						</div>
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
						<?php if( class_exists( 'WooCommerce' ) ) : ?>
						<div class="sw-paradise-center sticky-cart pull-right">
							<div class="sw-paradise-right pull-right">
								<div class="sw_paradise_cart pull-right">
									<?php get_template_part( 'woocommerce/minicart-ajax' ); ?>
								</div>					
							</div> 
						</div>
						<?php endif; ?>
					</div>
				</div>
			</div>
			<!-- /Primary navbar -->
			<?php } ?>
		</div>
	</header>



