jQuery(document).ready(function () {
	try {

		jQuery(document).on('input', '#complete_phone', function() {
			var start = this.selectionStart,
				end = this.selectionEnd;

			jQuery(this).val(jQuery(this).val().replace(/[^0-9]+/g, ''));

			this.setSelectionRange(start, end);
		});

		jQuery(document).on('input', '#complete_zip', function() {
			var start = this.selectionStart,
				end = this.selectionEnd;

			jQuery(this).val(jQuery(this).val().replace(/[^0-9]+/g, ''));

			this.setSelectionRange(start, end);
		});

		jQuery(document).on('blur', '#complete_zip', function() {
			jQuery.getJSON('/register/zipcode/', { zipcode : jQuery(this).val() }, function (data) {
				var state = jQuery('#complete_state');
				state.attr('data-city-id', data.city);
				state.val(data.state);
				state.trigger('change');
			});
		});

		jQuery(document).on('change', '#complete_state', function() {
			var stateDropDown = jQuery(this);
			var stateId = stateDropDown.val();
			var cityDropDown = jQuery('#complete_city');
			if (!stateId) {
				cityDropDown.html('<option value="">Pick your state first</option>');
				return true;
			}

			cityDropDown.hide();
			var cityLoader = jQuery('#complete-profile-city-loader').show();
			jQuery.ajax({
				url : '/home/getCities',
				type : 'GET',
				data : { stateId : stateId },
				dataType : 'html',
				success : function (html) {
					cityDropDown.html(html);
					var cityId = stateDropDown.data('city-id');
					if (cityId) {
						cityDropDown.val(cityId);
						stateDropDown.data('city-id', '');
					}
				},
				error : function () {
					jQuery('#complete-profile-alert-danger-msg').html('<strong>Ooops.</strong> There was a problem loading cities for your selected state..');
					jQuery('#complete-profile-alert-danger').show();
					cityDropDown.html('<option value="">Pick your state first</option>');
				},
				complete : function () {
					cityLoader.hide();
					cityDropDown.show();
				}
			});
			return true;
		});

		function renderAddresses(json) {
			try {
				var changePaymentAddresses = jQuery('#payment-method-addresses');
				var settingsAddresses = jQuery('#settings-addresses');
				if (json.addressesHtml) {
					if (changePaymentAddresses.length) {
						changePaymentAddresses.html(json.addressesHtml);
					}
					if (settingsAddresses.length) {
						settingsAddresses.html(json.addressesHtml);
					}
				}
			} catch(e) {
				giverhubError({e:e});
			}
		}

		window.prepareAddressModalForAdd = function(header) {
			try {
				var addressModal = jQuery('#address-modal');
				addressModal.find('input, select').val('');
				addressModal.find('.address-lead').html(header === undefined ? 'Add Address' : header);
				addressModal.data('scope', 'add');
			} catch (e) {
				giverhubError({e : e});
			}
		};

		jQuery(document).on('click', '.btn-add-address-from-payment-methods', function () {
			var btn = jQuery(this);
			try {

				jQuery('#change-payment').modal('hide');
				window.prepareAddressModalForAdd();

				jQuery('#address-modal').modal('show').data('from', 'change-payment');

			} catch (e) {
				giverhubError({e : e});
			}
		});

		jQuery(document).on('click', '.btn-add-address-from-settings', function () {
			var btn = jQuery(this);
			try {

				window.prepareAddressModalForAdd();
				jQuery('#address-modal').modal('show').data('from', 'settings');

			} catch (e) {
				giverhubError({e : e});
			}
		});

		jQuery(document).on('change', '#complete_zip', function () {
			var $this = jQuery(this);
			var start = this.selectionStart,
				end = this.selectionEnd;

			$this.val($this.val().replace(/[^0-9]+/g, ''));

			this.setSelectionRange(start, end);
		});

		jQuery(document).on('hidden.bs.modal', '#address-modal', function () {
			var addressModal = jQuery('#address-modal');
			if (addressModal.data('from') == 'change-payment') {
				jQuery('#change-payment').modal('show');
			}
		});

		jQuery(document).on('click', '#btn-save-address', function () {
			var btn = jQuery(this);
			try {
				var addressModal = jQuery('#address-modal');

				var address1 = jQuery('#complete_address').val();
				if (!address1.length) {
					giverhubError({msg : 'You need to enter address.'});
					btn.button('reset');
					return false;
				}

				var address2 = jQuery('#complete_address2').val();

				var zipcode = jQuery('#complete_zip').val();
				if (!zipcode.length) {
					giverhubError({msg : 'You need to enter zipcode.'});
					btn.button('reset');
					return false;
				}
				if (zipcode.length != 5) {
					giverhubError({msg : 'Zipcode needs to be 5 digits. You entered ' + zipcode.length + ' digits.'});
					btn.button('reset');
					return false;
				}

				var state = jQuery('#complete_state').val();
				if (!state.length) {
					giverhubError({msg : 'You need to select a state.'});
					btn.button('reset');
					return false;
				}

				var city = jQuery('#complete_city').val();
				if (!city.length) {
					giverhubError({msg : 'You need to select a city.'});
					btn.button('reset');
					return false;
				}

				var phone = jQuery('#complete_phone').val();
				if (!phone.length) {
					giverhubError({msg : 'You need to enter a phone number.'});
					btn.button('reset');
					return false;
				}
				if (phone.length != 10 && phone.length != 11) {
					giverhubError({msg : 'Phone number needs to be 10 or 11 digits. You entered ' + phone.length + ' digits.'});
					btn.button('reset');
					return false;
				}

				btn.button('loading');

				jQuery.ajax({
					url : '/members/save_address',
					type : 'post',
					dataType : 'json',
					data : {
						scope : addressModal.data('scope'),
						address1 : address1,
						address2 : address2,
						zipcode : zipcode,
						state : state,
						city : city,
						phone : phone,
						user_address_id : addressModal.data('user-address-id')
					},
					error : function () {
						giverhubError({msg : 'Request failed.'});
					},
					success : function (json) {
						try {
							if (json === undefined || !json || !json.success || json.user_address_id === undefined || !json.user_address_id) {
								if (json.msg !== undefined) {
									giverhubError({msg : json.msg});
								} else {
									giverhubError({msg : 'Bad response.'});
								}
							} else {
								renderAddresses(json);
								if (addressModal.data('from') == 'change-payment') {
									addressModal.modal('hide');
									jQuery('#change-payment').modal('show');
								} else if (addressModal.data('from') == 'petition') {
									body.data('default-address-id', json.user_address_id);
									addressModal.modal('hide');
									var $signPetitionModal = jQuery('#sign-petition-modal');
									var $signPetitionModalBody = $signPetitionModal.find('.modal-body');
									$signPetitionModalBody.html('<img src="/images/ajax-loaders/ajax-loader.gif" class="loading" alt="loading"/>');
									jQuery.ajax({
										url : '/petitions/sign_modal_body',
										dataType : 'json',
										data : { petition_id : addressModal.data('petition-id') },
										type : 'get',
										error : function() {
											giverhubError({msg : 'Request Failed'});
										},
										success : function(json) {
											try {
												checkSuccess(json);

												$signPetitionModalBody.html(json.html);
											} catch(e) {
												giverhubError({e:e});
											}
										}
									});
									$signPetitionModal.modal('show');
								} else {
									addressModal.modal('hide');
								}
							}
						} catch (e) {
							giverhubError({e : e});
						}
					},
					complete : function () {
						btn.button('reset');
					}
				});

			} catch (e) {
				giverhubError({e : e});
				btn.button('reset');
			}
			return false;
		});

		function prepareAddressModalForEdit(btn) {
			try {
				var addressModal = jQuery('#address-modal');

				jQuery('#complete_state').val(btn.data('state')).trigger('change');

				jQuery('#complete_address').val(btn.data('address1'));
				jQuery('#complete_address2').val(btn.data('address2'));
				jQuery('#complete_zip').val(btn.data('zipcode'));
				jQuery('#complete_phone').val(btn.data('phone'));

				var waitForCities = function() {
					if (jQuery('#complete_city option').length > 1) {
						jQuery('#complete_city').val(btn.data('city'));
					} else {
						// try again after a while..
						setTimeout(waitForCities, 300);
					}
				};
				waitForCities();

				addressModal.find('.address-lead').html('Edit Address');
				addressModal.data('scope', 'edit').data('user-address-id', btn.data('user-address-id'));
			} catch (e) {
				giverhubError({e : e});
			}
		}

		jQuery(document).on('click', '.btn-edit-address', function() {
			try {
				var btn = jQuery(this);

				var from = btn.closest('.addresses-container').data('address-container-from');

				prepareAddressModalForEdit(btn);

				jQuery('#change-payment').modal('hide');
				jQuery('#address-modal').modal('show').data('from', from);

			} catch(e) {
				giverhubError({e:e});
			}
		});

		function selectRow(btn, makeDefault) {
			var container = btn.closest('.addresses-container');

			var firstRow = jQuery(container.find('.address-row')[0]);
			var firstRowHtml = firstRow.html();
			var firstAddressId = firstRow.data('user-address-id');

			var row = btn.closest('.address-row');
			var rowHtml = row.html();
			var rowAddressId = row.data('user-address-id');

			row.html(firstRowHtml).data('user-address-id', firstAddressId);
			firstRow.html(rowHtml).data('user-address-id', rowAddressId);

			row.find('.btn-select-address').removeClass('hide');
			row.find('.btn-selected-address').addClass('hide');
			firstRow.find('.btn-select-address').addClass('hide');
			firstRow.find('.btn-selected-address').removeClass('hide');

			if (makeDefault) {
				row.find('.btn-make-default-address').removeClass('hide');
				row.find('.btn-current-default-address').addClass('hide');
				firstRow.find('.btn-make-default-address').addClass('hide');
				firstRow.find('.btn-current-default-address').removeClass('hide');
			}

		}

		jQuery(document).on('click', '.btn-select-address', function() {
			try {
				var btn = jQuery(this);

				selectRow(btn, false);
			} catch(e) {
				giverhubError({e:e});
			}
			return false;
		});

		jQuery(document).on('click', '.btn-make-default-address', function() {
			var btn = jQuery(this);
			try {

				jQuery.ajax({
					url : '/members/set_default_address',
					type : 'post',
					dataType : 'json',
					data : { user_address_id : btn.data('user-address-id') },
					error : function() {
						giverhubError({msg : 'Request failed.'});
					},
					success : function(json) {
						try {
							if (json === undefined || !json || !json.success) {
								if (json.msg !== undefined) {
									giverhubError({msg : json.msg});
								} else {
									giverhubError({msg : 'Bad response.'});
								}
							} else {
								selectRow(btn, true);
							}
						} catch (e) {
							giverhubError({e : e});
						}
					},
					complete : function() {
					}
				});

			} catch(e) {
				btn.button('reset');
				giverhubError({e:e});
			}
			return false;
		});

	} catch (e) {
		giverhubError({e : e});
	}
});