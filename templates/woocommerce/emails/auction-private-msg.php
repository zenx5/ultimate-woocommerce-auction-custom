<?php

/**
 * Admin - Private Message By User. (HTML)
 *
 */
/* Exit if accessed directly */
if (!defined('ABSPATH')) exit;
?>
<?php do_action('woocommerce_email_header', $email_heading, $email); ?>
<?php
$user_name = $email->object['user_name'];
$user_email = $email->object['user_email'];
$user_message = $email->object['user_message'];
$auction_url = $email->object['url_product'];
$product_id = $email->object['product_id'];
$product = wc_get_product($product_id);
$auction_title = $product->get_title();
?>
<p><?php printf(__("Hi Admin,", 'ultimate-woocommerce-auction-custom')); ?></p>
<p><?php printf(__("Bidder Sent Private Message for Auction <a href='%s'>%s</a>.", 'ultimate-woocommerce-auction-custom'), $auction_url, $auction_title); ?></p>
<p><?php printf(__("Here are the details : ", 'ultimate-woocommerce-auction-custom')); ?></p>
<table>
	<tr>
		<td><?php _e('Name:', 'ultimate-woocommerce-auction-custom'); ?></td>
		<td><?php echo esc_attr($user_name); ?></td>
	</tr>
	<tr>
		<td><?php _e('Email:', 'ultimate-woocommerce-auction-custom'); ?></td>
		<td><?php echo esc_attr($user_email); ?></td>
	</tr>
	<tr>
		<td><?php _e('Message:', 'ultimate-woocommerce-auction-custom'); ?></td>
		<td><?php echo esc_attr($user_message); ?></td>
	</tr>
</table>
<?php do_action('woocommerce_email_footer', $email); ?>