/**
 *  Module file for member-settings.js
 */
jQuery(document).ready(function(){

	try {

		/*-----------------------------------*/
		/*	USER INFO  */
		/*-----------------------------------*/
		if ($('.gh_personal_info').length > 0) {

			$('.gh_edit').click(function () {

				if ($(this).hasClass('saving')) {
					return false;

				}else if (!$(this).hasClass('save_btn')) {
					var i = $(this).parent().find('strong').text();

					$('.gh_personal_info input').fadeOut(200);
					$('.gh_personal_info .gh_edit').html('Edit <i class="icon-edit"></i>').removeClass('save_btn');

					$(this).addClass('save_btn');
					$(this).parent().find('input').val(i).fadeIn(200);
					$(this).html('Save <i class="icon-save"></i>');

				} else {

					var i = $(this).parent().find('input').val();
					var key = $(this).data('key');
					var type = $(this).data('type');
					var el = $(this)

					el.html('Saving ... ').removeClass('save_btn').addClass('saving');

					jQuery.ajax({
						url : '/members/settings/save/'+type,
						data : {
							'key' : key,
							'value' : i
						},
						error : function () {
							giverhubError({
								msg : 'Request Failed.'
							});
							el.html('Save <i class="icon-save"></i>').addClass('save_btn').removeClass('saving');
						},
						complete : function () {

						},
						success : function (json) {
							try {
								if (json === undefined || !json || json.status === undefined || !json.status) {
									giverhubError({
										msg : 'Bad response from server.'
									});
									el.html('Save <i class="icon-save"></i>').addClass('save_btn').removeClass('saving');

								} else {
									if(json.status == 'failed'){
										giverhubError({
											msg : json.message
											});
										el.html('Save <i class="icon-save"></i>').addClass('save_btn').removeClass('saving');

									}else{
										el.parent().find('strong').text(i);
										el.parent().find('input').fadeOut(200);
										el.html('Edit <i class="icon-edit"></i>').removeClass('saving');
										giverhubSuccess({
											subject : 'Successfully ',
											msg : json.message
											});
									}
								}
							} catch (e) {
								giverhubError({
									e : e
								});
							}
						},
						dataType : 'json',
						type : 'post'
					});

				}

				return false;
			});

			jQuery(document).on('keypress', '#new-password, #confirm-new-password', function(event) {
				try {
					if (event.charCode == 13) { // enter
						jQuery(this).parent().find('.save_btn').trigger('click');
					}
				} catch(e) {
					giverhubError({e:e});
				}
			});

			jQuery('.gh_edit_password').click(function () {
				try {
					var $this = jQuery(this);
					if ($this.hasClass('saving')) {
						return false;
					} else if (!$this.hasClass('save_btn')) {
						var $parent = $this.parent();

						$parent.addClass('showing');
						var i = $parent.find('strong').text();

						jQuery('.gh_personal_info input').fadeOut(200);
						jQuery('.gh_personal_info .gh_edit').html('Edit <i class="icon-edit"></i>').removeClass('save_btn');

						$this.addClass('save_btn');
						$parent.find('input').val('').fadeIn(200);
						$this.html('Save <i class="icon-save"></i>');
					} else {
						var newPassword = $this.parent().find('input#new-password').val();
						var confirmPassword = $this.parent().find('input#confirm-new-password').val();

						if (newPassword !== confirmPassword) {
							giverhubError({
								msg : 'Passwords do not match'
							});
							return false;
						}

						if (newPassword == '') {
							giverhubError({
								msg : 'Please enter new password twice'
							});
							return false;
						}

						var key = $this.data('key');
						var type = $this.data('type');
						var el = $this;

						el.html('Saving ... ').removeClass('save_btn').addClass('saving');

						jQuery.ajax({
							url : '/members/settings/save/' + type,
							data : {
								'key' : key,
								'value' : newPassword
							},
							error : function () {
								giverhubError({
									msg : 'Request Failed.'
								});
								el.html('Save <i class="icon-save"></i>').addClass('save_btn').removeClass('saving');
							},
							complete : function () {

							},
							success : function (json) {
								try {
									if (json === undefined || !json || json.status === undefined || !json.status) {
										giverhubError({
											msg : 'Bad response from server.'
										});
										el.html('Save <i class="icon-save"></i>').addClass('save_btn').removeClass('saving');
									} else {
										if (json.status == 'failed') {
											giverhubError({
												msg : json.message
											});
											el.html('Save <i class="icon-save"></i>').addClass('save_btn').removeClass('saving');
										} else {
											el.parent().find('strong').text(json.time_message);
											el.parent().find('input').val('').fadeOut(200);
											el.html('Edit <i class="icon-edit"></i>').removeClass('saving');
											el.parent().removeClass('showing');
											giverhubSuccess({
												subject : 'Password is ',
												msg : json.message
											});
										}
									}
								} catch (e) {
									giverhubError({
										e : e
									});
									el.html('Save <i class="icon-save"></i>').addClass('save_btn');
								}
							},
							dataType : 'json',
							type : 'post'
						});
					}
					return false;
				} catch(e) {
					giverhubError({e:e});
				}
			});
		}

		function switchSwitch($el, on) {
			var w = parseInt($el.width()) - 2;
			var checked = $el.parent().find('input').attr('checked');
			if (on && !checked) {
				$el.parent().find('input').attr('checked', true);
				$el.animate({left : w / 2.5}, 200);

			} else if (!on && checked) {
				$el.parent().find('input').attr('checked', false);
				$el.animate({left : '-2%'}, 200);
			}
		}


		$('.gh_switch_save .gh_btn_switch').each(function(i,e) {
			window.enableSwitch(e);
		});

		/*
		 * Site settings switch Save !
		 *
		 */

		if($('.site-settings-block').length){
			jQuery(document).on('click', '.site-settings-block .switch', function () {
				var el = $(this);

				var parent = el.closest('.gh_btn_switch');
				if (parent.hasClass('instant-donation-switch')) {
					return false;
				}

				var value = 0;
				if (parent.data('checked') === "1") {
					value = 0;
					parent.data('checked', '0');
				} else {
					value = 1;
					parent.data('checked', '1');
				}
				setTimeout(function(){
					var type = el.parent().data('type');
					var input = el.parent().find('input');


					jQuery.ajax({
						url : '/members/settings/save/settings',
						data : {
							'key' : type,
							'value' : value
						},
						error : function () {
							giverhubError({
								msg : 'Request Failed.'
							});

						},
						complete : function () {

						},
						success : function (json) {
							try {
								if (json === undefined || !json || json.status === undefined || !json.status) {
									giverhubError({
										msg : 'Bad response from server.'
									});
								} else {
									if(json.status == 'failed'){
										giverhubError({
											msg : json.message
										});
									}else{
										giverhubSuccess({
											subject : json.msg_subject,
											msg : json.msg_msg
										});
									}
								}
							} catch (e) {
								giverhubError({
									e : e
								});
							}
						},
						dataType : 'json',
						type : 'post'
					});
				}, 1);
			});
		}

		var $recurringDonationsContainer = jQuery('.recurring-donations-container');
		if ($recurringDonationsContainer.length) {
			jQuery.ajax({
				url : '/members/get_recurring_donations',
				type : 'get',
				dataType : 'json',
				error : function() {
					//giverhubError({subject: 'Could not load recurring donations!', msg : 'Request failed!'});
				},
				success : function(json) {
					try {
						if (json === undefined || !json || json.success === undefined || !json.success || json.html === undefined) {
							giverhubError({subject : 'Could not load recurring donations.', msg : (json.msg === undefined ? 'Bad response from server' : json.msg)});
						} else {
							jQuery('.recurring-donations-container').html(json.html);
							jQuery('.recurring-donations-container .gh_tooltip').tooltip();
						}
					} catch(e) {
						giverhubError({e:e});
					}
				}
			});

			$recurringDonationsContainer.on('click', '.btn-cancel-recurring-donation', function() {
				var btn = jQuery(this);
				try {
					btn.button('loading');

					var recurringDonationId = btn.data('recurring-donation-id');
					if (!recurringDonationId) {
						btn.button('reset');
						giverhubError({msg : 'Recurring donation id missing!'});
						return false;
					}

					jQuery.ajax({
						url : '/members/cancel_recurring_donation',
						type : 'post',
						dataType : 'json',
						data : { recurringDonationId : recurringDonationId },
						error : function() {
							giverhubError({msg : 'Request failed!'});
						},
						success : function(json) {
							try {
								if (json === undefined || !json || json.success === undefined || !json.success) {
									giverhubError({subject : 'Could not cancel..', msg : (json.msg === undefined ? 'Bad response from server' : json.msg)});
								} else {
									giverhubSuccess({subject : 'Success!', msg : 'The recurring donation was canceled!'});
									btn.parent().parent().find('.next-date').html('Cancelled');
									btn.parent().html('Cancelled');
								}
							} catch(e) {
								giverhubError({e:e});
							}
						},
						complete : function() {
							btn.button('reset');
						}
					});

				} catch(e) {
					btn.button('reset');
					giverhubError({e:e});
				}
				return false;
			});

			$recurringDonationsContainer.on('click', '.recurring-donation-notify-checkbox', function() {
				var $this = jQuery(this);
				var $img = $this.siblings('img');

				try {

					var notify = $this.prop('checked') ? '1' : '0';
					var recurring_donation_id = $this.data('recurring-donation-id');
					var checked = $this.prop('checked');

					jQuery.ajax({
						url : '/members/recurring_donation_notify',
						type : 'post',
						dataType : 'json',
						data : { notify : notify, recurring_donation_id : recurring_donation_id },
						error : function() {
							giverhubError({msg : 'Request Failed.'});
							$this.prop('checked', !checked);
						},
						beforeSend : function() {
							$this.hide();
							$img.show();
						},
						complete : function() {
							$this.show();
							$img.hide();
						},
						success : function(json) {
							try {
								if (json === undefined || !json || json.success === undefined || !json.success) {
									giverhubError({msg : 'Bad response'});
									$this.prop('checked', !checked);
								}
							} catch(e) {
								giverhubError({e:e});
								$this.prop('checked', !checked);
							}
						}
					});
					return true;
				} catch(e) {
					$img.hide();
					return false;
					$this.show();
					giverhubError({e:e});
				}

			});
		}

		var $nonprofit_data = jQuery('#nonprofit-data');

		if ($nonprofit_data.length) {
			$nonprofit_data.on('change', 'input', function() {
				try {
					var $this = jQuery(this);
					var checked = $this.prop('checked');
					var name = $this.attr('name');
					var $span = $this.parent().find('span');
					$span.css('display', 'inline');

					jQuery.ajax({
						url : '/members/save_setting',
						dataType : 'json',
						type : 'post',
						data : {
							name : name,
							value : checked ? '1' : '0'
						},
						error : function() {
							giverhubError({msg : 'Request Failed'});
							$this.prop('checked', !checked);
							$span.html('fail!').removeClass('hide');
						},
						success : function(json) {
							try {
								if (typeof(json) !== "object" || typeof(json.success) !== "boolean" || !json.success) {
									giverhubError({msg : 'Bad response'});
									$this.prop('checked', !checked);
									$span.html('fail!').removeClass('hide');
								} else {
									$span.html('saved!').removeClass('hide');
									$span.fadeOut(1000, function() {
										$span.addClass('hide');
									});
								}
							} catch(e) {
								$this.prop('checked', !checked);
								$span.html('fail!').removeClass('hide');
								giverhubError({e:e});
							}
						}
					});
				} catch(e) {
					giverhubError({e:e});
				}
			});

			$nonprofit_data.on('click', 'tr', function() {
				try {
					jQuery(this).find('input').trigger('click');
				} catch(e) {
					giverhubError({e:e});
				}
			});

			$nonprofit_data.on('click', 'input', function(e) {
				try {
					e.stopPropagation();
				} catch(e) {
					giverhubError({e:e});
				}
			});
		}

		jQuery(document).on('click', '.btn-delete-account', function() {
			var $this = jQuery(this);
			try {
				giverhubPrompt({
					msg : 'Are you sure that you want to delete your account?',
					yes : function() {
						try {
							$this.button('loading');

							jQuery.ajax({
								url : '/members/delete_account',
								type : 'post',
								dataType : 'json',
								error : function () {
									giverhubError({msg : 'Request Failed.'});
								},
								complete : function () {
									$this.button('reset');
								},
								success : function (json) {
									try {
										checkSuccess(json);

										if (typeof(json.failed) === "boolean" && json.failed) {
											giverhubError({msg : 'Sorry, something went wrong when deleting your account. Our admins have been notified and will manually delete your account and notify you by email.'});
										} else {
											giverhubSuccess({msg : 'Your account has been deleted...'});
											window.location = '/';
										}
									} catch (e) {
										giverhubError({e : e});
									}
								}
							});
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