<?php

/** @var \Charity $CI */
$CI =& get_instance();
?>

<?php $this->load->view('/members/_header', array('user' => $this->user)); ?>

<main class="members_main" role="main" id="main_content_area">
    <section class="container">
        <div class="row messages-controller" ng-controller="MessagesController" data-messages="<?php echo htmlspecialchars(json_encode($this->user->getMessageData())); ?>">
            <div class="col-xs-3">
                <div class="block nonprofit-list-block" id="nonprofit-list-block" ng-show="nonprofits">
                    <header>Messages from nonprofits</header>
                    <div class="nonprofit"
                         ng-class="{open: nonprofit==user_open, unseen : nonprofit.last_message && !nonprofit.last_message.seen && nonprofit.last_message.to && nonprofit.last_message.to.id == signed_in_user.id }"
                         ng-repeat="nonprofit in nonprofits"
                         ng-click="open(nonprofit)">
                        <span class="name">{{ nonprofit.entity.name }}</span>
                        <div class="date">{{ nonprofit.last_message.sent | amDateFormat:'M/D' }}</div>
                        <div class="msg">{{ nonprofit.last_message.message }}</div>
                    </div>
                </div>
                <?php $this->load->view('/partials/messages-user-list-block'); ?>
            </div>
            <div class="col-xs-9">
                <?php $this->load->view('/partials/messages-block'); ?>
            </div>
        </div>
    </section>
</main>