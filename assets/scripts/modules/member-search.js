try {
	jQuery(document).ready(function() {
		try {

			function initializeNonprofitFeed() {
				jQuery(document).on('click', '.view_more_nonprofits', function() {
					try {
						jQuery(".non-profits_view").submit();
					} catch(e) {
						giverhubError({e:e});
					}
					return false;
				});

				var nonprofitsSearchForm = jQuery('#nonprofits_search');
				var $nonprofitSearchResult = jQuery('.nonprofits-feed');
				var cached_search_nonprofits = {};

				var empty_string_results_nonprofits = $nonprofitSearchResult.html();

				nonprofitsSearchForm.on('keyup', '.find_nonprofits', function(event) {
					try {
						var $this = jQuery(this);
						var search_text = $this.val();

						if(search_text == ""){
							jQuery(".nonprofits-feed").css( { display:"none" } );
							jQuery(".nonprofits-feed-hover").css( { display:"block" } );
						} else {
							jQuery(".nonprofits-feed").css( { display:"block" } );
							jQuery(".nonprofits-feed-hover").css( { display:"none" } );
						}

						jQuery('.non-profits_view .search_text').val(search_text);

						if (event.keyCode == 13) {
							jQuery(".non-profits_view").submit();
							return false;
						}

						if (!search_text.length) {
							$nonprofitSearchResult.html(empty_string_results_nonprofits);
							return true;
						}

						var res = cached_search_nonprofits[search_text];

						if (res === undefined) {
							$nonprofitSearchResult.html('<img class="loading" src="/images/ajax-loaders/ajax-loader.gif">');
							cached_search_nonprofits[search_text] = {loading : true};

							jQuery.ajax({
								url : '/members/search_charities',
								type : 'get',
								dataType : 'json',
								data : { search : search_text },
								error : function() {
									giverhubError({msg : 'Request Failed.'});
								},
								success : function(json) {
									try {
										if (json === undefined || !json || json.success === undefined || !json.success || json.search === undefined || json.results === undefined) {
											giverhubError({msg : 'Bad response!'});
										} else {
											if (cached_search_nonprofits[json.search] === undefined) {
												return;
											}
											if (!cached_search_nonprofits[json.search]['loading']) {
												return;
											}
											cached_search_nonprofits[json.search]['loading'] = false;
											cached_search_nonprofits[json.search]['results'] = json.results;
											if ($this.val() == json.search) {
												$nonprofitSearchResult.html(json.results);
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
								$nonprofitSearchResult.html('<img class="loading" src="/images/ajax-loaders/ajax-loader.gif">');
							} else {
								$nonprofitSearchResult.html(res['results']);
							}
						}

					} catch(e) {
						giverhubError({e:e});
					}
					return true;
				});

				jQuery(".show-non-profits").mouseenter(function() {
					var search_value_nonprofits = jQuery(".find_nonprofits").val();
					if(!search_value_nonprofits.length) {
						jQuery(".nonprofits-feed").css( { display:"none" } );
						jQuery(".nonprofits-feed-hover").css( { display:"block" } );
					}

				}).mouseleave(function() {
						var has_focus_nonprofits = jQuery('.find_nonprofits').is(':focus');
						if(!has_focus_nonprofits) {
							jQuery(".nonprofits-feed").css( { display:"block" } );
							jQuery(".nonprofits-feed-hover").css( { display:"none" } );
						}
					});

				jQuery(".find_nonprofits").focusout(function(){
					jQuery(".nonprofits-feed").css( { display:"block" } );
					jQuery(".nonprofits-feed-hover").css( { display:"none" } );
				});
			}

			function initializePetitionFeed() {
				jQuery(document).on('click', '.view_more_petitions', function() {
					try {
						jQuery(".petition_view").submit();
					} catch(e) {
						giverhubError({e:e});
					}
					return false;
				});

				var petitionsSearchForm = jQuery('#petitions_search');
				var $petitionSearchResult = jQuery('.petitions-feed');

				var cached_search_petitions = {};

				var empty_string_results_petition = $petitionSearchResult.html();

				petitionsSearchForm.on('keyup', '.find_petitions', function(event) {
					try {
						var $this = jQuery(this);
						var search_text = $this.val();

						if(search_text == ""){
							jQuery(".petitions-feed").css( { display:"none" } );
							jQuery(".petitions-feed-hover").css( { display:"block" } );
						} else {
							jQuery(".petitions-feed").css( { display:"block" } );
							jQuery(".petitions-feed-hover").css( { display:"none" } );
						}

						jQuery('.petition_view .search_text').val(search_text);

						if (event.keyCode == 13) {
							jQuery(".petition_view").submit();
							return false;
						}

						if (!search_text.length) {
							$petitionSearchResult.html(empty_string_results_petition);
							return true;
						}

						var res = cached_search_petitions[search_text];

						if (res === undefined) {
							$petitionSearchResult.html('<img class="loading" src="/images/ajax-loaders/ajax-loader.gif">');
							cached_search_petitions[search_text] = { loading : true };

							jQuery.ajax({
								url : '/members/search_petitions',
								type : 'get',
								dataType : 'json',
								data : { search : search_text },
								error : function() {
									giverhubError({msg : 'Request Failed.'});
								},
								success : function(json) {
									try {
										if (json === undefined || !json || json.success === undefined || !json.success || json.search === undefined || json.results === undefined) {
											giverhubError({msg : 'Bad response!'});
										} else {
											if (cached_search_petitions[json.search] === undefined) {
												return;
											}
											if (!cached_search_petitions[json.search]['loading']) {
												return;
											}
											cached_search_petitions[json.search]['loading'] = false;
											cached_search_petitions[json.search]['results'] = json.results;
											if ($this.val() == json.search) {
												$petitionSearchResult.html(json.results);
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
								$petitionSearchResult.html('<img class="loading" src="/images/ajax-loaders/ajax-loader.gif">');
							} else {
								$petitionSearchResult.html(res['results']);
							}
						}

					} catch(e) {
						giverhubError({e:e});
					}
					return true;
				});

				jQuery(".show-petitions").mouseenter(function() {
					var search_value_petitions = jQuery(".find_petitions").val();
					if(!search_value_petitions.length) {
						jQuery(".petitions-feed").css( { display:"none" } );
						jQuery(".petitions-feed-hover").css( { display:"block" } );
					}
				})
					.mouseleave(function() {
						var has_focus_petitions = jQuery('.find_petitions').is(':focus');
						if(!has_focus_petitions) {
							jQuery(".petitions-feed").css( { display:"block" } );
							jQuery(".petitions-feed-hover").css( { display:"none" } );
						}
					});

				jQuery(".find_petitions").focusout(function(){
					jQuery(".petitions-feed").css( { display:"block" } );
					jQuery(".petitions-feed-hover").css( { display:"none" } );
				});
			}

			var $load_nonprofit_feed = jQuery('.load-nonprofit-feed');
			if ($load_nonprofit_feed.length) {
				jQuery.ajax({
					url : '/members/load_nonprofit_feed',
					dataType : 'json',
					type : 'get',
					error : function() {
						$load_nonprofit_feed.addClass('hide');
					},
					success : function(json) {
						try {
							if (typeof(json) !== "object" || typeof(json.success) !== "boolean" || typeof(json.feed_html) !== "string") {
								$load_nonprofit_feed.addClass('hide');
							} else {
								$load_nonprofit_feed.replaceWith(json.feed_html);
								initializeNonprofitFeed();
								if (jQuery(json.feed_html).find('.btn-donate-using-cc-paypal-button-with-amount').length) {
									window.giverhubStat('impression-donate-using-cc-paypal-button-with-amount', function() {});
								}
								if (jQuery(json.feed_html).find('.btn-donate-using-cc-paypal-button').length) {
									window.giverhubStat('impression-donate-using-cc-paypal-button', function() {});
								}
							}
						} catch(e) {
							$load_petition_feed.addClass('hide');
							giverhubError({e:e});
						}
					}
				});
			} else {
				initializeNonprofitFeed();
			}

			var $load_petition_feed = jQuery('.load-petition-feed');
			if ($load_petition_feed.length) {
				jQuery.ajax({
					url : '/members/load_petition_feed',
					dataType : 'json',
					type : 'get',
					error : function() {
						$load_petition_feed.addClass('hide');
					},
					success : function(json) {
						try {
							if (typeof(json) !== "object" || typeof(json.success) !== "boolean" || typeof(json.feed_html) !== "string") {
								$load_petition_feed.addClass('hide');
							} else {
								$load_petition_feed.replaceWith(json.feed_html);
								initializePetitionFeed();
								if (jQuery(json.feed_html).find('.btn-donate-using-cc-paypal-button-with-amount').length) {
									window.giverhubStat('impression-donate-using-cc-paypal-button-with-amount', function() {});
								}
								if (jQuery(json.feed_html).find('.btn-donate-using-cc-paypal-button').length) {
									window.giverhubStat('impression-donate-using-cc-paypal-button', function() {});
								}
							}
						} catch(e) {
							$load_petition_feed.addClass('hide');
							giverhubError({e:e});
						}
					}
				});
			} else {
				initializePetitionFeed();
			}


		} catch(e) {
			giverhubError({e:e});
		}
	});
} catch(e) {
	giverhubError({e:e});
}
