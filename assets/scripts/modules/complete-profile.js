jQuery(document).ready(function() {
	try {
		jQuery('#complete_zip').blur(function() {
			jQuery.getJSON('/register/zipcode/', { zipcode: jQuery(this).val() }, function(data) {
				var state = jQuery('#complete_state');
				state.attr('data-city-id', data.city);
				state.val(data.state);
				state.trigger('change');
			});
		});


		jQuery('#complete_state').change(function() {
			var stateDropDown = jQuery(this);
			var stateId = stateDropDown.val();
			var cityDropDown = jQuery('#complete_city');
			if (!stateId) {
				cityDropDown.html('<option value="">Pick your state first</option>');
				return true;
			}

			cityDropDown.hide();
			var cityLoader = jQuery('#complete-profile-city-loader').show();
			jQuery.ajax({
				url : '/home/getCities',
				type : 'GET',
				data : { stateId : stateId },
				dataType : 'html',
				success : function(html) {
					cityDropDown.html(html);
					var cityId = stateDropDown.data('city-id');
					if (cityId) {
						cityDropDown.val(cityId);
						stateDropDown.data('city-id', '');
					}
				},
				error : function() {
					jQuery('#complete-profile-alert-danger-msg').html('<strong>Ooops.</strong> There was a problem loading cities for your selected state..');
					jQuery('#complete-profile-alert-danger').show();
					cityDropDown.html('<option value="">Pick your state first</option>');
				},
				complete : function() {
					cityLoader.hide();
					cityDropDown.show();
				}
			});
		});


	} catch(e) {
		giverhubError({e:e});
	}
});