try {
	jQuery(document).ready(function() {
		try {

			window.initShareButtons = function(o) {
				var $el = o.el;
				var url = o.url;
				var type = o.event_type;
				var id = o.event_id;

				var tooltip_checked = '';
				var tooltip_unchecked = '';
				switch(type) {
					case 'donation' :
						tooltip_checked = 'Unchecking this checkbox will make your donation show as anonymous on GiverHub users activity feeds.';
						tooltip_unchecked = 'Checking this checkbox will make your donation public on peoples activity feeds.';
						break;
					case 'petition' :
						tooltip_checked = 'Unchecking this checkbox will make your signature hidden on GiverHub.';
						tooltip_unchecked = 'Checking this checkbox will make your signature public on GiverHub.';
						break;
				}

				$el.data('url', url);
				$el.data('options', o);
				$el.find('.twitter-button').attr('href', 'https://twitter.com/intent/tweet?hashtags=GiverHub&url='+encodeURIComponent(url));

				var $tooltip = $el.find('.giverhub-share-checkbox-wrapper').find('.gh_tooltip');

				if (typeof(o.hidden) === "boolean") {
					if (o.hidden) {
						$tooltip.attr('title', tooltip_unchecked);
					} else {
						$tooltip.attr('title', tooltip_checked);
					}
					$el.find('.share-on-giverhub-checkbox').prop('checked', !o.hidden);
				} else {
					$tooltip.attr('title', tooltip_checked);
					$el.find('.share-on-giverhub-checkbox').prop('checked', true);
				}
				$tooltip.attr('title-checked', tooltip_checked);
				$tooltip.attr('title-unchecked', tooltip_unchecked);
				$tooltip.tooltip('destroy').tooltip();

				$el.find('.share-on-giverhub-checkbox').data('type', type).data('id', id);
			};


			var $share_buttons_container = jQuery('.gh-share-buttons-container');

			$share_buttons_container.on('click', '.facebook-button', function() {
				try {
					var url = jQuery(this).closest('.gh-share-buttons-container').data('url');
					var options = jQuery(this).closest('.gh-share-buttons-container').data('options');

					if (options.event_type == 'donation') {
						FB.ui({
							method : 'share',
							href : url
						}, function (response) {
						});
					} else if (options.event_type == 'petition') {
						FB.ui({
							app_id : body.data('fb-app-id'),
							method : 'share',
							href : url
						}, function (response) {
							if (response !== null) {
								jQuery.ajax({
									url : '/petitions/fb_share',
									data : {
										petition_id : options.petition_id
									},
									type : 'post',
									dataType : 'json'
								});
							}
							giverhubSuccess({msg : 'Thank you for signing the petition.'});
						});
					}
				} catch (e) {
					giverhubError({e : e});
				}
			});

			$share_buttons_container.on('change', '.share-on-giverhub-checkbox', function() {
				try {
					var $cont = jQuery(this).closest('.gh-share-buttons-container')
					var $checkbox_wrapper = $cont.find('.giverhub-share-checkbox-wrapper');
					$checkbox_wrapper.find('label').addClass('hide');
					$checkbox_wrapper.find('.saving').removeClass('hide');

					var $this = jQuery(this);
					var checked = $this.prop('checked') ? 1 : 0;
					var type = $this.data('type');
					jQuery.ajax({
						url : '/members/toggle_hide_donation_or_petition',
						type : 'post',
						dataType : 'json',
						data : {
							type : $this.data('type'),
							id : $this.data('id'),
							pub : checked
						},
						error : function() {
							giverhubError({msg : 'Request failed!'});
						},
						complete : function() {
							$checkbox_wrapper.find('.saving').addClass('hide');
							$checkbox_wrapper.find('label').removeClass('hide');
						},
						success : function(json) {
							try {
								checkSuccess(json);

								if (type == 'donation') {
									if (!checked) {
										giverhubSuccess({msg : 'Your donation is now anonymous.'});
									} else {
										giverhubSuccess({msg : 'Your donation is now public.'});
									}
								} else if (type == 'petition') {
									if (!checked) {
										giverhubSuccess({msg : 'Your signature is now anonymous.'});
									} else {
										giverhubSuccess({msg : 'Your signature is now public.'});
									}
								}
							} catch(e) {
								giverhubError({e:e});
							}
						}
					});
				} catch(e) {
					giverhubError({e:e});
				}
			});
		} catch(e) {
			giverhubError({e:e});
		}
	});
} catch(e) {
	giverhubError({e:e});
}
