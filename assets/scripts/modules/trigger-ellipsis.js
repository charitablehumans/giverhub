try {
	jQuery(document).ready(function() {
		try {
			window.triggerEllipsis = function() {
				jQuery('.trigger-ellipsis').each(function(i,e) {
					jQuery(e).removeClass('trigger-ellipsis');
					if (jQuery(e).hasClass('dotdotdot')) {
						jQuery(e).dotdotdot();
					} else {
						jQuery(e).ellipsis({setTitle : 'onEllipsis', live : true});
					}
				});
			};
			window.triggerEllipsis();
		} catch(e) {
			giverhubError({e:e});
		}
	});
} catch(e) {
	giverhubError({e:e});
}
