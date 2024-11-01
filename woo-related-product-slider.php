<?php
/*
Plugin Name: WooCommerce Related Product Slider
Description: WooCommerce Related Product Slider allows you to display responsive related products slider on product single page.
Plugin URI: https://wplimb.com/plugins/woo-related-product-slider-pro/
Author: WPLimb
Author URI: http://wplimb.com/
Version: 1.0.1
Text Domain: woo-related-product-slider
Domain Path: /languages
*/

/**
 * Directory Constant
 */
define( 'WPL_WOO_RELATED_PRODUCT_SLIDER_URL', plugins_url('/') . plugin_basename( dirname( __FILE__ ) ) . '/' );
define( 'WPL_WOO_RELATED_PRODUCT_SLIDER_DIR', plugin_dir_path( __FILE__ ) );
define( 'WPL_WOO_RELATED_PRODUCT_SLIDER_BASENAME', plugin_basename( __FILE__ ) );

/**
 * Plugin Action Links
 * @param $links
 *
 * @return array
 */
function wpl_woo_related_product_slider_action_links( $links ) {
	if( is_admin() ) {
		$links[] = '<a href="admin.php?page=wrps_settings">'.__('Settings', 'woo-related-product-slider').'</a>';
	}
	return $links;
}
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'wpl_woo_related_product_slider_action_links' );

/**
 * Include files
 */
require_once( WPL_WOO_RELATED_PRODUCT_SLIDER_DIR . 'includes/functions.php' );