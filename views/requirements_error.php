<div class="error">
	<p><?php echo WCR_NAME; ?> error: Your environment doesn't meet all of the system requirements listed below.</p>
	<ul class="ul-disc">
		<li>
			<strong>PHP <?php echo WCR_REQUIRED_PHP_VERSION; ?>+</strong>
			<em>(You're running version <?php echo PHP_VERSION; ?>)</em>
		</li>
		<li>
			<strong>WordPress <?php echo WCR_REQUIRED_WP_VERSION; ?>+</strong>

			<em>(You're running version <?php echo esc_html( $wp_version ); ?>)</em>
		</li>
		<?php
			echo  ( ! is_plugin_active( 'woocommerce/woocommerce.php' ) ) ? '<li><strong>WooCommerce Plugin</strong> needs to be activate.</em></li>' : '';
		 ?>
	</ul>
	<p></p>
</div>

