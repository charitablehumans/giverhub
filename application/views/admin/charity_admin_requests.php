<?php
/** @var \Entity\CharityAdminRequest[] $requests */
/** @var \Base_Controller $CI */
$CI =& get_instance();
?>
<style>
    #charity_admin_requests_table {
        table-layout: fixed;
    }
    #charity_admin_requests_table td {
        overflow: auto;
    }
    .row {
        max-width: 90%;
    }
</style>
<div class="row">
    <h1>Charity Admin Requests</h1>
</div>

<div class="row">
    <table border="0" cellpadding="0" cellspacing="0" width="100%" id="charity_admin_requests_table">
        <thead>
        <tr>
            <th scope="col">Email</th>
            <th scope="col">User</th>
            <th scope="col">Message</th>
            <th scope="col">Pictures</th>
            <th scope="col">Nonprofit</th>
            <th scope="col">Approve</th>
        </tr>
        </thead>
        <tbody>
            <?php $CI->load->view('/admin/_charity_admin_requests', array('requests' => $requests)); ?>
        </tbody>
    </table>
</div>

<script type="text/javascript">
    jQuery(document).ready(function() {
        try {
            var $table = jQuery('#charity_admin_requests_table');

            $table.on('click', '.approve-charity-admin-request', function() {
                var $this = jQuery(this);

                try {
                    if ($this.hasClass('disabled')) {
                        return false;
                    }
                    $this.loading();
                    var request_id = $this.data('request-id');
                    jQuery.ajax({
                        url : '/admin/charity_admin_request_approve',
                        type : 'post',
                        dataType : 'json',
                        data : {
                            request_id : request_id
                        },
                        error : function() {
                            window.adminError({msg:'Request Failed'});
                        },
                        complete : function() {
                           $this.reset();
                        },
                        success : function(json) {
                            try {
                                if (!json || json === undefined || json.success === undefined || !json.success || json.html === undefined) {
                                    window.adminError({msg : 'Bad response from server.'});
                                } else {
                                    $table.find('tbody').html(json.html);
                                }
                            } catch(e) {
                                window.adminError({e:e});
                            }
                        }
                    });
                } catch(e) {
                    $this.reset();
                    window.adminError({e:e});
                }
                return false;
            });
        } catch(e) {
            window.adminError({e:e});
        }
    });
</script>