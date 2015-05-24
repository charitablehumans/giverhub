try {
	if (GIVERHUB_LIVE) {
		jQuery(document).ready(function() {
			try {
				jQuery(document).on('click', '.gh-trigger-event', function() {
					try {
						var $this = jQuery(this);
						ga('send', 'event', $this.data('event-category'), $this.data('event-action'), $this.data('event-label'));
						if (GIVERHUB_DEBUG) {
							console.log('send event category: ' + $this.data('event-category') + ' action: ' + $this.data('event-action') + ' label: ' + $this.data('event-label'));
						}
					} catch(e) {
						giverhubError({e:e});
					}
				});

				jQuery(document).on('show.bs.modal', function (event) {
					try {
						var delay_event = function() {
							ga('send', 'event', 'modal', 'show', '#' + event.target.id);
							ga('send','pageview','/virtual/modal/show/'+event.target.id);
							if (GIVERHUB_DEBUG) {
								console.log('send event category: modal action: show label: #' + event.target.id);
							}
						};
						setTimeout(delay_event,666);
					} catch(e) {
						giverhubError({e:e});
					}
				});

				jQuery('.trigger-ga-event').each(function(i,e) {
					var $e = jQuery(e);
					ga('send','pageview',$e.data('url'));
				});

			} catch(e) {
				giverhubError({e:e});
			}
		});
	}
} catch(e) {
	giverhubError({e:e});
}