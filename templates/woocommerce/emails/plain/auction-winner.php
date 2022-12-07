<?php

/**
 * Bidder placed a bid email notification (plain)
 *
 */
/* Exit if accessed directly */
if (!defined('ABSPATH')) exit;
global $woocommerce;
$product_id = $email->object['product_id'];
$product = wc_get_product($product_id);
$auction_url = $email->object['url_product'];
$user_name = $email->object['user_name'];
$auction_title = $product->get_title();
$auction_bid_value = wc_price($product->get_woo_ua_current_bid());
$checkout_url  = add_query_arg(array('pay-uwa-auction' => $product_id), woo_ua_auction_get_checkout_url());
$paynowbtn = __('Pay Now', 'ultimate-woocommerce-auction-custom');
echo esc_attr($email_heading) . "</br>";
printf(__("Hi %s,", 'ultimate-woocommerce-auction-custom'), $user_name);
echo "</br>";
printf(__("Congratulations! You are the winner! of the auction product <a href='%s'>%s</a>.", 'ultimate-woocommerce-auction-custom'), $auction_url, $auction_title);
echo "</br>";
printf(__("Winning bid %s.", 'ultimate-woocommerce-auction-custom'), $auction_bid_value);
echo "</br>";
printf(__("Please, proceed to checkout ,<a href='%s'>%s</a>.", 'ultimate-woocommerce-auction-custom'), $checkout_url, $paynowbtn);
echo "</br>";
echo apply_filters('woocommerce_email_footer_text', get_option('woocommerce_email_footer_text'));
