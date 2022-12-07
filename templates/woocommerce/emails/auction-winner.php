<?php

/**
 * Send Email to bidder when the bidder won the auction. (HTML)
 * 
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
?>
<?php do_action('woocommerce_email_header', $email_heading, $email); ?>
<?php
$product_id = $email->object['product_id'];
$product = wc_get_product($product_id);
$auction_url = $email->object['url_product'];
$user_name = $email->object['user_name'];
$auction_title = $product->get_title();
$auction_bid_value = wc_price($product->get_woo_ua_current_bid());
$thumb_image = $product->get_image('thumbnail');
$checkout_url  = add_query_arg(array('pay-uwa-auction' => $product_id), woo_ua_auction_get_checkout_url());
?>
<p><?php printf(__("Hi %s,", 'ultimate-woocommerce-auction-custom'), $user_name); ?></p>
<p><?php printf(__("Congratulations! You are the winner! of the auction product <a href='%s'>%s</a>.", 'ultimate-woocommerce-auction-custom'), $auction_url, $auction_title); ?></p>
<p><?php printf(__("Here are the details : ", 'ultimate-woocommerce-auction-custom')); ?></p>
<table>
    <tr>
        <td><?php _e('Image', 'ultimate-woocommerce-auction-custom'); ?></td>
        <td><?php _e('Product', 'ultimate-woocommerce-auction-custom'); ?></td>
        <td><?php _e('Winning bid', 'ultimate-woocommerce-auction-custom'); ?></td>
    </tr>
    <tr>
        <td><?php echo wp_kses_post($thumb_image); ?></td>
        <td><a href="<?php echo esc_url($auction_url); ?>"><?php echo esc_attr($auction_title); ?></a></td>
        <td><?php echo wp_kses_post($auction_bid_value);  ?></td>
    </tr>
</table>
<div>
    <p><?php _e('Please, proceed to checkout', 'ultimate-woocommerce-auction-custom'); ?></p>
    <p><a style="padding:6px 28px !important;font-size: 12px !important; background: #ccc !important; color: #333 !important; text-decoration: none!important; text-transform: uppercase!important; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif !important;font-weight: 800 !important; border-radius: 3px !important; display: inline-block !important;" href="<?php echo esc_url($checkout_url); ?>" class="button"><?php _e('Pay Now', 'ultimate-woocommerce-auction-custom') ?></a>
    </p>

</div>
<?php do_action('woocommerce_email_footer', $email); ?>