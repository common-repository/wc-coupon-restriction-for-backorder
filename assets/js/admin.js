jQuery(function ($) {
	var wcr_admin = {
		init: function(){
			$('.form-table tr:nth-child(2)').hide();
			$('input[name="wcr_coupon_restriction_chk"]').on( 'click', this.coupon_restriction_enable );
			$('input[name="wcr_coupon_restriction_chk"]').each(function () {
				if($(this).is(":checked")) {
					$(this).parents('tr').next('tr:first').show();
				} else {
					$(this).parents('tr').next('tr:first').hide();
				}
			});
		},
		coupon_restriction_enable: function(){
			if($(this).is(":checked")) {
				$(this).parents('tr').next('tr:first').show();
			} else {
				$(this).parents('tr').next('tr:first').hide();
			}
		},
	};
	wcr_admin.init();
});