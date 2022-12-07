<?php

/**
 * Auction Watchlist Button
 *
 */
// Exit if accessed directly
if (!defined('ABSPATH')) exit;
global $woocommerce, $product, $post;
global $sitepress;

if (!(method_exists($product, 'get_type') && $product->get_type() == 'auction')) {
	return;
}
$user_id = get_current_user_id();
$pid = $product->get_id();

/* For WPML Support - start */
if (function_exists('icl_object_id') && is_object($sitepress) && method_exists($sitepress, 'get_default_language')) {
	$pid = icl_object_id($pid, 'product', false, $sitepress->get_default_language());
}
/* For WPML Support - end */
?>
<div class="uwa-watchlist-button">
	<?php if ($product->is_woo_ua_user_watching()) : ?>
		<a href="javascript:void(0)" data-auction-id="<?php echo esc_attr($pid); ?>" class="remove-uwa uwa-watchlist-action "><?php _e('Remove from watchlist!', 'ultimate-woocommerce-auction-custom') ?></a>
		<a href="<?php echo esc_url(get_permalink(wc_get_page_id('myaccount'))); ?>" class="view_watchlist">
			<?php _e('View List', 'ultimate-woocommerce-auction-custom') ?></a>

	<?php else : ?>
		<a href="javascript:void(0)" data-auction-id="<?php echo esc_attr($pid); ?>" class="add-uwa uwa-watchlist-action <?php if ($user_id == 0) echo esc_attr("no-action"); ?>" title="<?php if ($user_id == 0) echo esc_html('Please sign in to add auction to watchlist.'); ?>"><?php _e('Add to watchlist!', 'ultimate-woocommerce-auction-custom') ?></a>
	<?php endif; ?>
</div>