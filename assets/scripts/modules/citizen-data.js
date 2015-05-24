try {
	jQuery(document).ready(function() {
		var $citizen_modal = jQuery('#citizen-admin-modal');
		var $citizen_body = $citizen_modal.find('.modal-body');
		try {
			jQuery(document).on('click', '.btn-admin-citizen', function() {
				var $this = jQuery(this);
				try {
					$this.button('loading');
					var charity_id = $this.data('charity-id');

					jQuery.ajax({
						url : '/charity/citizen_admin',
						type : 'get',
						dataType : 'json',
						data : { charity_id : charity_id },
						error : function() {
							giverhubError({msg : 'Request failed.'});
						},
						success : function(json) {
							try {
								if (json === undefined || !json || json.success === undefined || !json.success || json.html === undefined) {
									giverhubError({msg : 'Bad response.'});
								} else {
									$citizen_body.html(json.html);
									$citizen_modal.modal('show');
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
				return false;
			});
		} catch(e) {
			giverhubError({e:e});
		}
	});
} catch(e) {
	if (GIVERHUB_DEBUG) {
		console.dir(e);
	}
	giverhubError({e:e});
}