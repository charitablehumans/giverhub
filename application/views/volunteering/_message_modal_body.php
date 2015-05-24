<?php /** @var \Entity\Charity $charity */ ?>
<p>The following message will be sent to <?php echo $charity->getLink(); ?>: You can edit the text if needed.</p>
<textarea class="form-control" data-default="I, <?php echo $this->user ? htmlspecialchars($this->user->getName()) : 'x'; ?>, am interested in participating in the following volunteering event."></textarea>
<div class="event-wrapper"></div>
<button type="button" class="btn btn-primary btn-send-vol-request">send message</button>