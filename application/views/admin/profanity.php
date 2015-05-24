<?php
/** @var \Admin $CI */
$CI =& get_instance();
/** @var \Entity\Profanity[] $profanities */
/** @var \Entity\ChangeOrgPetition[] $filteredPetitions */
?>
<div class="row">
    <div class="column large-12">
        <h3>FAQs</h3>
    </div>
</div>
<div class="row">
    <div class="column large-12">
        <?php if ($this->session->flashdata('message')): ?>
            <div data-alert class="alert-box radius">
                <?php echo $this->session->flashdata('message'); ?>
                <a href="#" class="close">&times;</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<div class="row">
    <div class="column large-6">

        <h5>Profanities</h5>

        <form method="post" action="/admin/add_profanities">
            <label>Add Profanities (comma seperated, no spaces!)<br/><input type="text" style="width: 400px;" name="profanities" value=""></label>
            <input class="button tiny" type="submit" id="submit-profanities" value="SUBMIT">
        </form>
        <table class="tablesorter">
            <thead>
                <tr>
                    <th>id</th>
                    <th>word/phrase</th>
                    <th>delete</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($profanities as $profanity): ?>
                    <tr>
                        <td><?php echo $profanity->getId(); ?></td>
                        <td><?php echo htmlspecialchars($profanity->getProfanity()); ?></td>
                        <td><a href="#" data-profanity-id="<?php echo $profanity->getId(); ?>" class="button tiny alert btn-delete">delete!</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="column large-6">
        <h5>Filtered Petitions</h5>
        <a href="/admin/run_petition_filter" class="button tiny btn-rerun-petition-filter">Rerun Petition Filter!</a>
        <table class="tablesorter">
            <thead>
            <tr>
                <th>Petition</th>
                <th>Filter reason</th>
                <th>Remove from blacklist</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($filteredPetitions as $petition): ?>
                <tr>
                    <td><a href="/petitions/<?php echo $petition->getGiverhubUrlSlug(); ?>"><?php echo htmlspecialchars($petition->getTitle()); ?></a></td>
                    <td><?php echo nl2br(htmlspecialchars($petition->getProfanityFilter())); ?></td>
                    <td><a href="#" data-petition-id="<?php echo $petition->getId(); ?>" class="button tiny alert btn-remove-from-blacklist">remove!</a></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<script src="/assets/scripts/jquery.tablesorter.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $(".tablesorter").tablesorter();

        jQuery(document).on('click', '.btn-delete', function() {
            var btn = jQuery(this);
            try {
                btn.html('deleting...');

                jQuery.ajax({
                    url : '/admin/delete_profanity',
                    type : 'post',
                    dataType : 'json',
                    data : { profanityId : btn.data('profanity-id') },
                    error : function() {
                        alert('request failed');
                        btn.html('error!!');
                    },
                    success : function(json) {
                        try {
                            if (json === undefined || !json || json.success === undefined || !json.success) {
                                alert('bad response.');
                                btn.html('error!!!!');
                            } else {
                                btn.closest('tr').remove();
                            }
                        } catch(e) {
                            btn.html('error!!!');
                            alert(e);
                        }
                    }
                });
            } catch(e) {
                alert(e);
                btn.html('error!');
            }
            return false;
        });

        jQuery(document).on('click', '.btn-remove-from-blacklist', function() {
            var btn = jQuery(this);
            try {
                if (!confirm('Rerunning profanity filter may make this petition to be filtered again!')) {
                    return false;
                }
                btn.html('removing...');

                jQuery.ajax({
                    url : '/admin/remove_petition_from_blacklist',
                    type : 'post',
                    dataType : 'json',
                    data : { petitionId : btn.data('petition-id') },
                    error : function() {
                        alert('request failed');
                        btn.html('error!!');
                    },
                    success : function(json) {
                        try {
                            if (json === undefined || !json || json.success === undefined || !json.success) {
                                alert('bad response.');
                                btn.html('error!!!!');
                            } else {
                                btn.closest('tr').remove();
                            }
                        } catch(e) {
                            btn.html('error!!!');
                            alert(e);
                        }
                    }
                });
            } catch(e) {
                alert(e);
                btn.html('error!');
            }
            return false;
        });

    });
</script>