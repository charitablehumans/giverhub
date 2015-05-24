try {
	jQuery(document).ready(function() {
		try {
			var cached_autocomplete_results = {};

			var $autocomplete_results_container = jQuery('<ul class="autocomplete_results_container">aa</ul>');
			$autocomplete_results_container.hide();
			body.append($autocomplete_results_container);

			$autocomplete_results_container.on('click', '.view-more-a', function() {
				try {
					var $this = jQuery(this);
					var $parent = $this.parent();
					var $form = $parent.find('form');
					$form.submit();
				} catch(e) {
					giverhubError({e:e});
				}
				return false;
			});

			$autocomplete_results_container.on('mouseover', '.selectable', function() {
				try {
					var $selected = $autocomplete_results_container.find('.selected');
					$selected.removeClass('selected');
					jQuery(this).addClass('selected');
				} catch(e) {
					giverhubError({e:e});
				}
			});

			var KEY_UP = 38;
			var KEY_DOWN = 40;
			var KEY_ENTER = 13;
			var KEY_ESCAPE = 27;

			$autocomplete_results_container.on('click', function(event) {
				try {
					event.stopPropagation();
				} catch(e) {
					giverhubError({e:e});
				}
			});

			jQuery(document).on('click', function() {
				try {
					$autocomplete_results_container.hide();
				} catch(e) {
					giverhubError({e:e});
				}
			});

			var first_focus;
			jQuery(document).on('focus', '.gh_general_search_field', function() {
				try {
					jQuery(this).select();
					first_focus = true;
				} catch(e) {
					giverhubError({e:e});
				}
			});

			jQuery(document).on('mouseup', '.gh_general_search_field', function() {
				if (first_focus) {
					first_focus = false;
					return false;
				} else {
					return true;
				}
			});

			jQuery(document).on('click', '.gh_general_search_field', function(event) {
				try {
					jQuery(this).trigger('keyup');

					event.stopPropagation();
				} catch(e) {
					giverhubError({e:e});
				}
			});

			jQuery(document).on('keypress','.gh_general_search_field', function(event) {
				try {
					if (event.keyCode == KEY_ENTER && jQuery(this).val().length) {
						return false; // return false if ENTER is pressed.. disable form submit... unless the text is empty
					}
				} catch(e) {
					giverhubError({e:e});
				}
				return true;
			});

			jQuery(document).on('keyup', '.gh_general_search_field', function(event) {
				try {
					if (event.keyCode == KEY_DOWN || event.keyCode == KEY_UP) {
						var $selected = $autocomplete_results_container.find('.selected');

						var $target;
						if (event.keyCode == KEY_DOWN) {
							$target = $selected.next('.selectable');
							if (!$target.length) {
								$target = $autocomplete_results_container.find('.selectable').first();
							}
						} else {
							$target = $selected.prev('.selectable');
							if (!$target.length) {
								$target = $autocomplete_results_container.find('.selectable').last();
							}
						}

						$selected.removeClass('selected');
						$target.addClass('selected');
						return false;
					}

					if (event.keyCode == KEY_ENTER) {
						if ($autocomplete_results_container.is(':visible')) {
							var $selected_as = $autocomplete_results_container.find('.selected a');
							if ($selected_as.length) {
								$selected_as[0].click();
							}
						}
						return false;
					}

					if (event.keyCode == KEY_ESCAPE) {
						$autocomplete_results_container.hide();
						return false;
					}


					var $this = jQuery(this);
					var search_text = $this.val();

					// if no text is entered we hide the auto complete results container
					if (!search_text.length) {
						$autocomplete_results_container.hide();
						return true;
					}


					// position and display the search results container
					var offset = $this.offset();
					$autocomplete_results_container.css('top', (offset.top+$this.outerHeight()) + 'px');
					$autocomplete_results_container.css('left', offset.left + 'px');
					$autocomplete_results_container.css('min-width', ($this.outerWidth()+40) + 'px');
					$autocomplete_results_container.show();

					// check if we have the value in cache
					var result = cached_autocomplete_results[search_text];

					if (result === undefined) { // we do not have it in cache

						// show the loading template!
						$autocomplete_results_container.html(
							Handlebars.templates.autocomplete_results(
								{loading : true, search_text : search_text}
							)
						);

						// remember that we are loading the search_text
						cached_autocomplete_results[search_text] = {loading : true};

						// make the ajax request.. duh
						jQuery.ajax({
							url : '/home/auto_complete',
							type : 'get',
							data : { search_text : search_text },
							dataType : 'json',
							error : function() {
								// this is annoying when a user clicks a link during this ajax request causing this request to fail.
								//giverhubError({msg : 'Request Failed!'});
							},
							success : function(json) {
								try {
									if (json === undefined ||
										!json ||
										json.success === undefined ||
										!json.success ||
										json.search_text === undefined ||
										json.nonprofits === undefined ||
										json.petitions === undefined ||
										json.users === undefined ||
										json.pages === undefined) {
										giverhubError({msg : 'Bad response!'});
									} else {
										if (cached_autocomplete_results[json.search_text] === undefined) {
											return; // something is wrong
										}
										if (!cached_autocomplete_results[json.search_text]['loading']) {
											return; // something is wrong
										}



										// store the rendered template in result cache
										cached_autocomplete_results[json.search_text]['results'] = Handlebars.templates.autocomplete_results(
											{
												loading : false,
												search_text : search_text,
												items : json.nonprofits.concat(json.petitions).concat(json.users).concat(json.pages)
											}
										);

										// loading is complete
										cached_autocomplete_results[json.search_text]['loading'] = false;

										// if the value has not changed we want to display the results.
										// value can change after we started the ajax request.. ajax is not syncronous, remember!
										if ($this.val() == json.search_text) {
											$autocomplete_results_container.html(cached_autocomplete_results[json.search_text]['results']);
										}
									}
								} catch(e) {
									giverhubError({e:e});
								}
							}
						});
					} else {
						if (result['loading']) {
							// this search_text is still loading.. so we display the loading template
							$autocomplete_results_container.html(
								Handlebars.templates.autocomplete_results(
									{loading : true, search_text : search_text}
								)
							);
						} else {
							// we have the result in cache .. display!
							$autocomplete_results_container.html(result['results']);
						}
					}
				} catch(e) {
					giverhubError({e:e});
				}

				return true;
			});
		} catch(e) {
			giverhubError({e:e});
		}

	});
} catch(e) {
	giverhubError({e:e});
}