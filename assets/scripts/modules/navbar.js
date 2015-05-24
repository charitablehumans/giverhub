try {
	jQuery(document).ready(function() {
		try {
			var $search_placeholder = jQuery('.search-placeholder');
			if ($search_placeholder.length) {
				var $srch_term = jQuery('#srch-term');

				$search_placeholder.click(function() {
					try {
						$srch_term.trigger('focus');
					} catch(e) {
						giverhubError({e:e});
					}
				});

				var search_changed = function() {
					if ($srch_term.val().length) {
						$search_placeholder.addClass('hide');
					} else {
						$search_placeholder.removeClass('hide');
					}
				};

				$srch_term.on('input', search_changed);
				search_changed(); // trigger it once in case the value is already set server side
			}
		} catch(e) {
			giverhubError({e:e});
		}
	});
} catch(e) {
	giverhubError({e:e});
}