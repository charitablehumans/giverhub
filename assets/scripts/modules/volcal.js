try {
	jQuery(document).ready(function() {
		try {
			var $vol_cal_block = jQuery('.vol-cal-block');

			window.isEmbed = false;

			if (jQuery(document).hasClass('embedded-vol-cal')) {
				window.isEmbed = true;
			}


			var calendar_options = {
				header : {
					left: 'prev,next today',
					center: 'title',
					right: 'month,agendaWeek,agendaDay'
				},
				height: 420,
				eventLimit: true,
				eventLimitClick : 'popover',
				eventClick: function(calEvent, jsEvent, view) {
					if (window.isEmbed) {
						window.top.location.href = calEvent.url;
						return false;
					}
					try {
						var $vol_event_modal = jQuery('#vol-event-modal');
						$vol_event_modal.find('.modal-body').find('.wrapper').html(jQuery('#vol-event-block-' + calEvent.id).html());
						$vol_event_modal.find('.btn-volunteer').tooltip();
						$vol_event_modal.modal('show');
					} catch(e) {
						giverhubError({e:e});
					}
					return false;
				}
			};

			window.refreshOpportunityCalendar = function($vol_cal) {
				var timezone = $vol_cal.data('timezone');
				jQuery.ajax({
					url : '/charity/vol_cal/'+$vol_cal.data('nonprofit-id'),
					data : { timezone : timezone },
					type : 'get',
					dataType : 'json',
					error : function() {
						giverhubError({msg : 'Request Failed'});
					},
					success : function(json) {
						try {
							checkSuccess(json);
							$vol_cal.fullCalendar('destroy');
							if ($vol_cal.data('default-date')) {
								calendar_options.defaultDate = $vol_cal.data('default-date');
							}
							calendar_options.events = json.events;
							$vol_cal.fullCalendar(calendar_options);
						} catch(e) {
							giverhubError({e:e});
						}
					}
				});
			};

			if ($vol_cal_block.length) {
				$vol_cal_block.each(function(i,block) {
					try {
						var $block = jQuery(block);

						var $vol_cal = $block.find('.vol-cal');
						if ($vol_cal.hasClass('embed')) {
							window.isEmbed = true;
						}
						if ($vol_cal.data('default-date')) {
							calendar_options.defaultDate = $vol_cal.data('default-date');
						}
						if ($vol_cal.data('cal-height')) {
							calendar_options.height = $vol_cal.data('cal-height');
						}
						var opps = $vol_cal.data('events');
						var events = [];
						jQuery.each(opps, function (i, opp) {
							events.push(opp.cal_events[0]);
						});
						calendar_options.events = events;
						$vol_cal.fullCalendar(calendar_options);
						$vol_cal.data('event-array', events);

						$block.on('change', '.select-timezone', function () {
							var $this = jQuery(this);
							$vol_cal.data('timezone', $this.val());
							window.refreshOpportunityCalendar($vol_cal);
						});
					} catch(e) {
						giverhubError({e:e});
					}
				});

				$vol_cal_block.on('click', '.btn-show-calendar', function() {
					try {
						var $this = jQuery(this);
						var $block = $this.closest('.vol-cal-block');
						var $vol_cal = $block.find('.vol-cal');
						$vol_cal.removeClass('hide');
						$this.addClass('hide');
						if ($vol_cal.data('default-date')) {
							calendar_options.defaultDate = $vol_cal.data('default-date');
						}
						var opps = $vol_cal.data('events');
						var events = [];
						jQuery.each(opps, function (i, opp) {
							events.push(opp.cal_events[0]);
						});
						calendar_options.events = events;
						$vol_cal.fullCalendar('destroy');
						$vol_cal.fullCalendar(calendar_options);
						$vol_cal.data('event-array', events);
					} catch(e) {
						giverhubError({e:e});
					}
				});
			}



			jQuery('.embed-vol-cal-block').on('click', '.btn-vol-cal-embed', function() {
				try {
					jQuery('#embed-vol-cal-modal').modal('show');
				} catch(e) {
					giverhubError({e:e});
				}
			});

			var $vol_msg_modal = jQuery('#vol-msg-modal');
			if ($vol_msg_modal.length) {
				$vol_msg_modal.find('textarea').elastic();
				$vol_msg_modal.removeClass('fade');
			}

			jQuery(document).on('click', '.btn-volunteer', function() {
				var $this = jQuery(this);
				try {
					var $textarea = $vol_msg_modal.find('textarea');
					$textarea.val($textarea.data('default'));
					$vol_msg_modal.find('.event-wrapper').html(jQuery('#vol-event-block-' + $this.data('event-id')).html());

					$vol_msg_modal.data('event-id', $this.data('event-id'));
					$vol_msg_modal.modal('show');

				} catch(e) {
					giverhubError({e:e});
				}
			});

			$vol_msg_modal.on('shown.bs.modal', function (e) {
				var $textarea = $vol_msg_modal.find('textarea');
				$textarea.css('padding-bottom', ($vol_msg_modal.find('.event-wrapper').height()+10)+'px');
			});

			$vol_msg_modal.on('click', '.btn-send-vol-request', function() {
				var $this = jQuery(this);
				try {
					$this.button('loading');

					jQuery.ajax({
						url : '/members/send_vol_request',
						type : 'post',
						data : { event_id : $vol_msg_modal.data('event-id'), msg : $vol_msg_modal.find('textarea').val().trim() },
						dataType : 'json',
						error : function() {
							giverhubError({msg : 'Request Failed'});
							$this.button('reset');
						},
						success : function(json) {
							try {
								checkSuccess(json);

								$this.button('reset');
								$vol_msg_modal.modal('hide');
								jQuery('#vol-event-modal').modal('hide');
								giverhubSuccess({msg : 'Thanks for volunteering! The nonprofit will get back to you asap. Please check your messages by clicking the messages menu item on the left side of your dashboard.'})
							} catch(e) {
								giverhubError({e:e});
								$this.button('reset');
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