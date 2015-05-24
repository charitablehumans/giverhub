<?php /** @var \Entity\FAQ[] $faqs */ ?>
<section role="main">
    <div class="row">
        <div class="column large-10">
            <h3>FAQs</h3>
        </div>
        <div class="column large-2">
            <a class="button tiny right" href="/admin/add_faq">Add FAQ</a>
        </div>
    </div>
    <div class="row">
        <div class="column large-12">
            <table style="width:100%;">
                <thead>
                    <tr>
                        <th>Question</th>
                        <th>Answer</th>
                        <th>Order</th>
                        <th>Options</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($faqs as $faq): ?>
                        <tr>
                            <td><?php echo $faq->getFqQues(); ?></td>
                            <td><?php echo $faq->getFqAns(); ?></td>
                            <td><?php echo $faq->getFqOrder(); ?></td>
                            <td>
                                <a class="button tiny block" href="/admin/edit_faq/<?php echo $faq->getId(); ?>">Edit</a>
                                <a class="button tiny block alert delete_faq" href="#" data-faq-id="<?php echo $faq->getId(); ?>">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if(!$faqs): ?>
                        <tr>
                            <td colspan="4">No FAQs yet.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>
<script type="text/javascript">
try {

    jQuery(document).ready(function() {
        try {

            jQuery(document).on('click', '.delete_faq', function() {
                var $this = jQuery(this);
                try {
                    $this.loading('Deleting...');
                    jQuery.ajax({
                        url : '/admin/delete_faq',
                        type : 'post',
                        dataType : 'json',
                        data : { faq_id : $this.data('faq-id') },
                        error : function() {
                            window.adminError({msg:'Request Failed.'});
                        },
                        success : function(json) {
                            try {
                                if (!json || json === undefined || json.success === undefined || !json.success) {
                                    window.adminError({msg : 'Bad response.'});
                                } else {
                                    window.adminSuccess({msg : 'Delete successful!'});
                                    $this.closest('tr').remove();
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

        } catch(e) {
            window.adminError({e:e});
        }
    });

} catch(e) {
    window.adminError({e:e});
}
</script>