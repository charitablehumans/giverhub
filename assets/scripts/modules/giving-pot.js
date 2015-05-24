try {
	jQuery(document).ready(function() {
		try {
			var digitsOnlyRegex = /^\d+$/;
			var $edit_giving_pot_form_block = jQuery('.edit-giving-pot-form-block');
			var $giving_pot_preview_wrapper = jQuery('.giving-pot-preview-wrapper');

			if ($edit_giving_pot_form_block.length) {

				var $logo_wrapper = $edit_giving_pot_form_block.find('.logo-wrapper');
				var $logo_img = $logo_wrapper.find('img');
				var $logo_or_name_wrapper = $edit_giving_pot_form_block.find('.logo-or-name-wrapper');



				var $input_company_name = $edit_giving_pot_form_block.find('.input-company-name');
				var $input_pot_size = $edit_giving_pot_form_block.find('.input-pot-size');
				var $input_summary = $edit_giving_pot_form_block.find('.input-summary');
				var $input_body = $edit_giving_pot_form_block.find('.input-body');
				var $input_button_text = $edit_giving_pot_form_block.find('.input-button-text');
				var $input_button_url = $edit_giving_pot_form_block.find('.input-button-url');
				var $payment_method = $edit_giving_pot_form_block.find('.payment-method');

				$input_company_name.has_been_focused = false;
				$input_company_name.on('focus', function() {
					$input_company_name.has_been_focused = true;
				});

				$input_pot_size.has_been_focused = false;
				$input_pot_size.on('focus', function() {
					$input_pot_size.has_been_focused = true;
				});

				$input_summary.has_been_focused = false;
				$input_summary.on('focus', function() {
					$input_summary.has_been_focused = true;
				});

				$input_body.has_been_focused = false;
				$input_body.on('focus', function() {
					$input_body.has_been_focused = true;
				});

				$input_button_text.has_been_focused = false;
				$input_button_text.on('focus', function() {
					$input_button_text.has_been_focused = true;
				});

				$input_button_url.has_been_focused = false;
				$input_button_url.on('focus', function() {
					$input_button_url.has_been_focused = true;
				});



				function readPot() {
					var pot = {};
					if (!$logo_wrapper.hasClass('hide')) {
						pot.companyName = null;
						pot.companyLogo = $logo_img.attr('src');
					} else {
						pot.companyName = $input_company_name.val().trim();
						pot.companyLogo = null;
					}

					pot.potSize = $input_pot_size.val();
					pot.summary = $input_summary.val().trim();
					pot.body = $input_body.val().trim();
					function escapeHtml(text) {
						return text
							.replace(/&/g, "&amp;")
							.replace(/</g, "&lt;")
							.replace(/>/g, "&gt;")
							.replace(/"/g, "&quot;")
							.replace(/'/g, "&#039;");
					}
					function nl2br (str, is_xhtml) {
						var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';
						return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
					}
					pot.body = nl2br(escapeHtml(pot.body));
					pot.buttonText = $input_button_text.val().trim();
					pot.buttonUrl = $input_button_url.val().trim();

					return pot;
				}

				var urlRegex = /^http:\/\/|https:\/\//;

				function validatePot(triggerAll) {

					if (typeof(triggerAll) !== "boolean") {
						triggerAll = false;
					}
					var pot = readPot();

					var hasErrors = false;
					if ((triggerAll || $input_company_name.has_been_focused || $input_company_name.val().length) &&
						(typeof(pot.companyName) !== "string" || !pot.companyName.length) &&
						(typeof(pot.companyLogo) !== "string" || !pot.companyLogo.length)) {
						$edit_giving_pot_form_block.find('.logo-or-name-error').removeClass('hide');
						hasErrors = true;
					} else {
						$edit_giving_pot_form_block.find('.logo-or-name-error').addClass('hide');
					}

					var potSize = parseInt(pot.potSize);
					if ((triggerAll || $input_pot_size.has_been_focused || $input_pot_size.val().length) &&
						(!digitsOnlyRegex.test(pot.potSize) || isNaN(potSize) || potSize < 10)) {
						$edit_giving_pot_form_block.find('.pot-size-error').removeClass('hide');
						hasErrors = true;
					} else {
						$edit_giving_pot_form_block.find('.pot-size-error').addClass('hide');
					}

					if ((triggerAll || $input_summary.has_been_focused || $input_summary.val().length) &&
						(typeof(pot.summary) !== "string" || !pot.summary.length || pot.summary.length > 140)) {
						$edit_giving_pot_form_block.find('.summary-error').removeClass('hide');
						hasErrors = true;
					} else {
						$edit_giving_pot_form_block.find('.summary-error').addClass('hide');
					}

					if ((triggerAll || $input_body.has_been_focused || $input_body.val().length) &&
						(typeof(pot.body) !== "string" || !pot.body.length)) {
						$edit_giving_pot_form_block.find('.body-error').removeClass('hide');
						hasErrors = true;
					} else {
						$edit_giving_pot_form_block.find('.body-error').addClass('hide');
					}

					if ((triggerAll || $input_button_text.has_been_focused || $input_button_text.val().length) &&
						(typeof(pot.buttonText) !== "string" || !pot.buttonText.length)) {
						$edit_giving_pot_form_block.find('.button-text-error').removeClass('hide');
						hasErrors = true;
					} else {
						$edit_giving_pot_form_block.find('.button-text-error').addClass('hide');
					}


					if ((triggerAll || $input_button_url.has_been_focused || $input_button_url.val().length) &&
						(typeof(pot.buttonUrl) !== "string" || !pot.buttonUrl.length || !urlRegex.test(pot.buttonUrl))) {
						$edit_giving_pot_form_block.find('.button-url-error').removeClass('hide');
						hasErrors = true;
					} else {
						$edit_giving_pot_form_block.find('.button-url-error').addClass('hide');
					}

					if (triggerAll && !$payment_method.find('.selected-card').length) {
						$edit_giving_pot_form_block.find('.payment-method-error').removeClass('hide');
						hasErrors = true;
					} else {
						$edit_giving_pot_form_block.find('.payment-method-error').addClass('hide');
					}

					return !hasErrors;
				}

				function renderPreview() {
					$giving_pot_preview_wrapper.html(Handlebars.templates.giving_pot(readPot()));
				}

				var saveDraftTimeout;
				function saveDraft() {
					if (typeof(saveDraftTimeout) !== "undefined") {
						clearTimeout(saveDraftTimeout);
					}
					saveDraftTimeout = setTimeout(function() {
						$edit_giving_pot_form_block.find('.saving').removeClass('hide');
						jQuery.ajax({
							url : '/giving-pot/save-draft/'+$edit_giving_pot_form_block.data('giving-pot-id'),
							type : 'post',
							dataType : 'json',
							data : {pot: readPot()},
							complete : function() {
								$edit_giving_pot_form_block.find('.saving').addClass('hide');
								saveDraftTimeout = undefined;
							}
						});
					},1000);
				}

				$edit_giving_pot_form_block.on('click', '.btn-delete-giving-pot-logo', function() {
					var $this = jQuery(this);
					try {
						$this.button('loading');

						jQuery.ajax({
							url : '/upload/delete_giving_pot_logo',
							type : 'post',
							dataType : 'json',
							data : {
								'giving-pot-id' : $this.data('giving-pot-id')
							},
							error : function() {
								giverhubError({msg : 'Request Failed'});
							},
							complete : function() {
								$this.button('reset');
							},
							success : function(json) {
								try {
									checkSuccess(json);
									$logo_img.attr('src', $logo_img.data('empty-src'));
									$logo_wrapper.addClass('hide');
									$logo_or_name_wrapper.removeClass('hide');
									renderPreview();
									validatePot();
									saveDraft();
								} catch(e) {
									giverhubError({e:e});
								}
							}
						});
					} catch(e) {
						giverhubError({e:e});
					}
				});

				jQuery('#upload-giving-pot-logo-form')
					.fileupload()
					.bind(
						'fileuploaddone',
						function (a, b) {
							try {
								var d = jQuery.parseJSON(b.result);

								switch (d[0].error) {
									case undefined:
										$logo_or_name_wrapper.addClass('hide');
										$logo_img.attr('src', d[0].url);
										$logo_wrapper.removeClass('hide');
										$input_company_name.val('');
										$input_company_name.has_been_focused = true;
										renderPreview();
										validatePot();
										saveDraft();

										break;
									case 'acceptFileTypes':
										giverhubError({msg: 'Bad File Type. We only accept images, such as jpg, png and gif.'});
										break;
									default:
										giverhubError({msg : 'Unexpected Error: ' + d[0].error});
										break;
								}

							} catch (e) {
								giverhubError({e : e});
							}
						}
					)
					.bind(
						'fileuploadstart',
						function () {

						}
					)
					.bind(
						'fileuploadfail',
						function (a, b) {
							giverhubError({msg: 'Something went wrong when uploading the file."' + b.errorThrown + '". Notice that files bigger than 10Mb should not be uploaded. Thank you for your patience.'});
						}
					);


				$edit_giving_pot_form_block.on('input', '.trigger-render', function() {
					try {
						renderPreview();
						validatePot();
						saveDraft();
					} catch(e) {
						giverhubError({e:e});
					}
				});

				$edit_giving_pot_form_block.on('click', '.btn-publish-giving-pot', function() {
					var $this = jQuery(this);
					try {
						if (!validatePot(true)) {
							giverhubError({
								subject : 'Problems with the form',
								msg : 'Some fields are wrong or missing. Please check the form.'
							});
							return false;
						}

						$this.button('loading');

						jQuery.ajax({
							url : '/giving-pot/publish/'+$edit_giving_pot_form_block.data('giving-pot-id'),
							type : 'post',
							dataType : 'json',
							data : {
								pot : readPot()
							},
							error : function() {
								giverhubError({msg : 'Request Failed'});
							},
							complete : function() {
								$this.button('reset');
							},
							success : function(json) {
								try {
									checkSuccess(json);

									if (typeof(json.errors) === "object") {
										var msg = '';
										jQuery.each(json.errors, function(field,error) {
											msg += error['msg'] + '<br>';
										});
										giverhubError({msg: msg});
									} else {
										giverhubSuccess({
											subject : 'Great!',
											msg : 'The pot has been published, you are now being taken to the dashboard. Please wait...'
										});
										window.location = '/giving-pot/dashboard/'+$edit_giving_pot_form_block.data('giving-pot-id');
									}
								} catch(e) {
									giverhubError({e:e});
								}
							}
						});
					} catch(e) {
						giverhubError({e:e});
						$this.button('reset');
					}
					return false;
				});

				validatePot();
			}

			var $giving_pot_recipients_block = jQuery('.giving-pot-recipients-block');

			if ($giving_pot_recipients_block.length) {
				var $tbody = $giving_pot_recipients_block.find('tbody');
				var $recipients_form = $giving_pot_recipients_block.find('.giving-pot-recipients-form');
				var recipients = [
					{num: 1},
					{num: 2},
					{num: 3},
					{num: 4}
				];

				function renderRecipients() {
					$tbody.html('');
					jQuery.each(recipients, function (i, recipient) {
						$tbody.append(Handlebars.templates.giving_pot_recipient_form_row(recipient));
					});
				}
				renderRecipients();

				$giving_pot_recipients_block.on('click', '.btn-add-more-recipients', function() {
					try {
						recipients = [];
						var $trs = $tbody.find('tr');
						$tbody.find('tr').each(function(i,tr) {
							var $tr = jQuery(tr);
							var recipient = {
								num : i+1,
								name : $tr.find('.name').find('input').val(),
								email : $tr.find('.email').find('input').val(),
								amount : $tr.find('.amount').find('input').val()
							};
							recipients.push(recipient);
						});

						recipients.push({num : $trs.length+1});

						renderRecipients();
					} catch(e) {
						giverhubError({e:e});
					}
					return false;
				});


				var emailRegex = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;


				function validateRecipients() {
					$tbody.find('tr').each(function(i,tr) {
						var $tr = jQuery(tr);
						var $name = $tr.find('.name').find('input');
						var $email = $tr.find('.email').find('input');
						var $amount = $tr.find('.amount').find('input');

						var name = $name.val().trim();
						var email = $email.val().trim();
						var amount = $amount.val().trim();

						if (!name.length && !email.length && !amount.length) {
							$tr.find('input').removeClass('error').tooltip('destroy');
							return true; // same as "continue" in a php loop
						}

						if (!name.length) {
							$name.tooltip('destroy').addClass('error').tooltip({title : 'Name is required!'});
						} else {
							$name.removeClass('error').tooltip('destroy');
						}

						if (!amount.length || isNaN(parseInt(amount)) || amount < 10 || !digitsOnlyRegex.test(amount)) {
							$amount.tooltip('destroy').addClass('error').tooltip({title : 'Must be $10 or more.'});
						} else {
							$amount.removeClass('error').tooltip('destroy');
						}

						if (!email.length || !emailRegex.test(email)) {
							$email.tooltip('destroy').addClass('error').tooltip({title : 'Email is invalid!'});
						} else {
							$email.removeClass('error').tooltip('destroy');
						}

					});
				}

				$recipients_form.on('input', 'input', function() {
					try {
						validateRecipients();
					} catch(e) {
						giverhubError({e:e});
					}
				});


				$giving_pot_recipients_block.on('click', '.btn-send-givercards', function() {
					var $this = jQuery(this);
					try {
						$this.button('loading');

						jQuery.ajax({
							url : '/giving-pot/add-recipients/'+$giving_pot_recipients_block.data('giving-pot-id'),
							type : 'post',
							dataType : 'json',
							data :$recipients_form.serialize(),
							error : function() {
								giverhubError({msg : 'Request Failed.'});
							},
							complete : function() {
								$this.button('reset');
							},
							success : function(json) {
								try {
									checkSuccess(json);

									if (typeof(json.errors) === "object") {
										jQuery.each(json.errors, function (n, errors) {
											var $tr = $tbody.find('tr:nth-child(' + (n + 1) + ')');
											jQuery.each(errors, function (name, msg) {
												var $input = $tr.find('.' + name).find('input');
												$input.tooltip({title : msg});
												$input.addClass('error');
											});
										});
									} else if (typeof(json.error_msg) === "string") {
										giverhubError({msg : json.error_msg});
									} else if (typeof(json.no_recipients) === "boolean" && json.no_recipients) {
										giverhubError({subject : 'No recipients.', msg : 'You seem to have not added any recipients.'});
									} else {
										giverhubSuccess({subject : 'Great!', msg: 'The recipient(s) should have received their giver cards.'});
										recipients = [
											{num: 1},
											{num: 2},
											{num: 3},
											{num: 4}
										];
										renderRecipients();
										$giving_pot_preview_wrapper.html(Handlebars.templates.giving_pot(json.pot));
										jQuery('.giving-pot-existing-recipients-block').html(json.existing_recipients_html);
									}
								} catch(e) {
									giverhubError({e:e});
								}
							}
						});
					} catch(e) {
						$this.button('reset');
						giverhubError({e:e});
					}
				});
			}
		} catch(e) {
			giverhubError({e:e});
		}
	});
} catch(e) {
	giverhubError({e:e});
}