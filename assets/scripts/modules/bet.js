try {
	jQuery(document).ready(function() {
		try {
			var $betFormOnBetaFriendPageBlock = jQuery('.bet-form-on-bet-a-friend-page-block');
			var $bet_list = jQuery('.bet-list-container');
			var $main = jQuery('main');
			var bet_a_friend_page = $main.hasClass('bet-a-friend-page') ? 1 : 0;

			var fetchCharitySearchElements = function($el) {
				return {
					input : $el.find('.bet_charity'),
					results : $el.find('.bet_charity_results'),
					chosen : $el.find('.bet_charity_chosen')
				};
			};

			var clearFriendButtonString = '<button type="button" class="btn btn-xs btn-danger btn-clear-friend">x</button>';
			var clearCharityButtonString = '<button type="button" class="btn btn-xs btn-danger btn-clear-charity">x</button>';
			jQuery(document).on('click', '.learn-more-bet', function() {
				try {
					jQuery('#learn-about-bet-modal').modal('show');
				} catch(e) {
					giverhubError({e:e});
				}

				return false;
			});


			var betForm = jQuery('.bet-form');
			if ($betFormOnBetaFriendPageBlock.length) {
				betForm = jQuery('.bet-form-on-bet-a-friend-page-block').find('.bet-form');
			}

			betForm.on('keydown change click', 'input, textarea', function() {
				if (!body.data('signed-in')) {
					window.signInOrJoinFirst("To make bets you need to sign in or join first!");
					return false;
				}
			});

			var betTerms = jQuery('.bet-form .bet_terms');
			var betAmount = jQuery('.bet-form .bet_amount');
			var betTermsPlaceholder = betTerms.data('placeholder');

			function fix_position() {
				var start = this.selectionStart;
				var end = this.selectionEnd;

				var $this = jQuery(this);
				var val = $this.val();
				var val_length = val.length;
				var placeholder = $this.data('placeholder');

				var placeholder_length = placeholder.length;

				if (start < placeholder_length || end < placeholder_length) {
					this.selectionStart = placeholder_length;
					this.selectionEnd = placeholder_length;
				}
			}

			betForm.on('keyup', '.bet_terms', fix_position);
			betForm.on('change', '.bet_terms', fix_position);
			betForm.on('click', '.bet_terms', fix_position);

			var betTermsRegex = new RegExp('^'+betTermsPlaceholder);

			betForm.on('keyup', '.bet_terms', function() {
				try {
					var start = this.selectionStart;
					var end = this.selectionEnd;

					if (!betTermsRegex.test(betTerms.val())) {
						if (betTerms.data('prev-value')) {
							betTerms.val(betTermsPlaceholder + betTerms.data('prev-value'));
						} else {
							betTerms.val(betTermsPlaceholder);
						}

						if (start < betTermsPlaceholder.length || end < betTermsPlaceholder.length) {
							this.selectionStart = betTermsPlaceholder.length;
							this.selectionEnd = betTermsPlaceholder.length;
						} else {
							this.selectionStart = start;
							this.selectionEnd = end;
						}
					} else {
						betTerms.data('prev-value', betTerms.val().substring(betTermsPlaceholder.length));
					}
				} catch(e) {
					giverhubError({e:e});
				}
				return true;
			});

			betForm.on('keyup', '.bet_amount', function() {
				try {
					var start = this.selectionStart;
					var end = this.selectionEnd;

					betAmount.val(betAmount.val().replace(/[^0-9$]/g, ''));

					this.setSelectionRange(start, end);

				} catch(e) {
					giverhubError({e:e});
				}
				return true;
			});

			var betModal = jQuery('#bet-modal');

			var $name = betForm.find('.bet_name');
			var $terms = betForm.find('.bet_terms');
			var $amount = betForm.find('.bet_amount');


			var results_store = {};
			var charity_results_store = {};


			var $date = betForm.find('.bet_date');

			function startBet() {
				var charity_search_elements = fetchCharitySearchElements(betForm.find('.charity-search-container'));

				var $charity_input = charity_search_elements.input;
				var $bet_charity_results = charity_search_elements.results;
				var $chosen_charity = charity_search_elements.chosen;

				betForm.data('bet-id', '');
				$name.val('');
				$terms.val($terms.data('placeholder'));
				$amount.val('');

				$chosen_charity.html('').hide();

				$charity_input.val('').show();
				$bet_charity_results.html('').hide();
				$date.val('');

				if (!$betFormOnBetaFriendPageBlock.length) {
					betModal.data('prompt-hide', true);
					betModal.modal('show');
				}
			}

			if ($betFormOnBetaFriendPageBlock.length) {
				startBet();
			}

			var editBet = function(bet) {
				var charity_search_elements = fetchCharitySearchElements(betForm.find('.charity-search-container'));

				var $charity_input = charity_search_elements.input;
				var $bet_charity_results = charity_search_elements.results;
				var $chosen_charity = charity_search_elements.chosen;

				betForm.data('bet-id', bet.id);
				$name.val(bet.name);
				$terms.val($terms.data('placeholder') + bet.terms);
				$amount.val(bet.amount);

				$chosen_charity.html('<li data-charity-id="'+bet.charity.id+'"><a class="select-charity" href="#">'+bet.charity.name+clearCharityButtonString+'</a></li>').show();
				$charity_input.val('').hide();
				$bet_charity_results.html('').hide();
				$date.val(bet.determinationDate);

				if (!$betFormOnBetaFriendPageBlock.length) {
					betModal.data('prompt-hide', true);
					betModal.modal('show');
				}
			};


			var readForm = function() {
				var data = {};

				if (betForm.data('bet-id')) {
					data['bet_id'] = betForm.data('bet-id');
				}

				var name = $name.val().trim();
				if (!name.length) {
					giverhubError({subject : 'Missing name!', msg : 'You need to enter a name for your bet.'});
					giverhubError.hideEvent = function() {$name.focus()};
					return false;
				}
				data['name'] = name;

				var terms = $terms.val().trim();
				var terms_placeholder = $terms.data('placeholder');
				if (terms.length < terms_placeholder.length + 5) {
					giverhubError({subject : 'Bad Terms', msg : 'Your terms must contain at least 5 characters.'});
					giverhubError.hideEvent = function() {
						$terms.focus();
						var tmpStr = $terms.val();
						$terms.val('');
						$terms.val(tmpStr);
					};
					return false;
				}
				data['terms'] = terms.substring(terms_placeholder.length).trim();

				var amount = $amount.val().trim();
				amount = amount.replace(/[^0-9]/g, '');
				amount = parseInt(amount);
				if (isNaN(amount) || amount < 10) {
					giverhubError({subject : 'Bad Amount', msg : 'Your amount must be at least $10.'});
					giverhubError.hideEvent = function() {$amount.focus()};
					return false;
				}
				data['amount'] = amount;

				var date = $date.val().trim();
				var date_moment = moment(date, 'MM/DD/YY');
				if (!date_moment.isValid()) {
					giverhubError({subject : 'Invalid Date', msg : 'You need to select a date.'});
					giverhubError.hideEvent = function() {$date.focus()};
					return false;
				}
				var today_moment = moment();
				var diff_days = date_moment.diff(today_moment, 'days');
				if (diff_days < 0) {
					giverhubError({subject : 'Invalid Date', msg : 'Date cannot be in the past. Try using the datepicker.'});
					giverhubError.hideEvent = function() {$date.focus()};
					return false;
				}
				data['date'] = date;

				var $bet_form_chosen_charity = betForm.find('.bet_charity_chosen');
				var $bet_form_charity_input = betForm.find('.bet_charity');
				$li = $bet_form_chosen_charity.find('li');
				if (!$li.length) {
					giverhubError({subject : 'Select Non-profit', msg : 'You need to select a Non-profit'});
					giverhubError.hideEvent = function() {$bet_form_charity_input.focus()};
					return false;
				}
				var charity_id = jQuery($li[0]).data('charity-id');
				if (!charity_id) {
					giverhubError({subject : 'Select Non-profit', msg : 'Something went wrong with selecting your non-profit.'});
					giverhubError.hideEvent = function() {$bet_form_charity_input.focus()};
					return false;
				}
				data['charity_id'] = charity_id;

				return data;
			};

			var $confirmBetModal = jQuery('#confirm-bet-modal');
			$confirmBetModal.$friend_chosen = $confirmBetModal.find('.bet_friend_chosen');
			$confirmBetModal.$friend_input = $confirmBetModal.find('.bet_friend');
			$confirmBetModal.$friend_result = $confirmBetModal.find('.bet_friend_results');

			var review = function(bet) {
				$confirmBetModal.data('bet', bet);

				$confirmBetModal.find('.bet-data-name').html(bet.name);
				$confirmBetModal.find('.bet-data-terms').html(bet.terms);
				$confirmBetModal.find('.bet-data-amount').html(bet.amount);

				$confirmBetModal.find('.bet-data-first-user-name').html(bet.user.name);


				$confirmBetModal.find('.bet-data-first-charity-name').html(bet.charity.name);
				$confirmBetModal.find('.bet-data-determination-date').html(bet.determinationDate);


				$confirmBetModal.data('prompt-hide', true);
				$confirmBetModal.modal('show');
			};

			betModal.on('hide.bs.modal', function() {
				if (betModal.data('prompt-hide')) {
					return confirm('Are you sure that you want to close? Anything you entered will be lost.');
				}
				return true;
			});

			$confirmBetModal.on('hide.bs.modal', function() {
				if ($confirmBetModal.data('prompt-hide')) {
					return confirm('Are you sure that you want to close? Anything you entered will be lost.');
				}
				return true;
			});

			jQuery(document).on('click', '.btn-make-bet-review', function() {
				if (!body.data('signed-in')) {
					window.signInOrJoinFirst("To make bets you need to sign in or join first!");
					return false;
				}

				var $this = jQuery(this);
				try {
					var data = readForm();

					if (data === false) {
						return false;
					}

					data['bet-a-friend-page'] = bet_a_friend_page;

					$this.button('loading');
					jQuery.ajax({
						url : '/bet/save_for_later',
						type : 'post',
						dataType : 'json',
						data : data,
						error : function() {
							giverhubError({msg : 'Request Failed.'});
						},
						complete : function() {
							$this.button('reset');
						},
						success : function(json) {
							try {
								if (typeof(json) !== 'object' || typeof(json.success) !== 'boolean' || !json.success || typeof(json.bet) !== 'object' || typeof(json.bet_list) !== 'string') {
									giverhubError({msg : 'Bad response'});
								} else {
									betModal.data('prompt-hide', false);
									betModal.modal('hide');
									jQuery('.bet-list-container').html(json.bet_list);
									review(json.bet);
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

			jQuery(document).on('click', '.btn-make-bet-save-for-later', function() {
				if (!body.data('signed-in')) {
					window.signInOrJoinFirst("To make bets you need to sign in or join first!");
					return false;
				}

				var $this = jQuery(this);
				var originalText = $this.html();

				var reset = function() {
					$this.html(originalText);
					$this.removeAttr('disabled');
				};

				try {
					if ($this.attr('disabled') == 'disabled') {
						return false;
					}
					$this.html('Saving...').attr('disabled', 'disabled');

					var data = readForm();
					if (data === false) {
						reset();
						return false;
					}

					data['bet-a-friend-page'] = bet_a_friend_page;

					jQuery.ajax({
						url : '/bet/save_for_later',
						type : 'post',
						dataType : 'json',
						data : data,
						error : function() {
							giverhubError({msg : 'Request Failed.'});
						},
						complete : function() {
							reset();
						},
						success : function(json) {
							try {
								if (json === undefined || !json || json.success === undefined || !json.success || typeof(json.bet) !== 'object' || typeof(json.bet_list) !== 'string') {
									giverhubError({msg : 'Bad response'});
								} else {
									betModal.data('prompt-hide', false);
									betModal.modal('hide');

									$bet_list.html(json.bet_list);
									if (!bet_a_friend_page) {
										giverhubSuccess({msg : 'You can continue working on the bet later by visiting your bets page from the menu on the left side of the page.'});
									}
								}
							} catch(e) {
								giverhubError({e:e});
							}
						}
					});

				} catch(e) {
					reset();
					giverhubError({e:e});
				}
				return false;
			});

			jQuery(document).on('click', '.btn-start-bet', function() {
				try {
					if (!body.data('signed-in')) {
						jQuery('#signin-or-join-first-modal').modal('show');
						return false;
					}
					return true;
				} catch(e) {
					giverhubError({e:e});
				}

				return true;
			});

			$confirmBetModal.on('keyup', '.bet_friend', function() {
				try {
					var $this = jQuery(this);
					var value = $this.val();

					if (!value.length) {
						$confirmBetModal.$friend_result.hide();
						return true;
					}
					$confirmBetModal.$friend_result.show();

					var res = results_store[value];
					if (res === undefined) {
						$confirmBetModal.$friend_result.html('<img src="/images/ajax-loaders/ajax-loader.gif">');
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
											$confirmBetModal.$friend_result.html(json.results);
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
							$confirmBetModal.$friend_result.html('<img src="/images/ajax-loaders/ajax-loader.gif">');
						} else {
							$confirmBetModal.$friend_result.html(res['results']);
						}
					}
				} catch(e) {
					giverhubError({e:e});
				}
				return true;
			});

			$confirmBetModal.on('click', '.bet_friend_chosen .select-friend', function() {
				return false;
			});

			$confirmBetModal.on('click', '.bet_friend_results .select-friend', function() {
				try {
					var $existing_lis = $confirmBetModal.$friend_chosen.find('li');

					if ($existing_lis.length >= 3) {
						giverhubError({msg : 'You already have 3 friends added. Please remove one first.'});
						return false;
					}

					var $this = jQuery(this);
					var $li = $this.parent();
					var $newLi = $li.clone();
					$newLi.find('a').append(clearFriendButtonString);

					if ($existing_lis.length) {
						var duplicate = false;
						$existing_lis.each(function(i,e) {
							var $e = jQuery(e);
							if ($e.data('facebook-friend') == $newLi.data('facebook-friend') && $e.data('user-id') == $newLi.data('user-id')) {
								duplicate = true;
								return false;
							}
						});

						if (duplicate) {
							giverhubError({msg : 'You already added this friend.'});
							return false;
						}
					}

					$confirmBetModal.$friend_chosen.append($newLi);
					$confirmBetModal.$friend_chosen.show();

					$confirmBetModal.$friend_input.val('').focus().trigger('keyup');
				} catch(e) {
					giverhubError({e:e});
				}
				return false;
			});

			$confirmBetModal.on('click', '.bet_friend_chosen .btn-clear-friend', function() {
				try {
					var $this = jQuery(this);
					var $li = $this.closest('li');
					$li.remove();

					var $existing_lis = $confirmBetModal.$friend_chosen.find('li');

					if (!$existing_lis.length) {
						$confirmBetModal.$friend_chosen.hide();
					}
				} catch(e) {
					giverhubError({e:e});
				}
				return false;
			});




			var $emails = $confirmBetModal.find('.emails');
			$emails.on('click', '.btn-danger', function() {
				try {
					var $this = jQuery(this);
					$this.closest('li').remove();
				} catch(e) {
					giverhubError({e:e});
				}
			});

			var emailRegex = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

			$confirmBetModal.on('keyup', '.bet_friend', function(e) {
				var code = e.which; // recommended to use e.which, it's normalized across browsers
				if(code==13)e.preventDefault();
				if(code==32||code==13||code==188||code==186){
					$confirmBetModal.find('.btn-bet-add-email').trigger('click');
				}
			});

			$confirmBetModal.on('click', '.btn-bet-add-email', function() {
				try {
					var $lis = $emails.find('li');
					if ($lis.length >= 3) {
						giverhubError({msg : 'You can not add more than 3 emails.'});
						$email_input.focus();
						return false;
					}

					var $email_input = $confirmBetModal.find('.bet_friend');
					var email = $email_input.val().trim();

					var exists = false;
					$lis.each(function(i,li) {
						if (jQuery(li).data('email') == email) {
							exists = true;
						}
					});

					if (exists) {
						giverhubError({msg : 'The email has already been added'});
						$email_input.focus();
						return false;
					}

					if (!emailRegex.test(email)) {
						giverhubError({msg : 'Email is invalid.'});
						$email_input.focus();
						return false;
					}

					$emails.append(jQuery('<li data-email="'+email+'">'+email+'<button type="button" class="btn btn-danger btn-xs">x</button></li>'));

					$email_input.val('');
					$email_input.focus();
				} catch(e) {
					giverhubError({e:e});
				}
			});



			jQuery(document).on('keyup', '.bet_charity', function() {
				try {
					var $this = jQuery(this);
					var value = $this.val();

					var $charity_search_container = $this.closest('.charity-search-container');
					var elements = fetchCharitySearchElements($charity_search_container);

					var $charity_results_container = elements.results;

					if (!value.length) {
						$charity_results_container.hide();
						return true;
					}
					$charity_results_container.show();

					var res = charity_results_store[value];
					if (res === undefined) {
						$charity_results_container.html('<img src="/images/ajax-loaders/ajax-loader.gif">');
						charity_results_store[value] = {loading : true};
						jQuery.ajax({
							url : '/members/bet_friends_charity',
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
										if (charity_results_store[json.search] === undefined) {
											return;
										}
										if (!charity_results_store[json.search]['loading']) {
											return;
										}
										charity_results_store[json.search]['loading'] = false;
										charity_results_store[json.search]['results'] = json.results;
										if ($this.val() == json.search) {
											$charity_results_container.html(json.results);
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
							$charity_results_container.html('<img src="/images/ajax-loaders/ajax-loader.gif">');
						} else {
							$charity_results_container.html(res['results']);
						}
					}
				} catch(e) {
					giverhubError({e:e});
				}
				return true;
			});

			jQuery(document).on('click', '.bet_charity_chosen .select-charity', function() {
				return false;
			});

			jQuery(document).on('click', '.bet_charity_results .select-charity', function() {
				try {
					var $this = jQuery(this);
					var $li = $this.parent();
					var $newLi = $li.clone();

					var $charity_search_container = $this.closest('.charity-search-container');
					var elements = fetchCharitySearchElements($charity_search_container);

					var $charity_input = elements.input;
					var $charity_results_container = elements.results;
					var $chosen_charity = elements.chosen;

					$newLi.find('a').append(clearCharityButtonString);
					$charity_input.hide();
					$chosen_charity.html($newLi);
					$chosen_charity.show();
					$charity_results_container.hide();
				} catch(e) {
					giverhubError({e:e});
				}
				return false;
			});

			jQuery(document).on('click', '.btn-clear-charity', function() {
				try {
					var $this = jQuery(this);

					var $charity_search_container = $this.closest('.charity-search-container');
					var elements = fetchCharitySearchElements($charity_search_container);

					var $charity_input = elements.input;
					var $charity_results_container = elements.results;
					var $chosen_charity = elements.chosen;

					$chosen_charity.hide();
					$chosen_charity.html('');
					$charity_input.val('').show();
				} catch(e) {
					giverhubError({e:e});
				}
				return false;
			});

			var $acceptPromptBetModal = jQuery('#accept-prompt-bet-modal');

			var acceptPrompt = function(bet_id) {
				var $betRow = jQuery('.bet-row-'+bet_id);

				$acceptPromptBetModal.find('button').data('bet-id', bet_id);

				$acceptPromptBetModal.find('.bet-data-name').html($betRow.data('bet-name'));
				$acceptPromptBetModal.find('.bet-data-terms').html($betRow.data('bet-terms'));
				$acceptPromptBetModal.find('.bet-data-amount').html($betRow.data('bet-amount'));

				$acceptPromptBetModal.find('.bet-data-first-user-name').html($betRow.data('bet-first-user-name'));
				$acceptPromptBetModal.find('.bet-data-first-user-link').html(jQuery($betRow.data('bet-first-user-link')));

				$acceptPromptBetModal.find('.bet-data-other-user-name').html($betRow.data('bet-other-user-name'));

				$acceptPromptBetModal.find('.bet-data-other-user-first-name').html($betRow.data('bet-other-user-first-name'));

				$acceptPromptBetModal.find('.bet-data-first-charity-name').html($betRow.data('bet-first-charity-name'));
				$acceptPromptBetModal.find('.bet-data-first-charity-link').html(jQuery($betRow.data('bet-first-charity-link')));
				$acceptPromptBetModal.find('.bet-data-determination-date').html($betRow.data('bet-determination-date'));

				$acceptPromptBetModal.modal('show');
			};

			jQuery(document).on('click', '.btn-reject-bet', function() {
				var $this = jQuery(this);
				try {
					if (!confirm('Are you sure?')) {
						return false;
					}
					$this.button('loading');

					var bet_id = $this.data('bet-id');

					jQuery.ajax({
						url: '/bet/reject',
						type : 'post',
						dataType : 'json',
						data : {
							bet_id : bet_id
						},
						error : function() {
							giverhubError({msg : 'Failed request.'});
						},
						complete : function() {
							$this.button('reset');
						},
						success : function(json) {
							try {
								if (typeof(json) !== 'object' || typeof(json.success) !== 'boolean' || !json.success || typeof(json.bet_list) !== 'string' || typeof(json.bet_info) !== 'string') {
									giverhubError({msg : 'Bad response.'});
								} else {
									jQuery('.bet-info').html(json.bet_info);
									$bet_list.html(json.bet_list);
									$acceptPromptBetModal.modal('hide');
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
				return false;
			});


			var $betSuccessChooseCharityModal = jQuery('#bet-success-choose-charity-modal');

			var displaySuccessChooseCharityModal = function(bet) {
				var $charity_search_container = $betSuccessChooseCharityModal.find('.charity-search-container');
				var elements = fetchCharitySearchElements($charity_search_container);

				var $charity_input = elements.input;
				var $chosen_charity = elements.chosen;
				var $bet_charity_results = elements.results;

				$chosen_charity.html('').hide();
				$charity_input.val('').show();
				$bet_charity_results.html('').hide();

				$betSuccessChooseCharityModal.data('bet', bet);

				$betSuccessChooseCharityModal.find('.bet-data-name').html(bet.name);
				$betSuccessChooseCharityModal.find('.bet-data-terms').html(bet.terms);
				$betSuccessChooseCharityModal.find('.bet-data-amount').html(bet.amount);

				$betSuccessChooseCharityModal.find('.bet-data-first-user-name').html(bet.user.name);
				$betSuccessChooseCharityModal.find('.bet-data-first-user-link').html(bet.user.link);
				$betSuccessChooseCharityModal.find('.bet-data-first-user-first-link').html(bet.user.fnameLink);

				$betSuccessChooseCharityModal.modal('show');
			};

			$betSuccessChooseCharityModal.on('click', '.btn-submit-choose-bet-charity', function() {
				var $this = jQuery(this);
				try {
					var bet = $betSuccessChooseCharityModal.data('bet');

					var $succes_modal_chosen_charity = $betSuccessChooseCharityModal.find('.bet_charity_chosen');
					var $succes_modal_charity_input = $betSuccessChooseCharityModal.find('.bet_charity');
					var $li = $succes_modal_chosen_charity.find('li');
					if (!$li.length) {
						giverhubError({subject : 'Select Non-profit', msg : 'You need to select a Non-profit'});
						giverhubError.hideEvent = function() {$succes_modal_charity_input.focus()};
						return false;
					}
					var charity_id = jQuery($li[0]).data('charity-id');
					if (!charity_id) {
						giverhubError({subject : 'Select Non-profit', msg : 'Something went wrong with selecting your non-profit.'});
						giverhubError.hideEvent = function() {$succes_modal_charity_input.focus()};
						return false;
					}

					$this.button('loading');

					jQuery.ajax({
						url: '/bet/choose_charity',
						type : 'post',
						dataType : 'json',
						data : {
							bet_id : bet.id,
							charity_id : charity_id
						},
						error : function() {
							giverhubError({msg : 'Failed request.'});
						},
						complete : function() {
							$this.button('reset');
						},
						success : function(json) {
							try {
								if (typeof(json) !== 'object' || typeof(json.success) !== 'boolean' || !json.success || typeof(json.bet_list) !== 'string' || typeof(json.bet_info) !== 'string') {
									giverhubError({msg : 'Bad response.'});
								} else {
									jQuery('.bet-info').html(json.bet_info);
									$bet_list.html(json.bet_list);
									$betSuccessChooseCharityModal.modal('hide');
									giverhubSuccess({
										msg : 'Thanks for accepting the bet!',
										'facebook-share' : function () {
											FB.ui({
												method: 'share_open_graph',
												action_type: 'giverhub:accept',
												action_properties: JSON.stringify({
													bet: body.data('base-url') + 'bet/' + bet_id
												})
											}, function(response){
												jQuery('.success-facebook-share-message').removeClass('hide');
											});
										}
									});
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

			jQuery(document).on('click', '.btn-pick-charity-submit', function() {
				var $this = jQuery(this);
				try {
					var bet_id = $this.data('bet-id');
					var $parent = $this.parent();

					var $parent_chosen_charity = $parent.find('.bet_charity_chosen');
					var $parent_charity_input = $parent.find('.bet_charity');
					var $li = $parent_chosen_charity.find('li');
					if (!$li.length) {
						giverhubError({subject : 'Select Non-profit', msg : 'You need to select a Non-profit'});
						giverhubError.hideEvent = function() {$parent_charity_input.focus()};
						return false;
					}
					var charity_id = jQuery($li[0]).data('charity-id');
					if (!charity_id) {
						giverhubError({subject : 'Select Non-profit', msg : 'Something went wrong with selecting your non-profit.'});
						giverhubError.hideEvent = function() {$parent_charity_input.focus()};
						return false;
					}

					$this.button('loading');

					jQuery.ajax({
						url: '/bet/choose_charity',
						type : 'post',
						dataType : 'json',
						data : {
							bet_id : bet_id,
							charity_id : charity_id
						},
						error : function() {
							giverhubError({msg : 'Failed request.'});
						},
						complete : function() {
							$this.button('reset');
						},
						success : function(json) {
							try {
								if (typeof(json) !== 'object' || typeof(json.success) !== 'boolean' || !json.success || typeof(json.bet_list) !== 'string' || typeof(json.bet_info) !== 'string') {
									giverhubError({msg : 'Bad response.'});
								} else {
									jQuery('.bet-info').html(json.bet_info);
									$bet_list.html(json.bet_list);

									giverhubSuccess({
										msg : 'Thanks for accepting the bet!',
										'facebook-share' : function () {
											FB.ui({
												method: 'share_open_graph',
												action_type: 'giverhub:accept',
												action_properties: JSON.stringify({
													bet: body.data('base-url') + 'bet/' + bet_id
												})
											}, function(response){
												jQuery('.success-facebook-share-message').removeClass('hide');
											});
										}
									});
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

			jQuery(document).on('click', '.btn-accept-bet', function() {
				var $this = jQuery(this);
				try {
					$this.button('loading');

					var bet_id = $this.data('bet-id');

					jQuery.ajax({
						url: '/bet/accept',
						type : 'post',
						dataType : 'json',
						data : {
							bet_id : bet_id
						},
						error : function() {
							giverhubError({msg : 'Failed request.'});
						},
						complete : function() {
							$this.button('reset');
						},
						success : function(json) {
							try {
								if (typeof(json) !== 'object' || typeof(json.success) !== 'boolean' || !json.success || typeof(json.bet_list) !== 'string' || typeof(json.bet_info) !== 'string' || typeof(json.bet) !== 'object') {
									giverhubError({msg : 'Bad response.'});
								} else {
									jQuery('.bet-info').html(json.bet_info);
									$bet_list.html(json.bet_list);
									$acceptPromptBetModal.modal('hide');
									displaySuccessChooseCharityModal(json.bet);
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
				return false;
			});


			var $viewPendingBetModal = jQuery('#view-pending-bet-modal');

			var viewPendingBet = function(bet_id) {
				var $betRow = jQuery('.bet-row-'+bet_id);

				$viewPendingBetModal.find('.bet-data-name').html($betRow.data('bet-name'));
				$viewPendingBetModal.find('.bet-data-terms').html($betRow.data('bet-terms'));
				$viewPendingBetModal.find('.bet-data-amount').html($betRow.data('bet-amount'));

				$viewPendingBetModal.find('.bet-data-first-user-name').html($betRow.data('bet-first-user-name'));
				$viewPendingBetModal.find('.bet-data-other-user-name').html($betRow.data('bet-other-user-name'));
				$viewPendingBetModal.find('.bet-data-other-user-link').html($betRow.data('bet-other-user-link'));

				$viewPendingBetModal.find('.bet-data-other-user-first-name').html($betRow.data('bet-other-user-first-name'));

				$viewPendingBetModal.find('.bet-data-first-charity-name').html($betRow.data('bet-first-charity-name'));
				$viewPendingBetModal.find('.bet-data-determination-date').html($betRow.data('bet-determination-date'));

				$viewPendingBetModal.modal('show');
			};

			$bet_list.on('click', '.btn-bet-action', function() {
				try {
					var $this = jQuery(this);
					var $data_element = $this.closest('.bet-row');
					var bet_id = $data_element.data('bet-id');

					if ($this.hasClass('edit-bet')) {
						editBet(bet_id);
					} else if ($this.hasClass('pending-bet')) {
						if ($this.hasClass('my')) {
							viewPendingBet(bet_id);
						} else if ($this.hasClass('other')) {
							acceptPrompt(bet_id);
						} else {
							giverhubError({msg : 'Something unexpected happened. unknown bet owner.'});
						}
					} else {
						giverhubError({msg : 'Something unexpected happened. unknown bet type.'});
					}
				} catch(e) {
					giverhubError({e:e});
				}
				return false;
			});

			$bet_list.on('click', '.btn-edit-draft', function() {
				try {
					var $this = jQuery(this);
					var $betRow = $this.closest('.bet-row');

					var bet = $betRow.data('bet');
					editBet(bet);
				} catch(e) {
					giverhubError({e:e});
				}
				return false;
			});

			$confirmBetModal.on('click', '.btn-bet-revise', function() {
				try {
					var $this = jQuery(this);
					var bet = $confirmBetModal.data('bet');
					$confirmBetModal.data('prompt-hide', false);
					$confirmBetModal.modal('hide');
					editBet(bet);
				} catch(e) {
					giverhubError({e:e});
				}
				return false;
			});

			$confirmBetModal.on('click', '.btn-bet-confirm-send', function() {
				var $this = jQuery(this);
				try {
					$this.button('loading');

					var bet_id = $confirmBetModal.data('bet').id;

					var $emails = $confirmBetModal.find('.emails').find('li');

					var emails = [];
					$emails.each(function(i,li) {
						var $li = jQuery(li);
						emails.push($li.data('email'));
					});

					//var $friends = $confirmBetModal.$friend_chosen.find('li');

					//var friends = [];

					/*$friends.each(function(i,li) {
						var $li = jQuery(li);
						friends.push({
							facebook_friend : $li.data('facebook-friend'),
							friend_id : $li.data('user-id'),
							fb_id : $li.data('fb-id')
						});
					});*/

					var open = $confirmBetModal.find('.open-bet').prop('checked') ? 1 : 0;

					if (!open && !emails.length) {
						//giverhubError({subject: 'Oops, add friends!', msg : 'You need to add some friends.. OR make the bet open so that anyone can request to accept the bet.'});
						giverhubError({subject: 'Oops, add emails!', msg : 'You need to add atleast one email.. OR make the bet open so that anyone can request to accept the bet.'});
						$this.button('reset');
						return false;
					}
					jQuery.ajax({
						url: '/bet/send',
						type : 'post',
						dataType : 'json',
						data : {
							bet_id : bet_id,
							emails : emails,
							open : open
						},
						error : function() {
							giverhubError({msg : 'Failed request.'});
						},
						complete : function() {
							$this.button('reset');
						},
						success : function(json) {
							try {
								if (typeof(json) !== "object" || typeof(json.success) !== "boolean" || !json.success || typeof(json.bet_list) !== 'string') {
									giverhubError({msg : 'Bad response.'});
								} else {
									$bet_list.html(json.bet_list);
									$confirmBetModal.data('prompt-hide', false);
									$confirmBetModal.modal('hide');

									var the_success = function() {
										FB.getLoginStatus(function(response) {
											if (response.status === 'connected') {
												FB.ui({
													method: 'share_open_graph',
													action_type: 'giverhub:make',
													action_properties: JSON.stringify({
														bet: body.data('base-url') + 'bet/' + bet_id
													})
												}, function(response){
													giverhubSuccess({subject : 'Great!', msg : 'Please stand by while being taken to the bet page.'});
													setTimeout(function() {window.location = '/bet/'+bet_id;}, 1000);
												});
											} else {
												giverhubSuccess({subject : 'Great!', msg : 'Please stand by while being taken to the bet page.'});
												setTimeout(function() {window.location = '/bet/'+bet_id;}, 1000);
											}
										});
									};

									if (false && friends.length) {
										function send_to_fb() {
											var friend = friends.pop();
											if (!friend.facebook_friend) {
												if (friends.length) {
													send_to_fb();
												} else {
													the_success();
												}
												return;
											}

											var link = body.data('base-url') + 'bet/' + bet_id;

											FB.ui({
												method: 'feed',
												link: link,
												to: friend.fb_id
											}, function(response){
												if (response === null) {
													if (friends.length) {
														send_to_fb();
													} else {
														the_success();
													}
												} else if(response.error_code !== undefined) {
													giverhubError({msg : 'Facebook returned an error: ' + response.error_msg + ' error_code: ' + response.error_code});
													giverhubError.hideEvent = function() {
														if (friends.length) {
															send_to_fb();
														} else {
															the_success();
														}
													};
												} else {
													if (friends.length) {
														send_to_fb();
													} else {
														the_success();
													}
												}
											});
										}
										send_to_fb();
									} else {
										the_success();
									}
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
				return false;
			});

			$bet_list.on('click', '.btn-delete-draft', function() {
				var $this = jQuery(this);
				try {
					if (confirm('Are you sure?')) {
						$this.button('loading');

						var bet_id = $this.data('bet-id');

						jQuery.ajax({
							url : '/bet/delete_draft',
							type : 'post',
							dataType : 'json',
							data : {
								bet_id : bet_id
							},
							error : function() {
								giverhubError({msg : 'Request Failed!'});
							},
							complete : function() {
								$this.button('reset');
							},
							success : function(json) {
								try {
									if (typeof(json) !== 'object' || typeof(json.success) !== 'boolean' || !json.success || typeof(json.bet_list) !== 'string') {
										giverhubError({msg : 'Bad response.'});
									} else {
										$bet_list.html(json.bet_list);
										giverhubSuccess({msg : 'Draft was deleted!'});
									}
								} catch(e) {
									giverhubError({e:e});
								}
							}
						});
					}
				} catch(e) {
					$this.button('reset');
					giverhubError({e:e});
				}
				return false;
			});

			function claimWin(options) {
				var bet_id = options.bet_id;
				var complete = options.complete;
				var success = options.success;

				jQuery.ajax({
					url : '/bet/claim_win',
					type : 'post',
					dataType : 'json',
					data : {
						bet_id : bet_id
					},
					error : function() {
						giverhubError({msg : 'Request failed!'});
					},
					complete : function() {
						complete();
					},
					success : function(json) {
						try {
							if (typeof(json) !== 'object' || typeof(json.success) !== 'boolean' || !json.success || typeof(json.bet_list) !== 'string' || typeof(json.bet_info) !== 'string') {
								giverhubError({msg : 'Bad response!'});
							} else {
								$bet_list.html(json.bet_list);
								jQuery('.bet-info').html(json.bet_info);

								success();
							}
						} catch(e) {
							giverhubError({e:e});
						}
					}
				});
			}

			jQuery(document).on('click', '.btn-claim-win', function() {
				var $this = jQuery(this);
				try {
					var bet_id = $this.data('bet-id');

					$this.button('loading');
					claimWin({
						bet_id : bet_id,
						complete : function() {
							$this.button('reset');
						},
						success : function() {
							giverhubSuccess({msg : 'Great! Saved!'});
							giverhubSuccess({
								msg : 'Congratulations!',
								'facebook-share' : function () {
									FB.ui({
										method: 'share_open_graph',
										action_type: 'giverhub:win',
										action_properties: JSON.stringify({
											bet: body.data('base-url') + 'bet/' + bet_id
										})
									}, function(response){
										jQuery('.success-facebook-share-message').removeClass('hide');
									});
								}
							});
						}
					});

				} catch(e) {
					$this.button('reset');
					giverhubError({e:e});
				}
				return false;
			});

			function claimLoss(options) {
				var bet_id = options.bet_id;
				var complete = options.complete;
				var success = options.success;


				jQuery.ajax({
					url : '/bet/claim_loss',
					type : 'post',
					dataType : 'json',
					data : {
						bet_id : bet_id
					},
					error : function() {
						giverhubError({msg : 'Request failed!'});
					},
					complete : function() {
						complete();
					},
					success : function(json) {
						try {
							if (typeof(json) !== 'object' || typeof(json.success) !== 'boolean' || !json.success || typeof(json.bet_list) !== 'string' || typeof(json.bet_info) !== 'string' || typeof(json.bet) !== 'object') {
								giverhubError({msg : 'Bad response!'});
							} else {
								$bet_list.html(json.bet_list);
								jQuery('.bet-info').html(json.bet_info);

								success();
							}
						} catch(e) {
							giverhubError({e:e});
						}
					}
				});
			}

			function betDonation(bet, bet_friend, not_me, not_my_nonprofit) {
				giverhubSuccess({
					hideOk : true,
					hideSubject : true,
					msg : 'We\'re sorry you lost :( On the bright side, you now get to donate to a nonprofit that ' + not_me.name + ' loves! Plus you get the tax deduction which will be automatically itemized for you in your Donation History!',
					okButtonTitle : 'Proceed'
				});

				giverhubSuccess.hideEvent = function() {
					var donationModal = jQuery('#lbox_donations');

					window.changeSlide(1);

					var charityName = not_my_nonprofit.name;
					var charityId = not_my_nonprofit.id;
					var friend_name = not_me.name;
					var amount = bet.amount;

					donationModal.find('.charity-name').html(charityName + ' ('+friend_name+'\'s pick)');
					donationModal.find('.amount').val(amount);
					donationModal.data('charity-name', charityName).data('charity-id', charityId);

					donationModal.data('bet_id', bet.id);
					donationModal.data('bet_friend_id', bet_friend.id);

					donationModal.modal('show');
					jQuery('.donation-modal-amount-list-item').removeClass('active');
					jQuery('.modal-content .gh_step_list li').removeClass('active');
					jQuery('.modal-content .gh_step_list li.default').addClass('active');
					jQuery("#donate_amount").val(amount);
				};
			}


			jQuery(document).on('click', '.btn-claim-loss', function() {
				var $this = jQuery(this);
				try {
					var bet = $this.data('bet');
					var bet_friend = $this.data('bet-friend');
					var not_me = $this.data('not-me');
					var not_my_nonprofit = $this.data('not-my-nonprofit');

					$this.button('loading');

					claimLoss({
						bet_id : bet.id,
						complete : function() {
							$this.button('reset');
						},
						success : function() {
							betDonation(bet, bet_friend, not_me, not_my_nonprofit);
						}
					});

				} catch(e) {
					$this.button('reset');
					giverhubError({e:e});
				}
				return false;
			});

			jQuery(document).on('click', '.btn-bet-friend-donation', function() {
				try {
					var bet = jQuery(this).data('bet');
					var bet_friend = jQuery(this).data('bet-friend');
					var not_me = jQuery(this).data('not-me');
					var not_my_nonprofit = jQuery(this).data('not-my-nonprofit');

					betDonation(bet, bet_friend, not_me, not_my_nonprofit);
				} catch(e) {
					giverhubError({e:e});
				}
			});

			jQuery(document).on('click', '.btn-accept-open-bet', function() {
				var $this = jQuery(this);
				try {
					$this.button('loading');

					jQuery.ajax({
						url : '/bet/accept_open',
						data : {
							bet_id : $this.data('bet-id')
						},
						type : 'post',
						dataType : 'json',
						error : function() {
							giverhubError({msg : 'Request Failed'});
							$this.button('reset');
						},
						success : function(json) {
							try {
								if (typeof(json) !== 'object' || typeof(json.success) !== 'boolean' || !json.success || typeof(json.bet_list) !== 'string' || typeof(json.bet_info) !== 'string') {
									giverhubError({msg : 'Bad response!'});
									$this.button('reset');
								} else {
									$bet_list.html(json.bet_list);
									jQuery('.bet-info').html(json.bet_info);
									$this.remove();
									giverhubSuccess({subject : 'Great!', msg : 'A request has been sent to the person that created the bet.'})
								}
							} catch(e) {
								$this.button('reset');
								giverhubError({e:e});
							}
						},
						complete : function() {

						}
					});
				} catch(e) {
					$this.button('reset');
					giverhubError({e:e});
				}
			});

			jQuery(document).on('click', '.btn-accept-bet-request', function() {
				var $this = jQuery(this);
				try {
					$this.button('loading');

					jQuery.ajax({
						url : '/bet/accept_request',
						data : {
							friend_id : $this.data('friend-id')
						},
						type : 'post',
						dataType : 'json',
						error : function() {
							giverhubError({msg : 'Request Failed'});
						},
						success : function(json) {
							try {
								if (typeof(json) !== 'object' || typeof(json.success) !== 'boolean' || !json.success || typeof(json.bet_list) !== 'string' || typeof(json.bet_info) !== 'string') {
									giverhubError({msg : 'Bad response!'});
								} else {
									$bet_list.html(json.bet_list);
									jQuery('.bet-info').html(json.bet_info);
								}
							} catch(e) {
								$this.button('reset');
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

			jQuery(document).on('click', '.btn-reject-bet-request', function() {
				var $this = jQuery(this);
				try {
					$this.button('loading');

					jQuery.ajax({
						url : '/bet/reject_request',
						data : {
							friend_id : $this.data('friend-id')
						},
						type : 'post',
						dataType : 'json',
						error : function() {
							giverhubError({msg : 'Request Failed'});
						},
						success : function(json) {
							try {
								if (typeof(json) !== 'object' || typeof(json.success) !== 'boolean' || !json.success || typeof(json.bet_list) !== 'string' || typeof(json.bet_info) !== 'string') {
									giverhubError({msg : 'Bad response!'});
								} else {
									$bet_list.html(json.bet_list);
									jQuery('.bet-info').html(json.bet_info);
								}
							} catch(e) {
								$this.button('reset');
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

			if (window.bets_in_need_of_determination !== undefined && window.bets_in_need_of_determination.length) {
				var $betDeterminationModal = jQuery('#bet-determination-modal');
				var $betDeterminationModalBody = $betDeterminationModal.find('.modal-body');

				$betDeterminationModal.on('hidden.bs.modal', function() {
					determineNext();
				});

				function determineNext() {
					try {
						if (!window.bets_in_need_of_determination.length) {
							return;
						}

						var bet = window.bets_in_need_of_determination.pop();

						$betDeterminationModal.data('bet_id', bet.id);
						$betDeterminationModal.data('my_nonprofit_name', bet.my_nonprofit_name);
						$betDeterminationModal.data('my_nonprofit_id', bet.my_nonprofit_id);
						$betDeterminationModal.data('my_nonprofit_link', bet.my_nonprofit_link);
						$betDeterminationModal.data('not_my_nonprofit_name', bet.not_my_nonprofit_name);
						$betDeterminationModal.data('not_my_nonprofit_id', bet.not_my_nonprofit_id);
						$betDeterminationModal.data('not_my_nonprofit_link', bet.not_my_nonprofit_link);
						$betDeterminationModal.data('friend_name', bet.friend_name);
						$betDeterminationModal.data('friend_link', bet.friend_link);
						$betDeterminationModal.data('claim_conflict', bet.claim_conflict);
						$betDeterminationModal.data('amount', bet.amount);

						if (bet.claim_conflict) {
							$betDeterminationModal.find('.btn-claim-win').html('I totally won!');
							$betDeterminationModal.find('.btn-claim-loss').html('Ok, I admit it! I lost :(');
						} else {
							$betDeterminationModal.find('.btn-claim-win').html('Yaaay, I won!!!');
							$betDeterminationModal.find('.btn-claim-loss').html('I didn\'t win :(');
						}

						$betDeterminationModalBody.html(Handlebars.templates.bet_determination_modal({bet : bet}));
						$betDeterminationModal.modal('show');
					} catch(e) {
						giverhubError({e:e});
					}
				}

				determineNext();

				$betDeterminationModal.on('click', '.btn-claim-win', function() {
					var $this = jQuery(this);
					try {
						var bet_id = $betDeterminationModal.data('bet_id');

						$this.button('loading');

						claimWin({
							bet_id : bet_id,
							complete : function() {
								$this.button('reset');
							},
							success : function() {
								giverhubSuccess({
									msg : 'Congratulations!',
									'facebook-share' : function () {
										FB.ui({
											method: 'share_open_graph',
											action_type: 'giverhub:win',
											action_properties: JSON.stringify({
												bet: body.data('base-url') + 'bet/' + bet_id
											})
										}, function(response){
											jQuery('.success-facebook-share-message').removeClass('hide');
										});
									}
								});
								giverhubSuccess.hideEvent = function() {
									$betDeterminationModal.modal('hide');
								};
							}
						});

					} catch(e) {
						$this.button('reset');
						giverhubError({e:e});
					}
					return false;
				});

				$betDeterminationModal.on('click', '.btn-claim-loss', function() {
					var $this = jQuery(this);
					try {
						var bet_id = $betDeterminationModal.data('bet_id');

						$this.button('loading');

						claimLoss({
							bet_id : bet_id,
							complete : function() {
								$this.button('reset');
							},
							success : function() {

								var friend_name = $betDeterminationModal.data('friend_name');

								giverhubSuccess({
									hideOk : true,
									hideSubject : true,
									msg : 'We\'re sorry you lost :( On the bright side, you now get to donate to a nonprofit that ' + friend_name + ' loves! Plus you get the tax deduction which will be automatically itemized for you in your Donation History!',
									okButtonTitle : 'Proceed'
								});

								giverhubSuccess.hideEvent = function() {
									var donationModal = jQuery('#lbox_donations');

									window.changeSlide(1);


									var charityName = $betDeterminationModal.data('not_my_nonprofit_name');
									var charityId = $betDeterminationModal.data('not_my_nonprofit_id');
									var charityLink = $betDeterminationModal.data('not_my_nonprofit_link');
									var amount = $betDeterminationModal.data('amount');
									var friend_name = $betDeterminationModal.data('friend_name');
									var friend_link = $betDeterminationModal.data('friend_link');

									donationModal.find('.charity-name').html(charityName + ' ('+friend_name+'\'s pick)');
									donationModal.find('.amount').val(amount);
									donationModal.data('charity-name', charityName).data('charity-id', charityId);

									donationModal.data('bet_id', bet_id);
									donationModal.data('bet-a-friend-page', $bet_list.hasClass('bet-a-friend-page') ? 1 : 0);
									donationModal.modal('show');

									jQuery('.donation-modal-amount-list-item').removeClass('active');
									jQuery('.modal-content .gh_step_list li').removeClass('active');
									jQuery('.modal-content .gh_step_list li.default').addClass('active');
									jQuery("#donate_amount").val(amount);
								};
							}
						});

					} catch(e) {
						$this.button('reset');
						giverhubError({e:e});
					}
					return false;
				});
			}

			jQuery('#datetimepicker1').datetimepicker({
				pickTime: false,
				minDate : moment().subtract('days', 1)
			});

			if (jQuery('.trigger-fb-sign-in').length) {
				try {
					jQuery('.facebook-sign-in').click();
				} catch(e) {
					giverhubError({e:e});
				}
			}

			var $viewBetModal = jQuery('#view-bet-modal');
			var $viewBetModalBody = $viewBetModal.find('.modal-body');
			function viewBet(bet_data) {
				$viewBetModalBody.html(Handlebars.templates.view_bet_modal(bet_data));
				$viewBetModal.modal('show');
			}

			$bet_list.on('click', '.btn-view-bet', function () {
				try {
					var $this = jQuery(this);
					var $betRow = $this.closest('.bet-row');
					viewBet($betRow.data());

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