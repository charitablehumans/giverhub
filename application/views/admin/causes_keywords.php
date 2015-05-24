<?php
/** @var \Entity\CharityCategory[] $categories */
/** @var \Base_Controller $CI */
$CI =& get_instance();
?>

<style>
    .no-bottom-margin { margin-bottom: 0 !important; }
</style>
<div class="row">
    <div class="columns">
        <a class="button" href="/admin/enqueue_map_keywords_task">ENQUEUE MAPPING TASK</a>
    </div>
</div>
<div class="row">
    <div class="columns">
        <?php foreach($categories as $category): ?>
            <table class="large-12">
                <thead>
                    <tr>
                        <th>Category: <i><?php echo htmlspecialchars($category->getName()); ?></i></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($category->getCauses() as $cause): ?>
                    <tr>
                        <td>
                            <table class="large-12">
                                <thead>
                                    <tr>
                                        <th colspan="4">Cause: <i><?php echo htmlspecialchars($cause->getName()); ?></i></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $CI->load->view('admin/_cause_tbody', ['cause' => $cause]); ?>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endforeach; ?>
    </div>
</div>

<script type="text/javascript">
    try {
        jQuery(document).ready(function() {
            try {
                jQuery(document).on('click', '.btn-add-keyword', function() {
                    var btn = jQuery(this);
                    try {
                        btn.loading();

                        var tbody = btn.closest('tbody');
                        var keyword_input = tbody.find('.add-keyword-input');
                        var keyword = keyword_input.val();

                        if (!keyword.length) {
                            btn.reset();
                            window.adminError({subject: 'Missing keyword', msg : 'You need to enter a keyword.'});
                            return false;
                        }

                        var cause_id = btn.data('cause-id');

                        jQuery.ajax({
                            url : '/admin/add_cause_keyword',
                            type : 'post',
                            dataType : 'json',
                            data : { cause_id : cause_id, keyword : keyword },
                            error : function() {
                                window.adminError({msg : 'Request failed'});
                            },
                            success : function(json) {
                                try {
                                    if (!json || json === undefined || json.success === undefined || !json.success || json.html === undefined) {
                                        if (json.message !== undefined && json.subject !== undefined) {
                                            window.adminError({subject: json.subject, msg : json.message});
                                        } else {
                                            window.adminError({msg : 'Bad response from server.'});
                                        }
                                    } else {
                                        tbody.html(json.html);
                                    }
                                } catch(e) {
                                    window.adminError({e:e});
                                }
                            },
                            complete : function() {
                                btn.reset();
                            }
                        });
                    } catch(e) {
                        window.adminError({e:e});
                    }
                    return false;
                });

                jQuery(document).on('click', '.btn-delete-keyword', function() {
                    var btn = jQuery(this);
                    try {
                        if (!confirm('Are you sure?')) {
                            return false;
                        }
                        btn.loading();

                        var tbody = btn.closest('tbody');

                        var keyword_id = btn.data('keyword-id');

                        jQuery.ajax({
                            url : '/admin/delete_cause_keyword',
                            type : 'post',
                            dataType : 'json',
                            data : { keyword_id : keyword_id },
                            error : function() {
                                window.adminError({msg : 'Request failed'});
                            },
                            success : function(json) {
                                try {
                                    if (!json || json === undefined || json.success === undefined || !json.success || json.html === undefined) {
                                        window.adminError({msg : 'Bad response from server.'});
                                    } else {
                                        tbody.html(json.html);
                                    }
                                } catch(e) {
                                    window.adminError({e:e});
                                }
                            },
                            complete : function() {
                                btn.reset();
                            }
                        });
                    } catch(e) {
                        window.adminError({e:e});
                    }
                    return false;
                });

                jQuery(document).on('click', '.keyword-strength-checkbox', function() {
                    var btn = jQuery(this);
                    try {
                        var saving = btn.parent().find('.saving');

                        saving.html('Saving...');

                        var keyword_id = btn.data('keyword-id');

                        var checked = btn.prop('checked');

                        var strength = checked ? 1 : 0;

                        var ret = false;
                        jQuery.ajax({
                            async : false,
                            url : '/admin/set_cause_keyword_strength',
                            type : 'post',
                            dataType : 'json',
                            data : { keyword_id : keyword_id, strength : strength },
                            error : function() {
                                window.adminError({msg : 'Request failed'});
                                ret = false;
                            },
                            success : function(json) {
                                try {
                                    if (!json || json === undefined || json.success === undefined || !json.success) {
                                        window.adminError({msg : 'Bad response from server.'});
                                        ret = false;
                                    } else {
                                        ret = true;
                                    }
                                } catch(e) {
                                    ret = false;
                                    window.adminError({e:e});
                                }
                            },
                            complete : function() {
                                saving.html('Strong:');
                            }
                        });
                        return ret;
                    } catch(e) {
                        window.adminError({e:e});
                    }
                    return false;
                });


            } catch(e) {
                window.adminError({e:e});
            }
        });
    } catch(e) {
        window.adminError({e:e});
    }
</script>