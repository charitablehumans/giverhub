try {
	jQuery(document).ready(function(){
		try {
			var giverCardReviewModal 	= jQuery('#giver-card-review-modal');
			var giverCardForm 			= jQuery('#giver_cards');
			var giverCardDonationAmount = jQuery('#giver_cards .giver-cards-donation-amount-text');
			var $results_container		= jQuery('.giver_card_friend_results');
			var giverCardSuccessModal	= jQuery('#giver-card-success-msg-modal');
			var changePaymentModal 		= jQuery('#change-payment');
			var results_store 			= {};
			var clearFriendButtonString = '<button type="button" class="btn btn-xs btn-danger btn-clear-friend">x</button>';
			var $friend_input 			= giverCardForm.find('.giver_card_to_user');
			var $chosen_friend			= giverCardForm.find('.giver_card_friend_chosen');
			var $newUserEmail			= giverCardForm.find('.new_user_email');
			var $newUserName			= giverCardForm.find('.new_user_name');
			var $giverCardMessage		= giverCardForm.find('.giver_card_message');

			var $newRecipientDetails	= giverCardReviewModal.find('.new_recipient_details');
			var $existingRecipientDetails = giverCardReviewModal.find('.existing_recipient_details');

			var readGiverCardForm = function() {

				var data 			= {};
				var existing_email 	= $friend_input.val().trim();
				var new_email 		= $newUserEmail.val().trim();
				var new_name		= $newUserName.val().trim();

				if (!existing_email.length && !new_email.length && !new_name.length) {
					giverhubError({subject : 'Select User', msg : 'You need Select either existing user or enter new user\'s name and email address.'});
					giverhubError.hideEvent = function() {$friend_input.focus()};
					return false;
				}
				if (existing_email.length) {
					var $li = $chosen_friend.find('li');
					if (!$li.length) {
						giverhubError({subject : 'Select Friend', msg : 'You need to select a friend'});
						giverhubError.hideEvent = function() {$friend_input.focus()};
						return false;
					}
					var user_id = jQuery($li[0]).data('user-id');
					if (!user_id) {
						giverhubError({subject : 'Select Friend', msg : 'Something went wrong with selecting your friend.'});
						giverhubError.hideEvent = function() {$friend_input.focus()};
						return false;
					}
					data['user_id'] = user_id;
					data['facebook_friend'] = jQuery($li[0]).data('facebook-friend');
					data['existing_email'] 	= user_id;
					$newRecipientDetails.html('');
				} else {
					if (!new_name.length) {
						giverhubError({subject : 'Missing First name!', msg : 'You need to enter first name of the person'});
						giverhubError.hideEvent = function() {$newUserName.focus()};
						return false;
					}

					if (!new_email.length) {
						giverhubError({subject : 'Missing email address!', msg : 'You need to enter new user\'s email address'});
						giverhubError.hideEvent = function() {$newUserEmail.focus()};
						return false;
					}
					var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
					if( !emailReg.test( new_email ) ) {
						giverhubError({subject : 'Invalid email address', msg : 'You need to enter a valid email address'});
						giverhubError.hideEvent = function() {$newUserEmail.focus()};
						return false;
					}
					data['new_email'] = new_email;
					data['new_name']  = new_name;
					$existingRecipientDetails.html('');
				}

				var donation_amount = giverCardDonationAmount.val().trim();
				if (!donation_amount.length) {
					giverhubError({subject : 'Missing donation amount!', msg : 'You need to enter a donation amount.'});
					giverhubError.hideEvent = function() {giverCardDonationAmount.focus()};
					return false;
				}
				if (donation_amount < 10) {
					giverhubError({subject : 'Less donation amount', msg : 'You need to enter a donation amount greater than $10.'});
					giverhubError.hideEvent = function() {giverCardDonationAmount.focus()};
					return false;
				}
				data['donation_amount'] = donation_amount;

				var givercard_message = $giverCardMessage.val().trim();
				if (!givercard_message.length) {
					giverhubError({subject : 'Missing message', msg : 'You need to enter a GiverCard message'});
					giverhubError.hideEvent = function() {$giverCardMessage.focus()};
					return false;
				}
				data['givercard_message'] = givercard_message;

				return data;

			};


			function selectCard(card, noInstantDonations) {
				try {
					//var changePayment = jQuery('#change-payment');
					selectedCard = card;
					//var instantDonations = changePayment.data('instant-donations');

					if (noInstantDonations) {
						jQuery.ajax({
							url : '/donation/set_payment_cof',
							data : {cofId : card.COFId},
							dataType : 'json',
							type : 'post',
							error : function () {
								giverhubError({msg : 'GiverHub encountered an unexpected problem while trying to save your payment method.'});

							},
							success : function (json) {
								try {
									if (json === undefined || json.success === undefined || !json.success) {
										giverhubError({msg : 'GiverHub encountered an unexpected problem while saving your payment method.'});

									} else {

										jQuery('#givercard-donation-method-card').html(card.CardType + ' card ending in ' + card.CCSuffix + ' expires ' + (card.CCExpMonth < 10 ? '0'+card.CCExpMonth : card.CCExpMonth) + '/' + card.CCExpYear + ' (<a id="btn-add-payment-method-modal-givercard" href="#">Change</a>)');
										giverCardReviewModal.data('cof-id', card.COFId);
									}
								} catch (e) {
									giverhubError({msg : 'unexpected problem when reading response from server.', e:e});

								}
							}
						});
					}

				} catch (e) {
					giverhubError({e: e, msg : 'GiverHub encountered an unexpected problem when selecting your payment method.'});
				}
			}

			function rerenderCards() {
				var ul = jQuery('.saved-payment-methods');
				ul.html('');
				selectedCard = undefined;
				var hasCards = false;
				jQuery.each(cards, function (i, card) {
					var li = jQuery(
						'<li><p>' +
							card.CardType +
							' card ending in ' +
							card.CCSuffix +
							' expires ' +
							(card.CCExpMonth < 10 ? '0'+card.CCExpMonth : card.CCExpMonth) +
							'/' +
							card.CCExpYear +
							'</p><a ' +
							'class="btn btn-primary btn-select-card btn-xs" ' +
							'data-cof-id="' + card.COFId + '" ' +
							'href="#">' +
							'Select' +
							'</a>' +
							'<a ' +
							'class="btn btn-danger btn-remove-card btn-xs" ' +
							'data-loading-text="Removing..." ' +
							'data-cof-id="' + card.COFId + '" ' +
							'href="#">' +
							'Remove' +
							'</a>' +
							'</li>');
					ul.append(li);
					hasCards=true;
				});
				if (hasCards) {
					ul.append('<hr/>');
				}
				if (selectedCard === undefined && cards.length) {
					//selectCard(cards[0]);  // can't call this because this could set an instant donation card when not wanted.
				}
			}

			function reloadCardsGivercardPage(success, fail) {

				var savedPaymentMethodsArea = changePaymentModal.find('.saved-payment-methods');
				var savedPaymentMethodsLoading = changePaymentModal.find('.saved-payment-methods-loading');
				savedPaymentMethodsArea.hide();
				savedPaymentMethodsLoading.show();

				jQuery.ajax({
					url : '/donation/getCOFs',
					preventCache : new Date().getTime(),
					dataType : 'json',
					type : 'get',
					success : function (json) {
						if (GIVERHUB_DEBUG) {
							console.dir(json);
						}
						if (json.success) {
							cards = json.cards;
							rerenderCards();
							success(cards);

						} else {
							rerenderCards();
							fail();
						}
					},
					error : function () {
						rerenderCards();
						fail();
					},
					complete : function() {
						savedPaymentMethodsLoading.hide();
						savedPaymentMethodsArea.show();
					}
				});
			}

			function reviewGiverCard(giverCardArray) {

				if ( giverCardArray.existing_user_id != "" ) {
					giverCardReviewModal.find('.existing_recipient_details').html(giverCardArray.existing_user_email);
					giverCardReviewModal.find('.existing_recipient_id').html(giverCardArray.existing_user_id);
				}
				if ( giverCardArray.new_email != "" ) {
					giverCardReviewModal.find('.new_recipient_details').html(giverCardArray.new_email);
					giverCardReviewModal.find('.new_recipient_name').html(giverCardArray.givercard_recipient_fname);
				}
				if ( giverCardArray.fb_user != "") {
					giverCardReviewModal.find('.fb_user_details').html(giverCardArray.fb_user);
					giverCardReviewModal.find('.fb_user_id').html(giverCardArray.fb_user_id);
				}
				giverCardReviewModal.find('.givercard_recipient_fname').text(giverCardArray.givercard_recipient_fname);
				giverCardReviewModal.find('.givercard_message').text(giverCardArray.givercard_message);
				giverCardReviewModal.find('.givercard_amount').html(giverCardArray.donation_amount);
				giverCardReviewModal.modal('show');

				reloadCardsGivercardPage(function (cards) {
					jQuery('.givercard-donation-method-state').hide();
					if (cards.length) {
						var selected = false;
						jQuery.each(cards, function(i, card) {
							if (card.selected) {
								selectCard(card, true);
								selected = true;
								return false;
							}
						});
						if (!selected) {
							selectCard(cards[0], true);
						}
						jQuery('#givercard-donation-method-card').show();
					} else {
						jQuery('#givercard-donation-method-empty').show();
					}
				}, function () {
					jQuery('.givercard-donation-method-state').hide();
					jQuery('#givercard-donation-method-empty').show();
				});
			}

			jQuery(document).on('click', '.preview-giver-cards', function () {
				try {
					var data = readGiverCardForm();
					if (data === false) {
						return false;
					}

					var submitButton 	= jQuery(this);
					var $giverCardsForm = jQuery(submitButton.data('target-form'));
					var $giverCardError = jQuery('.giver_card_error');

					jQuery.ajax({
						url : '/members/giver_cards',
						type : 'post',
						dataType : 'json',
						data : data,
						error : function() {
							$giverCardError.html(json.msg);
							$giverCardError.addClass('alert-danger').show();
						},
						complete : function() {
							submitButton.button('reset');
						},
						success : function(json) {
							try {
								if (json === undefined || !json || json.success === undefined || !json.success) {
									giverhubError({msg : 'Bad response!'});
								} else {
									$giverCardError.html('');
									$giverCardError.removeClass('alert-danger');
									reviewGiverCard(json.giverCardArray);
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

			jQuery(document).on('click', '#btn-add-payment-method-modal-givercard', function () {
				try {
					giverCardReviewModal.modal('hide');
					showChangePaymentGivercard(false);
				} catch(e) {
					giverhubError({e:e});
				}
			});

			function showChangePaymentGivercard(instantDonations, options) {

				changePaymentModal.data('instant-donations', instantDonations);
				changePaymentModal.find('.loading').show();
				changePaymentModal.find('.not-loading').hide();
				changePaymentModal.modal('show');
				if (instantDonations === undefined) {
					instantDonations = false;
				}

				jQuery('.add-new-card-alert').hide();
				jQuery('#frm-add-new-card')[0].reset();
				jQuery('#newCardNumber').val('1').trigger('change').change().val('').trigger('change').change().keyup().keydown();

				changePaymentModal.data('instant-donations', instantDonations);

				var lead = changePaymentModal.find('.modal-body.not-loading p.lead');
				if (instantDonations) {
					lead.html(lead.data('instant-donations-text'));
				} else {
					lead.html(lead.data('non-instant-donations-text'));
				}

				var cancel;
				if (options !== undefined && options.cancel !== undefined) {
					cancel = options.cancel;
				} else {
					cancel = function () {
						jQuery('#change-payment').modal('hide');
					}
				}
				jQuery(document).off('click', '.btn-cancel-change-payment');
				jQuery(document).on('click', '.btn-cancel-change-payment', cancel);


				var btnSelectCard;
				if (options !== undefined && options.btnSelectCard !== undefined) {
					btnSelectCard = options.btnSelectCard;
				} else {
					btnSelectCard = function () {
						selectCard(getCardByCOFId(jQuery(this).attr('data-cof-id')), true);
						jQuery('#givercard-donation-method-empty').hide();
						jQuery('#givercard-donation-method-card').show();
						jQuery('#change-payment').data('not-cancelled', true).modal('hide');
						giverCardReviewModal.modal('show');
						return false;
					}
				}
				jQuery(document).off('click', '.btn-select-card');
				jQuery(document).on('click', '.btn-select-card', btnSelectCard);

				reloadCardsGivercardPage(
					function () {
						changePaymentModal.find('.loading').hide();
						changePaymentModal.find('.not-loading').show();
					},
					function () {
						switchAll(false, false);
						giverhubError({msg : 'There was a problem checking your existing payment methods.'});
						jQuery('.btn-donate').attr('disabled', false);
						changePaymentModal.modal('hide');
					}
				);
			}

			function getCardByCOFId(COFId) {
				var card;
				jQuery.each(cards, function (i, c) {
					if (c.COFId == COFId) {
						card = c;
						return false;
					}
				});
				return card;
			}

			jQuery(document).on('click', '.btn-giver-card-confirm', function () {
				try {
					var btn = jQuery(this);
					btn.button('loading');

					var existing_user_email	= giverCardReviewModal.find('.existing_recipient_details').html().trim();
					var new_user_email		= giverCardReviewModal.find('.new_recipient_details').html().trim();
					var new_user_name		= giverCardReviewModal.find('.new_recipient_name').html().trim();
					var existing_user_id	= giverCardReviewModal.find('.existing_recipient_id').html().trim();
					var doantion_amount 	= giverCardReviewModal.find('.givercard_amount').html().trim();
					var givercard_message 	= giverCardReviewModal.find('.givercard_message').html().trim();
					var post_user_id		= giverCardReviewModal.find('.fb_user_details').html().trim();
					var cofId 				= giverCardReviewModal.data('cof-id');
					var CSRFToken			= body.data('csrf-token');

					if (!cofId) {
						giverhubError({msg : 'You need to add a payment method first!'});
						btn.button('reset');
						return;
					}
					jQuery.ajax('/giver-cards/confirmGiverCardDonation', {
						dataType : 'json',
						type : 'POST',
						data : {
							cofId : cofId,
							amount : doantion_amount,
							existingUserEmail : existing_user_email,
							newUserEmail : new_user_email,
							newUserName : new_user_name,
							existingUserId : existing_user_id,
							message : givercard_message,
							CSRFToken : CSRFToken,
							postUserId : post_user_id
						},
						success : function (json) {
							if (json.success) {
								giverCardReviewModal.modal('hide');
								//giverCardSuccessModal.modal('show');

								var givercard_id = json.givercard_id;
								var the_success = function() {
									giverhubSuccess({
										msg : 'GiverCard was sent!',
										'facebook-share' : function () {
											FB.ui({
												method: 'share_open_graph',
												action_type: 'giverhub:sent',
												action_properties: JSON.stringify({
													givercard: body.data('base-url') + 'giver-cards/friendonfb/' + givercard_id
												})
											}, function(response){
												jQuery('.success-facebook-share-message').removeClass('hide');
											});
										}
									});
								};	

								if (json.fb_friend) {
									var givercard_id = json.givercard_id;
									FB.ui({
										method: 'send',
										link: body.data('base-url') + 'giver-cards/friendonfb/' + givercard_id + '?fb-givercard',
										to: giverCardReviewModal.find('.fb_user_id').html().trim()
									}, function(response){
										if (response.error_code !== undefined) {
											giverhubError({msg : 'Facebook returned an error: ' + response.error_msg + ' error_code: ' + response.error_code});
										} else {
											the_success();
										}
									});
								} else {
									the_success();
								}
								
							} else {
								giverhubError({msg : json.msg});
							}
						},
						error : function () {
							giverhubError({msg : 'There was an unexpected problem while processing your request. Please try again later. Thank you.'});
						},
						complete : function () {
							btn.button('reset');
							jQuery('#giver_cards')[0].reset();
							$chosen_friend.html('');
							$friend_input.css('display','block');
							$newUserEmail.removeAttr('disabled');
							$friend_input.removeAttr('disabled');
							$newUserName.removeAttr('disabled');
							//window.location.href = '/giver_cards/';
						}
					});

				} catch(e) {
					giverhubError({e:e});
				}
				return false;
			});

			giverCardForm.on('keyup', '.giver-cards-donation-amount-text', function() {
				try {
					var start 	= this.selectionStart;
					var end 	= this.selectionEnd;
					giverCardDonationAmount.val(giverCardDonationAmount.val().replace(/[^0-9$]/g, ''));
					this.setSelectionRange(start, end);
				} catch(e) {
					giverhubError({e:e});
				}
				return true;
			});

			giverCardForm.on('keyup', '.giver_card_to_user', function() {
				try {
					var $this = jQuery(this);
					var value = $this.val();

					if (!value.length) {
						$newUserEmail.removeAttr('disabled');
						$newUserName.removeAttr('disabled');
						$results_container.hide();
						return true;
					}
					$results_container.show();
					$newUserEmail.attr('disabled','disabled');
					$newUserName.attr('disabled','disabled');

					var res = results_store[value];
					if (res === undefined) {
						$results_container.html('<img src="/images/ajax-loaders/ajax-loader.gif">');
						results_store[value] = {loading : true};
						jQuery.ajax({
							url : '/members/bet_friends_friend',
							type : 'get',
							dataType : 'json',
							data : { search : value },
							error : function() {
								giverhubError({msg : 'Request Failed.'});
							},
							success : function(json) {
								try {
									if (json === undefined || !json || json.success === undefined || !json.success || json.search === undefined || json.results === undefined) {
										giverhubError({msg : 'Bad response!'});
									} else {
										if (results_store[json.search] === undefined) {
											return;
										}
										if (!results_store[json.search]['loading']) {
											return;
										}
										results_store[json.search]['loading'] = false;
										results_store[json.search]['results'] = json.results;
										if ($this.val() == json.search) {
											$results_container.html(json.results);
										}
									}
								} catch(e) {
									giverhubError({e:e});
								}
							},
							complete : function() {}
						});
					} else {
						if (res['loading']) {
							$results_container.html('<img src="/images/ajax-loaders/ajax-loader.gif">');
						} else {
							$results_container.html(res['results']);
						}
					}
				} catch(e) {
					giverhubError({e:e});
				}
				return true;
			});

			$results_container.on('click', '.select-friend', function() {
				try {
					var $this = jQuery(this);
					var $li = $this.parent();
					var $newLi = $li.clone();
					$newLi.find('a').append(clearFriendButtonString);
					$friend_input.hide();
					$chosen_friend.html($newLi);
					$chosen_friend.show();
					$results_container.hide();
				} catch(e) {
					giverhubError({e:e});
				}
				return false;
			});

			$chosen_friend.on('click', '.btn-clear-friend', function() {
				try {
					$chosen_friend.hide();
					$chosen_friend.html('');
					$friend_input.val('').show();
					$newUserEmail.removeAttr('disabled');
				} catch(e) {
					giverhubError({e:e});
				}
				return false;
			});

			giverCardForm.on('keyup', '.new_user_email, .new_user_name', function() {
				try {
					var $this = jQuery(this);
					var value = $this.val();
					if (!value.length) {
						$friend_input.removeAttr('disabled');
						return true;
					}
					$friend_input.attr('disabled','disabled');
				} catch(e) {
					giverhubError({e:e});
				}
			});

			jQuery("#givercard_payment").change(function() {
				try {
					if(this.checked) {
						jQuery('.givercard_select_options').css('display','block');
					} else {
						jQuery('.givercard_select_options').css('display','none');
					}
				} catch(e) {
					giverhubError({e:e});
				}
			});

			jQuery("#giver_card_name").on('change', function(){
				try {
					var givercardTransactionId = jQuery('#giver_card_name').val().trim();

					if (givercardTransactionId) {

						jQuery.ajax('/giver_cards/setGivercardPaymentCof', {
							dataType : 'json',
							type : 'POST',
							data : {
								givercardId : givercardTransactionId
							},
							success : function (json) {
								if (json.success) {
									jQuery('#lbox_donations').data('cof-id', json.cof_id);
									jQuery('#lbox_donations').data('givercard-balance-amount', json.balance_amount);
								}

							},
							error : function () {
								giverhubError({msg : 'There was an unexpected problem while processing your request. Please try again later. Thank you.'});
							},
							complete : function () {

							}
						});
					}
				} catch(e) {
					giverhubError({e:e});
				}
			});

			jQuery(".btn-donate-from-givercard-view-page").on('click', function(e){
				try {
					var btn = jQuery(this);
					btn.button('loading');
					var nonprofitSelection		= jQuery('.charity-search-container').find('.bet_charity_chosen');
					var balanceAmountBox		= jQuery('.view_givercard_page_text').find('.givercard_balance_amount');

					var nonprofitText 			= jQuery('.charity-search-container').find('#charity');
					var donationAmountText 		= jQuery('.gh_donation').find('.givercard-page-donation-amount-input');
					var selectedCharity 		= nonprofitText.val().trim();
					var donationAmount 			= donationAmountText.val().trim();
					var givercardBalAmount		= balanceAmountBox.html().trim();
					var nonprofitResult			= nonprofitSelection.html().trim();
					var givercardTransactionId	= jQuery('#givercard_transaction_id').val().trim();
					var givercardCofId			= jQuery('#givercard_cof_id').val().trim();
					var CSRFToken 				= body.data('csrf-token');
					var bet_id 					= null;

					if (!selectedCharity) {
						giverhubError({subject : 'Missing non-profit!', msg : 'You need Select non-profit for donation'});
						giverhubError.hideEvent = function() {nonprofitText.focus()};
						btn.button('reset');
						return false;
					}

					var nonprofitId = jQuery(nonprofitResult).data('charity-id');
					var nonprofitName = jQuery(nonprofitResult).find('a').attr('title');

					if (!donationAmount) {
						giverhubError({subject : 'Missing donation amount!', msg : 'You need enter donation amount'});
						giverhubError.hideEvent = function() {donationAmountText.focus()};
						btn.button('reset');
						return false;
					}
					if (donationAmount < 10) {
						giverhubError({subject : 'Missing donation amount!', msg : 'You need enter donation amount greater than 10'});
						giverhubError.hideEvent = function() {donationAmountText.focus()};
						btn.button('reset');
						return false;
					}

					//Check weather giver card is having sufficient amount v/s donation amount or not
					if ( donationAmount > Number(givercardBalAmount) ) {
						giverhubError({msg : 'This GiverCard is having only $'+givercardBalAmount+', you can\'t donate more than it'});
						giverhubError.hideEvent = function() {donationAmountText.focus()};
						btn.button('reset');
						return false;
					}

					//Check weather remaining amount after givercard donation is less than $10 or not
					var remainingAmountAfterDonation = Number(givercardBalAmount) - donationAmount;
					var totalAmount = Number(donationAmount) + Number(remainingAmountAfterDonation);

					if ( remainingAmountAfterDonation < 10 && remainingAmountAfterDonation != 0) {
						var totalUnsedAmount = 10 - remainingAmountAfterDonation;
						var newDonationAmount = donationAmount - totalUnsedAmount;
						if (newDonationAmount < 10) {
							giverhubError({msg : 'Donating $'+donationAmount+' would leave $'+remainingAmountAfterDonation+' unusable in your GiverCard. Please donate $'+totalAmount+' to empty GiverCard'});
							giverhubError.hideEvent = function() {donationAmountText.focus()};
							btn.button('reset');
							return false;
						} else {
							giverhubError({msg : 'Donating $'+donationAmount+' would leave $'+remainingAmountAfterDonation+' unusable in your GiverCard. Please donate $'+totalAmount+' to empty GiverCard or donate $'+newDonationAmount+' so that at least $10 will left in you Givercard'});
							giverhubError.hideEvent = function() {donationAmountText.focus()};
							btn.button('reset');
							return false;
						}
					}

					var signedIn = body.data('signed-in') ? true : false;

					if (!signedIn) {
						jQuery('#signin-or-join-first-modal').modal('show');
						btn.button('reset');
						return false;
					}
					e.preventDefault();

					jQuery.ajax('/donation/confirmDonation', {
						dataType : 'json',
						type : 'POST',
						data : {
							cofId : givercardCofId,
							amount : donationAmount,
							charityId : nonprofitId,
							givercard_transaction : true,
							givercard_transaction_id : givercardTransactionId,
							CSRFToken : CSRFToken,
							bet_id : bet_id
						},
						success : function (json) {
							if (json.success) {

								var givercardDonationSucessModal = jQuery('#givercardDonationSucessMsgModal');
								givercardDonationSucessModal.find('#donation-confirmation-name').html(nonprofitName);
								givercardDonationSucessModal.find('.donation-confirmation-amount').html(donationAmount);
								givercardDonationSucessModal.find('.donation-confirmapation-amount-earned-givercoin').html(parseInt(donationAmount) * parseInt(jQuery('#lbox_donations').data('givercoin-reward')));
								givercardDonationSucessModal.modal('show');
								nonprofitSelection.html('');
								nonprofitSelection.css('display','none');
								donationAmountText.val('');
								nonprofitText.val('');
								nonprofitText.css('display','block');
								balanceAmountBox.html(remainingAmountAfterDonation);
							} else {
								giverhubError({msg : json.msg});
							}
						},
						error : function () {
							giverhubError({msg : 'There was an unexpected problem while processing your request. Please try again later. Thank you.'});
						},
						complete : function () {
							btn.button('reset');
						}
					});

				} catch(e) {
					giverhubError({e:e});
				}

			});

			jQuery("#received_givercards_table > tbody > tr.received_givercard_listing_tr").on('click', function(){
				try {
					window.location.href = jQuery(this).data('url');
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
