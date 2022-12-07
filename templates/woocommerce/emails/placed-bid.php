<?php

/**
 * Bidder placed a bid email notification (HTML)
 *
 */
// Exit if accessed directly
if (!defined('ABSPATH')) exit;

?>
<?php do_action('woocommerce_email_header', $email_heading, $email); ?>

<?php
$product = $email->object['product'];
$auction_url = $email->object['url_product'];
$user_name = $email->object['user_name'];
$auction_title = $product->get_title();
$auction_bid_value = wc_price($product->get_woo_ua_current_bid());
$thumb_image = $product->get_image('thumbnail');
?>
<p><?php printf(__("Hi %s,", 'ultimate-woocommerce-auction-custom'), $user_name); ?></p>
<p><?php printf(__("You recently placed a bid on <a href='%s'>%s</a>.", 'ultimate-woocommerce-auction-custom'), $auction_url, $auction_title); ?></p>
<p><?php printf(__("Here are the details : ", 'ultimate-woocommerce-auction-custom')); ?></p>
<table>
   <tr>
      <td><?php _e('Image', 'ultimate-woocommerce-auction-custom'); ?></td>
      <td><?php _e('Product', 'ultimate-woocommerce-auction-custom'); ?></td>
      <td><?php _e('Your bid', 'ultimate-woocommerce-auction-custom'); ?></td>
   </tr>
   <tr>
      <td><?php echo wp_kses_post($thumb_image); ?></td>
      <td><a href="<?php echo esc_url($auction_url); ?>"><?php echo esc_attr($auction_title); ?></a></td>
      <td><?php echo wp_kses_post($auction_bid_value);  ?></td>
   </tr>
</table>


<?php do_action('woocommerce_email_footer', $email); ?>