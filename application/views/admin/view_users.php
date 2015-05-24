<?php
/** @var string $links */
/** @var \Entity\User[] $users */
/** @var \Base_Controller $CI */
$CI =& get_instance();
?>

<div class="row">
    <h1>Users</h1>
</div>

<div class="row">
    <div class="columns large-2">
        <?php echo $links; ?>
    </div>
    <div class="columns large-3">
        <input type="text" placeholder="Email Address" id="search_content"/>
    </div>
    <div class="columns large-2">
        <input class="button tiny" type="button" id="user_search" value="Search">
    </div>

    <div class="columns large-3">
        <select id="select_options">
            <option value="delete_profile_picture">Delete Profile Picture</option>
            <option value="admin">Change capabilities to Admin</option>
            <option value="confirmed">Change capabilities to Confirmed</option>
        </select>
    </div>
    <div class="columns large-2">
        <input class="button tiny" type="button" id="users_action" value="Apply">
    </div>
</div>
<div class="row">
    <table border="0" cellpadding="0" cellspacing="0" width="100%" id="user_details_table">
        <thead>
            <tr>
                <th scope="col" width="4%"><input name="checkbox" id="checkbox" class="toggle-all" type="checkbox">
                <label for="checkbox"></label></th>
                <th scope="col" width="16%">Username</th>
                <th scope="col" width="16%">Name</th>
                <th scope="col" width="21%">E-Mail</th>
                <th scope="col" width="9%">Profile Picture</th>
                <th scope="col" width="10%">Joined Date</th>
                <th scope="col" width="10%">capabilities</th>
                <th scope="col" width="4%">auto follow</th>
                <th scope="col" width="4%">delete</th>
            </tr>
        </thead>
        <tbody>
            <?php $CI->load->view('/admin/_users', array('users' => $users)); ?>
        </tbody>
    </table>
    <?php echo $links; ?>
    <div class="ajax-background"></div>
    <div style="display:none;" class="loading-ajax">
            <img src="<?php echo base_url(); ?>images/ajax-loaders/indicator.gif" height="50" width="50" >
    </div>

</div>


<script type="text/javascript">
    jQuery(document).ready(function() {
        jQuery(document).on('click', '.toggle-all', function() {
            var $this = jQuery(this);
            var checked = $this.is(':checked');

            jQuery('.checkers').prop('checked', checked);

            return true;
        });

        jQuery(document).on('click', '#user_search', function() {
            var $this = jQuery(this);

            try {
                $this.loading('Searching...');
                jQuery.ajax({
                    url : '/admin/search_user',
                    type : 'post',
                    data : { search : jQuery('#search_content').val() },
                    dataType : 'json',
                    error : function() {
                        window.adminError({msg : 'Request failed.'});
                    },
                    success : function(json) {
                        try {
                            if (!json || json === undefined || json.success === undefined || !json.success || json.html === undefined) {
                                window.adminError({msg : 'Bad response.'});
                            } else {
                                jQuery('tbody').html(json.html);
                            }
                        } catch(e) {
                            window.adminError({e : e});
                        }
                    },
                    complete : function() {
                        $this.reset();
                    }
                });
            } catch(e) {
                $this.reset();
                window.adminError({e:e});
            }
            return false;
        });

        jQuery(document).on('click', '#users_action', function() {
            var $this = jQuery(this);

            try {
                $this.loading('Applying...');

                var user_ids = [];
                jQuery('.checkers:checked').each(function(i,e) {
                    var $e = jQuery(e);
                    var user_id = $e.data('user-id');
                    if (user_id) {
                        user_ids.push(user_id);
                    }
                });
                if (!user_ids.length) {
                    adminError({subject : 'Select some users.', msg : ''});
                    $this.reset();
                    return false;
                }

                var action = jQuery('#select_options').val();

                jQuery.ajax({
                    url : '/admin/change_status_of_users',
                    type : 'post',
                    dataType : 'json',
                    data : { user_ids : user_ids, action : action },
                    error : function() {
                        window.adminError({msg : 'Request Failed.'});
                    },
                    success : function(json) {
                        try {
                            if (!json || json === undefined || json.success === undefined || !json.success || json.html === undefined) {
                                window.adminError({msg : 'Bad response.'});
                            } else {
                                jQuery('tbody').html(json.html);
                            }
                        } catch(e) {
                            window.adminError({e : e});
                        }
                    },
                    complete : function() {
                        $this.reset();
                    }
                });
            } catch(e) {
                $this.reset();
                window.adminError({e:e});
            }
            return false;
        });

        jQuery(document).on('click', '.auto_follow_checkbox', function() {
            var btn = jQuery(this);
            try {
                var checked = btn.prop('checked');
                var question = checked ? 'Are you sure you want to make this user automatically followed by all new users?' : 'Are you sure you want to stop having this user automatically followed?';
                if (confirm(question)) {
                    var ret = false;
                    jQuery.ajax({
                        async : false,
                        url : '/admin/auto_follow',
                        type : 'post',
                        dataType : 'json',
                        data : { user_id : btn.data('user-id'), auto_follow : checked ? '1' : '0' },
                        error : function() {
                            window.adminError({msg : 'Request Failed.'});
                            ret = true;
                        },
                        success : function(json) {
                            try {
                                if (!json || json === undefined || json.success === undefined || !json.success) {
                                    window.adminError({msg : 'Bad response.'});
                                }
                                ret = true;
                            } catch(e) {
                                window.adminError({e : e});
                                ret = false;
                            }
                        }
                    });
                    return ret;
                } else {
                    return false;
                }
            } catch(e) {
                window.adminError({e:e});
            }
            return false;
        });

        jQuery(document).on('click', '.delete-button', function() {
            var $this = jQuery(this);
            if ($this.hasClass('disabled')) {
                return;
            }

            if (confirm('Deleting ' + $this.data('email') + '. Are you sure?') && confirm('Deleting ' + $this.data('email') + '. Are you really sure?') && confirm('Deleting ' + $this.data('email') + '. Are you super sure???')) {
                $this.addClass('disabled');
                jQuery.ajax({
                    url : '/admin/delete_user',
                    type : 'post',
                    data : { user_id : $this.data('user-id') },
                    dataType : 'json',
                    error : function() {
                        window.adminError({msg : 'Request failed! contact developer .. probably something is still referencing the user in the database.'});
                        $this.removeClass('disabled');
                    },
                    success : function(json) {
                        if (typeof(json) !== "object" || typeof(json.success) !== "boolean" || !json.success) {
                            if (typeof(json.error_msg) === "string") {
                                window.adminError({msg : json.error_msg });
                            } else {
                                window.adminError({msg : 'Bad response. contact developer .. probably something is still referencing the user in the database.'});
                            }
                            $this.removeClass('disabled');
                        } else {
                            $this.closest('tr').remove();
                            alert('Success!');
                        }
                    }
                });
            }
        });
    });
</script>