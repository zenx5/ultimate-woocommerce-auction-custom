<?php

/**
 * Admin notification when auction won by Bidder. (plain)
 *
 */
if (!defined('ABSPATH')) exit;
/* Exit if accessed directly */
global $woocommerce;
$product_id = $email->object['product_id'];
$product = wc_get_product($product_id);
$auction_url = $email->object['url_product'];
$Bidder = $email->object['user_name'];
$auction_title = $product->get_title();
$auction_bid_value = wc_price($product->get_woo_ua_current_bid());
$thumb_image = $product->get_image('thumbnail');
$BidderLink = add_query_arg('user_id', $email->object['user_id'], admin_url('user-edit.php'));
echo esc_attr($email_heading) . "</br>";
printf(__("Hi Admin,", 'ultimate-woocommerce-auction-custom'));
echo "</br>";
printf(__("The auction has expired and won by user. Auction url <a href='%s'>%s</a>.", 'ultimate-woocommerce-auction-custom'), $auction_url, $auction_title);
echo "</br>";
printf(__("<a href='%s'>%s</a>.", 'ultimate-woocommerce-auction-custom'), $BidderLink, $Bidder);
echo "</br>";
echo "</br>";
printf(__("Winning bid %s.", 'ultimate-woocommerce-auction-custom'), $auction_bid_value);
echo "</br>";
echo apply_filters('woocommerce_email_footer_text', get_option('woocommerce_email_footer_text'));
