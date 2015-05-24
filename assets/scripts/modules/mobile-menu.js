try {
	jQuery(document).ready(function() {
		var $use_in_mobile_menu = jQuery('.use-in-mobile-menu');

		var $fb_style_menu = jQuery('.fb-style-menu');

		var labels = {};
		$use_in_mobile_menu.each(function(i,el) {
			var $el = jQuery(el);
			var label = $el.data('mobile-menu-label');

			if (typeof(label) === "string" && label.length && typeof(labels[label]) === "undefined") {
				$fb_style_menu.append(jQuery('<li class="menu-label">'+label+'</li>'));
				labels[label] = true;
			}
			var $li = jQuery('<li></li>');
			$li.html($el.clone());
			$fb_style_menu.append($li);
		});

		function toggleMobileMenu() {
			var $mobile_menu_bg = jQuery(".mobile-menu-bg");
			var $mobile_menu 	= jQuery(".mobile-menu");
			var $body			= jQuery('body');
			var $window_width	= jQuery(window).width();

			$body.css('min-width', $window_width);
			$mobile_menu_bg.toggle();
			var isVisible = $mobile_menu_bg.is(":visible");
			if(isVisible) {
				$mobile_menu.animate({ left: '0' }, { duration: 100, queue: false });
				$mobile_menu_bg.animate({ left: '0' }, { duration: 100, queue: false });
				$body.animate({ 'margin-left': '165px' }, { duration: 100, queue: false });
				$body.addClass('mobile-menu-open');
			} else {
				$mobile_menu_bg.animate({ left: '-200' }, { duration: 100, queue: false });
				$mobile_menu.animate({ left: '-200' }, { duration: 100, queue: false });
				$body.css('min-width','');
				$body.animate({ 'margin-left': '0' }, { duration: 100, queue: false });
				$body.removeClass('mobile-menu-open');
			}
		}
		// Code to show/hide fb style menu on mobile resolutions (less than 768px)
		jQuery(".m-menu").click(function(event) {
			event.stopPropagation();
			toggleMobileMenu();
		});

		// hide menu when clicking outside it
		jQuery(document).click(function(event) {
			if(body.hasClass('mobile-menu-open') && !jQuery(event.target).closest('.mobile-menu-bg').length) {
				toggleMobileMenu();
			}
		});

		jQuery('.fb-style-menu').on('click', 'a', function() {
			toggleMobileMenu();
		});

	});
} catch(e) {
	giverhubError({e:e});
}