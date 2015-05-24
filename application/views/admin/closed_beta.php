<?php
/** @var \Admin $CI */
$CI =& get_instance();
/** @var \Entity\ClosedBetaSignup[] $signups */
/** @var integer $page */
/** @var integer $pages */
?>
<div class="row">
    <div class="column large-12">
        <?php if ($this->session->flashdata('message')): ?>
            <div data-alert class="alert-box success radius">
                <?php echo $this->session->flashdata('message'); ?>
                <a href="#" class="close">&times;</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php function pager($page, $pages) { ?>
    <div class="row">
        <div class="column large-12">
            <ul class="pagination">
                <li class="arrow <?php if ($page == 1): ?>unavailable<?php endif; ?>"><a href="/admin/closed_beta/<?php echo $page-1; ?>">&laquo;</a></li>
                <?php for($p = 1; $p <= $pages; $p++): ?>
                    <li class="<?php if ($p == $page): ?>current<?php endif; ?>"><a href="/admin/closed_beta/<?php echo $p; ?>"><?php echo $p; ?></a></li>
                <?php endfor; ?>
                <li class="arrow <?php if ($page == $pages): ?>unavailable<?php endif; ?>"><a href="/admin/closed_beta/<?php echo $page+1; ?>">&raquo;</a></li>
            </ul>
        </div>
    </div>
<?php } ?>

<?php pager($page, $pages); ?>

<div class="row">
    <div class="column large-12">
        <table class="tablesorter" style="width:100%;">
            <thead>
            <tr>
                <th>email</th>
                <th>date</th>
                <th>approved</th>
                <th>approve</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($signups as $signup): ?>
                <tr>
                    <td><?php echo htmlspecialchars($signup->getEmail()); ?></td>
                    <td><?php echo htmlspecialchars($signup->getSignupDate()); ?></td>
                    <td class="approved_or_not"><?php echo $signup->getApproved() ? 'APPROVED' : 'WAITING'; ?></td>
                    <td><?php if (!$signup->getApproved()) : ?><a href="#" data-signup-id="<?php echo $signup->getId(); ?>" class="btn-approve">approve!</a><?php else: ?>approved!<?php endif; ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<form method="post" action="/admin/add_and_approve_multiple_emails">
    <div class="row">
        <div class="large-12 columns">
            <div class="row collapse">
                <div class="small-10 columns">
                    <input type="text" name="emails" placeholder="Emails (comma separated, no spaces!)"/>
                </div>
                <div class="small-2 columns">
                    <input class="button postfix" type="submit" id="invite-emails" value="INVITE EMAILS">
                </div>
            </div>
        </div>
    </div>
</form>

<?php pager($page, $pages); ?>

<script src="/assets/scripts/jquery.tablesorter.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $(".tablesorter").tablesorter();

        jQuery(document).on('click', '.btn-approve', function() {
            var btn = jQuery(this);
            try {
                if (btn.html() != 'approve!') {
                    return false;
                }
                btn.html('approving...');

                jQuery.ajax({
                    url : '/admin/closed_beta_approve',
                    type : 'post',
                    dataType : 'json',
                    data : { signupId : btn.data('signup-id') },
                    error : function() {
                        alert('request failed');
                    },
                    success : function(json) {
                        try {
                            if (json === undefined || !json || json.success === undefined || !json.success) {
                                alert('bad response.');
                            } else {
                                btn.html('done');
                                btn.closest('tr').find('.approved_or_not').html('APPROVED');
                                btn.removeClass('.btn-approve');
                            }
                        } catch(e) {
                            alert(e);
                        }
                    }
                });
            } catch(e) {
                alert(e);
                btn.html('approve!');
            }
            return false;
        });

    });
</script>