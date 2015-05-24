jQuery(document).ready(function () {
	try {
		jQuery(document).on('click', '.btn-select-all-keywords', function() {
			try {
				jQuery('.btn-keyword').each(function(i,e) {
					var btn = jQuery(e);
					if (!btn.hasClass('active')) {
						btn.button('toggle');
					}
				});
			} catch(e) {
				giverhubError({e:e});
			}
		});

		jQuery(document).on('click', '.btn-delete-selected-keywords', function(e) {
			e.preventDefault();
			try {
				var keywordIds = [];

				jQuery('.btn-keyword.active').each(function(i,e) {
					keywordIds.push(jQuery(e).data('keyword-id'));
				});

				if (!keywordIds.length) {
					giverhubError({msg : 'You need to select some keywords first.'});
					return false;
				}

				jQuery.ajax({
					url : '/charity/delete_keywords',
					type : 'post',
					dataType : 'json',
					data : { keywordIds : keywordIds },
					error : function() {
						giverhubError({msg : 'Request failed.'});
					},
					success : function(json) {
						try {
							if (json === undefined || !json || json.success === undefined || !json.success) {
								giverhubError({msg : 'Bad response from server. Try refreshing the page.'});
							} else {
								giverhubSuccess({msg : 'Success!'});
								jQuery('.btn-keyword.active').remove();

								//Remove deleted keywords from header
								$(".charity-header-keyword").each(function(){
									if($.inArray(parseInt($(this).attr("data-keyword-id")) , keywordIds) !== -1){
										$(this).remove();
									}
								});
							}
						} catch(e) {
							giverhubError({e:e});
						}
					}
				});

				return false;
			} catch(e) {
				giverhubError({e:e});
			}
			return false;
		});
	} catch (e) {
		giverhubError({e : e});
	}
});