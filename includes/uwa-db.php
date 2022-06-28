<?php
//Auction Log History DataBase and default setting load while plugin activate
global $wpdb;
$log_table = $wpdb->prefix . "woo_ua_auction_log";
$sql = "CREATE TABLE IF NOT EXISTS $log_table (
				`id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
				  `userid` bigint(20) unsigned NOT NULL,
				  `auction_id` bigint(20) unsigned DEFAULT NULL,
				  `bid` decimal(32,4) DEFAULT NULL,
				  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
				  `proxy` tinyint(1) DEFAULT NULL,
				  PRIMARY KEY (`id`)
				);";
require_once ABSPATH . 'wp-admin/includes/upgrade.php';
dbDelta($sql);
wp_insert_term('auction', 'product_type');
if (get_option('woo_ua_show_auction_pages_shop') == FALSE) {
	add_option('woo_ua_show_auction_pages_shop', 'yes');
}
if (get_option('woo_ua_show_auction_pages_search') == FALSE) {
	add_option('woo_ua_show_auction_pages_search', 'yes');
}

if (get_option('woo_ua_show_auction_pages_cat') == FALSE) {
	add_option('woo_ua_show_auction_pages_cat', 'yes');
}

if (get_option('woo_ua_show_auction_pages_tag') == FALSE) {
	add_option('woo_ua_show_auction_pages_tag', 'yes');
}


if (get_option('woo_ua_auctions_countdown_format') == FALSE) {
	add_option('woo_ua_auctions_countdown_format', 'yowdHMS');
}
if (get_option('woo_ua_auctions_bid_ajax_enable') == FALSE) {
	add_option('woo_ua_auctions_bid_ajax_enable', 'no');
}
if (get_option('woo_ua_auctions_bid_ajax_interval') == FALSE) {
	add_option('woo_ua_auctions_bid_ajax_interval', '1');
}

if (get_option('woo_ua_auctions_bids_reviews_tab') == FALSE) {
	add_option('woo_ua_auctions_bids_reviews_tab', 'yes');
}
if (get_option('woo_ua_auctions_private_message') == FALSE) {
	add_option('woo_ua_auctions_private_message', 'yes');
}

if (get_option('woo_ua_auctions_bids_section_tab') == FALSE) {
	add_option('woo_ua_auctions_bids_section_tab', 'yes');
}

if (get_option('woo_ua_auctions_watchlists') == FALSE) {
	add_option('woo_ua_auctions_watchlists', 'yes');
}

//cron setting

if (get_option('woo_ua_cron_auction_status') == FALSE) {
	add_option('woo_ua_cron_auction_status', '2');
}
if (get_option('woo_ua_cron_auction_status_number') == FALSE) {
	add_option('woo_ua_cron_auction_status_number', '25');
}
if (get_option('woo_ua_cron_auction_email') == FALSE) {
	add_option('woo_ua_cron_auction_email', '4');
}
if (get_option('woo_ua_cron_auction_no_process') == FALSE) {
	add_option('woo_ua_cron_auction_no_process', '25');
}
//new from version 1.1.0
if (get_option('woo_ua_expired_auction_enabled') == FALSE) {
	add_option('woo_ua_expired_auction_enabled', 'no');
}

update_option('woo_ua_auction_db_ver', WOO_UA_VERSION);
update_option('woo_ua_auction_ver', WOO_UA_VERSION);
flush_rewrite_rules();
