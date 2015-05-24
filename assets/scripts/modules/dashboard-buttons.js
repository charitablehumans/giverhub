try {
	jQuery(document).ready(function() {
		try {
			var $dashboard_buttons_wrapper = jQuery('.dashboard-buttons-wrapper');

			if ($dashboard_buttons_wrapper.length) {
				$dashboard_buttons_wrapper.on('click', '.dashboard-button-nonprofits', function () {
					try {
						jQuery(".non-profits_view").submit();
					} catch(e) {
						giverhubError({e:e});
					}
					return false;
				});

				$dashboard_buttons_wrapper.on('click', '.dashboard-button-petitions', function () {
					try {
						jQuery(".petition_view").submit();
					} catch(e) {
						giverhubError({e:e});
					}
					return false;
				});
			}
		} catch(e) {
			giverhubError({e:e});
		}
	});
} catch(e) {
	giverhubError({e:e});
}