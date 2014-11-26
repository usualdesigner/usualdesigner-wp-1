<?php
/**
 * Cart Page
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $woocommerce;

wc_print_notices();
?>

<?php do_action( 'woocommerce_before_cart' ); ?>

<form action="<?php echo esc_url( WC()->cart->get_cart_url() ); ?>" method="post">

<?php do_action( 'woocommerce_before_cart_table' ); ?>

<table class="shop_table cart" cellspacing="0">
	<thead>
		<tr>
			<th colspan="2" class="product-thumbnail product-name first"><?php _e( 'Items', 'wpdance' ); ?></th>
			<th class="product-price"><?php _e( 'Price', 'wpdance' ); ?></th>
			<th class="product-quantity"><?php _e( 'Quantity', 'wpdance' ); ?></th>
			<th class="product-subtotal"><?php _e( 'Total', 'wpdance' ); ?></th>
			<th class="product-remove last"><?php _e( 'Remove', 'wpdance' ); ?></th>
		</tr>
	</thead>
	<tbody>
		<?php do_action( 'woocommerce_before_cart_contents' ); ?>

		<?php
		if ( sizeof( WC()->cart->get_cart() ) > 0 ) {
		
			$showed_item = 0;
			$number_item = count(WC()->cart->get_cart());
		
			foreach ( WC()->cart->get_cart() as $cart_item_key => $values ) {
				$_product = $values['data'];
				$showed_item++;
				if($number_item>1){
					$class_row = ($showed_item==1)?" first":(($showed_item==$number_item)?" last":"");
				}
				else{
					$class_row = " first last";
				}
				
				if ( $_product->exists() && $values['quantity'] > 0 ) {
					?>
					<tr class = "<?php echo esc_attr( apply_filters('woocommerce_cart_table_item_class', 'cart_table_item', $values, $cart_item_key ) ); echo $class_row ?>">

						<!-- The thumbnail -->
						<td class="product-thumbnail">
							<?php
								$thumbnail = apply_filters( 'woocommerce_in_cart_product_thumbnail', $_product->get_image(), $values, $cart_item_key );
								if ( ! $_product->is_visible() || ( ! empty( $_product->variation_id ) && ! $_product->parent_is_visible() ) )
									echo $thumbnail;
								else
									printf('<a class="image" href="%s">%s</a>', esc_url( get_permalink( apply_filters('woocommerce_in_cart_product_id', $values['product_id'] ) ) ), $thumbnail );
							?>
						</td>
						<td class="product-title"><?php
								if ( ! $_product->is_visible() || ( ! empty( $_product->variation_id ) && ! $_product->parent_is_visible() ) )
									echo apply_filters( 'woocommerce_in_cart_product_title', $_product->get_title(), $values, $cart_item_key );
								else
									printf('<a href="%s">%s</a>', esc_url( get_permalink( apply_filters('woocommerce_in_cart_product_id', $values['product_id'] ) ) ), apply_filters('woocommerce_in_cart_product_title', $_product->get_title(), $values, $cart_item_key ) );

								// Meta data
								echo WC()->cart->get_item_data( $values );

                   				// Backorder notification
                   				if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $values['quantity'] ) )
                   					echo '<p class="backorder_notification">' . __( 'Available on backorder', 'wpdance' ) . '</p>';
							
								echo '<span class="wd_product_number"> '.apply_filters( 'woocommerce_checkout_item_quantity', '<span class="product-quantity">&times; ' . $values['quantity'] . '</span>', $values, $cart_item_key ).'</span>';
								echo ((strlen($_product->post->post_excerpt)>0)?'<p class="wd_product_excerpt">'.string_limit_words($_product->post->post_excerpt,10).'...</p>':' ');
							?>
						

						<!-- Product Name -->
								
						</td>

						<!-- Product price -->
						<td class="product-price">
							<?php
								$product_price = get_option('woocommerce_tax_display_cart') == 'excl' ? $_product->get_price_excluding_tax() : $_product->get_price_including_tax();

								echo apply_filters('woocommerce_cart_item_price_html', wc_price( $product_price ), $values, $cart_item_key );
							?>
						</td>

						<!-- Quantity inputs -->
						<td class="product-quantity">
							<?php
								if ( $_product->is_sold_individually() ) {
									$product_quantity = sprintf( '1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key );
								} else {

									$product_quantity = woocommerce_quantity_input( array(
										'input_name'  => "cart[{$cart_item_key}][qty]",
										'input_value' => $values['quantity'],
										'max_value'   => $_product->backorders_allowed() ? '' : $_product->get_stock_quantity(),
									), $_product, false );
								}

								echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key );
							?>
						</td>

						<!-- Product subtotal -->
						<td class="product-subtotal">
							<?php
								echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $values['quantity'] ), $values, $cart_item_key );
							?>
						</td>
						
						<td class="remove-product">
						<?php 
							//Remove from cart link
							echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf('<a href="%s" class="remove" title="%s">&times;</a>', esc_url( WC()->cart->get_remove_url( $cart_item_key ) ), __( 'Remove this item', 'wpdance' ) ), $cart_item_key );
						?>
						</td>			
						
					</tr>
					<?php
				}
			}
		}

		do_action( 'woocommerce_cart_contents' );
		?>
		<tr>
			<td colspan="6" class="actions">
			<input type="submit" class="button" name="update_cart" value="<?php _e( 'Update Cart', 'wpdance' ); ?>" /> 
			<a class="button bt_back_to_shop" href="<?php echo get_permalink( wc_get_page_id( 'shop' ) ); ?>"><?php _e( 'Back To Shop', 'wpdance' ) ?></a>
			<!--<p><a class="button" href="<?php //echo get_permalink(wc_get_page_id('shop')); ?>"><?php //_e( '&larr; Return To Shop', 'woocommerce' ) ?></a></p>-->
			<!--<input type="submit" class="checkout-button button alt" name="proceed" value="<?php //_e( 'Proceed to Checkout', 'wpdance' ); ?>" /> -->
			<?php do_action('woocommerce_proceed_to_checkout'); ?>

			<?php wp_nonce_field( 'woocommerce-cart' ); ?>	
			
			</td>
		</tr>

		<?php do_action( 'woocommerce_after_cart_contents' ); ?>
	</tbody>
</table>

<?php do_action( 'woocommerce_after_cart_table' ); ?>

</form>

<div class="cart-collaterals">
	
	<form action="<?php echo esc_url( WC()->cart->get_cart_url() ); ?>" method="post">	
		<div class="coupon_wrapper">
		
				<?php if ( WC()->cart->coupons_enabled() ) { ?>
					<div class="coupon">
						<div class="wd_title_cart"><h2 for="coupon_code" class="heading-title"><?php _e( 'Discount code', 'wpdance' ); ?></h2></div>
						<div class="content_coupon">
							<p><?php _e( 'Enter your coupon code if your have one', 'wpdance' ); ?></p>
							<input name="coupon_code" class="input-text" id="coupon_code" value="" /> 
							<input type="submit" class="button" name="apply_coupon" value="<?php _e( 'Apply Coupon', 'wpdance' ); ?>" />
						</div>

						<?php do_action('woocommerce_cart_coupon'); ?>

					</div>
				<?php } ?>

		</div>
	</form>
	
	<?php woocommerce_shipping_calculator(); ?>	
	
	<?php woocommerce_cart_totals(); ?>

	<?php do_action('woocommerce_cart_collaterals'); ?>

</div>

<?php do_action( 'woocommerce_after_cart' ); ?>