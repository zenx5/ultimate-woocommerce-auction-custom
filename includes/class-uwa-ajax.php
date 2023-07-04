<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 *
 * Handling AJAX Event.
 *
 * @class  UWA_AJAX
 * @package Ultimate Auction For WooCommerce
 * @author Nitesh Singh 
 * @since 1.0
 */
class UWA_AJAX {
	/**
	 * Hook in ajax handlers.
	 */
	public static function init() {
		add_action( 'init', array( __CLASS__, 'define_uwa_ajax' ), 0 );
		add_action( 'wp_loaded', array( __CLASS__, 'do_uwa_ajax' ), 10 );
		
	}
	/**
	 * Set AJAX constant and headers.
	 */
	public static function define_uwa_ajax() {
		if ( ! empty( $_GET['uwa-ajax'] ) ) {
			wc_maybe_define_constant( 'UWA_DOING_AJAX', true );
			wc_maybe_define_constant( 'WC_DOING_AJAX', true );
			if ( ! WP_DEBUG || ( WP_DEBUG && ! WP_DEBUG_DISPLAY ) ) {
				@ini_set( 'display_errors', 0 ); // Turn off display_errors during AJAX events to prevent malformed JSON
			}
			$GLOBALS['wpdb']->hide_errors();
		}
	}
	/**
	 * Send headers for Ajax Requests.
	 *	
	 */
	private static function wc_ajax_headers() {
		send_origin_headers();
		@header( 'Content-Type: text/html; charset=' . get_option( 'blog_charset' ) );
		@header( 'X-Robots-Tag: noindex' );
		send_nosniff_header();
		nocache_headers();
		status_header( 200 );
	}
	/**
	 * Check for  Ajax request and fire action.
	 */
	public static function do_uwa_ajax() {
		global $wp_query;
		if ( ! empty( $_GET['uwa-ajax'] ) ) {
			self::wc_ajax_headers();
			do_action( 'uwa_ajax_' . sanitize_text_field( $_GET['uwa-ajax'] ) );
			wp_die();
		}
	}

	public static function endpoint_get_auction($id = null) {

		function searchValueByKey( $elements, $key, $default, $is_bool = false ){
			$result = $default;
			foreach( $elements as $element ) {
				if( $element->key === $key ) {
					$result = $element->value;
				}
			}
			return $is_bool ? boolval($result) : $result;
		}
		
		$auctions = [];
		$query = $id ? 
		new WP_Query(array(
			'post_type' => 'product',
			'posts_per_page' => -1,
			'p' => $id
		))	:
		new WP_Query(array(
			'post_type' => 'product',
			'posts_per_page' => -1,
		));
		$posts = $query->posts;
		foreach( $posts as $post ){
			$result = (new WC_Product_Auction( $post->ID ))->get_data();
			$medias = [ wp_get_attachment_image_src($result['image_id']) ];
			foreach( $result['gallery_image_ids'] as $id ){
				$medias[] = wp_get_attachment_image_src($id);
			}
			$result['images'] = $medias;
			$auctions[] = [
				'id' => $result['id'],
				'name' => $result['name'],
				'slug' => $result['slug'],
				'status' => $result['status'],
				'images' => $result['images'],
				'latitudeStart' => floatval( searchValueByKey( $result['meta_data'], 'woo_ua_latitude', 0) ),
				'longitudeStart' => floatval( searchValueByKey( $result['meta_data'], 'woo_ua_longitude', 0) ),
				'latitudeEnd' => floatval( searchValueByKey( $result['meta_data'], 'woo_ua_latitude_end', 0) ),
				'longitudeEnd' => floatval( searchValueByKey( $result['meta_data'], 'woo_ua_longitude_end', 0) ),
				'latitudeOwner' => floatval( searchValueByKey( $result['meta_data'], 'woo_ua_latitude_curren', 0) ),
				'longitudeOwner' => floatval( searchValueByKey( $result['meta_data'], 'woo_ua_longitude_current', 0) ),
				'condition' => searchValueByKey( $result['meta_data'], 'woo_ua_product_condition', 'new'),
				'openPrice' => searchValueByKey( $result['meta_data'], 'woo_ua_opening_price', 0),
				'currentPrice' => searchValueByKey( $result['meta_data'], 'woo_ua_auction_current_bid', searchValueByKey( $result['meta_data'], 'woo_ua_opening_price', 0) ),
				'currentBider' => searchValueByKey( $result['meta_data'], 'woo_ua_auction_current_bider', 0),
				'bidCount' => searchValueByKey( $result['meta_data'], 'woo_ua_auction_bid_count', 0),
				'lowestPrice' => searchValueByKey( $result['meta_data'], 'woo_ua_lowest_price', 0),
				'typeChange' => searchValueByKey( $result['meta_data'], 'woo_ua_type_auction_increment', 'down'),
				'stepChange' => searchValueByKey( $result['meta_data'], 'woo_ua_bid_increment', ''),
				'endDate' => searchValueByKey( $result['meta_data'], 'woo_ua_auction_end_date', ''),
				'startDate' => searchValueByKey( $result['meta_data'], 'woo_ua_auction_start_date', ''),
				'started' => searchValueByKey( $result['meta_data'], 'woo_ua_auction_has_started', '0', true),
				'closed' => searchValueByKey( $result['meta_data'], 'woo_ua_auction_closed', '0', true),
				'lastActivity' => searchValueByKey( $result['meta_data'], 'woo_ua_auction_last_activity', '0'),
			];
		}
		return $auctions;
	}
}
UWA_AJAX::init();