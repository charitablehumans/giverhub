jQuery(document).ready(function() {
	try {
		/*

		 This routine checks the credit card number. The following checks are made:

		 1. A number has been provided
		 2. The number is a right length for the card
		 3. The number has an appropriate prefix for the card
		 4. The number has a valid modulus 10 number check digit if required

		 If the validation fails an error is reported.

		 The structure of credit card formats was gleaned from a variety of sources on the web, although the
		 best is probably on Wikepedia ("Credit card number"):

		 http://en.wikipedia.org/wiki/Credit_card_number

		 Parameters:
		 cardnumber           number on the card
		 cardname             name of card as defined in the card list below

		 Author:     John Gardner
		 Date:       1st November 2003
		 Updated:    26th Feb. 2005      Additional cards added by request
		 Updated:    27th Nov. 2006      Additional cards added from Wikipedia
		 Updated:    18th Jan. 2008      Additional cards added from Wikipedia
		 Updated:    26th Nov. 2008      Maestro cards extended
		 Updated:    19th Jun. 2009      Laser cards extended from Wikipedia
		 Updated:    11th Sep. 2010      Typos removed from Diners and Solo definitions (thanks to Noe Leon)
		 Updated:    10th April 2012     New matches for Maestro, Diners Enroute and Switch
		 Updated:    17th October 2012   Diners Club prefix 38 not encoded

		 */

		/*
		 If a credit card number is invalid, an error reason is loaded into the global ccErrorNo variable.
		 This can be be used to index into the global error  string array to report the reason to the user
		 if required:

		 e.g. if (!checkCreditCard (number, name) alert (ccErrors(ccErrorNo);
		 */


		function checkCC(cardnumber) {
			var ccErrorNo = 0;
			var ccErrors = [];

			ccErrors [0] = "Unknown card type";
			ccErrors [1] = "No card number provided";
			ccErrors [2] = "Credit card number is in invalid format";
			ccErrors [3] = "Credit card number is invalid";
			ccErrors [4] = "Credit card number has an inappropriate number of digits";
			ccErrors [5] = "Warning! This credit card number is associated with a scam attempt";

			function checkCreditCard(cardnumber, cardname) {

				// Array to hold the permitted card characteristics
				var cards = [];

				// Define the cards we support. You may add addtional card types as follows.

				//  Name:         As in the selection box of the form - must be same as user's
				//  Length:       List of possible valid lengths of the card number for the card
				//  prefixes:     List of possible prefixes for the card
				//  checkdigit:   Boolean to say whether there is a check digit

				cards [0] = {
					name : "Visa",
					length : "13,16",
					prefixes : "4",
					checkdigit : true
				};
				cards [1] = {
					name : "Mastercard",
					length : "16",
					prefixes : "51,52,53,54,55",
					checkdigit : true
				};
				cards [2] = {
					name : "DinersClub",
					length : "14,16",
					prefixes : "36,38,54,55",
					checkdigit : true
				};
				cards [3] = {
					name : "CarteBlanche",
					length : "14",
					prefixes : "300,301,302,303,304,305",
					checkdigit : true
				};
				cards [4] = {
					name : "Amex",
					length : "15",
					prefixes : "34,37",
					checkdigit : true
				};
				cards [5] = {
					name : "Discover",
					length : "16",
					prefixes : "6011,622,64,65",
					checkdigit : true
				};
				cards [6] = {
					name : "JCB",
					length : "16",
					prefixes : "35",
					checkdigit : true
				};
				cards [7] = {
					name : "enRoute",
					length : "15",
					prefixes : "2014,2149",
					checkdigit : true
				};
				cards [8] = {
					name : "Solo",
					length : "16,18,19",
					prefixes : "6334,6767",
					checkdigit : true
				};
				cards [9] = {
					name : "Switch",
					length : "16,18,19",
					prefixes : "4903,4905,4911,4936,564182,633110,6333,6759",
					checkdigit : true
				};
				cards [10] = {
					name : "Maestro",
					length : "12,13,14,15,16,18,19",
					prefixes : "5018,5020,5038,6304,6759,6761,6762,6763",
					checkdigit : true
				};
				cards [11] = {
					name : "VisaElectron",
					length : "16",
					prefixes : "4026,417500,4508,4844,4913,4917",
					checkdigit : true
				};
				cards [12] = {
					name : "LaserCard",
					length : "16,17,18,19",
					prefixes : "6304,6706,6771,6709",
					checkdigit : true
				};

				// Establish card type
				var cardType = -1;
				for (var i = 0; i < cards.length; i++) {

					// See if it is this card (ignoring the case of the string)
					if (cardname.toLowerCase() == cards[i].name.toLowerCase()) {
						cardType = i;
						break;
					}
				}

				// If card type not found, report an error
				if (cardType == -1) {
					ccErrorNo = 0;
					return false;
				}

				// Ensure that the user has provided a credit card number
				if (cardnumber.length == 0) {
					ccErrorNo = 1;
					return false;
				}

				// Now remove any spaces from the credit card number
				cardnumber = cardnumber.replace(/\s/g, "");

				// Check that the number is numeric
				var cardNo = cardnumber;
				var cardexp = /^[0-9]{13,19}$/;
				if (!cardexp.exec(cardNo)) {
					ccErrorNo = 2;
					return false;
				}

				// Now check the modulus 10 check digit - if required
				if (cards[cardType].checkdigit) {
					var checksum = 0;                                  // running checksum total
					var mychar = "";                                   // next char to process
					var j = 1;                                         // takes value of 1 or 2

					// Process each digit one by one starting at the right
					var calc;
					for (i = cardNo.length - 1; i >= 0; i--) {

						// Extract the next digit and multiply by 1 or 2 on alternative digits.
						calc = Number(cardNo.charAt(i)) * j;

						// If the result is in two digits add 1 to the checksum total
						if (calc > 9) {
							checksum = checksum + 1;
							calc = calc - 10;
						}

						// Add the units element to the checksum total
						checksum = checksum + calc;

						// Switch the value of j
						if (j == 1) {
							j = 2
						} else {
							j = 1
						}
						;
					}

					// All done - if checksum is divisible by 10, it is a valid modulus 10.
					// If not, report an error.
					if (checksum % 10 != 0) {
						ccErrorNo = 3;
						return false;
					}
				}

				// Check it's not a spam number
				if (cardNo == '5490997771092064') {
					ccErrorNo = 5;
					return false;
				}

				// The following are the card-specific checks we undertake.
				var LengthValid = false;
				var PrefixValid = false;
				var undefined;

				// We use these for holding the valid lengths and prefixes of a card type
				var prefix = [];
				var lengths = [];

				// Load an array with the valid prefixes for this card
				prefix = cards[cardType].prefixes.split(",");

				// Now see if any of them match what we have in the card number
				for (i = 0; i < prefix.length; i++) {
					var exp = new RegExp("^" + prefix[i]);
					if (exp.test(cardNo)) PrefixValid = true;
				}

				// If it isn't a valid prefix there's no point at looking at the length
				if (!PrefixValid) {
					ccErrorNo = 3;
					return false;
				}

				// See if the length is valid for this card
				lengths = cards[cardType].length.split(",");
				for (j = 0; j < lengths.length; j++) {
					if (cardNo.length == lengths[j]) LengthValid = true;
				}

				// See if all is OK by seeing if the length was valid. We only check the length if all else was
				// hunky dory.
				if (!LengthValid) {
					ccErrorNo = 4;
					return false;
				}

				// The credit card is in the required format.
				return true;
			}

			var types = ['Visa', 'Mastercard', 'Amex'];

			var type = false;
			jQuery.each(types, function (i, v) {
				if (checkCreditCard(cardnumber, v)) {
					type = v;
					return false; // break
				}
				return true;
			});

			var cardTypeElement = jQuery('#newCardType');
			if (type) {
				cardTypeElement.val(type);
			} else {
				cardTypeElement.val('');
			}
		}

		window.cards = [];
		var selectedCard = undefined;

		var $paymentMethod = jQuery('#settings-page-payment-method');
		function setSettingsPageDisplayedPaymentMethod(cofObj) {
			if (GIVERHUB_DEBUG) {
				console.dir(cofObj);
			}
			$paymentMethod.html(cofObj.CardType + ' ending in ' + cofObj.CCSuffix + ' expires ' + cofObj.CCExpMonth + '/' + cofObj.CCExpYear);
		}
		function getPaymentMethod() {
			$paymentMethod.html('Loading...');
			jQuery.ajax({
				url : '/donation/payment_method',
				type : 'get',
				dataType : 'json',
				error : function() {
					$paymentMethod.html('Error.'); // NOTICE: ONE DOT.
				},
				success : function(json) {
					try {
						if (json === undefined || json.success === undefined || !json.success || json.cardData === undefined) {
							$paymentMethod.html('Error..'); // NOTICE: TWO DOTS.
						} else {
							if (typeof(json.cardData) == 'string') {
								$paymentMethod.html(json.cardData);
							} else if (typeof(json.cardData) == 'object') {
								setSettingsPageDisplayedPaymentMethod(json.cardData);
							} else {
								$paymentMethod.html('Error....');// NOTICE: Four Dots ....
							}
						}
					} catch(e) {
						$paymentMethod.html('Error...'); // NOTICE: Three Dots ...
					}
				}
			});
		}
		if ($paymentMethod.length) {
			getPaymentMethod();
		}

		function selectCard(card, noInstantDonations) {
			try {
				var changePayment = jQuery('#change-payment');
				selectedCard = card;
				var instantDonations = changePayment.data('instant-donations');
				if ($paymentMethod.length) {
					$paymentMethod.html('Loading...');
				}
				if (instantDonations && !noInstantDonations) {
					jQuery.ajax({
						url : '/donation/set_instant_donation_cof',
						data : {cofId : card.COFId},
						dataType : 'json',
						type : 'post',
						error : function () {
							giverhubError({msg : 'GiverHub encountered an unexpected problem while trying to save your instant donation payment method.'});
							if ($paymentMethod.length) {
								$paymentMethod.html('Error.');
							}
						},
						success : function (json) {
							try {
								if (json === undefined || json.success === undefined || !json.success || json.showInstantDonationConfirmation === undefined) {
									giverhubError({msg : 'GiverHub encountered an unexpected problem while saving your instant donation payment method.'});
									if ($paymentMethod.length) {
										$paymentMethod.html('Error..');
									}
								} else {
									body.data('instant-donations', 1);
									setTimeout(function(){switchAll(true, false);},0);

									if (json.showInstantDonationConfirmation) {
										jQuery('#instant-donations-confirmation-modal').modal('show');
									}

									if ($paymentMethod.length) {
										setSettingsPageDisplayedPaymentMethod(card);
									}
								}
							} catch (e) {
								giverhubError({msg : 'unexpected problem when reading response from server.', e:e});
								if ($paymentMethod.length) {
									$paymentMethod.html('Error...');
								}
							}
						}
					});
				} else {
					jQuery.ajax({
						url : '/donation/set_payment_cof',
						data : {cofId : card.COFId},
						dataType : 'json',
						type : 'post',
						error : function () {
							giverhubError({msg : 'GiverHub encountered an unexpected problem while trying to save your payment method.'});
							if ($paymentMethod.length) {
								$paymentMethod.html('Error.');
							}
						},
						success : function (json) {
							try {
								if (json === undefined || json.success === undefined || !json.success) {
									giverhubError({msg : 'GiverHub encountered an unexpected problem while saving your payment method.'});
									if ($paymentMethod.length) {
										$paymentMethod.html('Error..');
									}
								} else {
									if ($paymentMethod.length) {
										setSettingsPageDisplayedPaymentMethod(card);
									}
								}
							} catch (e) {
								giverhubError({msg : 'unexpected problem when reading response from server.', e:e});
								if ($paymentMethod.length) {
									$paymentMethod.html('Error...');
								}
							}
						}
					});
				}

				jQuery('#step2-donation-method-card').html(card.CardType + ' card ending in ' + card.CCSuffix + ' expires ' + (card.CCExpMonth < 10 ? '0'+card.CCExpMonth : card.CCExpMonth) + '/' + card.CCExpYear + ' (<a id="btn-add-payment-method-modal" href="#">Change</a>)');
				jQuery('#lbox_donations').data('cof-id', card.COFId);
			} catch (e) {
				giverhubError({e: e, msg : 'GiverHub encountered an unexpected problem when selecting your payment method.'});
			}
		}

		function disableInstantDonations() {
			jQuery.ajax({
				url : '/donation/disable_instant_donations',
				type : 'post',
				data : {disable_instant_donations : 'yes'},
				dataType : 'json',
				error : function () {
					giverhubError({msg : 'GiverHub encountered an unexpected problem when trying to disable instant donations.'});
				},
				success : function (json) {
					try {
						if (json === undefined || json === null || !json || json.success === undefined || !json.success) {
							giverhubError({msg : 'Unexpected response from server when disabling instant donations.'});
						} else {
							body.data('instant-donations', 0);
							setTimeout(function(){switchAll(false,false);},0);
							jQuery('#donation_modal_instant_donation_enabled_txt').addClass("hidden");
							jQuery('.gh_step_list').removeClass("hidden");

							getPaymentMethod();

						}
					} catch (e) {
						giverhubError({msg : 'Unexpected problem while disabling instant donations.', e : e});
					}
				}
			});
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

		function reloadCards(success, fail) {
			var changePaymentModal = jQuery('#change-payment');
			var savedPaymentMethodsArea = changePaymentModal.find('.saved-payment-methods');
			var savedPaymentMethodsLoading = changePaymentModal.find('.saved-payment-methods-loading');
			savedPaymentMethodsArea.hide();
			savedPaymentMethodsLoading.show();
			jQuery('#btn-donation-modal-goto-step3').attr('disabled', 'disabled');

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
						jQuery('#btn-donation-modal-goto-step3').removeAttr('disabled');
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

		function showChangePayment(instantDonations, options) {

			jQuery('#donate-step2').modal('hide');

			var changePaymentModal = jQuery('#change-payment');

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
					selectCard(getCardByCOFId(jQuery(this).attr('data-cof-id')), false);
					jQuery('#change-payment').data('not-cancelled', true).modal('hide');
					jQuery('#lbox_donations').modal('show');
					return false;
				}
			}
			jQuery(document).off('click', '.btn-select-card');
			jQuery(document).on('click', '.btn-select-card', btnSelectCard);

			reloadCards(
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

		function instantDonationSwitch(on, $el) {
			var signedIn = body.data('signed-in') ? true : false;

			if (!signedIn) {
				jQuery('#signin-or-join-first-modal').modal('show');
				setTimeout(function(){switchAll(false, false);},0);
				return;
			}


			if (on) {
				showChangePayment(true, {
					cancel : function () {
						setTimeout(function(){switchAll(false, false);},0);
						jQuery('#change-payment').modal('hide');
						jQuery('#donation_modal_instant_donation_enabled_txt').addClass("hidden");
						jQuery('.gh_step_list').removeClass("hidden");
					},
					btnSelectCard : function () {
						selectCard(getCardByCOFId(jQuery(this).attr('data-cof-id')), false);
						jQuery('#change-payment').data('not-cancelled', true).modal('hide');
						jQuery('#donation_modal_instant_donation_enabled_txt').removeClass("hidden");
						jQuery('.gh_step_list').addClass("hidden");
						return false;
					}
				});
			} else {
				disableInstantDonations(function () {});
			}
		}

		function switchSwitch($el, on, triggerEvent) {
			if (triggerEvent === undefined) {
				triggerEvent = true;
			}
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

		window.enableSwitch = function(el) {
			var e = $(el),
				eon = e.data('on-label'),
				eoff = e.data('off-label'),
				ini_status = e.find('input').attr('checked');

			//Hide input
			e.find('input').attr('hidden', 'hidden');

			// Icons
			e.prepend('<span class="switch"></span>' +
				'<div class="icons">' +
				'<i class="' + eon + '"></i>' +
				'<i class="' + eoff + '"></i>' +
				'</div>');

			if (ini_status != null) {
				var btn_width = parseInt(e.find('.switch').width()) - 2;
				e.find('.switch').css({left : btn_width / 2.5}, 200);
			}


			// Function button
			e.find('.switch').click(function () {

				var	status = $(this).parent().find('input').attr('checked');

				if (status == null) {
					switchSwitch($(this), true);
				} else {
					switchSwitch($(this), false);
				}

			});

			e.addClass('enabled');
		};

		function switchAll(on, triggerEvent) {
			jQuery('.instant-donation-switch .switch').each(function(i,e) {
				switchSwitch(jQuery(e),on,triggerEvent);
			});
		}


		jQuery('.instant-donation-switch').each(function(i,e) {
			window.enableSwitch(e);
		});

		function cancelInstantDonations() {
			switchAll(false, false);
			jQuery('.instant-donations-on-off-text').html('On');
		}

		jQuery(document).on('hidden.bs.modal', '#change-payment', function () {
			var changePaymentModal = jQuery('#change-payment');
			var notCancelled = changePaymentModal.data('not-cancelled');
			var instantDonations = changePaymentModal.data('instant-donations');

			if (!notCancelled && instantDonations) {
				cancelInstantDonations();
			}
		});

		jQuery(document).on('click', '#btn-add-new-card', function () {
			var addButton = jQuery(this);

			try {
				var changePayment = jQuery('#change-payment');
				var addressesContainer = changePayment.find('.addresses-container');
				var firstRow = jQuery(addressesContainer.find('.address-row')[0]);
				var userAddressId = firstRow.data('user-address-id');
				if (!userAddressId) {
					giverhubError({msg : 'You need to add an address first.'});
					return false;
				}

				if (GIVERHUB_DEBUG) {
					console.log('uaid: ' + userAddressId);
				}
				jQuery('.add-new-card-alert').hide();

				addButton.button('loading');

				jQuery.ajax('/donation/addCard', {
					data : jQuery('#frm-add-new-card').serialize() + '&userAddressId='+userAddressId,
					dataType : 'json',
					type : 'POST',
					success : function (json) {
						try {
							if (GIVERHUB_DEBUG && json.debug) {
								console.dir(json.debug);
							}
							if (json.success) {
								jQuery('#add-new-card-alert-success').removeClass('hide').show();
								reloadCards(
									function (cards) {
										jQuery('.step2-donation-method-state').hide();
										if (cards.length) {
											selectCard(cards[0], true);
											jQuery('.btn-select-card').each(function(i,e) {
												if (jQuery(e).data('cof-id') == json.CofId) {
													if (jQuery('.btn-change-add-payments-from-settings').length) {
														setTimeout(function() {jQuery(e).trigger('click');}, 5000);
													} else {
														jQuery(e).trigger('click');
													}
												}
											});
											jQuery('#step2-donation-method-card').show();
										} else {
											jQuery('#step2-donation-method-empty').show();
										}
									}, function () {
										jQuery('.step2-donation-method-state').hide();
										jQuery('#step2-donation-method-empty').show();
									}
								);
							} else {
								jQuery('#add-new-card-alert-danger-msg').html(json.msg);
								jQuery('#add-new-card-alert-danger').removeClass('hide').show();
							}
						} catch(e) {
							giverhubError({e:e});
						}
					},
					complete : function () {
						addButton.button('reset');
					},
					error : function () {
						jQuery('#add-new-card-alert-danger-msg').html('Unexpected problem processing your request. Please try again later. Thank you!');
						jQuery('#add-new-card-alert-danger').removeClass('hide').show();
					}
				}, 'json');

			} catch(e) {
				giverhubError({e:e});
				addButton.button('reset');
			}
			return false;
		});

		jQuery(document).on('click', '.btn-close-instant-donation-confirmation', function () {
			var dontShow = jQuery('#dont-show-instant-confirmation-again').is(':checked');

			var instantDonationsConfirmationModal = jQuery('#instant-donations-confirmation-modal');
			if (dontShow) {
				jQuery.ajax({
					url : '/donation/dont_show_instant_donation_confirmation',
					dataType : 'json',
					data : {doit : 1},
					type : 'post',
					error : function () {
						giverhubError({msg : 'Could not save your preferences.'});
					},
					success : function (json) {
						if (json === undefined || !json || json.success === undefined || !json.success) {
							giverhubError({msg : 'Got an unexpected response from server while saving your preferences.'});
						}
					},
					complete : function () {
						instantDonationsConfirmationModal.modal('hide');
					}
				})
			} else {
				instantDonationsConfirmationModal.modal('hide');
			}
			return false;
		});

		function trigger_paypal_cc(btn) {

			if (btn.hasClass('btn-donate-using-cc-paypal-button') || btn.hasClass('btn-donate-using-cc-paypal-button-with-amount')) {
				var $step2_payment_methods = jQuery('#step2-payment-methods');

				var stat;

				if (btn.hasClass('btn-donate-using-cc-paypal-button')) {
					stat = 'donate-without-amount-';
				} else {
					stat = 'donate-with-amount-';
				}
				if (btn.hasClass('cc')) {
					$step2_payment_methods.find('#label-cc input').prop("checked", true).trigger("click");
					stat += 'cc';
				} else if (btn.hasClass('paypal')) {
					$step2_payment_methods.find('#label-paypal input').prop("checked", true).trigger("click");
					stat += 'paypal';
				} else {
					throw "Button is incorrectly configured. Should have cc or paypal class";
				}

				window.giverhubStat(stat, function() {});
			} else if (btn.hasClass('btn-donate-from-search')) {
				window.giverhubStat('donate-from-search', function() {});
			} else if (btn.hasClass('btn-donate-from-charity-with-amount')) {
				window.giverhubStat('donate-charity-with-amount', function() {});
			}
		}

		jQuery(document).on('click', '.btn-donate-using-cc-paypal-button, .btn-donate-from-search, .btn-donate-from-charity-header-without-amount', function () {
			try {
				var signedIn = body.data('signed-in') ? true : false;

				if (!signedIn) {
					jQuery('#signin-or-join-first-modal').modal('show');
					return false;
				}

				var donationModal = jQuery('#lbox_donations');
				var btn = jQuery(this);

				trigger_paypal_cc(btn);

				changeSlide(1);

				var charityName = btn.data('charity-name');
				var charityId = btn.data('charity-id');

				donationModal.find('.charity-name').html(charityName);
				donationModal.find('.amount').val('');
				donationModal.data('charity-name', charityName).data('charity-id', charityId);
				donationModal.data('bet_id', null);
				donationModal.data('bet_friend_id', null);
				donationModal.modal('show');
				jQuery('.donation-modal-amount-list-item').removeClass('active');
				jQuery('.donation-modal-amount-list-item.default').addClass('active');
				jQuery('.modal-content .gh_step_list li').removeClass('active');
				jQuery('.modal-content .gh_step_list li.default').addClass('active');
				jQuery("#donate_amount").val("75");

				if (GIVERHUB_LIVE) {
					ga('send', 'event', 'donation', 'step1');
					ga('send', 'pageview', '/virtual/donation/step1');
				}
			} catch(e) {
				giverhubError({e:e});
			}
			return false;
		});

		jQuery(document).on('click', '.donation-modal-amount-list-item', function() {
			var $this = jQuery(this);
			jQuery('.donation-modal-amount-list-item').each(function(i,e) {
				jQuery(e).removeClass('active');
			});
			$this.addClass('active');
			jQuery('.donation-modal-amount').val($this.data('amount'));
			return false;
		});

		jQuery(document).on('click', '.toggle-slider', function() {
			var $this = jQuery(this);
			var targetStep = parseInt($this.data('slider'));
			var currentStep = 1;
			jQuery('.toggle-slider').each(function(i,e) {
				if (jQuery(e).hasClass('active')) {
					currentStep = jQuery(e).data('slider');
				}
			});
			// only allow going backwards.
			if (targetStep < currentStep) {
				changeSlide(targetStep);
			}
			return false;
		});

		window.changeSlide = function changeSlide(nr) {
			var nxt_slide = 'slider'+nr,
				nxt_slide_height = $('.gh_slider_container #' + nxt_slide).height();

			var $you_are_donating_to = jQuery('#lbox_donations').find('.you-are-donating-to');
			if (nr == 1 || !nxt_slide_height) {
				$('.gh_slider_container > *').hide();
				$('.gh_slider_container #' + nxt_slide).css({left : 0}).show();
				$('.gh_slider_container').attr('style', 'height:auto;');
				$you_are_donating_to.html("You are donating to");
			} else {
				$('.gh_slider_container > *').fadeOut(300);
				$('.gh_slider_container').animate({height : nxt_slide_height}, 200, function () {
					$('.gh_slider_container #' + nxt_slide).css({left : 0}).fadeIn(300);
				});
			}

			if (nr == 3) {
				$('#lbox_donations').addClass('slide3');
				jQuery('#donation_modal_instant_donation_enabled_txt').addClass('hidden');
				jQuery('.gh_step_list').removeClass("hidden");

			} else {
				$('#lbox_donations').removeClass('slide3');

				if (body.data('instant-donations')) {
					jQuery('.gh_step_list').addClass("hidden");
					jQuery('#donation_modal_instant_donation_enabled_txt').removeClass('hidden');
				} else {
					jQuery('#donation_modal_instant_donation_enabled_txt').addClass('hidden');
					jQuery('.gh_step_list').removeClass("hidden");
				}
			}

			if (nr != null) {
				if ($('.gh_step_list').length > 0) {
					$('.gh_step_list a, .gh_step_list li').removeClass('active');
					$('.gh_step_list li[data-slider_step="' + nr + '"] a, .gh_step_list li[data-slider_step="' + nr + '"]').addClass('active');
				}
			}
		};

		function gotoStep2(amount, btn, fromCharityProfile) {
			if (GIVERHUB_LIVE) {
				ga('send', 'event', 'donation', 'step2');
				ga('send', 'pageview', '/virtual/donation/step2');
			}
			jQuery('#recurring-donations-select').val('NotRecurring');
			jQuery('#recurring-notify-label').addClass('hide');
			jQuery('#recurring-notify-checkbox').prop('checked', true);

			jQuery('#dedication_input').val('');
			jQuery('#dedication_chars').removeClass('red').html('');

			var donationModal = jQuery('#lbox_donations');
			var charityId = donationModal.data('charity-id');
			var charityName = donationModal.data('charity-name');

			donationModal.find('.charity-name').html(charityName);
			jQuery('#lbox_donations').scrollTop(0);
			if (amount >= 10) {
				jQuery('.modal-content .gh_step_list li').each(function(i,e) {
					jQuery(e).removeClass('active');
				});

				var instantDonations = body.data('instant-donations');
				if (instantDonations) {
					btn.data('loading-text', 'PROCESSING..');
					btn.button('loading');
					if (fromCharityProfile) {
						jQuery('.charity-profile-donation-amount-input').attr('disabled', 'disabled');
					}
					var CSRFToken = jQuery('body').data('csrf-token');

					jQuery.ajax('/donation/confirmDonation', {
						dataType : 'json',
						type : 'POST',
						data : {
							cofId : 'instant-donation',
							amount : amount,
							charityId : charityId,
							CSRFToken : CSRFToken,
							bet_id : donationModal.data('bet_id'),
							bet_friend_id : donationModal.data('bet_friend_id')
						},
						success : function (json) {
							try {
								if (json.success) {
									jQuery('.donation-confirmation-amount').html(amount);
									jQuery('.donation-confirmation-amount-earned-givercoin').html(parseInt(amount) * parseInt(jQuery('#lbox_donations').data('givercoin-reward')));
									jQuery('#donation-confirmation-name').html(charityName);
									changeSlide(3);
									donationModal.find('.you-are-donating-to').html('You successfully donated $'+amount+' to');
									if (fromCharityProfile) {
										donationModal.modal('show');
									}

									if (typeof(json.bet_info) === 'string') {
										jQuery('.bet-info').html(json.bet_info);
										jQuery('#bet-determination-modal').modal('hide');
									}
									if (typeof(json.bet_list) === 'string') {
										jQuery('.bet-list-container').html(json.bet_list);
										jQuery('#bet-determination-modal').modal('hide');
									}
								} else {
									giverhubError({msg : json.msg});
								}
							} catch(e) {
								giverhubError({e:e});
							}
						},
						error : function () {
							giverhubError({msg : 'There was an unexpected error while confirming your donation. Before trying again, Please wait a moment and see if you get a receipt in your inbox. We apologize for the inconvinience.'});
						},
						complete : function () {
							btn.button('reset');
							jQuery('.charity-profile-donation-amount-input').removeAttr('disabled');
						}
					});
					return false;
				} else {
					jQuery('.modal-content .gh_step_list li.confirm_donation').addClass('active');
				}

				jQuery('.step2-donation-method-state').hide();
				jQuery('#step2-donation-method-loading').show();

				reloadCards(function (cards) {
					jQuery('.step2-donation-method-state').hide();
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
						jQuery('#step2-donation-method-card').show();
					} else {
						jQuery('#step2-donation-method-empty').show();
					}
				}, function () {
					jQuery('.step2-donation-method-state').hide();
					jQuery('#step2-donation-method-empty').show();
				});

				jQuery('.donation-amount-displayed-in-step2').html('$'+amount);
				donationModal.data('amount', amount);
				changeSlide(2);
				if (fromCharityProfile) {
					donationModal.modal('show');
				}
			} else {
				if (amount > 0) { // if amount is over 0 .. we will show the wrong-amount modal
					jQuery('#lbox_donations').modal('hide');
					jQuery('#lbox_wrong_amount').modal('show');
				} else { // if there is no amount at all.. we will not show the wrong amount modal
					jQuery('#lbox_wrong_amount').find('.btn-continue-after-wrong-amount').trigger('click');
				}
			}
			return false;
		}

		jQuery(document).on('click', '.btn-donation-modal-goto-step2', function() {
			var amount = jQuery('.donation-modal-amount').val();
			window.giverhubStat("donation-goto-step2", function() {});
			gotoStep2(amount, jQuery(this));
			return false;
		});

		jQuery(document).on('click', '.btn-donate-from-charity-with-amount, .btn-donate-using-cc-paypal-button-with-amount', function() {
			var signedIn = body.data('signed-in') ? true : false;

			if (!signedIn) {
				jQuery('#signin-or-join-first-modal').modal('show');
				return false;
			}

			var btn = jQuery(this);

			trigger_paypal_cc(btn);

			var amount = btn.closest('.donate-container').find('.charity-profile-donation-amount-input').val();

			var donationModal = jQuery('#lbox_donations');
			donationModal.data('bet_id', null);
			donationModal.data('bet_friend_id', null);
			donationModal.data('charity-id', btn.data('charity-id'));
			donationModal.data('charity-name', btn.data('charity-name'));

			gotoStep2(amount, btn, true);
			return false;
		});

		function continueAfterWrongAmount() {
			jQuery('#lbox_wrong_amount').modal('hide');

			var donationModal = jQuery('#lbox_donations');
			jQuery('.donation-modal-amount-list-item').removeClass('active');
			jQuery('.modal-content .gh_step_list li').removeClass('active');
			jQuery('.modal-content .gh_step_list li.default').addClass('active');
			donationModal.find('.amount').val('');
			donationModal.data('bet_id', null);
			donationModal.data('bet_friend_id', null);
			donationModal.modal('show');
			changeSlide(1);
		}

		jQuery(document).on('click', '.btn-continue-after-wrong-amount', function() {
			continueAfterWrongAmount();
		});

		jQuery(document).on('hidden.bs.modal', '#lbox_wrong_amount', function () {
			continueAfterWrongAmount();
		});

		jQuery(document).on('click', '#btn-add-payment-method-modal', function () {
			window.giverhubStat("donation-add-credit-card-from-step2", function() {});
			jQuery('#lbox_donations').modal('hide');
			showChangePayment(false);
		});

		jQuery(document).on('change', '#recurring-donations-select', function() {
			try {
				var $notify = jQuery('#recurring-notify-label');
				if (jQuery(this).val() == 'NotRecurring') {
					$notify.addClass('hide');
				} else {
					$notify.removeClass('hide');
				}
			} catch(e) {
				giverhubError({e:e});
			}
		});


		jQuery(document).on('keyup', '#dedication_input', function() {
			try {
				var $this = jQuery(this);
				var $chars = jQuery('#dedication_chars');

				$chars.html($this.val().length + "/128");
				if ($this.val().length > 128) {
					$chars.addClass('red');
				} else {
					$chars.removeClass('red');
				}
			} catch(e) {
				giverhubError({e:e});
			}
		});

		jQuery(document).on('click', '.btn-donation-modal-goto-step3', function() {
			var btn = jQuery(this);
			try {
				btn.button('loading');

				var donationModal 				= jQuery('#lbox_donations');
				var giverCardTransaction 		= false;
				var givercard_transaction_id 	= null;

				var cofId = donationModal.data('cof-id');
				var amount = donationModal.data('amount');
				var charityId = donationModal.data('charity-id');
				var charityName = donationModal.data('charity-name');
				var bet_id = donationModal.data('bet_id');
				var bet_friend_id = donationModal.data('bet_friend_id');

				var recurType = jQuery('#recurring-donations-select').val();
				var recurNotify = jQuery('#recurring-notify-checkbox').prop('checked') ? '1' : '0';
				var CSRFToken = body.data('csrf-token');

				var payment_method = jQuery('.payment-method-radio:checked').val();

				if (typeof payment_method === "undefined") {
					giverhubError({subject : 'Select payment method', msg : 'You need to select a payment method first. PayPal or Credit Card'});
					btn.button('reset');
					return false;
				}

				if ( jQuery('#givercard_payment').is(":checked") ) {
					var givercardId = jQuery('#giver_card_name').val().trim();
					if ( !givercardId ) {
						giverhubError({msg : 'You need to select Giver Card first!'});
						btn.button('reset');
						return;
					}
					giverCardTransaction = true;
					givercard_transaction_id = givercardId;

					//Check weather giver card is having sufficient amount or not
					var givercardBalanceAmount = donationModal.data('givercard-balance-amount');
					if (givercardBalanceAmount < amount) {
						giverhubError({msg : 'This GiverCard is having only $'+givercardBalanceAmount+', you can\'t donate more than it'});
						btn.button('reset');
						return;
					}

					//Check weather remaining amount after givercard donation is less than $10 or not
					var remainingAmountAfterDonation = givercardBalanceAmount - amount;
					var totalAmount = Number(amount) + Number(remainingAmountAfterDonation);

					if ( remainingAmountAfterDonation < 10 && remainingAmountAfterDonation != 0 ) {
						var totalUnsedAmount = 10 - remainingAmountAfterDonation;
						var newDonationAmount = amount - totalUnsedAmount;

						if (newDonationAmount < 10) {
							giverhubError({msg : 'Donating $'+amount+' would leave $'+remainingAmountAfterDonation+' unusable in your GiverCard. Please donate $'+totalAmount+' to empty GiverCard'});
							btn.button('reset');
							return;
						} else {
							giverhubError({msg : 'Donating $'+amount+' would leave $'+remainingAmountAfterDonation+' unusable in your GiverCard. Please donate $'+totalAmount+' to empty GiverCard or donate $'+newDonationAmount+' so that at least $10 will left in you Givercard'});
							btn.button('reset');
							return;
						}
					}
				}

				var $dedication_input = jQuery('#dedication_input');

				if ($dedication_input.val().length > 128) {
					giverhubError({msg : 'Dedication is too long. Max 128 characters.'});
					btn.button('reset');
					return;
				}

				if (payment_method == "paypal") {
					jQuery.ajax({
						url : '/donation/initializePaypalDonation',
						type : 'post',
						dataType : 'json',
						data : {
							amount : amount,
							charityId : charityId,
							bet_id    : bet_id,
							bet_friend_id : bet_friend_id,
							dedication : $dedication_input.val()
						},
						success : function (json) {
							try {
								if (typeof(json) !== "object" || typeof(json.success) !== "boolean" || !json.success || typeof(json.RedirectURL) !== "string") {
									giverhubError({msg : 'There was an unexpected problem while processing your request. Please try again later. Thank you.'});
								} else {
									giverhubSuccess({subject : 'Redirecting to PayPal', msg : 'Please wait while you are being redirected to PayPal.'});
									window.location = json.RedirectURL;
									if (GIVERHUB_LIVE) {
										ga('send', 'event', 'donation', 'go to paypal');
										ga('send', 'pageview', '/virtual/donation/go-to-paypal');
									}
								}
							} catch(e) {
								giverhubError({e:e});
							}
						},
						error : function () {
							giverhubError({msg : 'There was an unexpected problem while processing your request. Please try again later. Thank you.'});
						},
						complete : function () {
							btn.button('reset');
						}
					});
				} else if (payment_method == "cc") {
					if (!cofId) {
						giverhubError({msg : 'You need to add a payment method first!'});
						btn.button('reset');
						return;
					}

					jQuery.ajax('/donation/confirmDonation', {
						dataType : 'json',
						type : 'POST',
						data : {
							cofId : cofId,
							amount : amount,
							charityId : charityId,
							recurType : recurType,
							recurNotify : recurNotify,
							CSRFToken : CSRFToken,
							bet_id    : bet_id,
							bet_friend_id : bet_friend_id,
							givercard_transaction : giverCardTransaction,
							givercard_transaction_id : givercard_transaction_id,
							dedication : $dedication_input.val()
						},
						success : function (json) {
							if (json.success) {
								if (GIVERHUB_LIVE) {
									ga('send', 'event', 'donation', 'success');
									ga('send', 'pageview', '/virtual/donation/success/cc');
								}

								jQuery('.donation-confirmation-amount').html(amount);
								jQuery('.donation-confirmation-amount-earned-givercoin').html(parseInt(amount) * parseInt(jQuery('#lbox_donations').data('givercoin-reward')));
								jQuery('#donation-confirmation-name').html(charityName);

								var $btn_share_donation_success = jQuery('.btn-share-donation-success');
								$btn_share_donation_success
									.data('donation-id', json.donation_id)
									.data('nonprofit-url', json.nonprofit_url);

								window.initShareButtons({
									event_type : 'donation',
									event_id : json.donation_id,
									el : donationModal.find('.gh-share-buttons-container'),
									url : json.nonprofit_url
								});

								jQuery('.modal-content .gh_step_list li').each(function(i,e) {
									jQuery(e).removeClass('active');
								});

								if (json.givercardDonation) {
									var givercardDonationSucessModal = jQuery('#givercardDonationSucessMsgModal');
									donationModal.modal('hide');
									givercardDonationSucessModal.find('#donation-confirmation-name').html(charityName);
									givercardDonationSucessModal.modal('show');
								} else {
									jQuery('.modal-content .gh_step_list li.make_donation').addClass('active');
									changeSlide(3);
									donationModal.find('.you-are-donating-to').html('You successfully donated $'+amount+' to');
									if (typeof(json.bet_info) === 'string') {
										jQuery('.bet-info').html(json.bet_info);
										jQuery('#bet-determination-modal').modal('hide');
									}
									if (typeof(json.bet_list) === 'string') {
										jQuery('.bet-list-container').html(json.bet_list);
										jQuery('#bet-determination-modal').modal('hide');
									}
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
						}
					});
				}
			} catch(e) {
				giverhubError({e:e});
				btn.button('reset');
			}
			return false;
		});

		jQuery(document).on('click', '.btn-change-add-payments-from-settings', function() {

			showChangePayment(false, {
				btnSelectCard : function () {
					selectCard(getCardByCOFId(jQuery(this).attr('data-cof-id')), false);
					jQuery('#change-payment').data('not-cancelled', true).modal('hide');
					return false;
				}
			});

		});

		jQuery(document).on('click', '.show-more', function () {
			var btnShowMore = jQuery(this);
			var $target = jQuery(btnShowMore.attr('data-target-tab'));
			var isTab = btnShowMore.hasClass('page_tab');
			if (isTab) {
				if ($target.find('.row').length) {
					return true;
				} else {
					$target.html(jQuery('<img class="tab-is-loading" src="/images/ajax-loaders/ajax-loader.gif" alt="Loading..."/>'));
				}
			}
			var search_type = btnShowMore.data('search-type');
			btnShowMore.button('loading');
			jQuery.ajax('/home/more', {
				type : 'POST',
				dataType : 'html',
				data : {
					search_text : btnShowMore.attr('data-search-text'),
					search_zip : btnShowMore.attr('data-search-zip'),
					causes : btnShowMore.attr('data-search-causes'),
					offset : btnShowMore.attr('data-offset'),
					tab : btnShowMore.attr('data-tab'),
					submit : 'Search',
					search_type : search_type
				},
				success : function (data) {
					btnShowMore.button('reset');
					if (data == 'no-more') {
						btnShowMore.addClass('disabled');
						$target.html('Your search for' +
							' <i>'+btnShowMore.attr('data-search-text')+'</i> '+(btnShowMore.attr('data-search-zip').length ? 'in zipcode '+btnShowMore.attr('data-search-zip') : '') +
							' did not match anything.<br/>' +
							'Please try with different search terms.<br/><br/>'+
							'If what you are looking for is missing, please let us know by clicking the ' +
							'<a href="#" ' +
							'data-placement="top" ' +
							'title="Leave us feedback! We\'re eager to hear what you like about GiverHub and what we can do to make it better." ' +
							'class="btn-feedback">feedback</a> ' +
							'button or dropping an email to <a href="mailto:admin@giverhub.com">admin@giverhub.com</a>');
					} else {
						if (isTab) {
							$target.find('.tab-is-loading').remove();
						}
						$target.append(data);

						jQuery('.gh_btn_switch').each(function(i,e) {
							if (!jQuery(e).hasClass('enabled')) {
								window.enableSwitch(e);
							}
						});
					}
				},
				complete : function () {

				}
			});
			jQuery(this).attr('data-offset', parseInt(jQuery(this).attr('data-offset')) + 20);
			return isTab;
		});

		function ajaxLoadPageTabContent(id) {
			var $tab = jQuery(id);
		}
		// need to ajax-load the contents of the sorted tabs
		jQuery(document).on('click', '.page_tab', function() {
			var id = '#tab-' + jQuery(this).attr('href').replace('#', '')
			ajaxLoadPageTabContent(id);
			return true;
		});


		var newCardNumber = jQuery('#newCardNumber');
		var prev = newCardNumber.val();

		var i = setInterval(function () {
			var val = newCardNumber.val();
			if (val != prev) {
				prev = val;
				if (val.length >= 12) {
					checkCC(val);
				}
			}
		}, 100);

		jQuery(document).on('click', '.btn-remove-card', function() {
			var $this = jQuery(this);
			try {
				$this.button('loading');

				jQuery.ajax({
					url : '/donation/removeCOF',
					type : 'POST',
					dataType : 'json',
					data : { cofId : $this.data('cof-id') },
					error : function() {
						giverhubError({msg : 'Request Failed. Please refresh the page and try again.'});
					},
					success : function(json) {
						try {
							checkSuccess(json);

							if (typeof(json.prevent_remove_msg) === "string") {
								giverhubError({subject : 'Card is in Use.', msg : json.prevent_remove_msg});
							} else {
								$this.parent().remove();
								if ($paymentMethod.length) {
									getPaymentMethod();
								}
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
				giverhubError({e:e});
			}
			return false;
		});

		jQuery('#frm-add-new-card').card({
			container: jQuery('#card-wrapper'),
			numberInput: '#newCardNumber',
			expiryInput: '#newExpiryMonth, #newExpiryYear',
			cvcInput: '#newSecurityCode',
			nameInput: '#newCardholderName'
		});

		jQuery(document).on('click', '.btn-add-payments-from-givercard-review', function () {
			showChangePayment(false);
		});

		jQuery(document).on('click', '.payment-method-radio', function() {
			try {
				var payment_method = jQuery('.payment-method-radio:checked').val();

				var $step2_donation_method = jQuery('#step2-donation-method');
				var $recurring_tr = jQuery('#recurring-tr');
				var $recurring_notify = $recurring_tr.find('input');
				var $label_paypal = jQuery('#label-paypal');
				var $label_cc = jQuery('#label-cc');

				if (payment_method == 'paypal') {
					$step2_donation_method.addClass('hide');
					$recurring_tr.addClass('hide');
					$label_paypal.addClass('selected');
					$label_cc.removeClass('selected');
				} else if (payment_method == 'cc') {
					$step2_donation_method.removeClass('hide');
					$recurring_notify.prop('checked', true);
					$recurring_tr.removeClass('hide');
					$label_cc.addClass('selected');
					$label_paypal.removeClass('selected');
				} else {
					throw "Invalid Payment Method: " + payment_method;
				}
			} catch(e) {
				giverhubError({e:e});
			}
		});

		var $edit_giving_pot_form_block = jQuery('.edit-giving-pot-form-block');
		if ($edit_giving_pot_form_block.length) {

			reloadCards(function() {}, function() {}); // trigger a reload so that it gets cached straight away

			$edit_giving_pot_form_block.on('click', '.btn-giving-pot-payment-method', function () {
				showChangePayment(false, {
					btnSelectCard : function () {

						jQuery.ajax({
							url : '/giving-pot/set-payment-method/'+$edit_giving_pot_form_block.data('giving-pot-id'),
							type : 'post',
							dataType : 'json',
							data : {card : getCardByCOFId(jQuery(this).attr('data-cof-id'))},
							error : function() {
								giverhubError({msg : 'Request Failed! Please refresh the page and try again.'});
							},
							success : function(json) {
								try {
									checkSuccess(json);
									if (typeof(json.payment_method_html) !== "string") {
										giverhubError({msg : 'Bad response. Please refresh the page and try again.'});
									}

									$edit_giving_pot_form_block.find('.payment-method').html(json.payment_method_html);
								} catch(e) {
									giverhubError({e:e});
								}
							}
						});

						jQuery('#change-payment').data('not-cancelled', true).modal('hide');
						return false;
					}
				});
			});
		}

		jQuery(document).on('click', '.btn-share-donation-success', function() {
			try {
				var $share_donation_success_container = jQuery('.share-donation-success-container');

				var $checkbox_share_donation_giverhub = $share_donation_success_container.find('.checkbox-share-donation-giverhub');
				var $checkbox_share_donation_facebook = $share_donation_success_container.find('.checkbox-share-donation-facebook');

				var giverhub = $checkbox_share_donation_giverhub.is(':checked');
				var facebook = $checkbox_share_donation_facebook.is(':checked');

				var $this = jQuery(this);
				$this.button('loading');

				function thanks() {
					giverhubSuccess({msg : 'Thank you for sharing!'});
					jQuery('#lbox_donations').modal('hide');
					$this.button('reset');
				}
				function shareOnFb() {
					FB.ui({
						method: 'share',
						href: $this.data('nonprofit-url')
					}, function(response){
						thanks();
					});
				}
				if (giverhub) {
					jQuery.ajax({
						url : '/donation/share',
						type : 'post',
						dataType : 'json',
						data : { donation_id : $this.data('donation-id') },
						error : function() {
							giverhubError({msg : 'Request Failed.'});
						},
						success : function(json) {
							try {
								checkSuccess(json);

							} catch(e) {
								giverhubError({e:e});
							}
						},
						complete : function() {
							if (facebook) {
								shareOnFb();
							} else {
								thanks();
							}
						}
					});
				} else if (facebook) {
					shareOnFb();
				} else {
					giverhubSuccess({msg : 'You did not check any of the checkboxes for sharing.'});
					$this.button('reset');
				}
			} catch(e) {
				giverhubError({e:e});
			}
		});
	} catch(e) {
		giverhubError({e:e});
	}
});
