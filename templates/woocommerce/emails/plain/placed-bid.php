<?php

/**
 * Bidder placed a bid email notification (plain)
 *
 */
/* Exit if accessed directly */
if (!defined('ABSPATH')) exit;
global $woocommerce;
$product = $email->object['product'];
$auction_url = $email->object['url_product'];
$user_name = $email->object['user_name'];
$auction_title = $product->get_title();
$auction_bid_value = wc_price($product->get_woo_ua_current_bid());
echo esc_attr($email_heading) . "</br>";
printf(__("Hi %s,", 'ultimate-woocommerce-auction-custom'), $user_name);
echo "</br>";
printf(__("You recently placed a bid on <a href='%s'>%s</a>.", 'ultimate-woocommerce-auction-custom'), $auction_url, $auction_title);
echo "</br>";
printf(__("Bid Value %s.", 'ultimate-woocommerce-auction-custom'), $auction_bid_value);
echo "</br>";
echo apply_filters('woocommerce_email_footer_text', get_option('woocommerce_email_footer_text'));
