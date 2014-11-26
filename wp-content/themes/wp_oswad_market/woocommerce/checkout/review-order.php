<?php
/**
 * Review order form
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.1.8
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $woocommerce;

//$available_methods = $woocommerce->shipping->get_available_shipping_methods();
?>
<?php if ( ! is_ajax() ) : ?><div id="order_review"><?php endif; ?>

	<table class="shop_table">
		<thead>
			<tr>
				<th colspan="2" class="product-thumbnail product-name first"><?php _e( 'Items', 'wpdance' ); ?></th>
				<th class="product-price"><?php _e( 'Price', 'wpdance' ); ?></th>
				<th class="product-total last"><?php _e( 'Total', 'wpdance' ); ?></th>
			</tr>
		</thead>
		<tfoot>
			<tr class="cart-subtotal">
				<th colspan="3"><?php _e( 'Subtotal', 'wpdance' ); ?></th>
				<td><?php wc_cart_totals_subtotal_html(); ?></td>
			</tr>

			<?php foreach ( WC()->cart->get_coupons( 'cart' ) as $code => $coupon ) : ?>
				<tr class="discount cart-discount coupon-<?php echo esc_attr( $code ); ?>">
					<th colspan="3"><?php wc_cart_totals_coupon_label( $coupon ); ?></th>
					<td><?php wc_cart_totals_coupon_html( $coupon ); ?></td>
				</tr>
			<?php endforeach; ?>

			<?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>

				<?php do_action('woocommerce_review_order_before_shipping'); ?>
				<?php wc_cart_totals_shipping_html(); ?>
				<?php do_action('woocommerce_review_order_after_shipping'); ?>

			<?php endif; ?>

			<?php foreach ( WC()->cart->get_fees() as $fee ) : ?>

				<tr class="fee fee-<?php echo $fee->id ?>">
					<th colspan="3"><?php echo esc_html( $fee->name ); ?></th>
					<td><?php
						wc_cart_totals_fee_html( $fee );
					?></td>
				</tr>

			<?php endforeach; ?>

			<?php
				// Show the tax row if showing prices exlcusive of tax only
				if ( WC()->cart->tax_display_cart == 'excl' ) {
					if ( get_option( 'woocommerce_tax_total_display' ) === 'itemized' ){
						foreach ( WC()->cart->get_tax_totals() as $code => $tax ) {
							echo '<tr class="tax-rate tax-rate-' . $code . '">
								<th colspan="3">' . esc_html( $tax->label ) . '</th>
								<td>' . wp_kses_post( $tax->formatted_amount ) . '</td>
							</tr>';
						}
					}
					else{
						echo '<tr class="tax-total">
							<th colspan="3">'. esc_html( WC()->countries->tax_or_vat() ).'</th>
							<td>'. wc_price( WC()->cart->get_taxes_total() ). '</td>
						</tr>';
					}
				}
			?>

			<?php foreach ( WC()->cart->get_coupons( 'order' ) as $code => $coupon ) : ?>
				<tr class="discount order-discount coupon-<?php echo esc_attr( $code ); ?>">
					<th colspan="3"><?php wc_cart_totals_coupon_label( $coupon ); ?></th>
					<td><?php wc_cart_totals_coupon_html( $coupon ); ?></td>
				</tr>
			<?php endforeach; ?>

			<?php do_action( 'woocommerce_review_order_before_order_total' ); ?>

			<tr class="total">
				<th colspan="3"><?php _e( 'Order Total', 'wpdance' ); ?></th>
				<td>
					<?php wc_cart_totals_order_total_html(); ?>
				</td>
			</tr>

			<?php do_action( 'woocommerce_review_order_after_order_total' ); ?>

		</tfoot>
		<tbody>
			<?php
				do_action( 'woocommerce_review_order_before_cart_contents' );

				if (sizeof(WC()->cart->get_cart())>0) :
					$number_item = count(WC()->cart->get_cart());
					$showed_item = 0;
					foreach (WC()->cart->get_cart() as $cart_item_key => $values) :
						$_product = $values['data'];
						$showed_item++;
						if($number_item>1){
							$class_row = ($showed_item==1)?" first":(($showed_item==$number_item)?" last":"");
						}
						else{
							$class_row = " first last";
						}
						$class_row .= " checkout_table_item";
						if ($_product->exists() && $values['quantity']>0) :
							$product_price = get_option('woocommerce_tax_display_cart') == 'excl' ? $_product->get_price_excluding_tax() : $_product->get_price_including_tax();
							$product_price = apply_filters('woocommerce_cart_item_price_html', wc_price( $product_price ), $values, $cart_item_key );
							echo '
								<tr class="' . esc_attr( apply_filters('woocommerce_cart_item_class', 'cart_item', $values, $cart_item_key ) ) . $class_row . '">
									<td class="product-thumbnail">' .
										'<a href="'.get_permalink( $values['product_id'] ).'">'
											.$_product->get_image().
										'</a>'.
										
									'</td>
									<td class="product-title">
										<span class="wd_product_title">'.apply_filters( 'woocommerce_cart_item_name', $_product->get_title(), $values, $cart_item_key ) .
										'<span class="wd_product_number">'.apply_filters( 'woocommerce_checkout_cart_item_quantity', ' <strong class="product-quantity"> &times; ' . $values['quantity'] . '</strong>', $values, $cart_item_key ) .'</span></span>'.WC()->cart->get_item_data( $values );
							echo ((strlen($_product->post->post_excerpt)>0)?'<p class="wd_product_excerpt">'.string_limit_words($_product->post->post_excerpt,6).'...</p>':' ');
							echo
									'</td>
									<td class="product-price">'.$product_price.'</td>
									<td class="product-total">' . apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $values['quantity'] ), $values, $cart_item_key ) . '</td>
								</tr>';
						endif;
					endforeach;
				endif;
				
				do_action( 'woocommerce_review_order_after_cart_contents' );
			?>
		</tbody>
	</table>
	<script type="text/javascript">
		jQuery("#order_review table.shop_table tbody tr:first").addClass("first");
		jQuery("#order_review table.shop_table tbody tr:last").addClass("last");
	</script>
	
	<div id="payment">
		<?php if (WC()->cart->needs_payment()) : ?>
		<ul class="payment_methods methods">
			<?php
				$available_gateways = WC()->payment_gateways->get_available_payment_gateways();
				if ( ! empty( $available_gateways ) ) {

					// Chosen Method
					if ( isset( WC()->session->chosen_payment_method ) && isset( $available_gateways[ WC()->session->chosen_payment_method ] ) ) {
						$available_gateways[ WC()->session->chosen_payment_method ]->set_current();
					} elseif ( isset( $available_gateways[ get_option( 'woocommerce_default_gateway' ) ] ) ) {
						$available_gateways[ get_option( 'woocommerce_default_gateway' ) ]->set_current();
					} else {
						current( $available_gateways )->set_current();
					}
					foreach ( $available_gateways as $key => $gateway ) {
						if(checked( $gateway->chosen, true, false )){
							$class_active = "active";
						}
						else{
							$class_active = "";
						}
						?>
						<li class="<?php echo $class_active; ?>">
							<input type="radio" id="payment_method_<?php echo $gateway->id; ?>" class="input-radio" name="payment_method" value="<?php echo esc_attr( $gateway->id ); ?>" <?php checked( $gateway->chosen, true ); ?> />
							<label for="payment_method_<?php echo $gateway->id; ?>"><?php echo $gateway->get_title(); ?> <?php echo $gateway->get_icon(); ?></label>
							<?php
								if ( $gateway->has_fields() || $gateway->get_description() ) :
									echo '<div class="payment_box payment_method_' . $gateway->id . '" ' . ( $gateway->chosen ? '' : 'style="display:none;"' ) . '>';
									$gateway->payment_fields();
									echo '</div>';
								endif;
							?>
						</li>
						<?php
					}
				} else {

					if ( ! WC()->customer->get_country() )
						$no_gateways_message = __( 'Please fill in your details above to see available payment methods.', 'wpdance' );
					else
						$no_gateways_message = __( 'Sorry, it seems that there are no available payment methods for your state. Please contact us if you require assistance or wish to make alternate arrangements.', 'wpdance' );

					echo '<p>' . apply_filters( 'woocommerce_no_available_payment_methods_message', $no_gateways_message ) . '</p>';

				}
			?>
		</ul>
		<?php endif; ?>

		<div class="form-row place-order">

			<noscript><?php _e( 'Since your browser does not support JavaScript, or it is disabled, please ensure you click the <em>Update Totals</em> button before placing your order. You may be charged more than the amount stated above if you fail to do so.', 'wpdance' ); ?><br/><input type="submit" class="button alt" name="woocommerce_checkout_update_totals" value="<?php _e( 'Update totals', 'wpdance' ); ?>" /></noscript>

			<?php wp_nonce_field( 'woocommerce-process_checkout' ); ?>

			<?php do_action( 'woocommerce_review_order_before_submit' ); ?>

			<?php
			$order_button_text = apply_filters('woocommerce_order_button_text', __( 'Place order', 'wpdance' ));
			echo apply_filters('woocommerce_order_button_html', '<input type="submit" class="button alt" name="woocommerce_checkout_place_order" id="place_order" value="' . $order_button_text . '" data-value="' . esc_attr( $order_button_text ) . '" />' );
			?>

			<?php if (wc_get_page_id('terms')>0 && apply_filters( 'woocommerce_checkout_show_terms', true ) ) : ?>
			<?php $terms_is_checked = apply_filters( 'woocommerce_terms_is_checked_default', isset( $_POST['terms'] ) ); ?>
			<p class="form-row terms">
				<label for="terms" class="checkbox"><?php _e( 'I have read and accept the', 'wpdance' ); ?> <a href="<?php echo esc_url( get_permalink(wc_get_page_id('terms')) ); ?>" target="_blank"><?php _e( 'terms &amp; conditions', 'wpdance' ); ?></a></label>
				<input type="checkbox" class="input-checkbox" name="terms" <?php checked( $terms_is_checked, true ); ?> id="terms" />
			</p>
			<?php endif; ?>

			<?php do_action( 'woocommerce_review_order_after_submit' ); ?>

		</div>

		<div class="clear"></div>

	</div>

<?php if ( ! is_ajax() ) : ?></div><?php endif; ?>
