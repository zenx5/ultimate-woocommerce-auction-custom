<?php

/**
 * Loop Add to Cart
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */
// Exit if accessed directly
if (!defined('ABSPATH')) exit;
global $product;
if (method_exists($product, 'get_type') && $product->get_type() == 'auction') :
	$user_id  = get_current_user_id();
	if ($user_id == $product->get_woo_ua_auction_current_bider() && $product->get_woo_ua_auction_closed() == '2' && !$product->get_woo_ua_auction_payed()) : ?>
		<a href="<?php echo apply_filters('ultimate_woocommerce_auction_pay_now_button_text', esc_attr(add_query_arg("pay-uwa-auction", $product->get_id(), woo_ua_auction_get_checkout_url()))); ?>" class="button"><?php _e('Pay Now', 'ultimate-woocommerce-auction-custom'); ?></a>
	<?php endif; ?>
<?php endif; ?>