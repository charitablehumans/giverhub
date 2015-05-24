try {
	jQuery(document).ready(function() {
		try {
			jQuery(document).on('click', '#save_blog', function () {
				try {
					var title 		= jQuery('#blog_title').val();
					var description = jQuery('#blog_description').val();
					var is_publish  = this.value;

					if(title == "" || description == "") {
						giverhubError({msg : 'Enter title and description.'});
						return false;
					}

					jQuery.ajax({
						url : '/blog/save_blog',
						type : 'post',
						dataType : 'json',
						data : { is_publish : is_publish, title : title, description : description},
						error : function() {
							//giverhubError({msg : 'Request Failed. Please try again later.'});
						},
						success : function(json) {
							//Blog added successfully
						}
					});
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