try {
	window.giverhubStat = function(name, callback) {
		if (typeof(name) !== "string") {
			throw "name must be a string";
		}
		if (typeof(callback) !== "function") {
			callback = function() {};
		}

		if (jQuery('body').data('user-is-admin') == "1") {
			callback();
			return;
		}

		jQuery.ajax({
			url : '/stat/register',
			type : 'post',
			dataType : 'json',
			data : { name : name },
			complete : function() {
				callback();
			}
		});
	};

	jQuery(document).ready(function() {
		if (jQuery('.btn-donate-using-cc-paypal-button').length) {
			window.giverhubStat('impression-donate-using-cc-paypal-button', function() {});
		}
		jQuery(document).on('mouseenter', '.btn-donate-using-cc-paypal-button', function() {
			var stat = 'hover-donate-';
			var $this = jQuery(this);
			stat += $this.hasClass('paypal') ? 'paypal' : '';
			stat += $this.hasClass('cc') ? 'cc' : '';

			window.giverhubStat(stat, function() {});
		});

		if (jQuery('.btn-donate-using-cc-paypal-button-with-amount').length) {
			window.giverhubStat('impression-donate-using-cc-paypal-button-with-amount', function() {});
		}
		jQuery(document).on('mouseenter', '.btn-donate-using-cc-paypal-button-with-amount', function() {
			var stat = 'hover-donate-with-amount-';
			var $this = jQuery(this);
			stat += $this.hasClass('paypal') ? 'paypal' : '';
			stat += $this.hasClass('cc') ? 'cc' : '';

			window.giverhubStat(stat, function() {});
		});

		if (jQuery('.btn-donate-from-search').length) {
			window.giverhubStat('impression-donate-from-search', function() {});
		}
		jQuery(document).on('mouseenter', '.btn-donate-from-search', function() {
			var stat = 'hover-donate-from-search';
			window.giverhubStat(stat, function() {});
		});

		if (jQuery('.btn-donate-from-charity-with-amount').length) {
			window.giverhubStat('impression-donate-from-charity-with-amount', function() {});
		}
		jQuery(document).on('mouseenter', '.btn-donate-from-charity-with-amount', function() {
			var stat = 'hover-donate-from-charity-with-amount';
			window.giverhubStat(stat, function() {});
		});
		jQuery('#trending-petitions-carousel').on('click', '.petition-link', function() {
			try {
				var $this = jQuery(this);
				var stat = $this.closest('.petition-wrapper').data('stat-name');

				window.giverhubStat(stat+',trend-pet', function () {
					window.location = $this.attr('href');
				});
				return false;
			} catch(e) {
				return true;
			}
		});
		jQuery('.trending-petitions-block').on('click', '.btn-hide-trending-pet-stats', function() {
			try {
				var $this = jQuery(this);
				$this.addClass('hide');
				jQuery('.trending-petitions-block').find('.admin-stats').addClass('hide');
			} catch(e) {
				giverhubError({e:e});
			}
		});
	});
} catch(e) {
	giverhubError({e:e});
}