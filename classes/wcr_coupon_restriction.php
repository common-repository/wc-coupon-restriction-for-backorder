<?php 
if(!class_exists('wcr_coupon_restriction')){

	/**
	 *	Controller 
	 */
	class wcr_coupon_restriction
	{
		
		function __construct()
		{
			add_filter( 'woocommerce_coupon_is_valid', array($this, 'wcr_woo_coupon_is_valid'), 10, 3 );
		}

		/**
		*	12-06-2021
		*
		*	Coupon restriction
		*/
		public function wcr_woo_coupon_is_valid( $is_valid, $coupon, $discounts ) 
		{
			$wcr_coupon_restrict = $coupon->get_meta( 'wcr_coupon_restrict' );
			if($wcr_coupon_restrict == 'yes')
			{
				add_filter('woocommerce_coupon_get_discount_amount', array($this, 'wcr_backorder_coupon_discount_amount_romove'), 10, 5);
				if(is_array(WC()->cart->get_cart()) && !empty(WC()->cart->get_cart()))
				{
					foreach ( WC()->cart->get_cart() as $cart_item ) 
					{
						$stock_info = $cart_item['data']->get_stock_quantity();
						if( $stock_info > 0 ){
							$is_valid = true;
							break;
						}
						if($cart_item['data']->is_on_backorder()){
							$stock_info = $cart_item['data']->get_stock_quantity();
							if( $stock_info < 1 ){
								$is_valid = false; 
							}
						}
					}
					if ( ! $is_valid ) 
					{
						$msg = $coupon->get_meta( 'wcr_coupon_restrict_msg' );
						if($msg)
						{
							$restrict_msg = $msg;
						}else{
							$restrict_msg = __('Sorry, this coupon applies only to In-Stock Products. Currently there are applicable  products in the cart.', 'wcr');
						}
						throw new Exception( __( $restrict_msg, 'wcr' ), 109 );
					}
				}
			}
			return $is_valid ;
		}

		/**
		 *	29-06-2021 
		 *
		 * 	Backorder discount amount remove
		 **/
		public function wcr_backorder_coupon_discount_amount_romove($discount, $price_to_discount, $cart_item, $single, $coupon) {
			if ($cart_item['data']->is_on_backorder()) {
				$discount = 0;
			}
			return $discount;
		}
	}
	new wcr_coupon_restriction();
}
?>