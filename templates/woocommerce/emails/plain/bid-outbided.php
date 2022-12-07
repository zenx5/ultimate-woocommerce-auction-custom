<?php

/**
 * User placed a bid email notification (plain)
 *
 */
if (!defined('ABSPATH')) exit;
/* Exit if accessed directly */
global $woocommerce;
$product = $email->object['product'];
$auction_url = $email->object['url_product'];
$user_name = $email->object['user_name'];
$auction_title = $product->get_title();
$auction_bid_value = wc_price($product->get_woo_ua_current_bid());
echo esc_attr($email_heading) . "</br>";
printf(__("Hi %s,", 'ultimate-woocommerce-auction-custom'), $user_name);
echo "</br>";
printf(__("Auction has been outbid. Auction url <a href='%s'>%s</a>.", 'ultimate-woocommerce-auction-custom'),    $auction_url, $auction_title);
echo "</br>";
printf(__("Bid Value %s.", 'ultimate-woocommerce-auction-custom'), $auction_bid_value);
echo "</br>";
printf(__("If you want to bid a new amount, click here <a href='%s'>%s</a>.", 'ultimate-woocommerce-auction-custom'), $auction_url, $auction_title);
echo "</br>";
echo apply_filters('woocommerce_email_footer_text', get_option('woocommerce_email_footer_text'));
