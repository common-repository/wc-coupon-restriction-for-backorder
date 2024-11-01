<?php 
if (!class_exists('wcr_main')) 
{
	/**
	 * Email controllar
	 */
	class wcr_main 
	{
		private static $instances = array();
		
		/**
     	 * Autoload method
     	 * @return void
     	 */
		public function __construct() {
			add_action('init', array($this, 'wcr_init_hook_callback'));
			add_action( 'woocommerce_coupon_options_usage_restriction', array($this, 'wcr_coupon_options_usage_restriction'), 10, 2 );
			add_action( 'woocommerce_coupon_options_save', array($this, 'wcr_coupon_options_save'), 10, 2 );	
		}

		public static function get_instance() {
			$module = get_called_class();
			if ( ! isset( self::$instances[ $module ] ) ) 
			{
				self::$instances[ $module ] = new $module();
			}
			return self::$instances[ $module ];
		}

		public function activate( $network_wide ) {
			
		}

		public function deactivate() {
			foreach ( $this->modules as $module ) {
				$module->deactivate();
			}
			flush_rewrite_rules();
		}

		/**
		 *	12-06-2021
		 *
		 * 	init hook call
		 */
		public function wcr_init_hook_callback() {
			add_action( 'wp_enqueue_scripts', array($this, 'wcr_load_resources'),99);
			add_action( 'admin_enqueue_scripts', array($this, 'wcr_load_resources'));
		}

		public function wcr_load_resources() {
			$data = [];
			$dependency = array( 'jquery' );
			if(is_admin()) {
				wp_enqueue_style( 'wcr-style-front', WCR_URL.'assets/css/style.css?time='.time() );
				wp_register_script( 'wcr-jquery-back', WCR_URL . 'assets/js/admin.js?time='.time(), $dependency, false, true);
				wp_localize_script( 'wcr-jquery-back', 'wcrload', $data );
				wp_enqueue_script( 'wcr-jquery-back' );
			}
		}

		/**
		 *	23-06-2021
		 *
		 *	Add new custom field usage restriction tab
		 */
		public function wcr_coupon_options_usage_restriction( $coupon_get_id, $coupon ) {
			$msg = $coupon->get_meta( 'wcr_coupon_restrict_msg' );
			woocommerce_wp_checkbox( array( 
				'id' 			=> 'wcr_coupon_restrict', 
				'label' 		=> __( 'Backorder Restriction', 'wcr' ),
				'description' 	=> __( 'Restrict the coupon apply on the Backorder Items. (<span class="description-inner-tag">will not apply to Backorder products stock > 0. </span>)', 'wcr') 
			));
			woocommerce_wp_text_input( array( 
				'id' => 'wcr_coupon_restrict_msg',  
				'label' => __( 'Backorder Restriction Message', 'wcr' ),  
				'placeholder' => __( 'Backorder Restriction Message', 'wcr' ),  
				'description' => __( '<i>This message will appear on the cart.</i>', 'wcr' ), 
				'type' => 'text',
				'value' => !empty($msg) ? $msg : __('Sorry, this coupon applies only to Instock Products. Currently there are no applicable products in the cart.')  
			));
		}

		/**
		 *	23-06-2021
		 *
		 *
		 *	Save custom coupon field.
		 */

		public function wcr_coupon_options_save( $post_id, $coupon ) {
			$wcr_coupon_restrict = isset( $_POST['wcr_coupon_restrict'] ) ? 'yes' : 'no';
		    $coupon->update_meta_data( 'wcr_coupon_restrict', $wcr_coupon_restrict);
		    if(isset($_POST['wcr_coupon_restrict_msg']) && !empty($_POST['wcr_coupon_restrict_msg'])){
		    	$coupon->update_meta_data( 'wcr_coupon_restrict_msg', sanitize_text_field($_POST['wcr_coupon_restrict_msg']));
		    }else{
		    	$coupon->update_meta_data( 'wcr_coupon_restrict_msg', '');
		    }
		    $coupon->save();
		}
	}
}
?>