<?php
/** @var \Entity\Charity $charity */
/** @var \Charity $CI */
$CI =& get_instance();
?>

<?php $this->load->view('/nonprofits/_charity_header', array('charity' => $charity)); ?>

<main class="members_main" role="main" id="main_content_area">
    <section class="container">
        <div class="row messages-controller" ng-controller="MessagesController" data-charity="<?php echo htmlspecialchars(json_encode($charity)); ?>" data-messages="<?php echo htmlspecialchars(json_encode($this->user->getMessageData($charity))); ?>">
            <script type="text/ng-template" id="accept_vol_request_modal_body.html">
                <header class="modal-header clearfix">
                    <span class="header">Volunteer Message</span>
                    <button type="button" class="close" ng-click="cancel()" data-dismiss="modal" aria-hidden="true">x</button>
                </header>
                <div class="modal-body">
                    <p>The following message will be sent to {{message.from.name}}: You can edit the text if needed.</p>
                    <textarea msd-elastic ng-style="{ 'padding-bottom' : event_height }" id="textarea-vol-request-accept" ng-model="msg.msg" class="form-control"></textarea>
                    <div id="event-wrapper-in-modal" class="event-wrapper">
                        <header>
                            <span class="dated">{{message.volunteer.event.date_string}}</span>
                            <span class="time">{{message.volunteer.event.time_string}}</span>
                        </header>
                        <p class="desc">{{message.volunteer.event.description}}</p>
                        <p class="location"><span class="bold">Location:</span> {{message.volunteer.event.location}}</p>
                    </div>
                    <button type="button" class="btn btn-primary btn-send-vol-request" ng-disabled="!msg.msg.length" ng-click="send()">send message</button>
                </div>
            </script>
            <div class="col-xs-3">
                <?php $this->load->view('/partials/messages-user-list-block'); ?>
            </div>
            <div class="col-xs-9">
                <?php $this->load->view('/partials/messages-block'); ?>
            </div>
        </div>
    </section>
</main>