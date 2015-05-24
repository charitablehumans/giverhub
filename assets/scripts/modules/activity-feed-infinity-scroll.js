try {

	/*
	 * Viewport - jQuery selectors for finding elements in viewport
	 *
	 * Copyright (c) 2008-2009 Mika Tuupola
	 *
	 * Licensed under the MIT license:
	 *   http://www.opensource.org/licenses/mit-license.php
	 *
	 * Project home:
	 *  http://www.appelsiini.net/projects/viewport
	 *
	 */
	(function($) {

		$.belowthefold = function(element, settings) {
			var fold = $(window).height() + $(window).scrollTop();
			return fold <= $(element).offset().top - settings.threshold;
		};

		$.abovethetop = function(element, settings) {
			var top = $(window).scrollTop();
			return top >= $(element).offset().top + $(element).height() - settings.threshold;
		};

		$.rightofscreen = function(element, settings) {
			var fold = $(window).width() + $(window).scrollLeft();
			return fold <= $(element).offset().left - settings.threshold;
		};

		$.leftofscreen = function(element, settings) {
			var left = $(window).scrollLeft();
			return left >= $(element).offset().left + $(element).width() - settings.threshold;
		};

		$.inviewport = function(element, settings) {
			return !$.rightofscreen(element, settings) && !$.leftofscreen(element, settings) && !$.belowthefold(element, settings) && !$.abovethetop(element, settings);
		};

		$.extend($.expr[':'], {
			"below-the-fold": function(a, i, m) {
				return $.belowthefold(a, {threshold : 0});
			},
			"above-the-top": function(a, i, m) {
				return $.abovethetop(a, {threshold : 0});
			},
			"left-of-screen": function(a, i, m) {
				return $.leftofscreen(a, {threshold : 0});
			},
			"right-of-screen": function(a, i, m) {
				return $.rightofscreen(a, {threshold : 0});
			},
			"in-viewport": function(a, i, m) {
				return $.inviewport(a, {threshold : 0});
			}
		});


	})(jQuery);

	jQuery(document).ready(function() {
		var $activity_load_more = jQuery('.activity-load-more');

		if (!$activity_load_more.length) {
			return;
		}

		var $activity_table_tbody = jQuery('.activity-table-tbody');


		$activity_load_more.is_loading_more = false;

		$activity_load_more.load_more = function() {
			$activity_load_more.is_loading_more = true;
			$activity_load_more.data('original-text', $activity_load_more.html());
			$activity_load_more.html($activity_load_more.data('loading-text'));
			$activity_load_more.attr('disabled', 'disabled');

			jQuery.ajax({
				url : '/activity/more',
				type : 'get',
				dataType : 'json',
				data : {
					offset : $activity_load_more.data('offset'),
					user_id : $activity_load_more.data('user-id')
				},
				success : function(json) {
					if (typeof(json) === "object" && typeof(json.success) === "boolean" && json.success && typeof(json.activities) === "object") {
						if (json.activities.length) {
							jQuery.each(json.activities, function(i,activity) {
								$activity_table_tbody.append(jQuery(activity));
							});

							window.triggerEllipsis();
							$activity_load_more.data('offset', parseInt($activity_load_more.data('offset'))+parseInt($activity_load_more.data('activities-per-page')));
						} else {
							$activity_load_more.addClass('hide');
						}
					}
				},
				complete : function() {
					$activity_load_more.is_loading_more = false;
					$activity_load_more.html($activity_load_more.data('original-text'));
				}
			});
		};

		var $html = jQuery('html');
		jQuery(document).scroll(function() {
			try {
				if ($html.width() > 978 && $activity_load_more.is(':in-viewport') && !$activity_load_more.is_loading_more) {
					$activity_load_more.load_more();
				}
			} catch(e) {
				giverhubError({e:e});
			}
		});

		jQuery(document).on('click', '.activity-load-more', function() {
			try {
				if (!$activity_load_more.is_loading_more) {
					$activity_load_more.load_more();
				}
			} catch(e) {
				giverhubError({e:e});
			}
			return false;
		});
	});
} catch(e) {
	giverhubError({e:e});
}