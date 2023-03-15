<?php
// Exit if accessed directly
if (!defined('ABSPATH')) exit;

class UWA_Gmap {

    public static function get_distance($origin, $destination, $units='imperial'){
        $key = get_option('uwa_google_map_api_key', '');
        $format = 'json';
        $url = "https://maps.googleapis.com/maps/api/distancematrix/$format?destinations=$destination&origins=$origin&units=$units&key=$key&mode=walking";
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        return $url;
    }

    public static function get_lineal_distance($origin, $destination){
        $latDiff = pow( $origin['lat'] - $destination['lat'], 2 );
        $lngDiff = pow( $origin['lng'] - $destination['lng'], 2 );
        $argRoot = $latDiff + $lngDiff;
        return [
            'origin' => $origin,
            'destination' => $destination,
            'distance' => 111 * pow( $argRoot, 0.5 )
        ];
    }

    public static function setMarkers($markers = []){
        ?>
        <script>
            function setMarkers(map) {
                <?=json_encode( $markers )?>.forEach( marker => {
                    new google.maps.Marker({
                        position: {
                            lat: parseFloat( marker.lat ),
                            lng: parseFloat( marker.lng ),
                        },
                        map
                    })
                })
            }
            window.setMarkers = setMarkers;
        </script>
        <?php
    }

    public static function initMap($id, $zoom, $lat = null , $lng = null ) {
        $hasCenter = $lat!==null && $lng!==null;
        ?>
        <script>
            function initMap() {
                <?php if( !$hasCenter ) : ?>
                    navigator.geolocation.getCurrentPosition( position => {
                        const { latitude:lat, longitude:lng } = position.coords
                        let map = new google.maps.Map(document.getElementById("<?=$id?>"), {
                            center: { lat, lng },
                            zoom: <?=$zoom?>,
                        });
                    })
                <?php else: ?>
                    let map = new google.maps.Map(document.getElementById("<?=$id?>"), {
                        center: {
                            lat: <?=$lat?>,
                            lng: <?=$lng?>
                        },
                        zoom: <?=$zoom?>,
                    });
                <?php endif; ?>
                if( window.setMarkers ){
                    window.setMarkers(map)
                }
            }
            window.initMap = initMap;
            var script = document.createElement('script');
            script.src = 'https://maps.googleapis.com/maps/api/js?key=<?=get_option('uwa_google_map_api_key', '')?>&callback=initMap';
            document.body.appendChild(script);
        </script>
        <?php


    }

}