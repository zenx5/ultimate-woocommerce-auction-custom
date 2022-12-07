<?php
if (!defined('ABSPATH')) {
    exit;
}
$user_id  = get_current_user_id();
$my_auctions_watchlist = get_woo_ua_auction_watchlist_by_user($user_id);

if (count($my_auctions_watchlist) > 0) { ?>
    <table class="shop_table shop_table_responsive">
        <tr>
            <th class="toptable"><?php _e('Image', 'ultimate-woocommerce-auction-custom'); ?></td>
            <th class="toptable"><?php _e('Product', 'ultimate-woocommerce-auction-custom'); ?></td>
            <th class="toptable"><?php _e('Current bid', 'ultimate-woocommerce-auction-custom'); ?></td>
            <th class="toptable"><?php _e('Status', 'ultimate-woocommerce-auction-custom'); ?></td>
        </tr>
        <?php
        foreach ($my_auctions_watchlist as $key => $value) {

            global $sitepress;
            /* For WPML Support - start */
            if (function_exists('icl_object_id') && is_object($sitepress) && method_exists(
                $sitepress,
                'get_current_language'
            )) {

                $value = icl_object_id(
                    (int)$value,
                    'product',
                    false,
                    $sitepress->get_current_language()
                );
            }
            /* For WPML Support - end */

            $product      = wc_get_product($value);
            if (!$product)
                continue;
            if (method_exists($product, 'get_type') && $product->get_type() == 'auction') {
                $product_name = get_the_title($value);
                $product_url  = get_the_permalink($value);
                $a            = $product->get_image('thumbnail');
        ?>
                <tr>
                    <td><?php echo wp_kses_post($a); ?></td>
                    <td><a href="<?php echo esc_url($product_url); ?>"><?php echo esc_html($product_name); ?></a></td>
                    <td><?php echo wp_kses_post($product->get_price_html()); ?></td>
                    <?php
                    if (($user_id == $product->get_woo_ua_auction_current_bider() && $product->get_woo_ua_auction_closed() == '2' && !$product->get_woo_ua_auction_payed())) {
                    ?>
                        <td><a href="<?php echo apply_filters('ultimate_woocommerce_auction_pay_now_button_text', esc_attr(add_query_arg("pay-uwa-auction", $product->get_id(), woo_ua_auction_get_checkout_url()))); ?>" class="button alt"><?php _e('Pay Now', 'ultimate-woocommerce-auction-custom') ?></a></td>
                    <?php  } elseif ($product->is_woo_ua_closed()) { ?>

                        <td><?php _e('Closed', 'ultimate-woocommerce-auction-custom'); ?></td>

                    <?php } else { ?>
                        <td><?php _e('Started', 'ultimate-woocommerce-auction-custom'); ?></td>
                    <?php
                    } ?>
                <tr>
            <?php }
        }
    } else {
        $shop_page_id = wc_get_page_id('shop');
        $shop_page_url = $shop_page_id ? get_permalink($shop_page_id) : '';
            ?>
            <div class="woocommerce-message woocommerce-message--info woocommerce-Message woocommerce-Message--info woocommerce-info">
                <a class="woocommerce-Button button" href="<?php echo esc_url($shop_page_url); ?>">
                    <?php _e('Go shop', 'woocommerce') ?> </a> <?php _e('No Watchlist auctions available yet.', 'ultimate-woocommerce-auction-custom') ?>
            </div>

        <?php } ?>
    </table>