<?php 
if(!class_exists('WC_Settings_Wcr')){
	
	/**
	 *	Controller
	 */
	class WC_Settings_Wcr {

	    public static function init() {
	        add_filter( 'woocommerce_settings_tabs_array', __CLASS__ . '::add_settings_tab', 50 );
	        add_action( 'woocommerce_settings_tabs_wcr_settings', __CLASS__ . '::settings_tab' );
	        add_action( 'woocommerce_update_options_wcr_settings', __CLASS__ . '::update_settings' );
	    }
	    
	    public static function add_settings_tab( $settings_tabs ) {
	        $settings_tabs['wcr_settings'] = __( 'Coupon Restriction', 'waqc' );
	        return $settings_tabs;
	    }

	    public static function settings_tab() {
	        woocommerce_admin_fields( self::get_settings() );
	    }


	    public static function update_settings() {
	        woocommerce_update_options( self::get_settings() );
	    }


	    public static function get_settings() {

	    	$settings = array(
	    		array( 
	    			'title' => __( 'Woocommerce Coupon Restriction', 'wcr' ),
	    			'type' 	=> 'title',
	    			'desc' 	=> '',
	    			'id' 	=>  'wcr_setting_panel', 
	    			'class' => 'wcr_setting_title' 
	    		),
	    		array(
	    			'title' 	=> __('Coupon Restriction', 'wcr'),
	    			'type' 		=> 'checkbox',
	    			'desc_tip' 	=> __('if checked, coupon restriction.', 'wcr'),
	    			'id' 		=> 'wcr_coupon_restriction_chk',
	    			'class' 	=> 'wcr_coupon_restriction_chk',
	    			'default' 	=> 'no',
	    		),
	    		array(
	    			'title' 	=> __('Message', 'wcr'),
	    			'type' 		=> 'textarea',
	    			'desc_tip' 	=> __('Write the coupon restriction message.', 'wcr'),
	    			'id' 		=> 'wcr_coupon_restriction_msg',
	    			'class' 	=> 'wcr_coupon_restriction_msg',
	    		),
	    	);
	    	array_push($settings, array( 'type' => 'sectionend', 'id' => 'wcr_setting_panel'));
	        return apply_filters( 'WC_Settings_wcr_settings', $settings );
	    }
	}
	WC_Settings_Wcr::init();
}
?>