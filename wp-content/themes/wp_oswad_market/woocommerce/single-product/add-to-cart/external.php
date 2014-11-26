<?php
/**
 * External product add to cart
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
global $smof_data;
if( (int)$smof_data['wd_prod_price'] == 1 )
	add_action('woocommerce_before_add_to_cart_button','woocommerce_template_single_price',1);
?>
<?php do_action('woocommerce_before_add_to_cart_button'); ?>

<p class="cart"><a href="<?php echo esc_url( $product_url ); ?>" rel="nofollow" class="single_add_to_cart_button button alt "><?php echo $button_text; ?></a></p>

<?php do_action('woocommerce_after_add_to_cart_button'); 
	  remove_action('woocommerce_before_add_to_cart_button','woocommerce_template_single_price',1);
?>