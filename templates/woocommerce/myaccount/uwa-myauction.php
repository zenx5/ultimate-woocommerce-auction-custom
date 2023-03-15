<?php
if (!defined('ABSPATH')) {
    exit;
}
$user_id  = get_current_user_id();
$my_auctions = get_woo_ua_auction_by_user($user_id);

if (count($my_auctions) > 0) { ?>

    <table class="shop_table shop_table_responsive">
        <tr>
            <th class="toptable"><?php _e('Image', 'ultimate-woocommerce-auction-custom'); ?></td>
            <th class="toptable"><?php _e('Product', 'ultimate-woocommerce-auction-custom'); ?></td>
            <th class="toptable"><?php _e('Your bid', 'ultimate-woocommerce-auction-custom'); ?></td>
            <th class="toptable"><?php _e('Current bid', 'ultimate-woocommerce-auction-custom'); ?></td>
            <th class="toptable"><?php _e('Status', 'ultimate-woocommerce-auction-custom'); ?></td>
        </tr>
        <?php

        foreach ($my_auctions as $my_auction) {
            global $product;
            global $sitepress;
            $product_id =  $my_auction->auction_id;

            /* For WPML Support - start */
            if (function_exists('icl_object_id') && is_object($sitepress) && method_exists($sitepress, 'get_current_language')) {

                $product_id = icl_object_id($product_id, 'product', false, $sitepress->get_current_language());
            }
            /* For WPML Support - end */

            $product = wc_get_product($product_id);

            if (is_object($product) && method_exists($product, 'get_type') && $product->get_type() == 'auction') {

                $product_name = get_the_title($product_id);
                $product_url  = get_the_permalink($product_id);
                $a            = $product->get_image('thumbnail');
        ?>
                <tr>
                    <td><?php echo wp_kses_post($a); ?></td>
                    <td><a href="<?php echo esc_url($product_url); ?>"><?php echo esc_attr($product_name); ?></a></td>
                    <td><?php echo wp_kses_post(wc_price($my_auction->max_bid)) ?></td>
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
                    <?php _e('Go shop', 'woocommerce') ?> </a> <?php _e('No auctions available yet.', 'ultimate-woocommerce-auction-custom') ?>
            </div>

        <?php } ?>

    </table>

    <div>
        <h4>En mi zona</h4>
        <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
        <script>
            function initMap() {
                navigator.geolocation.getCurrentPosition( position => {
                    const { latitude:lat, longitude:lng } = position.coords
                    let map = new google.maps.Map(document.getElementById("map"), {
                        center: { lat, lng },
                        zoom: 12,
                    });
                })
            }
            window.initMap = initMap;
        </script>
        <div id="map" style="width: auto; height: 300px;margin: auto;"></div>
        <script src="https://maps.googleapis.com/maps/api/js?key=<?=get_option('uwa_google_map_api_key', '')?>&callback=initMap&v=weekly" defer></script>
    </div>