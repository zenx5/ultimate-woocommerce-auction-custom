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
            const auctions = <?=json_encode( UWA_AJAX::endpoint_get_auction() )?>;
            console.log( auctions )
            function initMap() {
                navigator.geolocation.getCurrentPosition( async position => {
                    const { latitude:lat, longitude:lng } = position.coords
                    console.log( lat, lng )
                    const center = { lat:8.25, lng:-62.80 }

                    let map = new google.maps.Map(document.getElementById("map"), {
                        center: center,
                        zoom: 12,
                    });
                    const svgMarker = {
                        path: "M-1.547 12l6.563-6.609-1.406-1.406-5.156 5.203-2.063-2.109-1.406 1.406zM0 0q2.906 0 4.945 2.039t2.039 4.945q0 1.453-0.727 3.328t-1.758 3.516-2.039 3.070-1.711 2.273l-0.75 0.797q-0.281-0.328-0.75-0.867t-1.688-2.156-2.133-3.141-1.664-3.445-0.75-3.375q0-2.906 2.039-4.945t4.945-2.039z",
                        fillColor: "blue",
                        fillOpacity: 0.6,
                        strokeWeight: 0,
                        rotation: 0,
                        scale: 2,
                        anchor: new google.maps.Point(0, 20),
                    };
                    window.map = map
                    
                    new google.maps.Marker({
                        position: center,
                        icon: svgMarker, 
                        map
                    });
                    auctions.forEach( auction => {
                        // const diffLat = Math.pow( Math.abs( auction.latitudeStart - center.lat ), 2 )
                        // const diffLng = Math.pow( Math.abs( auction.longitudeStart - center.lng ), 2 )
                        // const distance = Math.pow( diffLat + diffLng, 0.5 )
                        // console.log( distance )
                        const marker = new google.maps.Marker({
                            position: { lat:auction.latitudeStart, lng:auction.longitudeStart },
                            map
                        });
                        marker.addListener('click', event => {
                            const details = document.querySelector("#details")
                            const slug = document.location.origin + '/producto/' + auction.slug
                            details.innerHTML = `
                                <p>Nombre: 
                                    <b>${auction.name}</b>
                                </p>
                                <a href="${slug}" target="_blank">Ir</a>`
                        } )
                    } )
                })
            }
            window.initMap = initMap;
        </script>
        <div id="map" style="width: auto; height: 300px;margin: auto;"></div>
        <div id="details" style="display:flex; justify-content:space-between; padding:20px;">

        </div>  
        <script src="https://maps.googleapis.com/maps/api/js?key=<?=get_option('uwa_google_map_api_key', '')?>&callback=initMap&v=weekly" defer></script>
    </div>