<?php
/**
 * Plugin Name: SW Portfolio
 * Plugin URI: http://smartaddons.com
 * Description: A plugin developed for portfolio item.
 * Version: 1.0
 * Author: smartaddons.com
 * Author URI: http://smartaddons.com
 *
 * This Widget help you to show images of product as a beauty reponsive slider
 */
require_once( plugin_dir_path( __FILE__ ) . 'taxonomy_portfolio.php' );
require_once( plugin_dir_path( __FILE__ ) . 'resize.php' );

class Sw_Portfolio{
	public $id = 1;
	function __construct(){
		/* Register postype and taxonomy */
		add_action( 'init', array( $this, 'portfolio_register' ), 5 );
		
		/* Add template for archive and single portfolio */
		add_filter( 'template_include', array( $this, 'Portfolio_Template_Loader' ) );
		
		/* Add shortcode portfolio */
		add_shortcode( 'portfolio', array( $this, 'P_Shortcode' ) );
		
		/* Add Script */
		add_action( 'wp_enqueue_scripts', array( $this, 'Portfolio_Script' ) );
		
		/* Create Vc_map */
		if (class_exists('Vc_Manager')) {
			add_action( 'vc_before_init', array( $this, 'P_integrateWithVC' ), 1000 );
		}
		
		/* Add ajax */
		add_action( 'wp_ajax_sw_portfolio_ajax', array( $this, 'sw_portfolio_ajax') );
		add_action( 'wp_ajax_nopriv_sw_portfolio_ajax', array( $this, 'sw_portfolio_ajax') );
	}
	
	/* Register postype and taxonomy */
	function portfolio_register() {
		$labels = array(
			'name' => _x('Portfolio', 'post type general name'),
			'singular_name' => _x('Portfolio Item', 'post type singular name'),
			'add_new' => _x('Add New', 'Portfolio item'),
			'add_new_item' => __('Add New Portfolio Item'),
			'edit_item' => __('Edit Portfolio Item'),
			'new_item' => __('New Portfolio Item'),
			'view_item' => __('View Portfolio Item'),
			'search_items' => __('Search Portfolio'),
			'not_found' =>  __('Nothing found'),
			'not_found_in_trash' => __('Nothing found in Trash'),
			'parent_item_colon' => ''
		);

		$args = array(
			'labels' => $labels,
			'public' => true,
			'has_archive' => true,
			'publicly_queryable' => true,
			'show_ui' => true,
			'query_var' => true,
			'menu_icon' => 'dashicons-images-alt',
			'rewrite' =>  true,
			'capability_type' => 'post',
			'hierarchical' => true,			
			'menu_position' => 4,
			'supports' => array( 'title','thumbnail', 'editor', 'author', 'revisions', 'comments' )
		  );

		register_post_type( 'portfolio' , $args );

		register_taxonomy("portfolio_cat", array("portfolio"), array("hierarchical" => true, "label" => "Categories Portfolio", "singular_label" => "Category Portfolio", 'rewrite' => true));
		register_taxonomy('portfolio_tag', 'portfolio', array(
			'hierarchical' => false, 
			'label' => "Tags", 
			'singular_name' => "Tag", 
			'rewrite' => true, 
			'query_var' => true
			)
		);
	}
	/* End register postype and taxonomy */
	
	/* Add template for archive and single portfolio */
	public function Portfolio_Template_Loader( $template ){			
		if ( is_single() && get_post_type() == 'portfolio' ) {				
			$template = dirname(__FILE__) . '/templates/single-portfolio.php'; 
		}
		return $template;
	}
	/* End add template for archive and single portfolio */
	public function Get_Pcat(){
		$terms = get_terms( 'portfolio_cat', array( 'parent' => 0 ) );
		$term = array();
		if( count( $terms ) > 0 ){
			foreach( $terms as $cat ){
				$term[$cat->name] = $cat -> term_id;
			}
		}
		return $term;
	}
	/* Add Script */
	public function Portfolio_Script(){
		wp_register_script( 'isotope_script', plugins_url( 'js/isotope.js', __FILE__ ),array(), null, true );
		wp_enqueue_script( 'isotope_script' );
		wp_register_script( 'portfolio_script', plugins_url( 'js/portfolio.js', __FILE__ ),array(), null, true );
		wp_localize_script( 'portfolio_script', 'ya_portfolio', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
		wp_enqueue_script( 'portfolio_script' );			
	}
	/* End Add Script */
	
	/* Add shortcode portfolio */
	function P_Shortcode( $atts, $content = null ){
		extract( shortcode_atts(
			array(
				'title' 		=> '',
				'description' 	=> '',
				'portfolio_id' 	=> '',
				'orderby' 		=> '',
				'order'			=> '',
				'number' 		=> 5,
				'col1' 			=> 4,
				'col2' 			=> 4,
				'col3' 			=> 3,
				'col4' 			=> 2,						
				'style'  		=> 'fitRows',
				'show_tab'		=> 'yes',
				'show_loadmore'	=> 'yes'
			), $atts )
		);		
		$this->id ++;
		$pf_id = 'ya_portfolio_' . $this->id;
		$portfolio = array();
		if( !is_array( $portfolio_id ) ){
			$portfolio = explode( ',', $portfolio_id );
		}
		ob_start();
		include( 'templates/portfolio-item.php' );
		$content = ob_get_clean();
		return $content;
		//ob_start();
	}
	/* End add shortcode portfolio */
	
	/* Create Vc_map */
	function P_integrateWithVC(){	
		vc_map( array(
		  "name" => __( "Portfolio", 'sw_core' ),
		  "base" => "portfolio",
		  "icon" => "icon-wpb-ytc",
		  "class" => "",
		  "category" => __( "SW Core", 'sw_core'),
		  "params" => array(
			 array(
				"type" => "textfield",
				"holder" => "div",
				"class" => "",
				"heading" => __( "Title", 'sw_core' ),
				"param_name" => "title",
				"value" => '',
				"description" => __( "Title", 'sw_core' )
			 ),
			 array(
				"type" => "textfield",
				"holder" => "div",
				"class" => "",
				"heading" => __( "Description", 'sw_core' ),
				"param_name" => "description",
				"value" => '',
				"description" => __( "Description", 'sw_core' )
			 ),
			  array(
				"type" => "checkbox",
				"holder" => "div",
				"class" => "",
				"heading" => __( "Category", 'sw_core' ),
				"param_name" => "portfolio_id",
				"value" => $this->Get_Pcat(),
				"description" => __( "Select Categories", 'sw_core' )
			 ),
			 array(
				"type" => "dropdown",
				"holder" => "div",
				"class" => "",
				"heading" => __( "Order By", 'sw_core' ),
				"param_name" => "orderby",
				"value" => array('Name' => 'name', 'Author' => 'author', 'Date' => 'date', 'Title' => 'title', 'Modified' => 'modified', 'Parent' => 'parent', 'ID' => 'ID', 'Random' =>'rand', 'Comment Count' => 'comment_count'),
				"description" => __( "Order By", 'sw_core' )
			 ),
			 array(
				"type" => "dropdown",
				"holder" => "div",
				"class" => "",
				"heading" => __( "Order", 'sw_core' ),
				"param_name" => "order",
				"value" => array('Descending' => 'DESC', 'Ascending' => 'ASC'),
				"description" => __( "Order", 'sw_core' )
			 ),
			 array(
				"type" => "textfield",
				"holder" => "div",
				"class" => "",
				"heading" => __( "Number Of Post", 'sw_core' ),
				"param_name" => "number",
				"value" => 5,
				"description" => __( "Number Of Post", 'sw_core' )
			 ),
			 array(
				"type" => "dropdown",
				"holder" => "div",
				"class" => "",
				"heading" => __( "Number of Columns >1200px: ", 'sw_core' ),
				"param_name" => "col1",
				"value" => array(1,2,3,4,5,6),
				"description" => __( "Number of Columns >1200px:", 'sw_core' )
			 ),
			 array(
				"type" => "dropdown",
				"holder" => "div",
				"class" => "",
				"heading" => __( "Number of Columns on 992px to 1199px:", 'sw_core' ),
				"param_name" => "col2",
				"value" => array(1,2,3,4,5,6),
				"description" => __( "Number of Columns on 992px to 1199px:", 'sw_core' )
			 ),
			 array(
				"type" => "dropdown",
				"holder" => "div",
				"class" => "",
				"heading" => __( "Number of Columns on 768px to 991px:", 'sw_core' ),
				"param_name" => "col3",
				"value" => array(1,2,3,4,5,6),
				"description" => __( "Number of Columns on 768px to 991px:", 'sw_core' )
			 ),
			 array(
				"type" => "dropdown",
				"holder" => "div",
				"class" => "",
				"heading" => __( "Number of Columns on 480px to 767px:", 'sw_core' ),
				"param_name" => "col4",
				"value" => array(1,2,3,4,5,6),
				"description" => __( "Number of Columns on 480px to 767px:", 'sw_core' )
			 ),
			 array(
				"type" => "dropdown",
				"holder" => "div",
				"class" => "",
				"heading" => __( "Number of Columns in 480px or less than:", 'sw_core' ),
				"param_name" => "col5",
				"value" => array(1,2,3,4,5,6),
				"description" => __( "Number of Columns in 480px or less than:", 'sw_core' )
			 ),			 
			  array(
				"type" => "dropdown",
				"holder" => "div",
				"class" => "",
				"heading" => __( "Layout", 'sw_core' ),
				"param_name" => "style",
				"value" => array( 'FitRows' => 'fitRows', 'Masonry' => 'masonry' ),
				"description" => __( "Layout", 'sw_core' )
			 ),			 
			  array(
				"type" => "dropdown",
				"holder" => "div",
				"class" => "",
				"heading" => __( "Show Tab", 'sw_core' ),
				"param_name" => "show_tab",
				"value" => array( 'Yes' => 'yes', 'No' => 'no' ),
				"description" => __( "Show Tab Filter", 'sw_core' )
			 ),			 
			  array(
				"type" => "dropdown",
				"holder" => "div",
				"class" => "",
				"heading" => __( "Show button loadmore", 'sw_core' ),
				"param_name" => "show_loadmore",
				"value" => array( 'Yes' => 'yes', 'No' => 'no' ),
				"description" => __( "Show button loadmore", 'sw_core' )
			 )
		  )
	   ) );
	}
	/* End Create Vc_map */
	
	/* Add ajax */
	function sw_portfolio_ajax(){
		$catid 		= (isset($_POST["catid"])   && $_POST["catid"] != '' ) ? $_POST["catid"] : '';
		$page 		= (isset($_POST["page"])    && $_POST["page"]> 0 ) ? $_POST["page"] : 0;
		$attributes = (isset($_POST["attributes"])    && $_POST["attributes"] != '' ) ? $_POST["attributes"] : '';
		$number 	= (isset($_POST["numb"])    && $_POST["numb"]>0) ? $_POST["numb"] : 0;
		$orderby 	= (isset($_POST["orderby"]) && $_POST["orderby"] != '') ? $_POST["orderby"] : '';
		$order 		= (isset($_POST["order"]) && $_POST["order"] != '') ? $_POST["order"] : '';
		$style 		= (isset($_POST["style"]) && $_POST["style"] != '') ? $_POST["style"] : '';
		$paged 		= (get_query_var('paged')) ? get_query_var('paged') : 1;
		$categories = explode( ',', $catid );
		$args = array(
			'post_type'		=> 'portfolio',
			'tax_query'		=> array(
				array(
					'taxonomy'	=> 'portfolio_cat',
					'field'		=> 'term_id',
					'terms' 		=> $categories
				)
			),				
			'posts_per_page' => $number,
			'orderby'		 => $orderby,
			'order'			 => $order,
			'offset'		 => $number*$page
		);
		$output = '';
		$query = new wp_query( $args ); 
		while( $query -> have_posts() ) : $query -> the_post();
		global $post;
		$img_size  	= get_post_meta( $post->ID, 'img_size', true );
		$pterms	   	= get_the_terms( $post->ID, 'portfolio_cat' );
		$width		= 0;
		$height 	= 0;
		if( $img_size == 'default' ){
			$width 	= 400;
			$height = 270;
		}else if( $img_size == 'p-double-width' ){
			$width 	= 682;
			$height = 230;
		}else if( $img_size == 'p-double-wh' ){
			$width 	= 800;
			$height = 540;
		}
		$term_str = '';
		foreach( $pterms as $key => $term ){
			$term_str .= $term -> slug. ' ';
		}
		$img = '';
		if( $style == 'masonry' ){
			?>
				<li class="portfolio-item <?php echo $attributes.' '.esc_attr( $term_str ). ' '. esc_attr( $img_size ); ?>">
					<div class="portfolio-item-inner">
						<div class="portfolio-in">
							<?php 
								if( has_post_thumbnail() ){
									$img = wp_get_attachment_url( get_post_thumbnail_id( $post->ID ) );
								}
							?>
							<a class="portfolio-img" href="<?php the_permalink() ?>" title="<?php the_title_attribute(); ?>">
							<?php the_post_thumbnail( 'large' ); ?>
							</a>
							<div class="p-item-content">
								<a class="p-item-title" href="<?php the_permalink() ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a>
								<a href="<?php the_permalink(); ?>" class="p-item item-more" title="<?php the_title_attribute(); ?>"><span class="fa fa-link"></span></a>
								<a href="<?php echo esc_attr( $img ); ?>" class="p-item item-popup" title="<?php the_title_attribute(); ?>"><span class="fa fa-search"></span></a>
							</div>
						</div>
					</div>
				</li>
			<?php } elseif( $style == 'fitRows') { ?>					
				<li class="portfolio-item <?php echo $attributes.' '.esc_attr( $term_str ); ?>">
					<div class="portfolio-item-inner">
						<div class="portfolio-in">
							<?php 
								if( has_post_thumbnail() ){
									$img = wp_get_attachment_url( get_post_thumbnail_id( $post->ID ) );
								}
							?>
							<a class="portfolio-img" href="<?php the_permalink() ?>" title="<?php the_title_attribute(); ?>">
							<?php the_post_thumbnail( 'large' ); ?>
							</a>
							<div class="p-item-content">
								<a class="p-item-title" href="<?php the_permalink() ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a>
								<a href="<?php the_permalink(); ?>" class="p-item item-more" title="<?php the_title_attribute(); ?>"><span class="fa fa-link"></span></a>
								<a href="<?php echo esc_attr( $img ); ?>" class="p-item item-popup" title="<?php the_title_attribute(); ?>"><span class="fa fa-search"></span></a>
							</div>
						</div>
					</div>
				</li>
		<?php } else { ?> 
				<li class="portfolio-item col-lg-4 col-md-4 col-sm-6 col-xs-12">
					<div class="portfolio-item-inner">
						<div class="portfolio-in">
							<?php 
								if( has_post_thumbnail() ){
									$img = wp_get_attachment_url( get_post_thumbnail_id( $post->ID ) );
								}
							?>
							<a class="portfolio-img" href="<?php the_permalink() ?>" title="<?php the_title_attribute(); ?>">
							<?php the_post_thumbnail( 'large' ); ?>
							</a>
							<div class="p-item-content">
								<a class="p-item-title" href="<?php the_permalink() ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a>
								<a href="<?php the_permalink(); ?>" class="p-item item-more" title="<?php the_title_attribute(); ?>"><span class="fa fa-link"></span></a>
								<a href="<?php echo esc_attr( $img ); ?>" class="p-item item-popup" title="<?php the_title_attribute(); ?>"><span class="fa fa-search"></span></a>
							</div>
						</div>
					</div>
				</li>
		<?php
			}
		endwhile;
		wp_reset_postdata();
		exit();
	}
}
new Sw_Portfolio();