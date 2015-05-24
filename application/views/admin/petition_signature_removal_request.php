<?php
/** @var \Entity\PetitionSignatureRemovalRequest $request */
/** @var \Base_Controller $CI */
$CI =& get_instance();
?>
<style>
    .row {
        max-width: 90%;
    }
</style>
<div class="row">
    <h1>Petition <?php echo $request->getType(); ?> removal request</h1>
    <h2>User: <?php echo $request->getUser()->getLink(); ?></h2>
    <h2>Email: <a href="mailto:<?php echo $request->getUser()->getEmail(); ?>"><?php echo $request->getUser()->getEmail(); ?></a></h2>
    <h2>Reason for removal request: <?php echo htmlspecialchars($request->getReason()); ?></h2>
    <?php if ($request->getEntity()): ?>
        <h2>Petition: <?php echo $request->getEntity()->getPetition()->getLink(); ?></h2>
        <pre><?php var_dump($request->getEntity()); ?></pre>
        <button data-id="<?php echo $request->getId(); ?>" class="grant-button" type="button">GRANT</button>
    <?php else: ?>
        <h3>The request was granted and the <?php echo $request->getType(); ?> was removed on <?php echo $request->getDateRemoved()->format('Y-m-d H:i:s'); ?> by <?php echo $request->getRemovedByUser() ? $request->getRemovedByUser()->getLink() : '?unknown?'; ?></h3>
    <?php endif; ?>
</div>

<script type="text/javascript">
    jQuery(document).ready(function() {
        try {
            jQuery(document).on('click', '.grant-button', function() {
                var $this = jQuery(this);
                try {
                    if ($this.hasClass('disabled')) {
                        return;
                    }

                    $this.loading('GRANTING...');

                    jQuery.ajax({
                        url : '/admin/grant_petition_signature_removal_request',
                        type : 'post',
                        dataType : 'json',
                        data : { id : jQuery(this).data('id') },
                        error : function() {
                            window.adminError({msg : 'request failed.'});
                            $this.reset();
                        },
                        success : function(json) {
                            try {
                                if (typeof(json) !== "object" || typeof(json.success) !== "boolean" || !json.success) {
                                    window.adminError({msg : 'bad response from server.'});
                                    $this.reset();
                                } else {
                                    window.location.reload();
                                }
                            } catch(e) {
                                $this.reset();
                                window.adminError({e:e});
                            }
                        }
                    });
                } catch(e) {
                    $this.reset();
                    window.adminError({e:e});
                }
            });
        } catch(e) {
            window.adminError({e:e});
        }
    });
</script>