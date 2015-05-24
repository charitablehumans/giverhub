<?php
/** @var string $id */
/** @var string $modal_size */
/** @var string $extra_classes */
/** @var string $header */
/** @var boolean $body_wrapper */
/** @var boolean $body_string */
/** @var string $body */
/** @var string $footer_string */
/** @var string $footer */
/** @var string $extra_attributes */
?>
<div class="modal fade gh-modal-style-2 <?php echo $extra_classes; ?>"
     id="<?php echo $id; ?>"
     tabindex="-1"
     role="dialog"
     aria-labelledby="<?php echo $id; ?>"
     <?php echo $extra_attributes; ?>
     aria-hidden="true">
    <div class="<?php echo $modal_size; ?>">
        <div class="modal-content">
            <header class="modal-header clearfix">
                <span class="header"><?php echo $header; ?></span>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
            </header>

            <?php if ($body_wrapper): ?>
                <section class="modal-body clearfix">
                    <?php if($body_string): ?>
                        <?php echo $body; ?>
                    <?php else: ?>
                        <?php $this->load->view($body); ?>
                    <?php endif; ?>
                </section>
            <?php else: ?>
                <?php if($body_string): ?>
                    <?php echo $body; ?>
                <?php else: ?>
                    <?php $this->load->view($body); ?>
                <?php endif; ?>
            <?php endif; ?>

            <?php if ($footer): ?>
                <footer class="modal-footer">
                    <?php if ($footer_string): ?>
                        <?php echo $footer; ?>
                    <?php else: ?>
                        <?php $this->load->view($footer); ?>
                    <?php endif; ?>
                </footer>
            <?php endif; ?>
        </div>
    </div>
</div>