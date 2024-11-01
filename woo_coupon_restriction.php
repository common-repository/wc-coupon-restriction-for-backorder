<?php
/**
 * Plugin Name: Coupon Restriction For Backorders on WooCommerce
 * Plugin URI: http://referral.staging.prismitsystems.com/
 * Description: This plugin used to apply coupon restriction on Backorder enabled products.
 * Version: 1.0.2
 * Author: Prism I.T. Systems
 * Author URI: http://www.prismitsystems.com
 * Developer: Prism I.T. Systems
 * Developer URI: http://www.prismitsystems.com
 * WC requires at least: 3.0.0
 * WC tested up to: 9.1.2
 * Text Domain: wcr
 * License: GPL2
 */
if ( ! defined( 'ABSPATH' ) ) {
	die( 'Access denied.' );
}

define( 'WCR_NAME','WC Backorder Coupon Restriction' );
define( 'WCR_REQUIRED_PHP_VERSION', '5.3' );
define( 'WCR_REQUIRED_WP_VERSION',  '3.1' );
define( 'WCR_DIR', plugin_dir_path( __FILE__ ) );
define( 'WCR_URL', plugin_dir_url( __FILE__ ) );

add_action('init', 'wcr_plugin_init'); 
if( !function_exists( 'wcr_plugin_init' ) ):
function wcr_plugin_init() {
    $locale = is_admin() && function_exists( 'get_user_locale' ) ? get_user_locale() : get_locale();
    $locale = apply_filters( 'plugin_locale', $locale, 'wcr' );
        
    unload_textdomain( 'wcr' );
    load_textdomain( 'wcr', WCR_DIR . 'languages/' . "wcr-".$locale . '.mo' );
    load_plugin_textdomain( 'wcr', false, WCR_DIR . 'languages' );
}
endif;

/**
 * Checks if the system requirements are met
 *
 * @return bool True if system requirements are met, false if not
 */
if( !function_exists('wcr_requirements_check') ){
	function wcr_requirements_check() {
		global $wp_version;		
		require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
		if ( version_compare( PHP_VERSION, WCR_REQUIRED_PHP_VERSION, '<' ) ) {
			return false;
		}	
		if ( version_compare( $wp_version, WCR_REQUIRED_WP_VERSION, '<' ) ) {
			return false;
		}		
		if ( ! is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
			return false;
		}
		return true;
	}
}

/**
 * Prints an error that the system requirements weren't met.
 */
if( !function_exists('wcr_requirements_error') ):
function wcr_requirements_error() {
	global $wp_version;
	require_once( dirname( __FILE__ ) . '/views/requirements_error.php' );
}
endif;

/*
 * Check requirements and load main class
 * The main program needs to be in a separate file that only gets loaded if the plugin requirements are met. Otherwise older PHP installations could crash when trying to parse it.
 */
if ( wcr_requirements_check() ) {
		require_once( WCR_DIR. 'classes/wcr_main.php');
	if (is_admin()) {
		//require_once( WCR_DIR. 'classes/wcr_settings.php');
	}else{
		require_once( WCR_DIR. 'classes/wcr_coupon_restriction.php');
	}    	  
	if ( class_exists( 'wcr_main' ) ) {
		$GLOBALS['wpps'] = wcr_main::get_instance();
		register_activation_hook(   __FILE__, array( $GLOBALS['wpps'], 'activate' ) );
		register_deactivation_hook( __FILE__, array( $GLOBALS['wpps'], 'deactivate' ) );
	}	
} else {
	add_action( 'admin_notices', 'wcr_requirements_error' );
}
?>