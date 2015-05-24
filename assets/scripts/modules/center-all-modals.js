try {
	jQuery(document).ready(function() {
		try {
			function center_modal($modal) {
				$modal.css('padding', '0');
				var $modal_pos_element = $modal.children().first();
				$modal_pos_element.css('padding', '0');

				var modal_width = $modal_pos_element.outerWidth();
				var modal_height = $modal_pos_element.outerHeight();

				if (!modal_width || !modal_height) {
					setTimeout(function() { center_modal($modal) }, 1); // call recursively until we have height / width ..
					return;
				}

				var $screen = jQuery(window);
				var screen_height = $screen.outerHeight();
				var screen_width = $screen.outerWidth();

				$modal_pos_element.css('margin-left', Math.max(0, (screen_width / 2) - (modal_width / 2)) + 'px');
				$modal_pos_element.css('margin-top', Math.max(0, (screen_height / 2) - (modal_height / 2)) + 'px');
			}

			jQuery(document).on('show.bs.modal', '.modal', function (event) {
				var $modal = jQuery(event.target);
				center_modal($modal);
			});

			jQuery(window).resize(function() {
				jQuery('.modal.in').each(function(i,e) {
					center_modal(jQuery(e));
				});
			});

			jQuery('.modal.in').each(function(i,e) {
				center_modal(jQuery(e));
			});

		} catch(e) {
			giverhubError({e:e});
		}
	});
} catch(e) {
	giverhubError({e:e});
}