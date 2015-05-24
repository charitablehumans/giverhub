try {
	jQuery(document).ready(function() {
		try {
			jQuery('.edit-mission-textarea').wysihtml5();
			jQuery('.wysihtml5-sandbox').wysihtml5_size_matters();

			jQuery(document).on('click', '.btn-mission-submit', function() {
				var $this = jQuery(this);
				try {
					var $form = $this.closest('form');

					var $mission = $form.find('.edit-mission-textarea');
					var $source = $form.find('.edit-mission-source');

					var mission_value = $mission.val();
					var source_value = $source.val();

					if (mission_value.length < 10) {
						giverhubError({msg : 'Please type a longer mission statement. Atleast 10 characters.'});
						return false;
					}

					if (source_value.length < 6) {
						giverhubError({msg : 'Please enter a source, at least 6 characters.'});
						return false;
					}

					var charity_id = $this.data('charity-id');

					$this.button('loading');

					jQuery.ajax({
						url : '/mission/submit',
						type : 'post',
						dataType : 'json',
						data : {
							source : source_value,
							mission : mission_value,
							charity_id : charity_id
						},
						error : function() {
							giverhubError({msg : 'Request Failed.'});
						},
						complete : function() {
							$this.button('reset');
						},
						success : function(json) {
							try {
								if (!json || json === undefined || json.success === undefined || !json.success || json.missionsHtml === undefined || !json.missionsHtml) {
									giverhubError({msg : 'Bad response.'});
								} else {
									jQuery('#missions-container').html(json.missionsHtml);
									jQuery('#saved-mission').removeClass('hide');
									var opacity = 1.0;
									var fade = function() {

										jQuery('#saved-mission').css('opacity', opacity);
										if (opacity <= 0.0) {
											return;
										}
										opacity -= 0.05;

										setTimeout(fade, 100);
									};
									setTimeout(fade, 1500);
								}
							} catch(e) {
								giverhubError({e:e});
							}
						}
					});
				} catch(e) {
					giverhubError({e:e});
				}
				return false;
			});


			var request_queue = [];
			var request_running = false;
			jQuery(document).on('click', '.btn-mission-vote', function() {
				try {
					var $this = jQuery(this);
					var mission_id = $this.data('mission-id');

					var up = $this.hasClass('up');
					var down = $this.hasClass('down');
					var was_selected_before_click = $this.hasClass('selected');

					var $other = $this.siblings(up ? '.down' : '.up');

					$this.toggleClass('selected');



					var $vote_sum = $this.siblings('.vote-sum');
					var vote_sum = parseInt($vote_sum.html());
					if (was_selected_before_click) {
						if (up) {
							vote_sum -= 1;
						} else {
							vote_sum += 1;
						}
					} else {
						if ($other.hasClass('selected')) {
							$other.removeClass('selected');
							if (up) {
								vote_sum += 2;
							} else {
								vote_sum -= 2;
							}
						} else {
							if (up) {
								vote_sum += 1;
							} else {
								vote_sum -= 1;
							}
						}
					}

					$vote_sum.html(vote_sum);

					var vote = 0;
					if (was_selected_before_click) {
						vote = 0;
					} else {
						if (up) {
							vote = 1;
						} else {
							vote = -1;
						}
					}


					// add request to end of request_queue ..
					request_queue.push(function() {
						jQuery.ajax({
							url : '/mission/vote',
							type : 'post',
							dataType : 'json',
							data : { mission_id : mission_id, vote : vote },
							error : function() {
								giverhubError({msg : 'Request Failed'});
							},
							success : function(json) {
								try {
									if (json === undefined || !json || json.success === undefined || !json.success) {
										giverhubError({msg : 'Bad response.'});
									} else {

									}
								} catch(e) {
									giverhubError({e:e});
								}
							},
							complete : function() {
								// is there stuff left in the queue?
								if (request_queue.length) {
									var request = request_queue.shift(); // take the first in line
									request_running = true;
									request(); // and run it
								} else {
									request_running = false;
								}
							}
						});
					});

					// if request is not running,
					if (!request_running) {
						var request = request_queue.shift(); // take the first one in the queue ...
						request_running = true;
						request(); // ... and run it.
					}

				} catch(e) {
					giverhubError({e:e});
				}
			});

			jQuery(document).on('click', '.btn-delete-mission', function() {
				var $this = jQuery(this);
				try {

					var res = confirm('Are you sure that you want to delete your mission statement?');
					if (!res) {
						return false;
					}

					$this.button('loading');
					var mission_id = $this.data('mission-id');

					jQuery.ajax({
						url : '/mission/delete',
						type : 'post',
						dataType : 'json',
						data : { mission_id : mission_id },
						error : function() {
							giverhubError({msg : 'Request Failed.'});
						},
						complete : function() {
							$this.button('reset');
						},
						success : function(json) {
							try {
								if (json === undefined || !json || json.success === undefined || !json.success || json.missionsHtml === undefined) {
									giverhubError({msg : 'Bad Response.'});
								} else {
									jQuery('.edit-mission-textarea').data('wysihtml5').editor.setValue('', false);
									jQuery('#edit-mission-source').val('');
									jQuery('#missions-container').html(json.missionsHtml);
								}
							} catch(e) {
								giverhubError({e:e});
							}
						}
					});

				} catch(e) {
					giverhubError({e:e});
				}
				return false;
			});

		} catch(e) {
			giverhubError({e:e});
		}
	});
} catch(e) {
	giverhubError({e:e});
}