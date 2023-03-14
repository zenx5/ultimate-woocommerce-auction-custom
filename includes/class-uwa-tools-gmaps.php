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

}