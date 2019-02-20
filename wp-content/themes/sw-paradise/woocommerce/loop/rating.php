<?php
/**
 * Loop Rating
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/rating.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $product;

if ( get_option( 'woocommerce_enable_review_rating' ) == 'no' )
	return;
global $product, $post, $wpdb, $average;
$count = $wpdb->get_var($wpdb->prepare("
	SELECT COUNT(meta_value) FROM $wpdb->commentmeta
	LEFT JOIN $wpdb->comments ON $wpdb->commentmeta.comment_id = $wpdb->comments.comment_ID
	WHERE meta_key = 'rating'
	AND comment_post_ID = %d
	AND comment_approved = '1'
	AND meta_value > 0
",$post->ID));

$rating = $wpdb->get_var($wpdb->prepare("
	SELECT SUM(meta_value) FROM $wpdb->commentmeta
	LEFT JOIN $wpdb->comments ON $wpdb->commentmeta.comment_id = $wpdb->comments.comment_ID
	WHERE meta_key = 'rating'
	AND comment_post_ID = %d
	AND comment_approved = '1'
",$post->ID));
?>
<div class="reviews-content">
	<?php
		if( $count > 0 ){
			$average = number_format($rating / $count, 1);
	?>
		<div class="star"><span style="width: <?php echo ($average*13).'px'; ?>"></span></div>
		
	<?php } else { ?>
	
		<div class="star"></div>
		
	<?php } ?>
</div>