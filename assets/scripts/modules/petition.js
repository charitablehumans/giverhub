try {
	jQuery(document).ready(function() {
		try {

			function show_sign_in_first_modal(petitionId) {
				jQuery.cookie('sign-in-to-sign-petition', petitionId, { path: '/' });
				window.signInOrJoinFirst('You need to be signed in to sign petitions');
			}



			jQuery(document).on('click', '.sign-petition-reason-overview', function() {
				try {
					if (!body.data('signed-in')) {
						show_sign_in_first_modal();
						return false;
					}
				} catch(e) {
					giverhubError({e:e});
				}
			});

			jQuery(document).on('click', '.btn-hide-signature', function() {
				try {
					if (!body.data('signed-in')) {
						show_sign_in_first_modal();
						return false;
					}
				} catch(e) {
					giverhubError({e:e});
				}
			});

			jQuery(document).on('click', '.btn-share-on-facebook', function() {
				try {
					if (!body.data('signed-in')) {
						show_sign_in_first_modal();
						return false;
					}
				} catch(e) {
					giverhubError({e:e});
				}
			});

			var $share_petition_modal = jQuery('#share-petition-modal');
			$share_petition_modal.on('hidden.bs.modal', function (e) {
				if ($body.hasClass('petitions')) {
					window.location = '/';
				}
			});

			jQuery(document).on('click', '.btn-sign-petition-overview', function() {
				var btn = jQuery(this);

				try {
					var petitionId = btn.data('petition-id');

					if (!body.data('signed-in')) {
						show_sign_in_first_modal(petitionId);
						return false;
					}

					if (btn.hasClass('unsign')) {
						giverhubSuccess({
							hideOk : true,
							subject : 'Instructions for unsigning this petition:',
							msg : 	'Step 1: Go to the change.org email confirming your signature<br/><br/>' +
									'Step 2: At the bottom of the email, click on the link that appears after the text “Didn’t sign this petition?”<br/><br/>' +
									'That’s it! Your signature will be automatically removed'
						});
						return false;
					}

					if (!body.data('default-address-id')) {
						window.prepareAddressModalForAdd('Before you can sign petitions change.org requires your address');
						jQuery('#address-modal').modal('show').data('from', 'petition').data('petition-id', btn.data('petition-id'));
						return false;
					}

					btn.button('loading');

					var $form = btn.closest('form');

					var reason = $form.find('.sign-petition-reason-overview').val().trim();

					var $sign_petition_hidden_overview = $form.find('.sign-petition-hidden-overview');
					var hidden;
					if ($sign_petition_hidden_overview.length) {
						hidden = $sign_petition_hidden_overview.is(':checked');
					} else {
						hidden = $form.find('.btn-hide-signature').hasClass('active');
					}


					var shareOnFacebook;
					var $share_petition_signature_on_facebook = $form.find('.share-petition-signature-on-facebook');
					if ($share_petition_signature_on_facebook.length) {
						shareOnFacebook = $share_petition_signature_on_facebook.is(':checked');
					}


					jQuery.ajax({
						url : '/petitions/sign',
						type : 'post',
						dataType : 'json',
						data : {
							reason : reason,
							hidden : hidden,
							petitionId : petitionId
						},
						error : function() {
							giverhubError({msg : 'Oops, request failed. We apologize and will work on resolving this issue so that you can sign petitions. Please try again later.'});
							btn.button('reset');
						},
						complete : function() {

						},
						success : function(json) {
							try {
								if (!json || json === undefined || json.success === undefined || !json.success) {
									if (json.msg === undefined || !json.msg) {
										giverhubError({msg : 'Request failed. Bad response from server. Please try again later.'});
									} else {
										if (json.msg.match('phone must match')) {

											giverhubSuccess({
												hideOk : true,
												subject : json.msg,
												msg : 'You will now be taken to change your phone number.',
												okButtonTitle : 'Proceed'
											});

											giverhubSuccess.hideEvent = function() {
												jQuery('#hidden-addresses .default-address').trigger('click');
											};

										} else {
											giverhubError({msg : json.msg});
										}
									}
									btn.button('reset');
								} else {
									btn.button('reset');
									btn.attr('disabled','disabled');
									jQuery('#sign-petition-reason-overview').attr('disabled', 'disabled');
									jQuery('#sign-petition-hidden-overview').attr('disabled', 'disabled');

									if (GIVERHUB_LIVE) {
										ga('send', 'event', 'petition', 'sign');
									}

									var $share_petition_modal = jQuery('#share-petition-modal');
									$share_petition_modal.on('hidden.bs.modal', function (e) {
										// do something...
									});
									window.initShareButtons({
										el : $share_petition_modal.find('.gh-share-buttons-container'),
										url : btn.data('petition-url'),
										event_type : 'petition',
										event_id : json.signature_id,
										petition_id : petitionId,
										hidden : hidden
									});
									if (typeof(shareOnFacebook) === "undefined") {
										$share_petition_modal.modal('show');
									} else if (shareOnFacebook) {
										FB.ui({
											app_id: body.data('fb-app-id'),
											method: 'share',
											href: btn.data('petition-url')
										}, function(response){
											if (response !== null) {
												jQuery.ajax({
													url : '/petitions/fb_share',
													data : {
														petition_id : petitionId
													},
													type : 'post',
													dataType : 'json'
												});
											}
											$share_petition_modal.modal('show');
										});
									} else {
										$share_petition_modal.modal('show');
									}
									jQuery('.btn-hide-signature').addClass('hide');
									btn.addClass('unsign').addClass('unsign-special');
									btn.html('Unsign');

									jQuery('#sign-petition-modal').modal('hide');
								}
							} catch (e) {
								giverhubError({e:e});
							}
						}
					});
				} catch(e) {
					btn.button('reset');
					giverhubError({e:e});
				}
				return false;
			});

			if (body.data('signed-in') && typeof(jQuery.cookie('sign-in-to-sign-petition')) !== "undefined" && jQuery.cookie('sign-in-to-sign-petition')) {
				var petition_id = jQuery.cookie('sign-in-to-sign-petition');
				jQuery('.btn-sign-petition-overview').each(function(i,btn) {
					var $btn = jQuery(btn);
					if ($btn.hasClass('unsign')) {
						return true;
					}
					var pet_id = $btn.data('petition-id');
					if (pet_id == petition_id) {
						jQuery.removeCookie('sign-in-to-sign-petition', { path: '/' });
						$btn.trigger('click');
						return false;
					}
				});
			}

			jQuery(document).on('click', '#expand-petition-overview', function() {
				jQuery('#petition-overview-container').addClass('expanded');
				jQuery('#petition_show_normal').css('display','none');
				jQuery('#petition_show_more').css('display','block');
				jQuery(this).parent().hide();
				return false;
			});


			var featurePetitionModal = jQuery('#feature-petition-modal');
			var featurePetitionModalTextArea = jQuery('#featured-text-textarea');
			var featurePetitionModalCheckBox= jQuery('#feature-this-petition-checkbox');

			function resetFeaturePetitionModal() {
				featurePetitionModalCheckBox.prop('checked', featurePetitionModal.data('is-featured') ? true : false);
				featurePetitionModalTextArea.val(featurePetitionModal.data('featured-text'));
			}

			jQuery(document).on('click', '.btn-feature-petition', function() {
				resetFeaturePetitionModal();
				featurePetitionModal.modal('show');
				return false;
			});

			jQuery(document).on('click', '.btn-submit-feature-petition-modal', function() {
				var btn = jQuery(this);
				btn.button('loading');

				var featuredText = featurePetitionModalTextArea.val();
				var isFeatured = featurePetitionModalCheckBox.prop('checked');

				var data = {
					featuredText : featuredText,
					isFeatured : isFeatured ? 1 : 0,
					petitionId : featurePetitionModal.data('petition-id')
				};

				jQuery.ajax({
					url : '/petitions/feature',
					type : 'post',
					data : data,
					dataType : 'json',
					error : function() {
						giverhubError({msg : 'Request failed.'});
					},
					success : function(json) {
						if (!json || !json.success) {
							giverhubError({msg : 'Bad response.'});
						} else {
							featurePetitionModal.data('is-featured', isFeatured);
							featurePetitionModal.data('featured-text', featuredText);
							featurePetitionModal.modal('hide');
						}
					},
					complete : function() {
						btn.button('reset');
					}
				});
			});

			var $petitionTitle = jQuery('.petition-title-dotdotdot');
			if ($petitionTitle.length) {
				$petitionTitle.ellipsis({setTitle : 'onEllipsis', live : true});
			}

			var $reasonTextWrappers = jQuery('.reason-text-wrapper');
			if ($reasonTextWrappers.length) {

				$reasonTextWrappers.each(function(i,e) {
					var $e = jQuery(e);
					$e.dotdotdot({
						watch: true,
						after: "a.read-more",
						callback: function(isTruncated, orgContent) {
							if (GIVERHUB_DEBUG) {
								console.log(isTruncated);
							}
							if (!isTruncated) {
								$e.find('.read-more').remove();
							}
						}
					});
				});

				$reasonTextWrappers.on('click', '.read-more', function() {
					try {
						var $this = jQuery(this);
						var $reasonTextWrapper = $this.closest('.reason-text-wrapper');
						$reasonTextWrapper.trigger('destroy.dot');
						$reasonTextWrapper.addClass('expanded');

					} catch(e){
						giverhubError({e:e});
					}
					return false;
				});
			}
			var $removal_request_modal = jQuery('#removal-request-modal');
			$removal_request_modal.$reason = $removal_request_modal.find('.reason');

			jQuery(document).on('click', '.removal-request-button', function() {
				try {
					if (!body.data('signed-in')) {
						window.signInOrJoinFirst('You need to be signed in to do that. It only takes 4 seconds to sign up using facebook or google.');
						return false;
					}

					var $this = jQuery(this);

					$removal_request_modal.data('type', $this.data('type'));
					$removal_request_modal.data('id', $this.data('id'));

					$removal_request_modal.modal('show');

					$removal_request_modal.$reason.focus();
				} catch(e) {
					giverhubError({e:e});
				}
				return false;
			});

			$removal_request_modal.on('click', '.btn-submit-removal-request', function() {
				var $this = jQuery(this);
				try {

					var reason = $removal_request_modal.$reason.val();
					var type = $removal_request_modal.data('type');
					var id = $removal_request_modal.data('id');

					if (!reason.length) {
						giverhubError({msg : 'Please enter a reason first!'});
						return;
					}

					$this.button('loading');

					jQuery.ajax({
						url : '/petitions/removal_request',
						type : 'post',
						dataType : 'json',
						data : { reason : reason, type : type, id : id},
						error : function() {
							giverhubError({msg : 'Request failed.'});
						},
						success : function(json) {
							try {
								if (typeof(json) !== "object" || typeof(json.success) !== "boolean" || !json.success) {
									if (typeof(json.requested_already) === "boolean" && json.requested_already) {

										jQuery('.removal-request-button-'+id).attr('disabled','disabled')
											.attr('title', 'Your request to remove this is being processed!');
										$removal_request_modal.modal('hide');
										giverhubError({msg : 'You have already requested to remove this.'});
									} else {
										giverhubError({msg : 'Bad response'});
									}
								} else {
									jQuery('.removal-request-button-'+id).attr('disabled','disabled')
										.attr('title', 'Your request to remove this is being processed!');
									$removal_request_modal.modal('hide');
									giverhubSuccess({msg : 'Your request is being processed.'});
								}
							} catch(e) {
								giverhubError({e:e});
							}
						},
						complete : function() {
							$this.button('reset');
						}
					});
				} catch(e) {
					$this.button('reset');
					giverhubError({e:e});
				}
			});

			function fb_share_petition(url, petition_id) {
				if (typeof(url) !== "string" || !url.length) {
					throw "invalid url:" + url;
				}
				var orig_petition_id = petition_id;
				petition_id = parseInt(petition_id);
				if (!petition_id) {
					throw "invalid petition_id: " + orig_petition_id;
				}

				FB.ui({
					app_id: body.data('fb-app-id'),
					method: 'share',
					href: url
				}, function (response) {
					if (response !== null) {
						jQuery.ajax({
							url : '/petitions/fb_share',
							data : {
								petition_id : petition_id
							},
							type : 'post',
							dataType : 'json'
						});
					}
				});
			}

			jQuery(document).on('click','.fb-share-petition-wrapper', function() {
				try {
					var $this = jQuery(this);
					fb_share_petition(document.URL, $this.data('petition-id'));
				} catch(e) {
					giverhubError({e:e});
				}
				return false;
			});

			jQuery(document).on('click','.btn-share-petition-history', function() {
				try {
					var $this = jQuery(this);
					fb_share_petition($this.data('href'), $this.data('petition-id'));
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