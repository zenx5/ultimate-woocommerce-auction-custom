<?php

/**
 * Map tab
 *
 */
if (!defined('ABSPATH')) exit; // Exit if accessed directly
global $woocommerce, $post, $product;

$latitude = searchValueByKey($product->get_data()['meta_data'], 'woo_ua_latitude', 0);
$longitude = searchValueByKey($product->get_data()['meta_data'], 'woo_ua_longitude', 0);
$markers = [
    [
        "lat" => $latitude,
        "lng" => $longitude
    ]
];
?>
<div class="container-map">

    <div id="map" style="height: 300px; width:auto;"></div>
    <?php UWA_Gmap::initMap('map',12, $latitude, $longitude); ?>
    <?php UWA_Gmap::setMarkers($markers); ?>
</div>